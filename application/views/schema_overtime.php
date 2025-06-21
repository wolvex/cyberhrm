<script src="<?php echo base_url('assets/js/schema_overtime.js')?>"></script>
<div class="ui small modal" id="mdlOvr">
	<div id="title" class="header">
		Tunjangan Lembur
	</div>
	<div class="content small text">
		<form id="frmOvr" class="ui mini form">
			<div class="field">
				<label>Jenis Lembur</label>
				<input name="NAME" readonly>
			</div>
			<div class="two fields">
				<div class="field">
					<label>Sampai Dengan Jam Ke-</label>
					<input name="HOUR1" value="0" data-tag="int">
				</div>
				<div class="field">
					<label>Faktor Pengali Upah per Jam</label>
					<input name="FACTOR1" value="0" data-tag="num">
				</div>
			</div>
			<div class="two fields">
				<div class="field">
					<label>Sampai Dengan Jam Ke-</label>
					<input name="HOUR2" value="0" data-tag="int">
				</div>
				<div class="field">
					<label>Faktor Pengali Upah per Jam</label>
					<input name="FACTOR2" value="0" data-tag="num">
				</div>
			</div>
			<div class="two fields">
				<div class="field">
					<label>Sampai Dengan Jam Ke-</label>
					<input name="HOUR3" value="0" data-tag="int">
				</div>
				<div class="field">
					<label>Faktor Pengali Upah per Jam</label>
					<input name="FACTOR3" value="0" data-tag="num">
				</div>
			</div>
			<input name="SCHEMA_ID" type="hidden">
			<input name="ABSENCE_ID" type="hidden">
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>