$(document).ready(function() {
	$("#grdShift").jqGrid({
		url: base_url + 'schema/shift/'+$('#SchemaID').val(),
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Shift', name: 'NAME', width: 150, align: 'left' },
			{ label: 'Jam Masuk', name: 'CLOCK_IN', width: 70, align: 'center' },
			{ label: 'Jam Pulang', name: 'CLOCK_OUT', width: 70, align: 'center' },
			{ label: 'Premi Hadir (Rp)', name: 'PREMI_ATTENDANCE', width: 70, align: 'right', formatter: 'number' },
			{ label: 'Premi Shift (Rp)', name: 'PREMI_SHIFT', width: 70, align: 'right', formatter: 'number' },
			{ label: 'Uang Makan (Rp)', name: 'ALLOWANCE', width: 70, align: 'right', formatter: 'number' }
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

function editShift(id) {
	var uri = base_url + "schema/shift/" + $('#SchemaID').val() + "/"  + id;
	queryRecord(uri, function(data){
		editMode('frmShift', data.payload);
		openModal('frmShift', 'Tunjangan Hadir', saveShift);
	});
}

function saveShift() {
	var uri = base_url + 'schema/updateShift';
	saveRecord('frmShift', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdShift').trigger('reloadGrid');
		closeModal('frmShift');
	});
}