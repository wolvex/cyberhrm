<script src="<?php echo base_url('assets/js/wage_schema.js')?>"></script>
<div class="ui small modal" id="mdlSchema">
	<div id="title" class="header">
		Skema Tunjangan Pegawai
	</div>
	<div class="content small text">
		<form id="frmSchema" class="ui mini form">
			<div class="field">
				<label>Nama Pegawai</label>
				<input type="text" name="EMP_NAME" readonly>
			</div>
			<div class="fields">
				<div class="three wide field">
					<label>Gaji Pokok</label>
					<input type="text" name="GROSS_WAGE" data-tag="num">
				</div>
				<div class="nine wide field">
					<label>Skema</label>
					<select id="cmbSchema" class="ui search dropdown" name="SCHEMA_ID">					
					</select>				
				</div>
				<div class="four wide field">
					<label>Tanggal Efektif</label>
					<div class="ui calendar" id="effective_at" data-type="date">
						<div class="ui input left icon">
							<i class="calendar icon"></i>
							<input name="EFFECTIVE_AT" placeholder="Tanggal efektif" type="text" data-tag="date">
						</div>
					</div>
				</div>
			</div>
			<div class="field">
				<label>Catatan</label>
				<textarea name="REMARKS" rows="2"></textarea>
			</div>
			<input name="ID" type="hidden" value="0">
			<input name="EMP_ID" type="hidden">
			<input name="STATUS" type="hidden" value="A">
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
				<div id="cmdDelete" class="ui mini negative labeled icon button">Hapus<i class="trash icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>