var adding = false;

$(document).ready(function() {
	document.title = 'CyberHRM - Ijin & Cuti';
	
	$('.ui.accordion').accordion('open', 1);
	$('#mnuOnleave').state('activate');

	$("#grdOnleave").jqGrid({
		url: base_url + 'onleave/query/?month=' + moment().format('YYYY-MM'),
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 40, align: 'center', exportcol: false },
			{ label: 'NIK', name: 'EMP_CODE', width: 60, align: 'center' },
			{ label: 'Pegawai', name: 'EMP_NAME', width: 150, align: 'left' },
			{ label: 'Departemen', name: 'DEPT_NAME', width: 120, align: 'center' },
			{ label: 'Jenis', name: 'ABSENCE_CODE', width: 120, align: 'center' },
			{ label: 'Mulai', name: 'STARTED_AT', width: 80, align: 'center' },
			{ label: 'Sampai', name: 'ENDED_AT', width: 80, align: 'center' },
			{ label: 'Keterangan', name: 'REASON', width: 200, align: 'left' },
		],
		page: 1,
		loadonce: true,
		viewrecords: true,
		//width: 900,
		height: 250,
		rowNum: 200,
		scroll: 1,
		shrinkToFit : false,
		emptyrecords: 'Scroll to bottom to retrieve new page'
        //pager: "#grdPage"
	});

	$('#cmdExport').on('click', function(){
		gridToCsv('grdOnleave', 'ijin');
	});

	$('#cmdAdd').on('click', addOnleave);

	$('#month_at').calendar({
		type: 'month',
		onChange: function (date, text, mode) {
			getOnleave(text);
		}
	});

	$('#findEmp').search({
		apiSettings: {
			url: base_url + 'employees/search?emp_status=PK&find={query}'
		},
		minCharacters : 3,
		maxResults: 20,
		cache: true,
			
		onSearchQuery: function() {
			//$('[name="coa_des"]').val("");
		},
		onSelect: function(result) {
			$('[name="EMP_ID').val(result.title);
			$('[name="EMP_NAME').val(result.description);
			getAvailQuota();
		},
		type: 'compact'
	});

	$('#cmbAbsence').on('change', function(){
		getAvailQuota();
	});

});

function showOnleaveForm(title) {
	openModal('frmOnleave', title, saveOnleave, removeOnleave);
	$('#started_at').calendar({
		type: 'date',
		endCalendar: $('#ended_at')
	});
	$('#ended_at').calendar({
		type: 'date',
		startCalendar: $('#started_at'),
		onChange: function(date, text) {
			if ( adding ) calcLeaveDays(text);
		}
	});
}

function calcLeaveDays(date) {
	//console.log($('[name="ENDED_AT"]').val());

	var sdt = moment($('[name="STARTED_AT"]').val());
	var edt = moment(date);

	var days = moment(edt).diff(sdt, 'days') + 1;

	$.ajax({
		url : base_url + "holiday/check/" + sdt.format('YYYY-MM-DD') + "/" + edt.format('YYYY-MM-DD'),
		type: "GET",
		dataType: "JSON",
		success: function(data) {
			if (data.status) {
				var rem = ''; days = 0;
				while (sdt.format('YYYYMMDD') <= edt.format('YYYYMMDD')) {
					var holiday = (sdt.format('d') == '0' || sdt.format('d') == '6');
					if ( !holiday ) {
						for (i=0;i<data.payload.length;i++) {
							if (sdt.format('YYYY-MM-DD') == data.payload[i].HOLIDAY_DATE) {
								holiday = true;
								break;
							}
						}						
					}
					if ( holiday ) {
						rem = rem + ', ' + sdt.format('DD-MMM-YYYY');
					} else days++;
					sdt = sdt.add(1, 'days');
				}
				if (rem != '') msgbox('Hari libur : ' + rem.substr(2), 'info');
			}
			$('[name="QUOTA_TAKEN"]').val(days);
			getAvailQuota();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			$('[name="QUOTA_TAKEN"]').val(days);
			getAvailQuota();
		}
	});
}

function getAvailQuota(date) {
	if ( !adding ) return;

	var emp  = $('[name="EMP_ID').val();
	var code = $('[name="ABSENCE_ID').val();
	if (date == undefined) date = $('[name="ENDED_AT"]').val();

	if (emp == '' || code == '' || date == '') return;

	$.ajax({
		url : base_url + "onleave/quota/" + emp + "/" + moment(date).format('YYYY-MM-DD') + "/" + code,
		type: "GET",
		dataType: "JSON",
		success: function(data) {
			if (data.status) {
				$('[name="QUOTA_AVAIL"]').val(data.payload.BALANCE);
			} else {
				$('[name="QUOTA_AVAIL"]').val('0');
			}
			$('[name="QUOTA_LEFT"]').val($('[name="QUOTA_AVAIL"]').val()-$('[name="QUOTA_TAKEN"]').val());
		}
	});
}

function addOnleave() {
	adding = true;
	addCombo('cmbAbsence', 'absence', undefined, function(){
		addNewMode('frmOnleave');
		showOnleaveForm('Ijin/Cuti Baru');
		//set default values
		$('#frmOnleave [name="ID"]').val("0");
		$('#frmOnleave [name="ABSENCE_ID"]').val("9").change();
		$('#frmOnleave #cmdSave').css('display', 'inline');
		$('#frmOnleave #QUOTA_AVAIL').css('display', 'inline');
	});
}

function editOnleave(id) {
	adding = false;
	var uri = base_url + "onleave/get/" + id;
	queryRecord(uri, function(data){
		addCombo('cmbAbsence', 'absence', undefined, function(){
			editMode('frmOnleave', data.payload);
			showOnleaveForm('Ubah Ijin/Cuti');
			$('#frmOnleave #cmdSave').css('display', 'none');
			$('#frmOnleave #QUOTA_AVAIL').css('display', 'none');
		});
	});
}

function saveOnleave() {
	var uri = base_url + ($('#frmOnleave input[name="ID"]').val() == '0' ? 'onleave/create' : 'onleave/update');
	saveRecord('frmOnleave', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdOnleave').trigger('reloadGrid');
		closeModal('frmOnleave');
		adding = false;
	});
}

function removeOnleave() {
	var uri = base_url + 'onleave/delete';
	removeRecord('frmOnleave', 'Anda akan menghapus data ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdOnleave').trigger('reloadGrid');
		closeModal('frmOnleave');
	});
}

function getOnleave(month) {
	reloadGrid({
		id: "grdOnleave",
		url: base_url + "onleave/query/?month="+moment('1 '+month).format('YYYY-MM'),
		rowNum: 200
	});
}	

var t;
function search() {
	clearTimeout(t);
	t = setTimeout(function(){
		var q = $('[name="month_at"]').val();
		if (q == '') {
			q = moment().format('YYYY-MM');
		} else {
			q = moment('01 '+q).format('YYYY-MM');
		}
		q = 'month='+q;
		
		var s = $('#txtFind').val().trim();
		if (s != '') q += '&find='+s;

		reloadGrid({
			id: "grdOnleave", 
			url: base_url+'onleave/query/?'+q,
			rowNum: 200
		});

	}, 200);
}