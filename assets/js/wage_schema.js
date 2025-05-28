$(document).ready(function() {
	$("#grdSchema").jqGrid({
		url: base_url + 'wageschema/query/' + $('#EmployeeID').val(),
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Gaji Pokok (Rp)', name: 'GROSS_WAGE', width: 120, align: 'center', formatter: 'number' },
			{ label: 'Skema Tunjangan', name: 'SCHEMA_NAME', width: 200, align: 'left' },
			{ label: 'Efektif', name: 'EFFECTIVE_AT', width: 80, align: 'center' },
			//{ label: 'Tidak Berlaku', name: 'EXPIRE_AT', width: 80, align: 'center' },
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

function addSchema() {
	addCombo('cmbSchema', 'schema', undefined, function(){
		addNewMode('frmSchema');
		openModal('frmSchema', 'Skema Baru', saveSchema);
		//set default values
		$('#frmSchema [name="EMP_NAME"]').val($('#frmEmployee [name="NAME"]').val() + ' (' + $('#frmEmployee [name="CODE"]').val() + ')');
		$('#frmSchema input[name="ID"]').val("0");
		$('#frmSchema #cmdSave').css('display', 'inline');
		$('#frmSchema #cmdDelete').css('display', 'none');
	});
}

function editSchema(id) {
	var uri = base_url + "wageschema/get/" + id;
	queryRecord(uri, function(data){
		addCombo('cmbSchema', 'schema', undefined, function(){
			editMode('frmSchema', data.payload);
			openModal('frmSchema', 'Skema Pegawai', undefined, removeSchema);
			$('#frmSchema #cmdSave').css('display', 'none');
			$('#frmSchema #cmdDelete').css('display', 'inline');
		});
	});
}

function saveSchema() {
	$('#frmSchema input[name="EMP_ID"]').val($('#EmployeeID').val());

	var uri = base_url + 'wageschema/create';
	saveRecord('frmSchema', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdSchema').trigger('reloadGrid');
		closeModal('frmSchema');
	});
}

function removeSchema() {
	var uri = base_url + 'wageschema/delete';
	removeRecord('frmSchema', 'Anda akan menghapus data ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdSchema').trigger('reloadGrid');
		closeModal('frmSchema');
	});
}