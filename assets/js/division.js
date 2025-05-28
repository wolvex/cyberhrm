$(document).ready(function() {
	$("#grdDiv").jqGrid({
		url: 'division/query',
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Kode', name: 'CODE', width: 100, align: 'center' },
			{ label: 'Divisi', name: 'NAME', width: 250, align: 'left' },
			{ label: 'Departemen', name: 'DEPT_NAME', width: 250, align: 'left' }
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

function showDivForm() {
	$('#mdlDiv').modal({
		closable: false,
		onDeny: function(e) {
			if (e.attr("id") == 'cmdDeleteDiv') {
				removeDiv(); return false;
			} else {
				return true;
			}
		},
		onApprove: function() {
			saveDiv(); return false;
		}
	}).modal('show'); // show bootstrap modal when complete loaded
}

function addDiv() {
	editDiv(0);
}

function editDiv(id) {
	addCombo('cmbParent', 'department');

	$('#frmDiv').trigger("reset"); // reset form on modals
	
	if (id == 0) {
		//add new record
		$('#cmdDeleteDiv').css('display','none');
		$('[name="ID"]').val("0");
		$('#ttlDiv').text('Tambah Divisi');
		showDivForm();

	} else {
		//update existing record
		$('#cmdDeleteDiv').css('display','inline');
		$('#ttlDiv').text('Ubah Divisi');
		
		//Ajax Load data from ajax
		$.ajax({
			url : "division/queryById/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				if (data.status) {
					$('[name="ID"]').val(data.dept.ID);
					$('[name="CODE"]').val(data.dept.CODE);
					$('[name="NAME"]').val(data.dept.NAME);
					$('[name="DEPT_NAME"]').val(data.dept.DEPT_NAME);

					showDivForm();
				} else {
					toast("Data tidak ditemukan", "error");
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				toast("Gagal berkomunikasi dengan server", "error");
			}
		});
	}
}
 
function saveDiv() {
    $('#cmdSaveDiv').attr('disabled',true); //set button disable 
	
    // ajax adding data to database
    $.ajax({
        url : $('[name="ID"]').val() == '0' ? 'division/create' : 'division/update',
        type: "POST",
        data: $('#frmDiv').serialize(),
		dataType: "JSON",
        success: function(data) { 
			$('#cmdSaveDiv').attr('disabled',false); //set button enable 
            if(data.status) {
				toast("Data berhasil disimpan","success");
				$('#grdDiv').trigger('reloadGrid');
				$('#mdlDiv').modal('hide');
            } else {
				toast(data.error,'error');
			}
        },
        error: function (jqXHR, textStatus, errorThrown) {
            toast('Maaf, data tidak dapat disimpan','error');
			$('#cmdSaveDiv').attr('disabled',false); //set button enable
        }
	});
}

function removeDiv() {	
	confirm('Anda akan menghapus data ini. Lanjutkan ?', 'warning', function(){
		// ajax adding data to database
		$.ajax({
			url : 'division/delete',
			type: "POST",
			data: $('#frmDiv').serialize(),
			dataType: "JSON",
			success: function(data) { 
				$('#cmdDeleteDiv').attr('disabled',false); //set button enable 
				if(data.status) {
					toast("Data berhasil dihapus","success");
					$('#grdDiv').trigger('reloadGrid');
					$('#mdlDiv').modal('hide');
				} else {
					toast(data.error,'error');
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				toast('Maaf, data tidak dapat dihapus','error');
				$('#cmdDeleteDiv').attr('disabled',false); //set button enable
			}
		});
	});
}