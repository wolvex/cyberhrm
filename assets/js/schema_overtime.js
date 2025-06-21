$(document).ready(function() {
	$("#grdOvr").jqGrid({
		url: base_url + 'schema/overtime/'+$('#SchemaID').val(),
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Jenis Lembur', name: 'NAME', width: 150, align: 'left' },
			{ label: 'Jam Ke-', name: 'HOUR1', width: 70, align: 'center' },
			{ label: 'Faktor', name: 'FACTOR1', width: 70, align: 'center', formatter: 'number' },
			{ label: 'Jam Ke-', name: 'HOUR2', width: 70, align: 'center' },
			{ label: 'Faktor', name: 'FACTOR2', width: 70, align: 'center', formatter: 'number' },
			{ label: 'Jam Ke-', name: 'HOUR3', width: 70, align: 'center' },
			{ label: 'Faktor', name: 'FACTOR3', width: 70, align: 'center', formatter: 'number' },
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

function editOvertime(id) {
	var uri = base_url + "schema/overtime/" + $('#SchemaID').val() + "/"  + id;
	queryRecord(uri, function(data){
		editMode('frmOvr', data.payload);
		openModal('frmOvr', 'Tunjangan Hadir', saveOvertime);
	});
}

function saveOvertime() {
	var uri = base_url + 'schema/updateOvertime';
	saveRecord('frmOvr', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdOvr').trigger('reloadGrid');
		closeModal('frmOvr');
	});
}