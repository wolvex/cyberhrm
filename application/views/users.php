<script src="<?php echo base_url('assets/js/users.js')?>"></script>

<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/users.png')?>">
			<div class="content">
				Pengguna
			<div class="sub header">Profil pengguna dan ganti password</div>
		  </div>
		</h3>
		<div class="ui icon mini input" style="padding-bottom:5px">
			<div id="cmdAdd" class="ui mini green labeled icon button" onclick="addUsers()">Tambah<i class="plus square icon"></i></div>
		</div>
		<table id="grdUsers"></table>
    <!--/div-->
</div>

<div class="ui small modal" id="mdlUsers">
	<div id="title" class="header">
		Profil Pengguna
	</div>
	<div class="content small text">
		<form id="frmUsers" class="ui mini form">
			<input name="ID" type="hidden">			
			<div class="fields">
				<div class="three wide field">
					<label>ID</label>
					<input name="ID" data-tag="key">
				</div>
				<div class="nine wide field">
					<label>Name</label>
					<input name="NAME">
				</div>
				<div class="four wide field">
					<label>Role</label>
					<select id="cmbRole" class="ui dropdown" name="ROLE">
						<option value="ADMIN">Administrator</option>
						<option value="EMP_MGR">Manager Personalia</option>
						<option value="EMP_STAFF">Petugas Personalia</option>
						<option value="PAY_MGR">Manager Payroll</option>
						<option value="PAY_STAFF">Petugas Payroll</option>
					</select>
				</div>
			</div>
			<div class="three fields">				
				<div class="field">
					<label>E-Mail</label>
					<input name="EMAIL">
				</div>
				<div class="field">
					<label>Password</label>
					<input name="PASSWORD" type="password" placeholder="Password">
				</div>
				<div class="field">
					<label>Confirm Password</label>
					<input name="CONFIRMPASSWORD" type="password" placeholder="Confirm Password">
				</div>				
			</div>
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
				<div id="cmdDelete" class="ui mini negative labeled icon button">Hapus<i class="trash icon"></i></div>
				<div id="cmdReset" class="ui mini negative labeled icon button">Reset<i class="refresh icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>