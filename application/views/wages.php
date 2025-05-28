<script src="<?php echo base_url('assets/js/wages.js')?>"></script>


<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/wages.png')?>">
			<div class="content">
				Gaji Pegawai
			<div class="sub header">Skema gaji, tunjangan, dan potongan pegawai</div>
		  </div>
		</h3>

		<div class="ui mini icon input" style="padding-bottom:5px">
			<input id="txtFind" class="prompt" placeholder="Cari pegawai" type="text" onchange="searchEmployee()">
			<i class="search link icon" onclick="searchEmployee()"></i>
		</div>
		<div class="ui checkbox" style="padding:5px;font-size:8pt">
			<input name="chkPermanen" type="checkbox" checked="" onchange="searchEmployee()">
			<label>Permanen</label>
		</div>
		<div class="ui mini checkbox" style="padding:5px;font-size:8pt">
			<input name="chkKontrak" type="checkbox" checked="" onchange="searchEmployee()">
			<label>Kontrak</label>
		</div>
		<div class="ui mini checkbox" style="padding:5px;font-size:8pt">
			<input name="chkHarian" type="checkbox" checked="" onchange="searchEmployee()">
			<label>Harian</label>
		</div>
		<table id="grdEmployee"></table>
    <!--/div-->
</div>