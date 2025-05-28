/*jshint multistr: true */
Chart.defaults.global.defaultFontFamily = 'Lato';
Chart.defaults.global.legend.position = 'right';

$(document).ready(function() {
	document.title = 'CyberHRM - Dashboard';
	
	$('.ui.accordion').accordion('open', 0);

	$(document).on("click", "#grdNewEmployee tbody tr", function() {
		var id = $(this).data('id');
        employee(id);
	});

	$(document).on("click", "#grdExpiryEmployee tbody tr", function() {
		var id = $(this).data('id');
        employee(id);
	});
	
	getNewEmployee();

	getExpiryEmployee();

	chartDepartement();

	chartEmployeeGrowth();
});

function getData(uri, successFunc) {
	$.ajax({
		url : uri,
		type: "GET",
		dataType: "JSON",
		success: function(data) {
			if (data.status) {
				successFunc(data);
			} else {
				//toast("Data tidak ditemukan", "error");
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			//waitMode(false);
			//toast("Gagal berkomunikasi dengan server", "error");
		}
	});
}

function getNewEmployee(data) {
	if (data == undefined) {
		getData(base_url + 'dashboard/getNewEmployee', function(data){
			getNewEmployee(data);
		});
	} else {
		$.each(data.payload, function(idx, rec){
			var row = "<tr data-id='" + rec.ID + "'><td>"+ rec.CODE +"</td><td>"+ rec.NAME +"</td><td>" + rec.JOINED_AT + "</td></tr>";
			$('#grdNewEmployee tbody').append(row);
		});
	}
}

function getExpiryEmployee(data) {
	if (data == undefined) {
		getData(base_url + 'dashboard/getExpiryEmployee', function(data){
			getExpiryEmployee(data);
		});
	} else {
		$.each(data.payload, function(idx, rec){
			var age = moment().diff(moment(rec.BORNED_AT), 'years');
			var row = "<tr data-id='" + rec.ID + "'><td>"+ rec.CODE +"</td><td>"+ rec.NAME + 
				"</td><td>" + rec.JOINED_AT + "</td></td><td>" + age + " Tahun</td></tr>";
			$('#grdExpiryEmployee tbody').append(row);
		});
	}
}

function chartDepartement(data) {
	if (data == undefined) {
		getData(base_url + 'dashboard/getDeptEmployee', function(data){
			chartDepartement(data);
		});
		return;
	}

	var keys = new Array();
	var vals = new Array();
	var cols = new Array();

	$.each(data.payload, function(idx, rec){
		keys.push(rec.NAME);
		vals.push(rec.JLH);
		//random color
		var r = Math.floor(Math.random() * 255);
        var g = Math.floor(Math.random() * 255);
        var b = Math.floor(Math.random() * 255);
        cols.push("rgba(" + r + "," + g + "," + b + ",0.7)");
	});

	var ctx = $("#cvsDept");
	var myChart = new Chart(ctx, {
		type: 'pie',
		data: {
			labels: keys,
			datasets: [{
				label: 'Komposisi Pegawai',
				data: vals,
				backgroundColor: cols,
				borderWidth: 1
			}]
		},
		options: {
			plugins: {
				labels: {
					render: 'percentage',
					fontColor: 'white',
					precision: 1
				}
			}
		}
	});
}

function chartEmployeeGrowth(data) {
	if (data == undefined) {
		getData(base_url + 'dashboard/getEmployeeGrowth', function(data){
			chartEmployeeGrowth(data);
		});
		return;
	}

	var ctx = $("#cvsGrowth");
	var myChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: data.payload[0].TAHUN,
			datasets: [{
				label: 'Permanen',
				backgroundColor: 'rgb(75, 192, 192)',
				borderColor: 'rgb(75, 192, 192)',
				fill: false,
				data: data.payload[0].PERMANEN
			},{
				label: 'Kontrak',
				backgroundColor: 'rgb(54, 162, 235)',
				borderColor: 'rgb(54, 162, 235)',
				fill: false,
				data: data.payload[0].KONTRAK
			},{
				label: 'Harian',
				backgroundColor: 'rgb(255, 159, 64)',
				borderColor: 'rgb(255, 159, 64)',
				fill: false,
				data: data.payload[0].HARIAN
			}]
		}
	});
}

function employee(id) {
	window.open(base_url + 'employee/view/'+id, '_blank');
}