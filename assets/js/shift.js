$(document).ready(function() {
	$("#grdShift").jqGrid({
		url: base_url + 'shift/query',
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Kode', name: 'CODE', width: 70, align: 'center' },
			{ label: 'Deskripsi', name: 'DESCRIPTION', width: 200, align: 'left' },
			{ label: 'Jam Mulai', name: 'START_TIME', width: 100, align: 'center' },
			{ label: 'Jam Akhir', name: 'END_TIME', width: 100, align: 'center' },
			{ label: 'Min. Jam Kerja', name: 'MIN_WORK_HOUR', width: 100, align: 'center' },
		],
		page: 1,
		//width: 900,
		height: 250,
		rowNum: 20,
		scroll: 1,
		shrinkToFit : false,
		emptyrecords: 'Scroll to bottom to retrieve new page'
        //pager: "#grdPage"
	});
});

function addShift() {
	addNewMode('frmShift');
	openModal('frmShift', 'Shift Kerja Baru', saveShift);
	//set default values
	$('#frmShift input[name="ID"]').val("0");
}

function editShift(id) {
	var uri = base_url + "shift/get/" + id;
	queryRecord(uri, function(data){
		editMode('frmShift', data.payload);
		openModal('frmShift', 'Ubah Shift Kerja', saveShift, removeShift);
	});
}

function saveShift() {
	var uri = base_url + ($('#frmShift input[name="ID"]').val() == '0' ? 'shift/create' : 'shift/update');
	saveRecord('frmShift', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdShift').trigger('reloadGrid');
		closeModal('frmShift');
	});
}

function removeShift() {
	var uri = base_url + 'shift/delete';
	removeRecord('frmShift', 'Anda akan menghapus data ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdShift').trigger('reloadGrid');
		closeModal('frmShift');
	});
}