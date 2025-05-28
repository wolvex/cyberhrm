$(document).ready(function() {
	document.title = 'CyberHRM - Hari Libur';
	
	$('.ui.accordion').accordion('open', 1);
	$('#mnuHoliday').state('activate');

	$("#grdHoliday").jqGrid({
		url: base_url + 'holiday/query/?year='+moment().format('YYYY'),
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Tanggal', name: 'HOLIDAY_AT', width: 100, align: 'center' },
			{ label: 'Durasi', name: 'FULL_DAY', width: 100, align: 'center' },
			{ label: 'Deskripsi', name: 'DESCRIPTION', width: 200, align: 'left' },
			{ label: 'Jenis', name: 'TYPE', width: 100, align: 'center' }
		],
		page: 1,
		//width: 900,
		height: 250,
		rowNum: 200,
		scroll: 1,
		shrinkToFit : false,
		emptyrecords: 'Scroll to bottom to retrieve new page'
        //pager: "#grdPage"
	});

	$('#year_at').calendar({
		type: 'year',
		onChange: function (date, text, mode) {
			getHoliday(text);
		}
	});

});

function addHoliday() {
	addNewMode('frmHoliday');
	openModal('frmHoliday', 'Hari Libur Baru', saveHoliday);
		//set default values
	$('#frmHoliday input[name="ID"]').val("0");	
	$('#frmHoliday select[name="FULL_DAY"]').val("1").change();
}

function editHoliday(id) {
	var uri = base_url + "holiday/get/" + id;
	queryRecord(uri, function(data){
		editMode('frmHoliday', data.payload);
		alert($('[name="HOLIDAY_AT"]').val());
		openModal('frmHoliday', 'Ubah Hari Libur', saveHoliday, removeHoliday);
	});
}

function saveHoliday() {
	var uri = base_url + ($('#frmHoliday input[name="ID"]').val() == '0' ? 'holiday/create' : 'holiday/update');
	saveRecord('frmHoliday', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdHoliday').trigger('reloadGrid');
		closeModal('frmHoliday');
	});
}

function removeHoliday() {
	var uri = base_url + 'holiday/delete';
	removeRecord('frmHoliday', 'Anda akan menghapus data ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdHoliday').trigger('reloadGrid');
		closeModal('frmHoliday');
	});
}

function getHoliday(year) {
	$("#grdHoliday").setGridParam({
		url: base_url + "holiday/query/?year="+year,
		page: 1
	}).trigger("reloadGrid");
}	
