$(document).ready(function() {
	document.title = 'CyberHRM - Proses Penggajian';
	
	$('.ui.accordion').accordion('open', 2);
	$('#mnuPayday').state('activate');

	$("#grdPayroll").jqGrid({
		url: base_url + 'paydays/query/?year='+moment().format('YYYY'),
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Proses', name: 'CODE', width: 200, align: 'left' },
			{ label: 'Periode', name: 'PERIOD', width: 100, align: 'center' },
			{ label: 'Mulai', name: 'START_AT', width: 80, align: 'center' },
			{ label: 'Akhir', name: 'END_AT', width: 80, align: 'center' },
			{ label: 'Tempo', name: 'DUE_AT', width: 80, align: 'center' },
			{ label: 'Diproses', name: 'CREATED_AT', width: 80, align: 'center' },
			{ label: 'Status', name: 'STATUS', width: 90, align: 'center' },
			{ label: 'Tercetak', name: 'PRINTED_AT', width: 80, align: 'center' },
			{ label: 'Catatan', name: 'REMARKS', width: 300, align: 'left' },
		],
		page: 1,
		width: 900,
		height: 250,
		rowNum: 200,
		scroll: 1,
		shrinkToFit : false,
		emptyrecords: 'Scroll to bottom to retrieve new page'
        //pager: "#grdPage"
	});

	$('#year_at').calendar({
		type: 'year',
		onChange: function (date, text, mode) {
			getPayroll(text);
		}
	});

	$('#cmbPayroll').on('change', function(){
		setDateRange();
	});
});

function setDateRange(d) {
	var dte = moment('01 '+moment().format('MMM YYYY'));
	if (d != undefined) {
		dte = moment('01 '+d);
	}
	var code = $('#cmbPayroll').val();

	if (code == 'honorium') {
		if (moment().format('DD') <= '15') {
			dte = moment('16 '+dte.format('MMM YYYY'));
			$('[name="DUE_AT"]').val(dte.format("MMMM 05, YYYY"));
			$('[name="START_AT"]').val(dte.subtract(1,'months').format("MMMM DD, YYYY"));
			$('[name="END_AT"]').val(dte.endOf('month').format("MMMM DD, YYYY"));
		} else {
			$('[name="START_AT"]').val(dte.format("MMMM DD, YYYY"));
			$('[name="END_AT"]').val(dte.add(14, 'days').format("MMMM DD, YYYY"));
			$('[name="DUE_AT"]').val(dte.format("MMMM 20, YYYY"));
		}		
	} else {
		//dte = moment('01 '+dte.subtract(1,'months').format('MMM YYYY'));
		if (code == 'incentive') {
			$('[name="DUE_AT"]').val(dte.format("MMMM 15, YYYY"));
		} else {
			$('[name="DUE_AT"]').val(dte.format("MMMM 25, YYYY"));
		}
		$('[name="START_AT"]').val(dte.subtract(1,'months').format("MMMM DD, YYYY"));
		$('[name="END_AT"]').val(dte.endOf('month').format("MMMM DD, YYYY"));
	}
}

function addPayroll() {
	addCombo('cmbPayroll','payroll', undefined, function() {
		addNewMode('frmPayroll');
		openModal('frmPayroll', 'Proses Penggajian Baru', savePayroll);
		$('#start_at').calendar({
			type: 'date',
			endCalendar: $('#end_at')
		});
		$('#end_at').calendar({
			type: 'date',
			startCalendar: $('#start_at')
		});
		$('#period').calendar({
			type: 'month',
			onChange: function (date, text, mode) {
				setDateRange(text);
			}
		});	
		//set default values
		$('#frmPayroll [name="ID"]').val("0");	
		$('#frmPayroll [name="PERIOD"]').val(moment().format("MMMM YYYY"));	
		$('#frmPayroll [name="CODE"]').prop('disabled', false);
		setDateRange();
	});
}

function editPayroll(id) {
	document.location = base_url + "payday?id=" + id;
}

function savePayroll() {
	var uri = base_url + 'paydays/create';
	saveRecord('frmPayroll', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdPayroll').trigger('reloadGrid');
		closeModal('frmPayroll');
	});
}

function getPayroll(year) {
	$("#grdPayroll").setGridParam({
		url: base_url + "paydays/query/?year="+year,
		page: 1
	}).trigger("reloadGrid");
}