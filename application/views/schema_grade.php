<script src="<?php echo base_url('assets/js/schema_grade.js')?>"></script>
<div class="ui small modal" id="mdlGrade">
	<div id="title" class="header">
		Tunjangan Jabatan
	</div>
	<div class="content small text">
		<form id="frmGrade" class="ui mini form">
			<div class="two fields">
				<div class="field">
					<label>Jabatan</label>
					<input type="text" name="DESCRIPTION" readonly style="text-align:center;font-weight:bold">
				</div>
				<div class="field">
					<label>Tunjangan</label>
					<input type="text" name="ALLOWANCE" data-tag="num">
				</div>
			</div>
			<input name="SCHEMA_ID" type="hidden">
			<input name="GRADE_ID" type="hidden">
		</form>
		<div class="actions action-box">
			<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
			<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
		</div>
	</div>
</div>