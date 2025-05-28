<script src="<?php echo base_url('assets/js/company.js')?>"></script>

<div class="ui left aligned segment" style="margin-top: 50px; max-width: 950px; min-height: 500px">
	<h3 class="ui header">
		<img src="<?php echo base_url('assets/images/briefcase.jpg')?>">
		<div class="content">
			Profil Perusahaan
			<div class="sub header">Informasi umum perusahaan</div>
		</div>
	</h3>
		
    <form id="frmCompany" class="ui small form" method="post">
        <div class="field">
			<label>Nama Perusahaan</label>
			<input name="CORP_NAME" placeholder="Nama Perusahaan" type="text">
        </div>
		<div class="four fields">
			<div class="field">
				<label>Nomor Ijin</label>
				<input name="LICENSE_NO" placeholder="Nomor Ijin" type="text">
			</div>
			<div class="field">
				<label>Berdiri</label>
				<div class="ui calendar" id="established_at">
				<div class="ui input left icon">
					<i class="calendar icon"></i>
					<input name="ESTABLISHED_AT" placeholder="Berdiri" type="text" data-tag="date">
				</div>
				</div>
			</div>
			<div class="field">
				<label>N P W P</label>
				<input name="NPWP" placeholder="N P W P" type="text">
			</div>
			<div class="field">
				<label>Terdaftar</label>
				<div class="ui calendar" id="registered_at">
				<div class="ui input left icon">
					<i class="calendar icon"></i>
					<input name="REGISTERED_AT" placeholder="Terdaftar" type="text" data-tag="date">
				</div>
				</div>
			</div>
		</div>
        <div class="field">
			<label>Alamat</label>
			<textarea name="ADDRESS" placeholder="Alamat" rows="1"></textarea>
        </div>
		<div class="three fields">
			<div class="field">
				<label>Kota</label>
				<input name="CITY" placeholder="Kota" type="text">
			</div>
			<div class="field">
				<label>Negara</label>
				<input name="COUNTRY" placeholder="Negara" type="text">
			</div>
			<div class="field">
				<label>Kode Pos</label>
				<input name="ZIP_CODE" placeholder="Kode Pos" type="text">
			</div>
		</div>
		<div class="three fields">
			<div class="field">
				<label>Telepon</label>
				<input name="PHONE_NUM" placeholder="Telepon" type="text">
			</div>
			<div class="field">
				<label>Fax</label>
				<input name="FAX_NUM" placeholder="Fax" type="text">
			</div>
			<div class="field">
				<label>E-Mail</label>
				<input name="EMAIL" placeholder="E-Mail" type="text" data-tag="email">
			</div>
		</div>
		<input type="hidden" name="ID">
		<div id="divCmd">
			<div id="cmdSave" class="ui right aligned teal mini button" onclick="save()">Simpan</div>
			<div id="cmdRefresh" class="ui right aligned mini button" onclick="location.reload()">Refresh</div> 
		</div>
    </form>
</div>