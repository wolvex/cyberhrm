var save_method; //for save method string
var table;

$(document).ready(function() {
    document.title = 'CyberHRM - Profil Perusahaan';
    
	$('.ui.accordion').accordion('open', 0);
    $('#mnuCompany').state('activate');
	 
    $('#registered_at').calendar({
		type: 'date'
	});		
	$('#established_at').calendar({
		type: 'date'
    });
    
    //populate form
    waitMode(true);
    $.ajax({
        url : base_url + 'company/get',
        type: "POST",
        dataType: "JSON",
        success: function(data) { 
            waitMode(false);
			bindForm('frmCompany', data.payload);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            waitMode(false);
            toast('Gagal terhubung dengan server');
        }
    });
});

function save() {
    $('#cmdSave').attr('disabled',true); //set button disable 
 
    // ajax adding data to database
    waitMode(true);
    $.ajax({
        url : base_url + 'company/save',
        type: "POST",
        data: $('#frmCompany').serialize(),
        dataType: "JSON",
        success: function(data) { 
            waitMode(false);
			if(data.status) { //if success close modal and reload ajax table
				toast("Data tersimpan", "success");
				//location.reload();
			} else {
				toast(data.error, "error");
			}
			$('#cmdSave').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown) {
            waitMode(false);
            toast('Gagal menyimpan data');
			$('#cmdSave').attr('disabled',false); //set button enable 
        }
    });
}