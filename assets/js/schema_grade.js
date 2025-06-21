$(document).ready(function() {
	$("#grdGrade").jqGrid({
		url: base_url + 'schema/grade/'+$('#SchemaID').val(),
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Jabatan', name: 'DESCRIPTION', width: 200, align: 'left' },
			{ label: 'Tunjangan (Rp)', name: 'ALLOWANCE', width: 100, align: 'right', formatter: 'number' }
		],
		page: 1,
		//width: 900,
		height: 200,
		rowNum: 20,
		scroll: 1,
		shrinkToFit : false,
		emptyrecords: 'Scroll to bottom to retrieve new page'
        //pager: "#grdPage"
	});
});


function editGrade(id) {
	var uri = base_url + "schema/grade/" + $('#SchemaID').val() + "/"  + id;
	queryRecord(uri, function(data){
		editMode('frmGrade', data.payload);
		openModal('frmGrade', 'Tunjangan Jabatan', saveGrade);
	});
}

function saveGrade() {
	var uri = base_url + 'schema/updateGrade';
	saveRecord('frmGrade', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdGrade').trigger('reloadGrid');
		closeModal('frmGrade');
	});
}