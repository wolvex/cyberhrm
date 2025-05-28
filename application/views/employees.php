<script src="<?php echo base_url('assets/js/employees.js')?>"></script>

<style type="text/css">
	.ui-jqgrid .ui-jqgrid-htable th {
		height: 3em !important;
	}
	th.ui-th-column div {
		white-space: normal !important;
		height: auto !important;
		padding: 2px;
	}
</style>

<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/employee.png')?>">
			<div class="content">
				Pegawai
			<div class="sub header">Daftar pegawai</div>
		  </div>
		</h3>

		<div class="ui icon mini input" style="padding-bottom:5px">
			<input id="txtFind" class="prompt" placeholder="Cari pegawai" type="text" onchange="searchEmployee()">
			<i class="search link icon" onclick="searchEmployee()"></i>
		</div>
		<div id="cmdExport" class="ui mini labeled icon button">Export<i class="download icon"></i></div>
		<div id="cmdAdd" class="ui mini green labeled icon button">Pegawai Baru<i class="plus square icon"></i></div>

		<div class="ui checkbox" style="padding:10px;font-size:8pt">
			<input name="chkPermanen" type="checkbox" checked="" onchange="searchEmployee()">
			<label>Permanen</label>
		</div>
		<div class="ui mini checkbox" style="padding:10px;font-size:8pt">
			<input name="chkKontrak" type="checkbox" checked="" onchange="searchEmployee()">
			<label>Kontrak</label>
		</div>
		<div class="ui mini checkbox" style="padding:10px;font-size:8pt">
			<input name="chkHarian" type="checkbox" checked="" onchange="searchEmployee()">
			<label>Harian</label>
		</div>
		<table id="grdEmployee"></table>
		<div id="employeeCount" class="ui label" style="margin-top:10px">Ditemukan 100 pegawai</div>
    <!--/div-->
</div>