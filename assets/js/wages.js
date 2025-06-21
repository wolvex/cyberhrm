$(document).ready(function() {
	document.title = 'CyberHRM - Gaji Pegawai';
	
	$('.ui.accordion').accordion('open', 2);
	$('#mnuWages').state('activate');

	$("#grdEmployee").jqGrid({
		url: 'wages/query/?emp_status=PHK',
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 40, align: 'center' },
			{ label: 'NIK', name: 'CODE', width: 70, align: 'center' },
			{ label: 'Nama', name: 'NAME', width: 200, align: 'left' },
			{ label: 'No KTP', name: 'CITIZEN_ID', width: 120, align: 'center' },
			{ label: 'Skema', name: 'SCHEMA_NAME', width: 200, align: 'left' }
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

var t;
function searchEmployee() {
	clearTimeout(t);
	t = setTimeout(function(){
		var q = 'wages/query';		
		var s = '';
		$("input:checked").each(function(){
			switch ($(this).prop('name')) {
				case 'chkPermanen': s += 'P'; break;
				case 'chkKontrak': s += 'K'; break;
				case 'chkHarian': s += 'H'; break;
			}
		});
		if (s == '') s = 'PHK';
		q += '/?emp_status=' + s;
		
		s = $('#txtFind').val().trim();
		if (s != '') q += '&find='+s;
		
		$("#grdEmployee").setGridParam({
			url: q,
			page: 1
		}).trigger('reloadGrid');	
	}, 500);
}

function editEmployee(id) {
	window.open('wage/view/'+id, '_blank');
}