var adding = false;

$(document).ready(function() {
	document.title = 'CyberHRM - Pengguna';
	
	$('.ui.accordion').accordion('open', 0);
	$('#mnuUsers').state('activate');

	$("#grdUsers").jqGrid({
		url: base_url + 'users/query',
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 40, align: 'center' },
			{ label: 'ID', name: 'ID', width: 60, align: 'center' },
			{ label: 'Nama', name: 'NAME', width: 150, align: 'left' },
			{ label: 'Role', name: 'ROLE', width: 100, align: 'center' },
			{ label: 'Email', name: 'EMAIL', width: 200, align: 'center' },
			{ label: 'Login Terakhir', name: 'LAST_LOGIN', width: 80, align: 'center' }
		],
		page: 1,
		//width: 900,
		height: 250,
		rowNum: 200,
		scroll: 1,
		shrinkToFit : false,
		emptyrecords: 'Scroll to bottom to retrieve new page'
        //pager: "#grdPage"
	});
});

function addUsers() {
	adding = true;
	addNewMode('frmUsers');
	openModal('frmUsers', 'Pengguna Baru', saveUsers, removeUsers);
}

function editUsers(id) {
	adding = false;
	var uri = base_url + "users/get/" + id;
	queryRecord(uri, function(data){
		editMode('frmUsers', data.payload);
		openModal('frmUsers', 'Ubah Profil Pengguna', saveUsers, removeUsers);
	});
}

function saveUsers() {
	var p1 = $('[name="PASSWORD"]').val();
	var p2 = $('[name="CONFIRMPASSWORD"]').val();

	if (p1 != '' && p1 != p2) {
		toast("Password dan Confirm Password tidak sama", "error");
		return;
	}
	
	var uri = base_url + (adding ? 'users/create' : 'users/update');
	saveRecord('frmUsers', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdUsers').trigger('reloadGrid');
		closeModal('frmUsers');
		adding = false;
	});
}

function removeUsers(id) {
	if (id == 'cmdReset') {
		var uri = base_url + 'users/reset';
		saveRecord('frmUsers', uri, function() {
			toast("Password berhasil direset","success");
			$('#grdUsers').trigger('reloadGrid');
			closeModal('frmUsers');
		});
	} else {
		var uri = base_url + 'users/delete';
		removeRecord('frmUsers', 'Anda akan menghapus data ini. Lanjutkan ?', uri, function() {
			toast("Data berhasil dihapus","success");
			$('#grdUsers').trigger('reloadGrid');
			closeModal('frmUsers');
		});
	}
	adding = false;
}
