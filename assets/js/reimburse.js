$(document).ready(function() {
	document.title = 'CyberHRM - Komponen pemotong gaji';
	
	$('.ui.accordion').accordion('open', 2);
	$('#mnuReimburse').state('activate');

	$("#grdAdjustment").jqGrid({
		url: base_url + 'reimburse/query/?month=' + moment().format('YYYY-MM'),
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

	//addCombo('cmbSelect', 'reimburse', 'koperasi');
});

function addAdjustment() {
	addCombo('cmbAdjustment', 'reimburse', undefined, function(){
		addNewMode('frmAdjustment');
		openModal('frmAdjustment', 'Reimburse Baru', saveAdjustment);
		$('[name="ID"]').val("0");	
	});
}

function editAdjustment(id) {
	var uri = base_url + "reimburse/get/" + id;
	queryRecord(uri, function(data){
		addCombo('cmbAdjustment', 'reimburse', undefined, function(){
			editMode('frmAdjustment', data.payload);
			openModal('frmAdjustment', 'Ubah Reimburse', saveAdjustment, removeAdjustment);
		});
	});
}

function saveAdjustment() {
	if ($('[name="TRX_AT"]').val() == '')
		$('[name="TRX_AT"]').val($('[name="ADJUST_AT"]').val());

	var uri = base_url + ($('[name="ID"]').val() == '0' ? 'reimburse/create' : 'reimburse/update');
	saveRecord('frmAdjustment', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdAdjustment').trigger('reloadGrid');
		closeModal('frmAdjustment');
	});
}

function removeAdjustment() {
	var uri = base_url + 'reimburse/delete';
	removeRecord('frmAdjustment', 'Anda akan menghapus data ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdAdjustment').trigger('reloadGrid');
		closeModal('frmAdjustment');
	});
}

function getAdjustment(month) {
	$("#grdAdjustment").setGridParam({
		url: base_url + "reimburse/query/?month="+moment('1 '+month).format('YYYY-MM'),
		page: 1
	}).trigger("reloadGrid");
}	
