$(document).ready(function() {
	document.title = 'CyberHRM - Absen Manual';
	
	$('.ui.accordion').accordion('open', 1);
	$('#mnuAbsent').state('activate');

	$('.menu .item').tab();	

	$("#grdAbsent").jqGrid({
		url: base_url + 'absent/query/?month=' + moment().format('YYYY-MM'),
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 40, align: 'center', exportcol: false },
			{ label: 'NIK', name: 'EMP_CODE', width: 60, align: 'center' },
			{ label: 'Pegawai', name: 'EMP_NAME', width: 150, align: 'left' },
			{ label: 'Departemen', name: 'DEPT_NAME', width: 120, align: 'center' },
			{ label: 'Jam Masuk', name: 'CLOCK_IN', width: 120, align: 'center' },
			{ label: 'Jam Pulang', name: 'CLOCK_OUT', width: 120, align: 'center' },
			{ label: 'Keterangan', name: 'REMARKS', width: 200, align: 'left' },
		],
		page: 1,
		//width: 900,
		autowidth: true,
		height: 250,
		rowNum: 200,
		scroll: 1,
		loadonce: true,
		viewrecords: true,
		shrinkToFit : false,
		//emptyrecords: 'Scroll to bottom to retrieve new page'
        //pager: "#grdPage"
	});

	$("#exportAbsent").on("click", function(){
		gridToCsv('grdAbsent', 'kehadiran');
	});

	$("#grdPunch").jqGrid({
		mtype: "GET",
		url: base_url + "x100c/search/?month="+moment().format('YYYY-MM'),
		datatype: "json",
		colModel: [
			{ label: 'NIP', name: 'CODE', width: 120, align: 'center' },
			{ label: 'Nama', name: 'NAME', width: 120, align: 'center' },
			{ label: 'Jam', name: 'WORK_AT', width: 120, align: 'center' },
			{ label: 'Masuk/Pulang', name: 'STATUS', width: 120, align: 'center' }
		],
		page: 1,
		//width: 900,
		height: 250,
		rowNum: 200,
		scroll: 1,
		loadonce: true,
		viewrecords: true,
		shrinkToFit : false,
		//emptyrecords: 'Scroll to bottom to retrieve new page'
        //pager: "#grdPage"
	});

	$("#exportPunch").on("click", function(){
		gridToCsv('grdPunch', 'absensi');
	});

	$('#month_at').calendar({
		type: 'month',
		onChange: function (date, text, mode) {
			getAbsent(text);
		}
	});

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
		},
		type: 'compact'
	});

	$("#cmdAdd").on("click", function(){
		addAbsent();
	});
});

function showAbsentForm(title) {
	openModal('frmAbsent', title, saveAbsent, removeAbsent);
	$('#clock_in').calendar({
		endCalendar: $('#clock_out'),
		ampm: false
	});
	$('#clock_out').calendar({
		startCalendar: $('#clock_in'),
		ampm: false
	});
}

function addAbsent() {
	addNewMode('frmAbsent');
	showAbsentForm('Absen Manual Baru');
	//set default values
	$('#frmAbsent [name="ID"]').val("0");
}

function editAbsent(id) {
	adding = false;
	var uri = base_url + "absent/get/" + id;
	queryRecord(uri, function(data){
		editMode('frmAbsent', data.payload);
		showAbsentForm('Ubah Ijin/Cuti');
	});
}

function saveAbsent() {
	var uri = base_url + ($('#frmAbsent input[name="ID"]').val() == '0' ? 'absent/create' : 'absent/update');
	saveRecord('frmAbsent', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdAbsent').trigger('reloadGrid');
		closeModal('frmAbsent');
		adding = false;
	});
}

function removeAbsent() {
	var uri = base_url + 'absent/delete';
	removeRecord('frmAbsent', 'Anda akan menghapus data ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdAbsent').trigger('reloadGrid');
		closeModal('frmAbsent');
	});
}

function getAbsent(month) {
	reloadGrid({
		id: 'grdAbsent',
		url: base_url + "absent/query/?month="+moment('1 '+month).format('YYYY-MM'),
		rowNum: 200
	});

	reloadGrid({
		id: 'grdPunch', 
		url: base_url + "x100c/search/?month="+moment('1 '+month).format('YYYY-MM'),
		rowNum: 200
	});
}	

var t;
function searchAbsent() {
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
			id: 'grdAbsent', 
			url: base_url+'absent/query/?'+q, 
			rowNum: 200
		});
		
		reloadGrid({
			id: 'grdPunch',
			url: base_url+'x100c/search/?'+q,
			rowNum: 200
		});
		
	}, 200);
}