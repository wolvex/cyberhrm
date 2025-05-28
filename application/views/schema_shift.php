<script src="<?php echo base_url('assets/js/schema_shift.js')?>"></script>
<div class="ui small modal" id="mdlShift">
	<div id="title" class="header">
		Tunjangan Kehadiran
	</div>
	<div class="content small text">
		<form id="frmShift" class="ui mini form">
			<div class="fields">
				<div class="ten wide field">
					<label>Shift</label>
					<input type="text" name="NAME" readonly style="text-align:center;font-weight:bold">
				</div>
				<div class="three wide field">
					<label>Jam Masuk</label>
					<input type="text" name="CLOCK_IN" readonly style="text-align:center;font-weight:bold">
				</div>
				<div class="three wide field">
					<label>Jam Pulang</label>
					<input type="text" name="CLOCK_OUT" readonly style="text-align:center;font-weight:bold">
				</div>
			</div>
			<div class="three fields">
				<div class="field">
					<label>Premi Kehadiran</label>
					<input type="text" name="PREMI_ATTENDANCE" data-tag="num">
				</div>
				<div class="field">
					<label>Premi Shift</label>
					<input type="text" name="PREMI_SHIFT" data-tag="num">
				</div>
				<div class="field">
					<label>Uang Makan</label>
					<input type="text" name="ALLOWANCE" data-tag="num">
				</div>
			</div>
			<input name="SCHEMA_ID" type="hidden">
			<input name="SHIFT_ID" type="hidden">
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>