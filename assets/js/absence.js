$(document).ready(function() {
	$("#grdAbsence").jqGrid({
		url: base_url + 'absence/query',
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Kode', name: 'CODE', width: 70, align: 'center' },
			{ label: 'Deskripsi', name: 'DESCRIPTION', width: 200, align: 'left' },
			{ label: 'Jenis', name: 'TYPE', width: 100, align: 'center' }
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

function showAbsenceForm() {
	$('#mdlAbsence').modal({
		closable: false,
		onDeny: function(e) {
			if (e.attr("id") == 'cmdDeleteAbsence') {
				removeAbsence(); return false;
			} else {
				return true;
			}
		},
		onApprove: function() {
			saveAbsence(); return false;
		}
	}).modal('show'); // show bootstrap modal when complete loaded
	
	$('#cmbAbsence').dropdown();
}

function addAbsence() {
	addNewMode('frmAbsence');
	openModal('frmAbsence', 'Kode Absensi Baru', saveAbsence);
	//default values
	$('#frmAbsence input[name="ID"]').val("0");
}

function editAbsence(id) {
	var uri = base_url + "absence/get/" + id;
	queryRecord(uri, function(data){
		editMode('frmAbsence', data.payload);
		openModal('frmAbsence', 'Ubah Absensi', saveAbsence, removeAbsence);
	});
}
 
function saveAbsence() {
	var uri = base_url + ($('#frmAbsence input[name="ID"]').val() == '0' ? 'absence/create' : 'absence/update');
	saveRecord('frmAbsence', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdAbsence').trigger('reloadGrid');
		$('#mdlAbsence').modal('hide');
	});
}

function removeAbsence() {
	var uri = base_url + 'absence/delete';
	removeRecord('frmAbsence', 'Anda akan menghapus data ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdAbsence').trigger('reloadGrid');
		$('#mdlAbsence').modal('hide');
	});
}