$(document).ready(function() {
	$("#grdCarier").jqGrid({
		url: base_url + 'carier/query/' + $('#EmployeeID').val(),
		mtype: "GET",
		datatype: "json",
		colModel: [
			{ label: '-', name: 'CMD', width: 50, align: 'center' },
			{ label: 'Jabatan', name: 'JOB_TITLE', width: 150, align: 'center' },
			{ label: 'Departemen', name: 'DEPT_NAME', width: 120, align: 'center' },
			{ label: 'Grade', name: 'GRADE_NAME', width: 70, align: 'center' },
			{ label: 'Status', name: 'STATUS_NAME', width: 70, align: 'center' },
			{ label: 'Supervisor', name: 'SPV_NAME', width: 150, align: 'center' },
			{ label: 'Manager', name: 'MGR_NAME', width: 150, align: 'center' },
			{ label: 'Efektif', name: 'EFFECTIVE_AT', width: 80, align: 'center' }
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

	$('#findSpv').search({
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
			$('#frmCarier input[name="APPROVER1').val(result.title);
			$('#frmCarier input[name="SPV_NAME').val(result.description);
		},
		type: 'compact'
	});

	$('#findMgr').search({
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
			$('#frmCarier input[name="APPROVER2').val(result.title);
			$('#frmCarier input[name="MGR_NAME').val(result.description);
		},
		type: 'compact'
	});
});

function addCarier() {
	addCombo('cmbDept', 'dept', undefined, function(){
		addCombo('cmbJob', 'job', undefined, function(){
			addCombo('cmbGrade', 'grade', undefined, function(){
				addNewMode('frmCarier');
				openModal('frmCarier', 'Penugasan Baru', saveCarier);
				//set default values
				$('#frmCarier input[name="EMP_ID"]').val($('#EmployeeID').val());
				$('#frmCarier input[name="ID"]').val("0");	
				$('#frmCarier select[name="FULL_DAY"]').val("1").change();
			});
		});
	});
}

function editCarier(id) {
	var uri = base_url + "carier/get/" + id;
	queryRecord(uri, function(data){
		addCombo('cmbDept', 'dept', undefined, function(){
			addCombo('cmbJob', 'job', undefined, function(){
				addCombo('cmbGrade', 'grade', undefined, function(){
					editMode('frmCarier', data.payload);
					openModal('frmCarier', 'Ubah Penugasan', saveCarier, removeCarier);

					$('#frmCarier input[name="EMP_ID"]').val($('#EmployeeID').val());
				});
			});
		});
	});
}

function saveCarier() {
	//var s = $('#cmbJob').val().split(';');
	//$('#frmCarier input[name="DEPT_ID"]').val(s[0]);
	//$('#frmCarier input[name="JOB_ID"]').val(s[1]);

	if ($('[name="SPV_NAME"]').val() == '') {
		$('[name="APPROVER1"]').val('');
	}

	if ($('[name="MGR_NAME"]').val() == '') {
		$('[name="APPROVER2"]').val('');
	}

	var uri = base_url + ($('#frmCarier input[name="ID"]').val() == '0' ? 'carier/create' : 'carier/update');
	saveRecord('frmCarier', uri, function() {
		toast("Data berhasil disimpan","success");
		$('#grdCarier').trigger('reloadGrid');
		closeModal('frmCarier');
	});
}

function removeCarier() {
	var uri = base_url + 'carier/delete';
	removeRecord('frmCarier', 'Anda akan menghapus data ini. Lanjutkan ?', uri, function() {
		toast("Data berhasil dihapus","success");
		$('#grdCarier').trigger('reloadGrid');
		closeModal('frmCarier');
	});
}