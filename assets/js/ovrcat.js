$(document).ready(function() {
	$("#grdOvr").jqGrid({
		url: base_url + 'ovrcategory/query',
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Departemen', name: 'DEPT_NAME', width: 200, align: 'center' },
			{ label: 'Kode', name: 'CODE', width: 70, align: 'center' },
			{ label: 'Deskripsi', name: 'DESCRIPTION', width: 250, align: 'left' }
		],
		page: 1,
		//width: 900,
		height: 250,
		rowNum: 200,
		scroll: 0,
		shrinkToFit : false,
		emptyrecords: 'Scroll to bottom to retrieve new page',
        //pager: "#grdPage"
	});
});

function addOvr() {
	addCombo('cmbDept', 'department', undefined, function(){
		addNewMode('frmOvr');
		openModal('frmOvr', 'Kategori Lembur Baru', saveOvr);
		//set default values
		$('#frmOvr input[name="ID"]').val("0");	
	});
}

function editOvr(id) {
	var uri = base_url + "ovrcategory/get/" + id;
	queryRecord(uri, function(data){
		addCombo('cmbDept', 'department', undefined, function(){
			editMode('frmOvr', data.payload);
			openModal('frmOvr', 'Ubah Kategori Lembur', saveOvr, removeOvr);
		});
	});
}

function saveOvr() {
	var uri = base_url + ($('#frmOvr input[name="ID"]').val() == '0' ? 'ovrcategory/create' : 'ovrcategory/update');
	saveRecord('frmOvr', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdOvr').trigger('reloadGrid');
		closeModal('frmOvr');
	});
}

function removeOvr() {
	var uri = base_url + 'ovrcategory/delete';
	removeRecord('frmOvr', 'Anda akan menghapus data ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdOvr').trigger('reloadGrid');
		closeModal('frmOvr');
	});
}

var timer;
function searchOvr() {
	clearTimeout(timer);
	timer = setTimeout(function() {
		var $uri = 'ovrcategory/query';

		var $key = $('#txtFind').val();
		if ($key != '') $uri = 'ovrcategory/query/?find='+$key;
	
		$("#grdOvr").setGridParam({
			url: base_url + $uri,
			page: 1
		}).trigger("reloadGrid");

	}, 500);
}	
