$(document).ready(function() {
	document.title = 'CyberHRM - Gaji Pegawai';
	
	$('.ui.accordion').accordion('open', 2);
	$('#mnuWages').state('activate');
	$('.menu .item').tab();	

	//populate data
	$.ajax({
		url : base_url + "employee/get/" + $('#EmployeeID').val(),
		type: "GET",
		dataType: "JSON",
		success: function(data) {
			if (data.status) {
				//data found, populate form
				bindForm('frmEmployee', data.payload);
				$('[name="BORNED_AT"]').val(moment(data.payload.BORNED_AT).format("MMMM DD, YYYY"));
				$('[name="JOINED_AT"]').val(moment(data.payload.JOINED_AT).format("MMMM DD, YYYY"));
				//$('[name="RESIGNED_AT"]').val(moment(data.employee.RESIGNED_AT).format("MMMM DD, YYYY"));
				$('#divTitle').text(data.payload.NAME);
				$('#divSubtitle').text('NIP: '+data.payload.CODE);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			toast("Gagal berkomunikasi dengan server", "error");
		}
	});

	$("#grdCarier").jqGrid({
		url: base_url + 'carier/query/' + $('#EmployeeID').val(),
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Jabatan', name: 'JOB_TITLE', width: 150, align: 'center' },
			{ label: 'Departemen', name: 'DEPT_NAME', width: 120, align: 'center' },
			{ label: 'Grade', name: 'GRADE_NAME', width: 70, align: 'center' },
			{ label: 'Status', name: 'STATUS_NAME', width: 70, align: 'center' },
			{ label: 'Supervisor', name: 'SPV_NAME', width: 150, align: 'center' },
			{ label: 'Manager', name: 'MGR_NAME', width: 150, align: 'center' },
			{ label: 'Efektif', name: 'EFFECTIVE_AT', width: 80, align: 'center' }
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