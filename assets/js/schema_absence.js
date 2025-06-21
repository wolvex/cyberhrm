$(document).ready(function() {
	$("#grdAbsence").jqGrid({
		url: base_url + 'schema/absence/'+$('#SchemaID').val(),
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Jenis Cuti/Ijin', name: 'NAME', width: 150, align: 'left' },
			{ label: 'Qouta Tahunan (hari)', name: 'ANNUAL_QUOTA', width: 100, align: 'right', formatter: 'integer' },
			{ label: 'Max. Carry Over (hari)', name: 'CARRY_OVER', width: 100, align: 'right', formatter: 'integer' }
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

function editAbsence(id) {
	var uri = base_url + "schema/absence/" + $('#SchemaID').val() + "/"  + id;
	queryRecord(uri, function(data){
		editMode('frmAbsence', data.payload);
		openModal('frmAbsence', 'Quota Ijin/Cuti', saveAbsence);
	});
}

function saveAbsence() {
	var uri = base_url + 'schema/updateAbsence';
	saveRecord('frmAbsence', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdAbsence').trigger('reloadGrid');
		closeModal('frmAbsence');
	});
}