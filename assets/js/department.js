$(document).ready(function() {
	$("#grdDept").jqGrid({
		url: base_url + 'department/query',
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Kode', name: 'PFX', width: 100, align: 'center' },
			{ label: 'Departemen', name: 'NAME', width: 250, align: 'left' },
			{ label: 'Kode Penulisan', name: 'CODE', width: 100, align: 'left' }
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
	
});

function addDept() {
	addNewMode('frmDept');
	openModal('frmDept', 'Departemen Baru', saveDept);
	$('#frmDept [name="ID"]').val("0");
}

function editDept(id) {
	var uri = base_url + "department/get/" + id;
	queryRecord(uri, function(data){
		editMode('frmDept', data.payload);
		openModal('frmDept', 'Ubah Departemen', saveDept, removeDept);
	});
}

function saveDept() {
	var uri = base_url + ($('#frmDept [name="ID"]').val() == '0' ? 'department/create' : 'department/update');
	saveRecord('frmDept', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdDept').trigger('reloadGrid');
		closeModal('frmDept');
	});
}

function removeDept() {
	var uri = base_url + 'department/delete';
	removeRecord('frmDept', 'Anda akan menghapus departemen ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdDept').trigger('reloadGrid');
		closeModal('frmDept');
	});
}