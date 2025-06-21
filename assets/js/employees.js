$(document).ready(function() {
	document.title = 'CyberHRM - Pegawai';
	
	$('.ui.accordion').accordion('open', 1);
	$('#mnuEmployees').state('activate');

	$("#grdEmployee").jqGrid({
		url: 'employees/query',
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 25, align: 'center', exportcol: false },
			{ label: 'NIK', name: 'CODE', width: 70, align: 'center' },
			{ label: 'Nama', name: 'NAME', width: 150, align: 'left' },
			{ label: 'Jenis Kelamin', name: 'GENDER_NAME', width: 50, align: 'center' },
			{ label: 'Departemen', name: 'DEPT_NAME', width: 120, align: 'center' },
			{ label: 'Grade', name: 'GRADE_NAME', width: 70, align: 'center' },
			{ label: 'Bergabung', name: 'JOINED_AT', width: 70, align: 'center' },
			{ label: 'KTP', name: 'CITIZEN_ID', width: 120, align: 'center' },
			{ label: 'NPWP', name: 'NPWP', width: 120, align: 'center' },
			{ label: 'BPJS KTK', name: 'BPJS_KTK', width: 120, align: 'center' },
			{ label: 'BPJS KSH', name: 'BPJS_KSH', width: 120, align: 'center' },
		],
		page: 1,
		autowidth: true,
		loadonce: true,
		viewrecords: true,
		height: 300,
		rowNum: 1000,
		scroll: 0,
		shrinkToFit : false,
		loadComplete: function() {
			$('#employeeCount').text("Ditemukan "+ $(this).getGridParam("reccount")+" pegawai");
		}
		//emptyrecords: 'Scroll to bottom to retrieve new page'
        //pager: "#grdPage"
	});

	$("#cmdExport").on("click", function(){
		$("#grdEmployee").jqGrid("exportToCsv",{
			separator: ",",
			separatorReplace : "", // in order to interpret numbers
			quote : '"', 
			escquote : '"', 
			newLine : "\r\n", // navigator.userAgent.match(/Windows/) ?	'\r\n' : '\n';
			replaceNewLine : " ",
			includeCaption : true,
			includeLabels : true,
			includeGroupHeader : true,
			includeFooter: true,
			fileName : "employee.csv",
			returnAsString : false
		})
	});

	$('#cmdAdd').on("click", function() {
		addEmployee();
	});
});

var t;
function searchEmployee() {
	clearTimeout(t);
	t = setTimeout(function(){
		var q = 'filter=0';
		
		var s = $('#txtFind').val().trim();
		if (s != '') q += '&find='+s;

		s = '';
		$("input:checked").each(function(){
			switch ($(this).prop('name')) {
				case 'chkPermanen': s += 'P'; break;
				case 'chkKontrak': s += 'K'; break;
				case 'chkHarian': s += 'H'; break;
			}
		});
		if (s != '') q += '&emp_status=' + s;
		
		$("#grdEmployee").setGridParam({
			url: 'employees/query/?'+q,
			datatype: "json",
			rowNum: 1000,
			page: 1
		}).trigger('reloadGrid');	
	}, 500);
}

function addEmployee() {
	window.open('employee/view/0', '_blank');
}

function editEmployee(id) {
	window.open('employee/view/'+id, '_blank');
}