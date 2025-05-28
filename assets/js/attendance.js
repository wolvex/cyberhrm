$(document).ready(function() {
	document.title = 'CyberHRM - Kehadiran';
	
	$('.ui.accordion').accordion('open', 1);
	$('#mnuTimesheet').state('activate');

	$('.menu .item').tab();	

	//alert($('[name="month_at"]').val());

	$("#grdTimesheet").jqGrid({
		mtype: "GET",
		url: base_url + 'attendance/query/' + $('#EMP_ID').val() + '/' + moment($('[name="month_at"]').val()).format('YYYY-MM'),
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 40, align: 'center' },
			{ label: 'Tanggal', name: 'WORK_AT', width: 80, align: 'center' },
			{ label: 'Shift', name: 'SHIFT_CODE', width: 70, align: 'center' },
			{ label: 'Jam Masuk', name: 'START_TIME', width: 70, align: 'center' },
			{ label: 'Jam Pulang', name: 'END_TIME', width: 70, align: 'center' },
			{ label: 'Absen Masuk', name: 'CLOCK_IN', width: 70, align: 'center' },
			{ label: 'Absen Pulang', name: 'CLOCK_OUT', width: 70, align: 'center' },
			{ label: 'Telat Datang (menit)', name: 'LATE_MINUTE', width: 70, align: 'center' },
			{ label: 'Pulang Cepat (menit)', name: 'EARLY_MINUTE', width: 70, align: 'center' },
			{ label: 'Lama Kerja (jam)', name: 'WORK_HOUR', width: 70, align: 'center' },
			{ label: 'Jenis Lembur', name: 'OVERTIME_CODE', width: 70, align: 'center' },
			{ label: 'Lama Lembur (menit)', name: 'OVERTIME_MIN', width: 70, align: 'center' },
			{ label: '', name: 'IS_LATE', hidden: true, exportcol: false },
			{ label: '', name: 'IS_INCOMPLETE', hidden: true, exportcol: false }
		],
		page: 1,
		//width: 900,
		height: 250,
		rowNum: 200,
		scroll: 1,
		shrinkToFit : false,
		gridview: true,
		rowattr: function(rd) {
			if (rd.IS_LATE == 'Y') {
				return {"style": "background-color: #f7ffc4"};
			} else if (rd.IS_INCOMPLETE == 'Y') {
				return {"style": "background-color: #ffe9c6"};
			}
		}
		//emptyrecords: 'Scroll to bottom to retrieve new page'
        //pager: "#grdPage"
	});

	$("#grdPunch").jqGrid({
		mtype: "GET",
		url: base_url + 'x100c/query/' + $('#EMP_ID').val() + '/' + moment($('[name="month_at"]').val()).format('YYYY-MM'),
		datatype: "json",
		colModel: [
			{ label: 'Jam', name: 'WORK_AT', width: 120, align: 'center' },
			{ label: 'Masuk/Pulang', name: 'STATUS', width: 120, align: 'center' }
		],
		page: 1,
		//width: 900,
		height: 250,
		rowNum: 200,
		scroll: 1,
		shrinkToFit : false,
		//emptyrecords: 'Scroll to bottom to retrieve new page'
        //pager: "#grdPage"
	});

	$('#month_at').calendar({
		type: 'month',
		onChange: function (date, text, mode) {
			getTimesheet(date);
		}
	});
});

function getTimesheet(date) {
	var period = "";
	if (date != undefined) {
		period = moment(date).format('YYYY-MM');
	} else {
		period = moment('1 '+ $('[name="month_at"]').val()).format('YYYY-MM');
	}	
	
	$("#grdTimesheet").setGridParam({
		url: base_url + 'attendance/query/' + $('#EMP_ID').val() + '/' + period,
		page: 1
	}).trigger('reloadGrid');

	$("#grdPunch").setGridParam({
		url: base_url + 'x100c/query/' + $('#EMP_ID').val() + '/' + period,
		page: 1
	}).trigger('reloadGrid');
}

function addTimesheet() {
	addCombo('cmbShift', 'shift', void 0, function(){
		//addCombo('cmbAbsence', 'absence', void 0, function(){
			addNewMode('frmTimesheet');
			openModal('frmTimesheet', 'Jadwal Kerja Baru', saveTimesheet);
			//set default values
			$('#frmTimesheet [name="ID"]').val("0");
		//});
	});
}

function editTimesheet(id) {
	var uri = base_url + "attendance/get/" + id;
	queryRecord(uri, function(data){
		addCombo('cmbShift', 'shift', undefined, function(){
			//addCombo('cmbAbsence', 'absence', undefined, function(){
				editMode('frmTimesheet', data.payload);
				openModal('frmTimesheet', 'Ubah Jadwal Kerja', saveTimesheet, removeTimesheet);
			//});
		});
	});
}

function saveTimesheet() {
	$('#frmTimesheet [name="EMP_ID"]').val($('#EMP_ID').val());

	var uri = base_url + ($('#frmTimesheet input[name="ID"]').val() == '0' ? 'attendance/create' : 'attendance/update');
	saveRecord('frmTimesheet', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdTimesheet').trigger('reloadGrid');
		closeModal('frmTimesheet');
	});
}

function removeTimesheet() {
	var uri = base_url + 'attendance/delete';
	removeRecord('frmTimesheet', 'Anda akan menghapus data ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdTimesheet').trigger('reloadGrid');
		closeModal('frmTimesheet');
	});
}
