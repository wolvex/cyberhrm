$(document).ready(function() {
	$("#grdDoc").jqGrid({
		url: base_url + 'empdoc/query/' + $('#EmployeeID').val(),
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 80, align: 'center' },
			{ label: 'Deskripsi', name: 'DESCRIPTION', width: 350, align: 'left', cellattr: function (rowId, tv, rawObject, cm, rdata) { return 'style="white-space: normal;"' } },
			{ label: 'Nama File', name: 'FILE_NAME', width: 200, align: 'left' },
			{ label: 'Tanggal Upload', name: 'MODIFIED_AT', width: 80, align: 'center' },
			{ label: 'Diupload Oleh', name: 'MODIFIED_BY', width: 80, align: 'center' }
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

	var btn = document.getElementById('cmdUpload'),
		progressBar = document.getElementById('progressBar'),
		progressOuter = document.getElementById('progressOuter');

	var uploader = new ss.SimpleUpload({
		button: btn,
		url: base_url + 'empdoc/upload',
		name: 'FILE_NAME',
		multipart: true,
		maxSize: 1024 * 5,
		allowedExtensions: ["jpg", "png", "gif", "bmp", "docx", "pdf"],
		responseType: 'json',
		startXHR: function() {
			progressOuter.style.display = 'block'; // make progress bar visible
			this.setProgressBar( progressBar );
		},
		onSubmit: function() {
			uploader.setData({
				ID: '0',
				EMP_ID: $('#EmployeeID').val(),
				DESCRIPTION: $('#fileDescription').val()
			});
			btn.innerHTML = 'Uploading...'; // change button text to "Uploading..."
		},
		onComplete: function( filename, response ) {
			progressOuter.style.display = 'none'; // hide progress bar when upload is completed
			btn.innerHTML = 'Upload File';
			if ( !response ) {
				toast("File tidak berhasil diupload", "error");
			} else if ( response.status ) {
				toast("File berhasil diupload", "success");
				$('#grdDoc').trigger('reloadGrid');
				$('#mdlDoc').modal('hide');				
			} else {
				if ( response.error )  {
					toast(response.error, "error");
				} else {
					toast("File tidak berhasil diupload", "error");
				}
			}
		},
		onError: function() {
			progressOuter.style.display = 'none';
			btn.innerHTML = 'Upload File';
			toast("File tidak berhasil diupload", "error");
		}
	});

});

function addDoc() {
	$('#frmDoc').trigger("reset"); // reset form on modals

	$('#frmDoc input[name="EMP_ID"]').val($('#EmployeeID').val());
	$('#frmDoc input[name="ID"]').val("0");
	$('#ttlDoc').text('Dokumen Baru');

	$('#mdlDoc').modal({
		closable: true
	}).modal('show'); // show bootstrap modal when complete loaded
}

function deleteDoc(id) {
	confirm('Anda akan menghapus file ini. Lanjutkan ?', 'warning', function(){
		// ajax adding data to database
		$.ajax({
			url : base_url + 'empdoc/delete/'+id,
			type: "GET",
			dataType: "JSON",
			success: function(data) { 
				if(data.status) {
					toast("File berhasil dihapus","success");
					$('#grdDoc').trigger('reloadGrid');
					$('#mdlDoc').modal('hide');
				} else {
					toast(data.error,'error');
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				toast('Maaf, file tidak dapat dihapus','error');
			}
		});
	});
}

function download(fn) {
	fn = base_url + fn;
	window.open(encodeURI(fn), '_blank');
}