<script src="<?php echo base_url('assets/js/employee.js')?>"></script>

<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/employee.png')?>">
			<div class="content">
				<div id="divTitle" class="header"></div>
				<div id="divSubtitle" class="sub header"></div>
		  </div>
		</h3>

		<div class="ui top attached tabular menu">
			<a class="active item" data-tab="general">Informasi Umum</a>
			<a class="item" data-tab="carier">Penugasan</a>
			<a class="item" data-tab="study">Kualifikasi</a>
			<a class="item" data-tab="family">Keluarga</a>
			<a class="item" data-tab="document">Dokumen</a>
		</div>

		<div class="ui bottom attached active tab segment" data-tab="general">
			<form id="frmEmployee" class="ui small form" method="post">
				<div class="fields">
					<div class="two wide field">
						<label>Nomor Induk</label>
						<input name="CODE" placeholder="Nomor Induk Pegawai" type="text" data-tag="key">
					</div>
					<div class="five wide field">
						<label>Nama Lengkap</label>
						<input name="NAME" placeholder="Nama Lengkap" type="text">
					</div>
					<div class="two wide field">
						<label>Jenis Kelamin</label>
						<select id="cmbGender" class="ui fluid dropdown" name="GENDER">
							<option value="P" selected>Pria</option>
							<option value="W">Wanita</option>
						</select>
					</div>					
					<div class="three wide field">
						<label>Tanggal Bergabung</label>
						<div class="ui calendar" id="joined_at">
							<div class="ui input left icon">
								<i class="calendar icon"></i>
								<input name="JOINED_AT" placeholder="Tanggal bergabung" type="text" data-tag="date">
							</div>
						</div>
					</div>
					<div class="three wide field">
						<label>Tanggal Berhenti</label>
						<div class="ui calendar" id="resigned_at">
							<div class="ui input left icon">
								<i class="calendar icon"></i>
								<input name="RESIGNED_AT" placeholder="Tanggal berhenti" type="text" data-tag="date">
							</div>
						</div>
					</div>
					<div class="three wide field">
						<label>Tanggal Lahir</label>
						<div class="ui calendar" id="borned_at">
							<div class="ui input left icon">
								<i class="calendar icon"></i>
								<input name="BORNED_AT" placeholder="Tanggal lahir" type="text" data-tag="date">
							</div>
						</div>
					</div>
				</div>
				<div class="six fields">
					<div class="field">
						<label>Nomor KTP</label>
						<input name="CITIZEN_ID" placeholder="Nomor KTP" type="text">
					</div>
					<div class="field">
						<label>N P W P</label>
						<input name="NPWP" placeholder="N P W P" type="text">
					</div>
					<div class="field">
						<label>BPJS KTK</label>
						<input name="BPJS_KTK" placeholder="BPJS KTK" type="text">
					</div>
					<div class="field">
						<label>BPJS KSH</label>
						<input name="BPJS_KSH" placeholder="BPJS KSH" type="text">
					</div>
					<div class="field">
						<label>Nomor Passport</label>
						<input name="PASSPORT_ID" placeholder="Nomor Passport" type="text">
					</div>				
					<!--div class="field">
						<label>Status Menikah</label>
						<select id="cmbMarried" class="ui dropdown" name="MARRIED">
							<option value="Y" selected>Sudah Menikah</option>
							<option value="N">Belum Menikah</option>
						</select>
					</div-->
					<div class="field">
						<label>Warga Negara</label>
						<input name="NATIONALITY" placeholder="Warga Negara" type="text">
					</div>				
				</div>
				<div class="fields">
					<div class="eight wide field">
						<label>Alamat</label>
						<textarea rows="1" name="ADDRESS" placeholder="Alamat"></textarea>
					</div>					
					<div class="two wide field">
						<label>Kota</label>
						<input name="CITY" placeholder="Kota" type="text">
					</div>
					<div class="two wide field">
						<label>Provinsi</label>
						<input name="PROVINCE" placeholder="Provinsi" type="text">
					</div>
					<div class="two wide field">
						<label>Negara</label>
						<input name="COUNTRY" placeholder="Negara" type="text">
					</div>
					<div class="two wide field">
						<label>Kode Pos</label>
						<input name="ZIP_CODE" placeholder="Kode Pos" type="text">
					</div>
				</div>
				<div class="fields">
					<div class="two wide field">
						<label>Nomor Telepon</label>
						<input name="WORK_PHONE" placeholder="Nomor Telepon" type="text">
					</div>
					<div class="two wide field">
						<label>Nomor Seluler</label>
						<input name="MOBILE_PHONE" placeholder="Nomor Seluler" type="text">
					</div>
					<div class="three wide field">
						<label>E-Mail</label>
						<input name="EMAIL" placeholder="E-Mail" type="text">
					</div>
					<div class="three wide field">
						<label>Bank</label>
						<select id="cmbBankCode" class="ui fluid dropdown" name="BANK_CODE">
							<option value="013" selected>Bank Central Asia</option>
							<option value="008">Bank Mandiri Tbk</option>
						</select>
					</div>
					<div class="two wide field">
						<label>Nomor Rekening</label>
						<input name="BANK_ACCOUNT" placeholder="Rekening Bank" type="text">
					</div>
					<div class="four wide field">
						<label>Catatan</label>
						<textarea rows="2" name="REMARKS" placeholder="Catatan"></textarea>
					</div>
				</div>
				<input id="EmployeeID" name="ID" type="hidden" value="<?php echo $ID ?>">
				<div id="divCmd" class="ui mini buttons">
					<div id="cmdAdd" class="ui mini green labeled icon button" onclick="addEmployee()">Baru<i class="plus square icon"></i></div>
					<div id="cmdSave" class="ui mini teal labeled icon button" onclick="saveEmployee()">Simpan<i class="save icon"></i></div>
				</div>
				<div id="divCmd" class="ui mini buttons">
					<div id="cmdDelete" class="ui mini red labeled icon button" onclick="deleteEmployee()">Hapus<i class="trash icon"></i></div>
					<div id="cmdRefresh" class="ui mini labeled icon button" onclick="location.reload()">Refresh<i class="undo icon"></i></div> 
				</div>
			</form>
		</div>
	
		<div class="ui bottom attached tab segment" data-tab="carier">
			<div class="ui icon mini input" style="padding-bottom:5px">
				<div id="cmdAddCarier" class="ui mini green labeled icon button" onclick="addCarier()">Baru<i class="plus square icon"></i></div>
			</div>
			<table id="grdCarier"></table>
		</div>

		<div class="ui bottom attached tab segment" data-tab="study">
			<div class="ui icon mini input" style="padding-bottom:5px">
				<div id="cmdAddStudy" class="ui mini green labeled icon button" onclick="addStudy()">Baru<i class="plus square icon"></i></div>
			</div>
			<table id="grdStudy"></table>
		</div>

		<div class="ui bottom attached tab segment" data-tab="family">
			<div class="ui icon mini input" style="padding-bottom:5px">
				<div id="cmdAddFamily" class="ui mini green labeled icon button" onclick="addFamily()">Baru<i class="plus square icon"></i></div>
			</div>
			<table id="grdFamily"></table>
		</div>

		<div class="ui bottom attached tab segment" data-tab="document">
			<div class="ui icon mini input" style="padding-bottom:5px">
				<div id="cmdAddDoc" class="ui mini green labeled icon button" onclick="addDoc()">Baru<i class="plus square icon"></i></div>
			</div>
			<table id="grdDoc"></table>
		</div>
    <!--/div-->
</div>
<?php require(ROOT_PATH.'/views/carier.php'); ?>
<?php require(ROOT_PATH.'/views/study.php'); ?>
<?php require(ROOT_PATH.'/views/family.php'); ?>
<?php require(ROOT_PATH.'/views/empdoc.php'); ?>