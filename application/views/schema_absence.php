<script src="<?php echo base_url('assets/js/schema_absence.js')?>"></script>
<div class="ui small modal" id="mdlAbsence">
	<div id="title" class="header">
		Tunjangan Jabatan
	</div>
	<div class="content small text">
		<form id="frmAbsence" class="ui mini form">
			<div class="two fields">
				<div class="field">
					<label>Jenis Cuti/Ijin</label>
					<input type="text" name="NAME" readonly style="text-align:center;font-weight:bold">
				</div>
				<div class="field">
					<label>Quota Tahunan (hari)</label>
					<input type="text" name="ANNUAL_QUOTA" data-tag="int">
				</div>
				<div class="field">
					<label>Max. Carry Over (hari)</label>
					<input type="text" name="CARRY_OVER" data-tag="int">
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