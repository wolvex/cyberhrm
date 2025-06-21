$(document).ready(function() {
	$("#grdStudy").jqGrid({
		url: base_url + 'study/query/' + $('#EmployeeID').val(),
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Institusi', name: 'INSTITUTE', width: 150, align: 'center' },
			{ label: 'Jenis', name: 'TYPE_NAME', width: 80, align: 'center' },
			{ label: 'Sertifikasi', name: 'DESCRIPTION', width: 120, align: 'center' },
			{ label: 'Lulus', name: 'GRADUATED_AT', width: 80, align: 'center' }
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

function addStudy() {
	addCombo('cmbCert', 'certificate', undefined, function(){
		addNewMode('frmStudy');
		openModal('frmStudy', 'Pendidikan Baru', saveStudy);
		//set default values
		$('#frmStudy input[name="ID"]').val("0");
	});
}

function editStudy(id) {
	var uri = base_url + "study/get/" + id;
	queryRecord(uri, function(data){
		addCombo('cmbCert', 'certificate', undefined, function(){
			editMode('frmStudy', data.payload);
			openModal('frmStudy', 'Ubah Pendidikan', saveStudy, removeStudy);

			$('#frmStudy input[name="EMP_ID"]').val($('#EmployeeID').val());
		});
	});
}

function saveStudy() {
	$('#frmStudy input[name="EMP_ID"]').val($('#EmployeeID').val());

	var uri = base_url + ($('#frmStudy input[name="ID"]').val() == '0' ? 'study/create' : 'study/update');
	saveRecord('frmStudy', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdStudy').trigger('reloadGrid');
		closeModal('frmStudy');
	});
}

function removeStudy() {
	var uri = base_url + 'study/delete';
	removeRecord('frmStudy', 'Anda akan menghapus data ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdStudy').trigger('reloadGrid');
		closeModal('frmStudy');
	});
}