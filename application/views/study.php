<script src="<?php echo base_url('assets/js/study.js')?>"></script>
<div class="ui small modal" id="mdlStudy">
	<div id="title" class="header">
		Kualifikasi
	</div>
	<div class="content small text">
		<form id="frmStudy" class="ui mini form">
			<div class="three fields">
				<div class="field">
					<label>Nama Institusi</label>
					<input name="INSTITUTE" placeholder="Nama Institusi">
				</div>
				<div class="field">
					<label>Jenis Pendidikan</label>
					<select id="cmbStudy" class="ui fluid dropdown" name="STUDY_TYPE">
						<option value="F" selected>Formal</option>
						<!--option value="N">Non-Formal</option-->
					</select>
				</div>
				<div class="field">
					<label>Jenis Sertifikasi</label>
					<select id="cmbCert" class="ui fluid dropdown" name="CERTIFICATE_ID">
					</select>
				</div>
			</div>
			<div class="three fields">
				<div class="field">
					<label>Mulai</label>
					<div class="ui calendar" id="enrolled_at" data-type="date">
						<div class="ui input left icon">
							<i class="calendar icon"></i>
							<input name="ENROLLED_AT" placeholder="Mulai" type="text" data-tag="date">
						</div>
					</div>
				</div>
				<div class="field">
					<label>Lulus</label>
					<div class="ui calendar" id="graduated_at" data-type="date">
						<div class="ui input left icon">
							<i class="calendar icon"></i>
							<input name="GRADUATED_AT" placeholder="Lulus" type="text" data-tag="date">
						</div>
					</div>
				</div>
				<div class="field">
					<label>Catatan</label>
					<input name="REMARKS" placeholder="Catatan">
				</div>
			</div>
			<input name="EMP_ID" type="hidden">
			<input name="ID" type="hidden">
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo outline icon"></i></div>
				<div id="cmdDelete" class="ui mini negative labeled icon button">Hapus<i class="trash icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>