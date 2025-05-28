$(document).ready(function() {
	$("#grdJob").jqGrid({
		url: base_url + 'job/query',
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Kode', name: 'CODE', width: 100, align: 'center' },
			{ label: 'Jabatan', name: 'TITLE', width: 250, align: 'left' }
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

function addJob() {
	addNewMode('frmJob');
	openModal('frmJob', 'Jabatan Baru', saveJob);
	$('#frmJob [name="ID"]').val("0");
}

function editJob(id) {
	var uri = base_url + "job/get/" + id;
	queryRecord(uri, function(data){
		editMode('frmJob', data.payload);
		openModal('frmJob', 'Ubah Jabatan', saveJob, removeJob);
	});
}

function saveJob() {
	var uri = base_url + ($('#frmJob [name="ID"]').val() == '0' ? 'job/create' : 'job/update');
	saveRecord('frmJob', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdJob').trigger('reloadGrid');
		closeModal('frmJob');
	});
}

function removeJob() {
	var uri = base_url + 'job/delete';
	removeRecord('frmJob', 'Anda akan menghapus jabatan ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdJob').trigger('reloadGrid');
		closeModal('frmJob');
	});
}

var timer;
function searchJob() {
	clearTimeout(timer);
	timer = setTimeout(function() {
		var $uri = 'job/query';

		var $key = $('#txtFind').val();
		if ($key != '') $uri = 'job/query/?find='+$key;
	
		$("#grdJob").setGridParam({
			url: base_url + $uri,
			page: 1
		}).trigger("reloadGrid");

	}, 500);
}	