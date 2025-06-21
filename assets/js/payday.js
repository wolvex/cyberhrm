$(document).ready(function() {
	document.title = 'CyberHRM - Proses Penggajian';
	
	$('.ui.accordion').accordion('open', 2);
	$('#mnuPayday').state('activate');

	var id = $('#frmPayroll [name="ID"]').val();

	addCombo('cmbPayroll', 'payroll', undefined, function() {
		queryRecord(base_url + 'payday/get/' + id, function(data){
			bindForm('frmPayroll', data.payload);
			
			switch ( $('#cmbStatus').val() ) {
				case 'Q':
					//$('#cmdProcess').css('display', 'inline'); 
					//$('#cmdDelete').css('display', 'inline'); 
					$('#divPrint').css('display', 'none'); 
					break;
				default:
					if ($('[name="PRINTED_AT"]').val() == '') {
						//$('#cmdDelete').css('display', 'inline');
					} else {
						//$('#cmdDelete').css('display', 'none');
					}
					$('#divPrint').css('display', 'inline'); 
					break;
			}
			populateGrid(id);
		});		
	});
});

function populateGrid(id) {
	var cols = [
		{ label: '-', name: 'CMD', width: 25, align: 'center' },
		{ label: 'N I P', name: 'EMP_CODE', width: 70, align: 'center' },
		{ label: 'Pegawai', name: 'EMP_NAME', width: 150, align: 'left' },
		{ label: 'Total Bayar', name: 'GRDTOT', width: 80, align: 'right', formatter: gridDecimal },
		{ label: 'Infaq', name: 'INFAQ', width: 80, align: 'right', formatter: gridDecimal },
		{ label: 'Pembulatan', name: 'ROUNDING', width: 80, align: 'right', formatter: gridDecimal },
		{ label: 'Nett', name: 'NETT', width: 80, align: 'right', formatter: gridDecimal }
	];

	var ptype = $('#cmbPayroll').val();
	if (ptype == 'salary') {
		cols.push(
			{ label: 'Gaji Pokok', name: 'SALARY', width: 80, align: 'right', formatter: gridDecimal },
			{ label: 'JP', name: 'JPFEE', width: 80, align: 'right', formatter: gridDecimal },
			{ label: 'JHT', name: 'JHTFEE', width: 80, align: 'right', formatter: gridDecimal },
			{ label: 'Mangkir (Hari)', name: 'ABSENTDAY', width: 80, align: 'right', formatter: 'integer' },
			{ label: 'Potongan Mangkir', name: 'ABSENTCOST', width: 80, align: 'right', formatter: gridDecimal },
			{ label: 'SPSI', name: 'SPSI', width: 80, align: 'right', formatter: gridDecimal },
			{ label: 'Koperasi', name: 'KOPERASI', width: 80, align: 'right', formatter: gridDecimal }
		);
	} else if (ptype == 'incentive') {
		cols.push(
			{ label: 'Tj.Beras', name: 'BERAS', width: 80, align: 'right', formatter: gridDecimal },
			{ label: 'Tj.Makan', name: 'MEAL', width: 80, align: 'right', formatter: gridDecimal },
			{ label: 'Tj.Hadir', name: 'PREMI', width: 80, align: 'right', formatter: gridDecimal },
			{ label: 'Tj.Lembur', name: 'OVERTIME', width: 80, align: 'right', formatter: 'integer' },
			{ label: 'Tj.Shift', name: 'SHIFT', width: 80, align: 'right', formatter: gridDecimal },
			{ label: 'Tj.Prestasi', name: 'BONUS', width: 80, align: 'right', formatter: gridDecimal },
			{ label: 'Tj.Jabatan', name: 'ALLOWANCE', width: 80, align: 'right', formatter: gridDecimal }
		);
	} else {
		cols.push(
			{ label: 'Upah', name: 'SALARY', width: 80, align: 'right', formatter: gridDecimal },
			{ label: 'Astek', name: 'JHTFEE', width: 80, align: 'right', formatter: gridDecimal },
			{ label: 'SPSI', name: 'SPSI', width: 80, align: 'right', formatter: gridDecimal },
			{ label: 'Tj.Makan', name: 'MEAL', width: 80, align: 'right', formatter: gridDecimal },
			{ label: 'Tj.Lembur', name: 'OVERTIME', width: 80, align: 'right', formatter: 'integer' },
			{ label: 'Koperasi', name: 'KOPERASI', width: 80, align: 'right', formatter: gridDecimal }
		);
	}

	$.jgrid.gridUnload("grdPayslip");
	//$("#grdTimesheet").jqGrid("gridUnload");

	$("#grdPayslip").jqGrid({
		url: base_url + 'payslip/query/'+id,
		mtype: "GET",
		datatype: "json",
		colModel: cols,
		page: 1,
		//width: 900,
		height: 250,
		rowNum: 1200,
		//scroll: 1,
		shrinkToFit : false,
		autowidth: true,
		emptyrecords: 'Scroll to bottom to retrieve new page'
        //pager: "#grdPage"
	});
}

function processPayroll() {
	confirm('Selama proses berlangsung, mohon tidak melakukan proses refresh. Lanjutkan ?', 'warning', function(){
		var id = $('[name="ID"]').val();
		if (id == '' || id == '0') return;

		waitMode(true);
		$.ajax({
			url : base_url + "payroll/process/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				if (data.status) {
					toast("Payroll selesai diproses","success");
					setTimeout(function(){
						location.reload();
					}, 1000);
				} else {
					toast("Proses payroll gagal, " + data.error, "error");
				}
				waitMode(false);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				toast("Proses payroll gagal, " + textStatus, "error");
				waitMode(false);
			}
		});
	});
}

function removePayroll() {
	var uri = base_url + 'payday/delete';
	removeRecord('frmPayroll', 'Anda akan menghapus data ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		document.location = base_url + 'paydays';
	});
}

function printSlip(confirmed, empId) {
	var printAt = $('[name="PRINTED_AT"]').val();

	if (printAt == '' && (confirmed == undefined || !confirmed)) {
		confirm('Setelah dicetak, payroll ini tidak akan bisa dihapus. Lanjutkan ?', 'warning', function(){
			printSlip(true, empId);
		});
		return;
	}

	var id = $('#frmPayroll [name="ID"]').val();
	if (id == '' || id == '0') return;
	if (empId == undefined) empId = 0;

	waitMode(true);
	$.ajax({
		url : base_url + "payday/preview/" + id,
		type: "GET",
		dataType: "JSON",
		success: function(data) {
			if (data.status) {
				var title = '';
				var subtitle = $('#frmPayroll [name="PERIOD"]').val();
				var ptype = $('#cmbPayroll').val(); 
				switch (ptype) {
					case 'salary':
						title = 'Slip Gaji Pegawai'; break;
					case 'incentive':
						title = 'Slip Tunjangan Pegawai'; break;
					case 'honorium':
						title = 'Slip Honor Pegawai'; break;
				}
				console.log("payrollId["+id+"]payslipId["+empId+"]");
				previewReport({
					rptName: (empId == 0 ? 'payslip' : 'payslip1'),
					rptTitle: title,
					rptSubtitle: subtitle,
					payrollId: id,
					payslipId: empId
				});
			} else {
				toast(data.error, "error");
			}
			waitMode(false);
		},
		error: function (jqXHR, textStatus, errorThrown) {
			toast("Proses cetak payroll gagal, " + textStatus, "error");
			waitMode(false);
		}
	});
}

function printSummary() {
	var id = $('#frmPayroll [name="ID"]').val();
	var ptype = $('#cmbPayroll').val(); var subtitle = $('#frmPayroll [name="PERIOD"]').val();
	var title = '';
	switch (ptype) {
		case 'salary':
			title = 'Rekapitulasi Gaji Pegawai'; break;
		case 'incentive':
			title = 'Rekapitulasi Tunjangan Pegawai'; break;
		case 'honorium':
			title = 'Rekapitulasi Honor Pegawai'; break;
	}

	previewReport({
		rptName: ptype+'_by_dept',
		rptTitle: title,
		rptSubtitle: subtitle,
		payrollId: id
	});
}

function printDetail() {
	var id = $('#frmPayroll [name="ID"]').val();
	var ptype = $('#cmbPayroll').val();
	var title = ''; var subtitle = $('#frmPayroll [name="PERIOD"]').val();
	switch (ptype) {
		case 'salary':
			title = 'Daftar Gaji Pegawai'; break;
		case 'incentive':
			title = 'Daftar Tunjangan Pegawai'; break;
		case 'honorium':
			title = 'Daftar Honor Pegawai'; 
			subtitle = moment($('[name="START_AT"]').val()).format('DD-MMM-YYYY') + ' s/d ' + 
				moment($('[name="END_AT"]').val()).format('DD-MMM-YYYY');
			break;
	}

	previewReport({
		rptName: ptype+'_detail',
		rptTitle: title,
		rptSubtitle: subtitle,
		payrollId: id
	});
}

var t;
function searchEmployee() {
	clearTimeout(t);
	t = setTimeout(function(){
		var id = $('#frmPayroll [name="ID"]').val();
		var s  = $('#txtFind').val().trim();
		if (s != '') id = id+'/?find='+s;
		
		$("#grdPayslip").setGridParam({
			url: 'payslip/query/'+id,
			page: 1
		}).trigger('reloadGrid');	
	}, 500);
}