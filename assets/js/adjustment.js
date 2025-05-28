$(document).ready(function() {
	document.title = 'CyberHRM - Komponen pemotong gaji';
	
	$('.ui.accordion').accordion('open', 2);
	$('#mnuAdjustment').state('activate');

	$("#grdAdjustment").jqGrid({
		url: base_url + 'adjustment/query/?month=' + moment().format('YYYY-MM'),
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 40, align: 'center' },
			{ label: 'Pegawai', name: 'EMP_NAME', width: 150, align: 'left' },
			{ label: 'Jenis', name: 'CODE', width: 100, align: 'center' },
			{ label: 'Tanggal', name: 'ADJUST_AT', width: 80, align: 'center' },
			{ label: 'Jumlah', name: 'AMOUNT', width: 80, align: 'right', formatter: 'number' },
			{ label: 'Status', name: 'STATUS', width: 70, align: 'center' },
			{ label: 'Keterangan', name: 'REMARKS', width: 200, align: 'left' },
		],
		page: 1,
		//width: 900,
		height: 250,
		rowNum: 1000,
		scroll: 1,
		shrinkToFit : false,
		emptyrecords: 'Scroll to bottom to retrieve new page'
        //pager: "#grdPage"
	});

	$('#month_at').calendar({
		type: 'month',
		onChange: function (date, text, mode) {
			getAdjustment(text);
		}
	});

	$('#findEmp').search({
		apiSettings: {
			url: base_url + 'employees/search?find={query}'
		},
		minCharacters : 3,
		maxResults: 20,
		cache: true,
			
		onSearchQuery: function() {
			//$('[name="coa_des"]').val("");
		},
		onSelect: function(result) {
			$('[name="EMP_ID').val(result.title);
			$('[name="EMP_NAME').val(result.description);
		},
		type: 'compact'
	});

	//addCombo('cmbSelect', 'adjustment', 'koperasi');

	$('#txtFile').change(function(){
		readFile();
	});
});

function addAdjustment() {
	addCombo('cmbAdjustment', 'adjustment', undefined, function(){
		addNewMode('frmAdjustment');
		openModal('frmAdjustment', 'Pemotongan Baru', saveAdjustment);
		$('[name="ID"]').val("0");
		$('[name="EMP_ID"]').val("");
	});
}

function editAdjustment(id) {
	var uri = base_url + "adjustment/get/" + id;
	queryRecord(uri, function(data){
		addCombo('cmbAdjustment', 'adjustment', undefined, function(){
			editMode('frmAdjustment', data.payload);
			openModal('frmAdjustment', 'Ubah Pemotongan', saveAdjustment, removeAdjustment);
		});
	});
}

function saveAdjustment() {
	var uri = base_url + ($('[name="ID"]').val() == '0' ? 'adjustment/create' : 'adjustment/update');
	if ($('#frmAdjustment [name="TRX_AT"]').val() == '') {
		$('#frmAdjustment [name="TRX_AT"]').val($('#frmAdjustment [name="ADJUST_AT"]').val());
	}
	saveRecord('frmAdjustment', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdAdjustment').trigger('reloadGrid');
		closeModal('frmAdjustment');
	});
}

function removeAdjustment() {
	var uri = base_url + 'adjustment/delete';
	removeRecord('frmAdjustment', 'Anda akan menghapus data ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdAdjustment').trigger('reloadGrid');
		closeModal('frmAdjustment');
	});
}

function getAdjustment(month) {
	$("#grdAdjustment").setGridParam({
		url: base_url + "adjustment/query/?month="+moment('1 '+month).format('YYYY-MM'),
		page: 1
	}).trigger("reloadGrid");
}	

function upload(code) {
	$('#frmFile').trigger('reset');
	$('#frmFile [name="code"]').val(code);

	openModal('frmFile', 'Upload '+code, function(){
		var text = $('#fileContent').val();
		if (text == '') return;

		$.ajax({
			type: "POST",
			url: "adjustment/upload",
			data: $('#frmFile').serialize(),
			dataType: "JSON",
			success: function(data) {
				if (data.status) {
					toast("Transaksi berhasil diupload", "success");
					$("#grdAdjustment").setGridParam({
						page: 1
					}).trigger("reloadGrid");
					closeModal('frmFile');
				} else {
					toast("Transaksi gagal diupload", "error");
				}
			}
		});
	});
}

function readFile() {
	var input = document.getElementById("txtFile");
	var fReader = new FileReader();
	fReader.readAsText(input.files[0]);
	fReader.onloadend = function(event){
		var text = fReader.result;
		var lines = text.split('\n').length;
		if (lines > 500) {
			toast("Maaf, tidak bisa memproses lebih dari 500 transaksi dalam 1 file");
			return;
		}
		$('#frmFile #lblFile').text('Isi file ('+lines+' baris) :');
		$('#frmFile [name="fileContent"]').val(text);
	}
}