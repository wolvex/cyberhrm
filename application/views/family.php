<script src="<?php echo base_url('assets/js/family.js')?>"></script>
<div class="ui small modal" id="mdlFamily">
	<div id="title" class="header">
		Keluarga
	</div>
	<div class="content small text">
		<form id="frmFamily" class="ui mini form">
			<div class="three fields">
				<div class="field">
					<label>Nama</label>
					<input name="NAME" placeholder="Nama">
				</div>
				<div class="field">
					<label>Hubungan</label>
					<select id="cmbRelation" class="ui fluid dropdown" name="RELATION_TYPE">
						<option value="S" selected>Pasangan</option>
						<option value="C">Anak</option>
					</select>
				</div>
				<div class="field">
					<label>Tanggal Lahir</label>
					<div class="ui calendar" id="family_borned_at" data-type="date">
						<div class="ui input left icon">
							<i class="calendar icon"></i>
							<input name="BORNED_AT" placeholder="Mulai" type="text" data-tag="date">
						</div>
					</div>
				</div>
			</div>
			<div class="four fields">
				<div class="field">
					<label>Tanggungan Karyawan</label>
					<select id="cmbStatus" class="ui fluid dropdown" name="STATUS">
						<option value="A" selected>Ya</option>
						<option value="I">Tidak</option>
					</select>
				</div>
				<div class="field">
					<label>Nomor KTP</label>
					<input name="CITIZEN_ID" placeholder="Nomor KTP">
				</div>
				<div class="field">
					<label>Nomor Kartu Keluarga</label>
					<input name="FAMILY_ID" placeholder="Nomor Kartu Keluarga">
				</div>
				<div class="field">
					<label>Nomor Telepon/Seluler</label>
					<input name="PHONE_NO" placeholder="Nomor Telepon/Seluler">
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