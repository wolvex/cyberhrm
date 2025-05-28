$(document).ready(function() {
	document.title = 'CyberHRM - Kehadiran';
	
	$('.ui.accordion').accordion('open', 1);
	$('#mnuTimesheet').state('activate');

	$('#month_at').calendar({
		type: 'month',
		onChange: function (date, text, mode) {
			getTimesheet(date);
		}
	});

	var btn = document.getElementById('cmdUpload'),
		progressBar = document.getElementById('progressBar'),
		progressOuter = document.getElementById('progressOuter');

	var uploader = new ss.SimpleUpload({
		button: btn,
		url: base_url + 'timesheet/upload',
		name: 'FILENAME',
		multipart: true,
		maxSize: 1024 * 5,
		allowedExtensions: ["txt"],
		responseType: 'json',
		startXHR: function() {
			progressOuter.style.display = 'block'; // make progress bar visible
			this.setProgressBar( progressBar );
		},
		onSubmit: function() {
			btn.innerHTML = 'Uploading...'; // change button text to "Uploading..."
		},
		onComplete: function( filename, response ) {
			progressOuter.style.display = 'none'; // hide progress bar when upload is completed
			btn.innerHTML = 'Upload File';
			if ( !response ) {
				toast("File tidak berhasil diupload", "error");
			} else if ( response.status ) {
				toast("Upload berhasil. Tunggu beberapa menit untuk melihat hasilnya", "advise");
				setTimeout($('#grdTimesheet').trigger('reloadGrid'), 60000);
				$('#mdlUpload').modal('hide');
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

	getTimesheet('');
});

function getTimesheet(month) {
	if (month == '') {
		month = moment().format('YYYY-MM');
	} else {
		month = moment(month).format('YYYY-MM');
	}

	var cols = [
		{ label: '-', name: 'CMD', width: 25, align: 'center' },
		{ label: 'NIK', name: 'CODE', width: 70, sortable: true, align: 'center' },
		{ label: 'Pegawai', name: 'NAME', width: 150, sortable: true, align: 'left' },
		{ label: 'Departemen', name: 'DEPT_NAME', width: 120, sortable: true, align: 'center' }
	];

	var d = moment(month + "-01");
	while (d.format('YYYY-MM') == month) {
		if (d.format('ddd') == 'Sun' || d.format('ddd') == 'Sat') {
			cols.push({ label: d.format("ddd") + '<br>' + d.format("DD-MMM"), name: 'D' + d.format("DD"), width: 45, align: 'center', classes: 'red-col' });
		} else {
			cols.push({ label: d.format("ddd") + '<br>' + d.format("DD-MMM"), name: 'D' + d.format("DD"), width: 45, align: 'center' });		}
		d = d.add(1, 'd');
	}

	$.jgrid.gridUnload("grdTimesheet");
	//$("#grdTimesheet").jqGrid("gridUnload");

	$("#grdTimesheet").jqGrid({
		url: 'timesheet/query/?month='+month,
		mtype: "GET",
		datatype: "json",
		colModel: cols,
		page: 1,
		width: 900,
		height: 250,
		rowNum: 500,
		//scroll: 1,
		shrinkToFit : false,
		emptyrecords: 'Scroll to bottom to retrieve new page'
        //pager: "#grdPage"
	});
}	

function uploadTimesheet() {
	$('#frmUpload').trigger("reset"); // reset form on modals
	$('#ttlUpload').text('Upload Jadwal Kerja');

	$('#mdlUpload').modal({
		closable: true
	}).modal('show'); // show bootstrap modal when complete loaded
}

function editTimesheet(id) {
	var month = $('[name="month_at"]').val();
	if (month == '') {
		month = moment().format('YYYY-MM');
	} else {
		month = moment('1 '+month).format('YYYY-MM');
	}
	document.location = base_url + 'attendance/?id=' + id + '&period=' + month;
}

var t;
function searchTimesheet() {
	clearTimeout(t);
	t = setTimeout(function(){
		var q = $('[name="month_at"]').val();
		if (q == '') {
			q = moment().format('YYYY-MM');
		} else {
			q = moment('01 '+q).format('YYYY-MM');
		}
		q = 'month='+q;
		
		var s = $('#txtFind').val().trim();
		if (s != '') q += '&find='+s;

		$("#grdTimesheet").setGridParam({
			url: 'timesheet/query/?'+q,
			page: 1
		}).trigger('reloadGrid');	
	}, 500);
}