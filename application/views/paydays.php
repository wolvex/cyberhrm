<script src="<?php echo base_url('assets/js/paydays.js')?>"></script>

<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/payroll.png')?>">
			<div class="content">
				Proses Penggajian
			<div class="sub header">Proses penggajian (payroll) dan tunjangan pegawai</div>
		  </div>
		</h3>

		<div class="ui icon mini input" style="padding-bottom:5px">
			<div class="ui calendar" id="year_at">
				<div class="ui input left icon">
					<i class="calendar icon"></i>
					<input name="year_at" placeholder="Tahun" type="text">
				</div>
			</div>
		</div>
		<div id="cmdAdd" class="ui mini green labeled icon button" onclick="addPayroll()">
			Proses Baru<i class="plus square icon"></i>
		</div>
		<table id="grdPayroll"></table>
    <!--/div-->
</div>

<div class="ui small modal" id="mdlPayroll">
	<div id="title" class="header">
		Proses Penggajian dan Tunjangan
	</div>
	<div class="content small text">
		<form id="frmPayroll" class="ui mini form">
			<div class="fields">
				<div class="two wide field">
					<label>Proses ID</label>
					<input name="ID" readonly>
				</div>				
				<div class="ten wide field">
					<label>Proses</label>
					<select id="cmbPayroll" class="ui dropdown" name="CODE"></select>
				</div>
				<div class="four wide field">
					<label>Status</label>
					<select id="cmbStatus" class="ui dropdown" name="STATUS" disabled>
						<option value="Q">Akan Diproses</option>
						<option value="P">Sedang Proses</option>
						<option value="C">Selesai Proses</option>
					</select>
				</div>
			</div>
			<div class="four fields">
				<div class="field">
					<label>Periode</label>
					<div class="ui calendar" id="period" data-tag="nodraw">
						<div class="ui input left icon">
							<i class="calendar icon"></i>
							<input name="PERIOD" placeholder="Bulan" type="text">
						</div>
					</div>
				</div>
				<div class="field">
					<label>Mulai</label>
					<div class="ui calendar" id="start_at">
						<div class="ui input left icon">
							<i class="calendar icon"></i>
							<input name="START_AT" placeholder="Kehadiran Mulai" type="text">
						</div>
					</div>
				</div>
				<div class="field">
					<label>Sampai Dengan</label>
					<div class="ui calendar" id="end_at">
						<div class="ui input left icon">
							<i class="calendar icon"></i>
							<input name="END_AT" placeholder="Sampai Dengan" type="text">
						</div>
					</div>
				</div>
				<div class="field">
					<label>Jatuh Tempo</label>
					<div class="ui calendar" id="due_at" data-type="date">
						<div class="ui input left icon">
							<i class="calendar icon"></i>
							<input name="DUE_AT" placeholder="Jatuh Tempo" type="text">
						</div>
					</div>
				</div>
			</div>
			<div class="field">
				<label>Catatan</label>
				<textarea name="REMARKS" rows="1"></textarea>
			</div>
			<div class="four fields">
				<div class="field">
					<label>Tanggal Proses</label>
					<input name="CREATED_AT" readonly placeholder="Tanggal Proses">
				</div>
				<div class="field">
					<label>Diproses Oleh</label>
					<input name="CREATED_BY" readonly placeholder="Diproses Oleh">
				</div>
				<div class="field">
					<label>Tanggal Cetak</label>
					<input name="PRINTED_AT" readonly placeholder="Tanggal Cetak">
				</div>
				<div class="field">
					<label>Dicetak Oleh</label>
					<input name="PRINTED_BY" readonly placeholder="Dicetak Oleh">
				</div>
			</div>
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>