$(document).ready(function() {
	$("#grdFamily").jqGrid({
		url: base_url + 'family/query/' + $('#EmployeeID').val(),
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Nama', name: 'NAME', width: 150, align: 'center' },
			{ label: 'Hubungan', name: 'RELATION_NAME', width: 100, align: 'center' },
			{ label: 'Lahir', name: 'BORNED_AT', width: 80, align: 'center' },
			{ label: 'Tanggungan', name: 'STATUS', width: 80, align: 'center' },
			{ label: 'Nomor KTP', name: 'CITIZEN_ID', width: 150, align: 'center' },
			{ label: 'Nomor KK', name: 'FAMILY_ID', width: 150, align: 'center' },
			{ label: 'Nomor Telp', name: 'PHONE_NO', width: 100, align: 'center' }
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

function addFamily() {
	addNewMode('frmFamily');
	openModal('frmFamily', 'Anggota Keluarga Baru', saveFamily);
	//set default values
	$('#frmFamily input[name="ID"]').val("0");
}

function editFamily(id) {
	var uri = base_url + "family/get/" + id;
	queryRecord(uri, function(data){
		editMode('frmFamily', data.payload);
		openModal('frmFamily', 'Ubah Anggota Keluarga', saveFamily, removeFamily);

		$('#frmFamily input[name="EMP_ID"]').val($('#EmployeeID').val());
	});
}

function saveFamily() {
	$('#frmFamily input[name="EMP_ID"]').val($('#EmployeeID').val());

	var uri = base_url + ($('#frmFamily input[name="ID"]').val() == '0' ? 'family/create' : 'family/update');
	saveRecord('frmFamily', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdFamily').trigger('reloadGrid');
		closeModal('frmFamily');
	});
}

function removeFamily() {
	var uri = base_url + 'family/delete';
	removeRecord('frmFamily', 'Anda akan menghapus data ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdFamily').trigger('reloadGrid');
		closeModal('frmFamily');
	});
}