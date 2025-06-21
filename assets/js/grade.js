$(document).ready(function() {
	$("#grdGrade").jqGrid({
		url: base_url + 'grade/query',
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Kode', name: 'CODE', width: 100, align: 'center' },
			{ label: 'Level', name: 'NAME', width: 250, align: 'left' }
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

function addGrade() {
	addNewMode('frmGrade');
	openModal('frmGrade', 'Level Baru', saveGrade);
	$('#frmGrade [name="ID"]').val("0");
}

function editGrade(id) {
	var uri = base_url + "grade/get/" + id;
	queryRecord(uri, function(data){
		editMode('frmGrade', data.payload);
		openModal('frmGrade', 'Ubah Level', saveGrade, removeGrade);
	});
}

function saveGrade() {
	var uri = base_url + ($('#frmGrade [name="ID"]').val() == '0' ? 'grade/create' : 'grade/update');
	saveRecord('frmGrade', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdGrade').trigger('reloadGrid');
		closeModal('frmGrade');
	});
}

function removeGrade() {
	var uri = base_url + 'grade/delete';
	removeRecord('frmGrade', 'Anda akan menghapus jabatan ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdGrade').trigger('reloadGrid');
		closeModal('frmGrade');
	});
}