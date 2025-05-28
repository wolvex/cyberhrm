$(document).ready(function() {
	document.title = 'CyberHRM - Skema Tunjangan & Potongan';
	
	$('.ui.accordion').accordion('open', 2);
	$('#mnuSchema').state('activate');

	$("#grdSchema").jqGrid({
		url: 'schemas/query',
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 25, align: 'center' },
			{ label: 'Nama', name: 'NAME', width: 150, align: 'left' },
			{ label: 'Deskripsi', name: 'DESCRIPTION', width: 300, align: 'left' },
			{ label: 'Efektif', name: 'EFFECTIVE_AT', width: 70, align: 'center' },
			{ label: 'Status', name: 'STATUS', width: 70, align: 'center' },
			{ label: 'Total Pegawai', name: 'TOTAL_EMPLOYEE', width: 80, align: 'center' },
		],
		page: 1,
		//width: 900,
		height: 300,
		rowNum: 1000,
		scroll: 1,
		shrinkToFit : false,
		emptyrecords: 'Scroll to bottom to retrieve new page'
        //pager: "#grdPage"
	});
});

function addSchema() {
	window.open(base_url + 'schema/view/0', '_blank');
}

function editSchema(id) {
	window.open(base_url + 'schema/view/'+id, '_blank');
}