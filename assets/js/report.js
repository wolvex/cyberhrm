/*jshint multistr: true */

$(document).ready(function() {
	document.title = 'CyberHRM - Laporan';
	
	$('.ui.accordion').accordion('open', 0);
	$('#mnuReport').state('activate');

	$(document).on("click", "#grdReport tbody tr", function() {
        preview($(this));
	});
	
	populateReport();
});

function populateReport(list) {
	if (list == undefined) {
		$.ajax({
			url : base_url + 'report/query',
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				if (data.status) {
					populateReport(data);
				} else {
					//toast("Data tidak ditemukan", "error");
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				//waitMode(false);
				toast("Gagal berkomunikasi dengan server", "error");
			}
		});
		return;
	}

	$.each(list.payload, function(idx, report){
		var row = "<tr><td style='display:none'>"+ report.NAME +"</td><td>"+ report.CODE +"</td><td>"+ report.DESCRIPTION +"</td></tr>";
		$('#grdReport #content').append(row);
	});
}

function submitReport(name, title, subtitle) {
	addParam('rptName', name);
	addParam('rptTitle', title);
	if (subtitle != undefined) {
		addParam('rptSubtitle', subtitle);
	}
	window.open('about:blank', title);

	$('#frmDialog').prop('target', title);
	$('#frmDialog').submit();
}

var dteCount = 0;
var cmbCount = 0;

function clearForm() {
	$('#frmDialog').children().each(function () {
		var nm = $(this).prop('id');
		if ($(this).is('input') || nm.indexOf('date') == 0 || nm.indexOf('combo') == 0) {
			$(this).remove();
		}
	});

	dteCount = 0;
	cmbCount = 0;
}

function addCalendar(label,defVal,type) {
	dteCount++;
	if (label == undefined) label = 'Per Tanggal';
	if (defVal == undefined) defVal = moment().format("MMMM DD, YYYY");
	if (type == undefined) type = 'date';
	var id = 'date'+dteCount;

	var elem = "\
	<div id='"+id+"f' class='field'>\
		<label>"+label+"</label>\
		<div class='ui calendar' data-type='"+type +"'>\
			<div class='ui input left icon'>\
				<i class='calendar icon'></i>\
				<input name='"+id+"' readonly value='"+defVal+"'>\
			</div>\
		</div>\
	</div>";
	$(elem).insertBefore( ".action-box" );
}

function addSelect(label,entity,funcName) {
	cmbCount++;
	if (label == undefined) label = 'Pilih';	
	var id = 'combo'+cmbCount;

	var elem = "\
	<div id='"+id+"f' class='field'>\
		<label>"+label+"</label>\
		<select id='"+id+"' class='ui simple dropdown' name='"+id+"'></select>\
	</div>";
	$(elem).insertBefore( ".action-box" );
	//$('#'+id).dropdown();

	addCombo(id, entity, undefined, funcName);
}

function addParam(key, val) {
	var elem = "<input type='hidden' name='"+key+"' value='"+val+"'>";
	$(elem).insertBefore( ".action-box" );
}

function preview(tr) {
	var name  = tr.find('td:eq(0)').html();
	var title = tr.find('td:eq(2)').html();

	clearForm();
	switch ( name ) {
		case "employee": case "timesheet_summary":
			addCalendar();
			addSelect('Departemen','department',function(){
				openModal('frmDialog', title, function(){
					var asOf = $('[name="date1"]').val();
					var dept = $('[name="combo1"]').val();
					addParam('deptId1', dept);
					addParam('deptId2', dept);
					submitReport(name, title, 'Per '+moment(asOf).format('DD-MMM-YYYY'));
					closeModal('frmDialog');
				});	
			});
			break;
		
		case "bpjs_by_dept": case "bpjs2_by_dept":
			addCalendar('Periode', moment().format('MMMM YYYY'), 'month');
			openModal('frmDialog', title, function(){
				var period = $('[name="date1"]').val();
				addParam('period', moment('01 '+period).format('YYYY-MM'));
				submitReport(name, title, period);
				closeModal('frmDialog');
			});
			break;
		
		case "gross_annual":
			addCalendar('Tahun', moment().format('MMMM YYYY'), 'year');
			openModal('frmDialog', title, function(){
				var year = $('[name="date1"]').val();
				addParam('year', year);
				submitReport(name, title, year);
				closeModal('frmDialog');
			});
			break;
	}
}