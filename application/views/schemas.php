<script src="<?php echo base_url('assets/js/schemas.js')?>"></script>
<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/allowance.png')?>">
			<div class="content">
				Skema Tunjangan dan Potongan
			<div class="sub header">Skema tunjangan dan potongan pegawai</div>
		  </div>
		</h3>

		<div id="cmdAdd" class="ui mini green labeled icon button" onclick="addSchema()">Skema Baru<i class="plus square icon"></i></div><br><br>
		<table id="grdSchema"></table>
    <!--/div-->
</div>