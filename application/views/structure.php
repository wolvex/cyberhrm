<script src="<?php echo base_url('assets/js/structure.js')?>"></script>
<script src="<?php echo base_url('assets/js/department.js')?>"></script>
<script src="<?php echo base_url('assets/js/division.js')?>"></script>
<script src="<?php echo base_url('assets/js/grade.js')?>"></script>
<script src="<?php echo base_url('assets/js/job.js')?>"></script>

<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/hierarchy.png')?>">
			<div class="content">
				Struktur Perusahaan
			<div class="sub header">Departement, divisi, dan jabatan</div>
		  </div>
		</h3>

		<div class="ui top attached tabular menu">
			<a class="active item" data-tab="department">Departemen</a>
			<a class="item" data-tab="grade">Level</a>
			<a class="item" data-tab="job">Jabatan</a>
		</div>

		<div class="ui bottom attached active tab segment" data-tab="department">
			<div class="ui icon mini input" style="padding-bottom:5px">
				<div id="cmdAddDept" class="ui mini green labeled icon button" onclick="addDept()">Tambah<i class="plus square icon"></i></div>
			</div>
			<table id="grdDept"></table>
		</div>

		<div class="ui bottom attached tab segment" data-tab="grade">
			<div class="ui icon mini input" style="padding-bottom:5px">
				<div id="cmdAddGrade" class="ui mini green labeled icon button" onclick="addGrade()">Tambah<i class="plus square icon"></i></div>
			</div>
			<table id="grdGrade"></table>
		</div>

		<div class="ui bottom attached tab segment" data-tab="job">
			<div class="ui icon mini input" style="padding-bottom:5px">
				<input id="txtFind" class="prompt" placeholder="Cari text" type="text" onchange="searchJob()">
				<i class="search link icon" onclick="searchJob()"></i>
			</div>
			<div id="cmdAddJob" class="ui mini green labeled icon button" onclick="addJob()">Tambah<i class="plus square icon"></i></div>
			<table id="grdJob"></table>
		</div>


    <!--/div-->
</div>

<div class="ui small modal" id="mdlDept">
	<div id="title" class="header">
		Departemen
	</div>
	<div class="content small text">
		<form id="frmDept" class="ui mini form">
			<div class="two fields">
				<div class="field">
					<label>Kode</label>
					<input name="PFX" placeholder="Kode">
				</div>
				<div class="field">
					<label>Kode Dokumen</label>
					<input name="CODE" placeholder="Kode Dokumen">
				</div>
			</div>
			<div class="field">
				<label>Nama</label>
				<input name="NAME" placeholder="Nama">
			</div>
			<input name="ID" type="hidden">
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo outline icon"></i></div>
				<div id="cmdDelete" class="ui mini negative labeled icon button">Hapus<i class="trash icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>

<div class="ui small modal" id="mdlGrade">
	<div id="title" class="header">
		Level
	</div>
	<div class="content small text">
		<form id="frmGrade" class="ui mini form">
		<div class="field">
				<label>Kode</label>
				<input name="CODE" placeholder="Kode">
			</div>
			<div class="field">
				<label>Nama</label>
				<input name="NAME" placeholder="Nama">
			</div>
			<input name="ID" type="hidden">
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo outline icon"></i></div>
				<div id="cmdDelete" class="ui mini negative labeled icon button">Hapus<i class="trash icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>

<div class="ui small modal" id="mdlJob">
	<div id="title" class="header">
		Jabatan
	</div>
	<div class="content small text">
		<form id="frmJob" class="ui mini form">
			<div class="two fields">
				<div class="field">
					<label>Kode</label>
					<input name="CODE" placeholder="Kode">
				</div>
				<div class="field">
					<label>Jabatan</label>
					<input name="TITLE" placeholder="Jabatan">
				</div>
			</div>
			<input name="ID" type="hidden">
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo outline icon"></i></div>
				<div id="cmdDelete" class="ui mini negative labeled icon button">Hapus<i class="trash icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>