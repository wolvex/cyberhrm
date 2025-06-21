$(document).ready(function() {
	document.title = 'CyberHRM - Pegawai';
	
	$('.ui.accordion').accordion('open', 1);
	$('#mnuEmployees').state('activate');
	$('.menu .item').tab();	

	$('#joined_at').calendar({
		type: 'date'
	});

	$('#borned_at').calendar({
		type: 'date'
	});

	$('#resigned_at').calendar({
		type: 'date'
	});

	//populate data
	$.ajax({
		url : base_url + "employee/get/" + $('#EmployeeID').val(),
		type: "GET",
		dataType: "JSON",
		success: function(data) {
			if (data.status) {
				//data found, populate form
				editEmployee(data.payload);
			} else {
				//data not found, go into addnew mode
				addEmployee();
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			toast("Gagal berkomunikasi dengan server", "error");
		}
	});
});

function addEmployee() {
	addNewMode('frmEmployee');

	$('#frmEmployee').trigger('reset');

	$('#divTitle').text("Pegawai Baru");
	$('#divSubtitle').text("Pendaftaran pegawai baru");
	
	$('#frmEmployee [name="CODE"]').prop('readonly',false);
	$('#frmEmployee [name="ID"]').val("0");
	
	$('[data-tab="carier"]').remove();
	$('[data-tab="study"]').remove();
	$('[data-tab="family"]').remove();
	$('[data-tab="document"]').remove();
	
	$('#frmEmployee #cmdAdd').remove();
	$('#frmEmployee #cmdDelete').remove();
	$('#frmEmployee #cmdAdd').remove();
}

function editEmployee(data) {
	bindForm('frmEmployee', data);
	$('#divTitle').text(data.NAME);
	$('#divSubtitle').text('NIP: '+data.CODE);
}

function saveEmployee() {
	var uri = base_url + ($('#frmEmployee [name="ID"]').val() == '0' ? 'employee/create' : 'employee/update');
	saveRecord('frmEmployee', uri, function(data) {
		toast("Data berhasil disimpan","success");
		if (data.payload.ID != '0') {
			document.location = base_url + 'employee/view/'+data.payload.ID;
		}
	});
}

function deleteEmployee() {
	var uri = base_url + 'employee/delete';
	removeRecord('frmEmployee', 'Anda akan menghapus data pegawai ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		document.location = base_url + 'employees';
	});
}