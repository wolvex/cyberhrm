<script src="<?php echo base_url('assets/js/schema.js')?>"></script>

<div class="ui left aligned segment" style="min-height: 550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/allowance.png')?>">
			<div class="content">
				Skema Tunjangan & Potongan
			<div class="sub header">Skema tunjangan & potongan pegawai</div>
		  </div>
		</h3>
		
		<div class="ui mini buttons">
			<div id="cmdAddSchema" class="ui mini green labeled icon button inline" onclick="addSchema()">Baru<i class="plus square icon"></i></div>
			<div id="cmdSaveSchema" class="ui mini blue labeled icon button inline" onclick="saveSchema()">Simpan<i class="save icon"></i></div>
			<div id="cmdCopySchema" class="ui mini teal labeled icon button" onclick="copySchema()">Copy<i class="copy icon"></i></div>
		</div>
		<div class="ui mini buttons">
			<div id="cmdApproveSchema" class="ui mini green labeled icon button" onclick="approveSchema()">Setujui<i class="thumbs up icon"></i></div>
			<div id="cmdApplySchema" class="ui mini blue labeled icon button" onclick="applySchema(false)">Aktifkan<i class="play icon"></i></div>
		</div>
		<div class="ui mini buttons">
			<div id="cmdDeleteSchema" class="ui mini red labeled icon button" onclick="deleteSchema()">Hapus<i class="trash icon"></i></div>
			<div id="cmdRefreh" class="ui mini labeled icon button" onclick="location.reload()">Refresh<i class="undo icon"></i></div>
		</div>

		<div class="ui segment">
			<form id="frmSchema" class="ui small form" method="post">
				<div class="fields">
					<div class="four wide field">
						<label>Nama</label>
						<input name="NAME" placeholder="Nama" type="text">
					</div>
					<div class="seven wide field">
						<label>Deskripsi</label>
						<input name="DESCRIPTION" placeholder="Deskripsi" type="text">
					</div>
					<div class="three wide field">
						<label>Tanggal Efektif</label>
						<div class="ui calendar" id="effective_at" date-type="date">
							<div class="ui input left icon">
								<i class="calendar icon"></i>
								<input name="EFFECTIVE_AT" placeholder="Tanggal efektif" type="text" data-tag="date">
							</div>
						</div>
					</div>
					<div class="two wide field">
						<label>Status</label>
						<select id="cmbStatus" class="ui fluid dropdown" name="STATUS" disabled>
							<option value="D">Draft</option>
							<option value="A">Disetujui</option>
							<option value="S">Default</option>
						</select>
					</div>
				</div>						
				<input id="SchemaID" name="ID" type="hidden" value="<?php if (isset($ID)) echo $ID ?>">
			</form>
		</div>

		<div class="ui top attached tabular menu">
			<a class="active item" data-tab="common">Umum</a>
			<a class="item" data-tab="shift">Kehadiran</a>
			<a class="item" data-tab="grade">Jabatan</a>
			<a class="item" data-tab="overtime">Lembur</a>
			<a class="item" data-tab="absence">Cuti & Ijin</a>
			<a class="item" data-tab="others">Lain-Lain</a>
		</div>
		
		<?php require(ROOT_PATH.'/views/schema_common.php'); ?>
		<?php require(ROOT_PATH.'/views/schema_others.php'); ?>

		<div class="ui bottom attached tab segment" data-tab="shift">
			<table id="grdShift"></table>
		</div>

		<div class="ui bottom attached tab segment" data-tab="grade">
			<table id="grdGrade"></table>
		</div>

		<div class="ui bottom attached tab segment" data-tab="overtime">
			<table id="grdOvr"></table>
		</div>

		<div class="ui bottom attached tab segment" data-tab="absence">
			<table id="grdAbsence"></table>
		</div>
    <!--/div-->
</div>

<?php require(ROOT_PATH.'/views/schema_shift.php'); ?>
<?php require(ROOT_PATH.'/views/schema_grade.php'); ?>
<?php require(ROOT_PATH.'/views/schema_absence.php'); ?>
<?php require(ROOT_PATH.'/views/schema_overtime.php'); ?>

<div class="ui small modal" id="mdlApply">
	<div id="ttlApply" class="header">
		Aktifkan Skema ke Pegawai
	</div>
	<div class="content small text">
		<form id="frmApply" class="ui mini form">
			<div class="ui orange inverted segment">
				Operasi ini akan mengaktifkan skema ini ke pegawai terpilih.<br>
				Skema yang saat ini aktif di pegawai, tidak akan berlaku lagi setelah skema ini masuk masa efektifnya.
			</div>
			<div class="ui labeled fluid input">
				<label class="ui right pointing label">Aplikasi ke Pegawai Dengan Status:</label>
				<select id="cmbStatus" class="ui fluid dropdown" name="EMPSTATUS">
					<option value="P">Permanen</option>
					<option value="K">Kontrak</option>
					<option value="H">Harian</option>
				</select>
			</div>
		</form>
		<div class="actions action-box">
			<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
			<div id="cmdApply" class="ui mini positive labeled icon button">O K<i class="checkmark icon"></i></div>
		</div>
	</div>
</div>