<script src="<?php echo base_url('assets/js/wage.js')?>"></script>

<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/wages.png')?>">
			<div class="content">
				<div id="divTitle" class="header"></div>
				<div id="divSubtitle" class="sub header"></div>
		  </div>
		</h3>

		<div class="ui top attached tabular menu">
			<a class="active item" data-tab="general">Informasi Umum</a>
			<a class="item" data-tab="carier">Penugasan</a>
			<a class="item" data-tab="schema">Gaji & Tunjangan</a>
		</div>

		<div class="ui bottom attached active tab segment" data-tab="general">
			<form id="frmEmployee" class="ui small form" method="post">
				<div class="fields">
					<div class="two wide field">
						<label>Nomor Induk</label>
						<input name="CODE" readonly type="text" data-tag="key">
					</div>
					<div class="ten wide field">
						<label>Nama Lengkap</label>
						<input name="NAME" readonly type="text">
					</div>
					<div class="two wide field">
						<label>Jenis Kelamin</label>
						<select id="cmbGender" class="ui fluid dropdown" name="GENDER" disabled>
							<option value="P" selected>Pria</option>
							<option value="W">Wanita</option>
						</select>
					</div>
					<div class="two wide field">
						<label>Tanggal Lahir</label>
						<input name="BORNED_AT" readonly type="text">
					</div>
				</div>
				<div class="five fields">
					<div class="field">
						<label>Tanggal Bergabung</label>
						<input name="JOINED_AT" readonly type="text">
					</div>
					<!--div class="field">
						<label>Tanggal Berhenti</label>
						<input name="RESIGNED_AT" readonly type="text">
					</div-->
					<div class="field">
						<label>Nomor KTP</label>
						<input name="CITIZEN_ID" readonly type="text">
					</div>
					<div class="field">
						<label>N P W P</label>
						<input name="NPWP" readonly type="text">
					</div>
					<div class="field">
						<label>BPJS KTK</label>
						<input name="BPJS_KTK" readonly type="text">
					</div>
					<div class="field">
						<label>BPJS KSH</label>
						<input name="BPJS_KSH" readonly type="text">
					</div>
				</div>
				<div class="four fields">
					<div class="field">
						<label>Nomor Passport</label>
						<input name="PASSPORT_ID" readonly type="text">
					</div>
					<div class="field">
						<label>Warga Negara</label>
						<input name="NATIONALITY" readonly type="text">
					</div>				
					<div class="field">
						<label>Bank</label>
						<select id="cmbBankCode" class="ui fluid dropdown" name="BANK_CODE" disabled>
							<option value="013" selected>Bank Central Asia</option>
							<option value="008">Bank Mandiri Tbk</option>
						</select>
					</div>
					<div class="field">
						<label>Nomor Rekening</label>
						<input name="BANK_ACCOUNT" readonly type="text">
					</div>
				</div>
				<div class="field">
					<label>Catatan</label>
					<input name="REMARKS" readonly type="text">
				</div>
				<input id="EmployeeID" name="ID" type="hidden" value="<?php echo $ID ?>">
				<div id="divCmd" class="ui mini buttons">
					<div id="cmdRefresh" class="ui mini labeled icon button" onclick="location.reload()">Refresh<i class="undo icon"></i></div> 
				</div>
			</form>
		</div>
	
		<div class="ui bottom attached tab segment" data-tab="carier">
			<table id="grdCarier"></table>
		</div>

		<div class="ui bottom attached tab segment" data-tab="schema">
			<div class="ui icon mini input" style="padding-bottom:5px">
				<div id="cmdAddSchema" class="ui mini green labeled icon button" onclick="addSchema()">Skema Baru<i class="plus square icon"></i></div>
			</div>
			<table id="grdSchema"></table>
		</div>
    <!--/div-->
</div>
<?php require(ROOT_PATH.'/views/wage_schema.php'); ?>