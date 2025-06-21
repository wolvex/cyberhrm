<script src="<?php echo base_url('assets/js/reimburse.js')?>"></script>

<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/claim.png')?>">
			<div class="content">
				Reimburse Pegawai
			<div class="sub header">Reimburse (penggantian uang) pegawai</div>
		  </div>
		</h3>

		<div class="ui mini form">
			<div class="fields">
				<!--div class="three wide field">
					<select id="cmbSelect" class="ui dropdown" placeholder="Jenis Komponen">
					</select>
				</div-->
				<div class="three wide field">		
					<div class="ui calendar" id="month_at">
						<div class="ui input left icon">
							<i class="calendar icon"></i>
							<input name="month_at" placeholder="Periode" type="text">
						</div>
					</div>
				</div>
				<div class="thirteen wide field" style="align: left">
					<div id="cmdAdd" class="ui mini green labeled icon button" onclick="addAdjustment()">Baru<i class="plus square icon"></i></div>
				</div>
			</div>
		</div>
		<table id="grdAdjustment"></table>
    <!--/div-->
</div>

<div class="ui small modal" id="mdlAdjustment">
	<div id="title" class="header">
		Reimburse pegawai
	</div>
	<div class="content small text">
		<form id="frmAdjustment" class="ui mini form">
			<div class="three fields">
				<div class="field">
					<label>Pegawai</label>
					<div id="findEmp" class="ui fluid dropdown search">
						<div class="ui left icon input">
							<input class="prompt" name="EMP_NAME" placeholder="Pegawai">
							<input type="hidden" name="EMP_ID">
							<i class="search icon"></i>
						</div>
						<div class="results flowing scrolling menu"></div>
					</div>
				</div>
				<div class="field">
					<label>Jenis Komponen</label>
					<select id="cmbAdjustment" class="ui dropdown" name="CODE">
					</select>
				</div>
				<div class="field">
					<label>No Referensi</label>
					<input name="REF_NO" placeholder="Nomor Referensi">
				</div>
			</div>
			<div class="three fields">
				<div class="field">
					<label>Tanggal Reimburse</label>
					<div class="ui calendar" id="adjust_at" data-type="date">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input name="ADJUST_AT" placeholder="Tanggal Reimburse" type="text">
					</div>
					</div>
				</div>
				<div class="field">
					<label>Tanggal Transaksi</label>
					<div class="ui calendar" id="trx_at" data-type="date">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input name="TRX_AT" placeholder="Tanggal Transaksi" type="text">
					</div>
					</div>
				</div>
				<div class="field">
					<label>Nilai (Rp)</label>
					<input name="AMOUNT" value="0" data-tag="num">
				</div>
			</div>
			<div class="field">
				<label>Catatan</label>
				<textarea name="REMARKS" rows="2"></textarea>
			</div>
			<input name="STATUS" type="hidden" value="A">
			<input name="ID" type="hidden">

			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
				<div id="cmdDelete" class="ui mini negative labeled icon button">Hapus<i class="trash icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>