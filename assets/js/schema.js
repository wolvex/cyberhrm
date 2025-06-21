$(document).ready(function() {
	document.title = 'CyberHRM - Skema Tunjangan & Potongan Pegawai';
	
	$('.ui.accordion').accordion('open', 2);
	$('#mnuSchema').state('activate');
	$('.menu .item').tab({
		onVisible: function(path){
			//alert(path);
		}
	});

	$('#effective_at').calendar({
		type: 'date'
	});

	//populate data
	$.ajax({
		url : base_url + "schema/get/" + $('#SchemaID').val(),
		type: "GET",
		dataType: "JSON",
		success: function(data) {
			if (data.status) {
				//data found, populate form
				editSchema(data.payload);
			} else {
				//data not found, go into addnew mode
				addSchema();
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			toast("Gagal berkomunikasi dengan server", "error");
		}
	});
});

function addSchema() {
	$('#frmSchema').trigger('reset');

	$('#divTitle').text("Skema Baru");
	$('#divSubtitle').text("Pembuatan skema baru");
	
	$('[name="ID"]').val("0");
	
	$('[data-tab="grade"]').remove();
	$('[data-tab="shift"]').remove();
	$('[data-tab="overtime"]').remove();
	$('[data-tab="absence"]').remove();
	
	$('#cmdAddSchema').remove();
	$('#cmdDeleteSchema').remove();
	$('#cmdApplySchema').remove();
	$('#cmdApproveSchema').remove();
	$('#cmdCopySchema').remove();
}

function editSchema(data) {
	bindForm('frmSchema', data);
	bindForm('frmCommon', data);
	bindForm('frmOthers', data);
	$('#divTitle').text('Skema Tunjangan & Potongan');
	$('#divSubtitle').text('Skema tunjangan & potongan pegawai');

	switch (data.STATUS) {
		case 'D':
			$('#cmdApproveSchema').html('Setujui<i class="thumbs up icon">');
			$('#cmdApplySchema').remove(); break;
		case 'A':
			$('#cmdApproveSchema').html('Set Default<i class="thumbs up icon">'); break;
		default:
			$('#cmdApproveSchema').remove(); break;
	}
}

function approveSchema() {
	var url = base_url + 'schema/';
	var status = $('[name="STATUS"]');
	var cmd = $('#cmdApproveSchema');

	switch (status.val()) {
		case 'D':
			url = url + 'approve'; break;
		case 'A':
			url = url + 'setDefault'; break;
	}

	//enable STATUS so that can be transmitted in POST
	status.prop("disabled", false);
	cmd.prop('disabled', true);

	$.ajax({
        url : url,
        type: "POST",
        data: $('#frmSchema').serialize(),
		dataType: "JSON",
        success: function(data) { 
			cmd.prop('disabled', false);
			status.prop('disabled', true);
            if(data.status) {
				toast("Skema berhasil disetujui", "success");
				location.reload();
            } else {
				toast(data.error,'error');
			}
        },
        error: function (jqXHR, textStatus, errorThrown) {
			cmd.prop('disabled', false);
			status.prop('disabled', true);
            toast('Maaf, skema tidak dapat diproses','error');
        }
	});
}

function applySchema(b) {
	if ( !b ) {
		modalSchema = true;
		$('#mdlApply').modal({
			closable: false,
			onApprove: function() {
				applySchema(true); return false;
			}
		}).modal('show'); 

	} else {

		modalSchema = false;
		var cmd = $('#cmdApplySchema');

		//enable STATUS so that can be trasmitted in POST
		$('[name="STATUS"]').prop("disabled", false);
		cmd.prop("disabled", true);

		//waitMode(true);
		$.ajax({
			url : base_url + 'schema/apply',
			type: "POST",
			data: $('#frmSchema').serialize() + '&' + $('#frmApply').serialize(),
			dataType: "JSON",
			success: function(data) { 
				$('[name="STATUS"]').prop("disabled", true);
				cmd.prop("disabled", false);
				//waitMode(false);
				if(data.status) {
					toast("Skema berhasil di-apply ke " + data.payload.rows + " pegawai", "advise");
					setTimeout(location.reload(), 5000);
				} else {
					toast(data.error,'error');
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				//waitMode(false);
				$('[name="STATUS"]').prop("disabled", true);
				cmd.prop("disabled", false);
				toast('Maaf, skema tidak dapat diproses','error');			
			}
		});
	}
}

function copySchema() {
	var cmd = $('#cmdCopySchema');	
	cmd.prop('disabled',true);

    // ajax adding data to database
    $.ajax({
        url : base_url + 'schema/copy',
        type: "POST",
        data: $('#frmSchema').serialize(),
		dataType: "JSON",
        success: function(data) { 
			cmd.prop('disabled',false);
            if(data.status) {
				toast("Skema berhasil dicopy","success");
				if (data.payload.ID != '0') {
					document.location = base_url + 'schema/view/'+data.payload.ID;
				}
            } else {
				toast(data.error,'error');
			}
        },
        error: function (jqXHR, textStatus, errorThrown) {
			cmd.prop('disabled',false);
            toast('Maaf, skema tidak dapat dicopy','error');
        }
	});
}

function saveSchema() {
	var cmd = $('#cmdSaveSchema');	
	cmd.prop('disabled',true); //set button disable 
	
	var params = $('#frmSchema').serialize() + '&' + $('#frmCommon').serialize() + '&' + $('#frmOthers').serialize();

    // ajax adding data to database
    $.ajax({
        url : base_url + ($('#SchemaID').val() == '0' ? 'schema/create' : 'schema/update'),
        type: "POST",
        data: params,
		dataType: "JSON",
        success: function(data) { 
			cmd.prop('disabled',false); //set button enable 
            if(data.status) {
				toast("Data berhasil disimpan","success");
				if (data.payload.ID != '0') {
					document.location = base_url + 'schema/view/'+data.payload.ID;
				}
            } else {
				toast(data.error,'error');
			}
        },
        error: function (jqXHR, textStatus, errorThrown) {
            toast('Maaf, data tidak dapat disimpan','error');
			cmd.prop('disabled',false); //set button enable
        }
	});
}

function deleteSchema() {
	var cmd = $('#cmdDeleteSchema');
	
	cmd.prop('disabled',true); //set button disable 
	
	confirm('Anda akan menghapus data ini. Lanjutkan ?', 'warning', function(){
		// ajax adding data to database
		$.ajax({
			url : base_url + 'schema/delete',
			type: "POST",
			data: $('#frmSchema').serialize(),
			dataType: "JSON",
			success: function(data) { 
				cmd.prop('disabled',false); //set button enable 
				if(data.status) {
					toast("Data berhasil dihapus","success");
					document.location = base_url + 'schemas';
				} else {
					toast(data.error,'error');
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				toast('Maaf, data tidak dapat dihapus','error');
				cmd.prop('disabled',false); //set button enable
			}
		});
	});
}