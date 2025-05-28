$(document).ready(function() {
	document.title = 'CyberHRM - Lembur';
	
	$('.ui.accordion').accordion('open', 1);
	$('#mnuOvertime').state('activate');

	$("#grdOvertime").jqGrid({
		url: base_url + 'overtime/query/?month=' + moment().format('YYYY-MM'),
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 40, align: 'center', exportcol: false },
			{ label: 'Pegawai', name: 'EMP_NAME', width: 150, align: 'left' },
			{ label: 'Departemen', name: 'DEPT_NAME', width: 120, align: 'center' },
			{ label: 'Jenis', name: 'OVERTIME_CODE', width: 100, align: 'center' },
			{ label: 'Keperluan', name: 'OVERTIME_CAT', width: 150, align: 'left' },
			{ label: 'Dari', name: 'START_CLOCK', width: 110, align: 'center' },
			{ label: 'Sampai', name: 'END_CLOCK', width: 110, align: 'center' },
			{ label: 'Durasi (jam)', name: 'WORK_HOUR', width: 80, align: 'right', formatter: 'number' },
			{ label: 'Status', name: 'STATUS', width: 70, align: 'center' },
			{ label: 'Keterangan', name: 'REMARKS', width: 200, align: 'left' },
		],
		page: 1,
		loadonce: true,
		viewrecords: true,
		//width: 900,
		height: 250,
		rowNum: 1000,
		scroll: 1,
		shrinkToFit : false,
		emptyrecords: 'Scroll to bottom to retrieve new page'
        //pager: "#grdPage"
	});

	$('#cmdExport').on('click', function(){
		gridToCsv('grdOvertime', 'lembur');
	});

	$('#cmdAdd').on('click', addOvertime);

	$('#findEmp').search({
		apiSettings: {
			url: base_url + 'employees/search?find={query}'
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

			$('#frmOvertime').prop('disabled', true);
			addCombo('cmbCategory','ovrcat '+result.department, undefined, function(){
				$('#frmOvertime').prop('disabled', false);
			});
			//checkTimesheet();
			actualCombo();
		},
		type: 'compact'
	});

	$('#month_at').calendar({
		type: 'month',
		onChange: function(date, text) {
			getOvertime(text);
		}
	});
});

function modalForm(title, approveFunc, denyFunc) {
	openModal('frmOvertime', title, approveFunc, denyFunc);
	$('#start_clock').calendar({
		ampm: false,
		endCalendar: $('#end_clock'),
		onChange: function(date, text) {
			if (isLoadingMode('frmOvertime')) return;
			actualCombo();
		}
	});
	$('#end_clock').calendar({
		ampm: false,
		startCalendar: $('#start_clock'),
		onChange: function(date, text) {
			if (isLoadingMode('frmOvertime')) return;
			var sdt = moment($('[name="START_CLOCK"]').val());
			var min = round(moment(date).diff(sdt, 'hours'),2);
			$('[name="DURATION"]').val(min);
			checkHoliday(date);			
			//checkTimesheet();
		}
	});
}

function actualCombo(d, e) {
	if (d == undefined) {
		var dte = $('[name="START_CLOCK"]');
		if (dte.val() == '') return;
		d = moment(dte.val()).format('YYYY-MM-DD');
	}

	if (e == undefined) {
		var emp = $('[name="EMP_ID"]');
		if (emp.val() == '') return;
		e = emp.val();
	}

	addCombo('cmbAbsent','timesheet '+e+' '+d);
}

function checkTimesheet() {
	var dte = $('[name="START_CLOCK"]');
	if (dte.val() == '') return;

	var emp = $('[name="EMP_ID"]');
	if (emp.val() == '') return;

	console.log('Date: ' + dte.val());

	$.ajax({
		url : base_url + "timesheet/check/" + emp.val() + "/" + moment(dte.val(), 'MMMM D, YYYY H:mm').format('YYYY-MM-DD'),
		type: "GET",
		dataType: "JSON",
		success: function(data) {
			if (data.status) {
				$('#SHIFT_CODE').val(data.payload.SHIFT_CODE);
				$('#SHIFT_IN').val(data.payload.SHIFT_IN);
				$('#SHIFT_OUT').val(data.payload.SHIFT_OUT);
			}
		}
	});
}

function checkHoliday(date) {
	var d = moment(date).format('d');
	if (d == '0' || d == '6') {
		$('#cmbOvertime').val('6').change(); //lembur libur (LL)
	} else {
		$('#cmbOvertime').val('5').change(); //lembur biasa (LB)
	}

	var dte = moment(date).format('YYYY-MM-DD');
	$.ajax({
		url : base_url + "holiday/check/" + dte + "/" + dte,
		type: "GET",
		dataType: "JSON",
		success: function(data) {
			if (data.status) {
				if (data.payload[0].TYPE == '1') {
					$('#cmbOvertime').val('7').change(); //lembur istimewa (LI)
				}
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			
		}
	});
}

function addOvertime() {
	$('#cmbAbsent').empty();
	$('#cmbAbsent').children('option').remove();
	$('#cmbAbsent').hide().show();

	addCombo('cmbOvertime', 'overtime', undefined, function(){
		addCombo('cmbCategory', 'ovrcat', undefined,function(){
			addNewMode('frmOvertime');
			modalForm('Data Lembur Baru', saveOvertime);
			//set default values
			$('[name="ID"]').val("0");
		});
	});
}

function editOvertime(id) {
	var uri = base_url + "overtime/get/" + id;
	queryRecord(uri, function(data){
		addCombo('cmbOvertime', 'overtime', undefined, function(){
			addCombo('cmbCategory', 'ovrcat '+data.payload.DEPARTMENT, undefined, function(){
				var e = data.payload.EMP_ID;
				var d = moment(data.payload.CLOCK_IN).format('YYYY-MM-DD');
				addCombo('cmbAbsent', 'timesheet '+e+' '+d, undefined, function(){
					editMode('frmOvertime', data.payload);
					modalForm('Ubah Data Lembur', saveOvertime, removeOvertime);
					//checkTimesheet();
				});
			});
		});
	});
}
 
function saveOvertime() {
	var uri = base_url + ($('[name="ID"]').val() == '0' ? 'overtime/create' : 'overtime/update');
	saveRecord('frmOvertime', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdOvertime').trigger('reloadGrid');
		closeModal('frmOvertime');
	});
}

function removeOvertime() {
	var uri = base_url + 'overtime/delete';
	removeRecord('frmOvertime', 'Anda akan menghapus data ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdOvertime').trigger('reloadGrid');
		closeModal('frmOvertime');
	});
}

function getOvertime(month) {
	reloadGrid({
		id: "grdOvertime",
		url: base_url + "overtime/query/?month="+moment('1 '+month).format('YYYY-MM'),
		rowNum: 1000
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
			id: "grdOvertime",
			url: base_url + "overtime/query/?"+q,
			rowNum: 1000
		});
			
	}, 200);
}