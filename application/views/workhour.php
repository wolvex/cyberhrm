<script src="<?php echo base_url('assets/js/workhour.js')?>"></script>
<script src="<?php echo base_url('assets/js/shift.js')?>"></script>
<script src="<?php echo base_url('assets/js/absence.js')?>"></script>
<script src="<?php echo base_url('assets/js/ovrcat.js')?>"></script>

<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/workhour.png')?>">
			<div class="content">
				Jam Kerja
			<div class="sub header">Shift, ijin, cuti, dan lembur</div>
		  </div>
		</h3>

		<div class="ui top attached tabular menu">
			<a class="active item" data-tab="shift">Shift Kerja</a>
			<a class="item" data-tab="absence">Absensi</a>
			<a class="item" data-tab="overtime">Lembur</a>
		</div>

		<div class="ui bottom attached active tab segment" data-tab="shift">
			<div class="ui icon mini input" style="padding-bottom:5px">
				<div id="cmdAddShift" class="ui mini green labeled icon button" onclick="addShift()">Shift Baru<i class="plus square icon"></i></div>
			</div>
			<table id="grdShift"></table>
		</div>

		<div class="ui bottom attached tab segment" data-tab="absence">
			<div class="ui icon mini input" style="padding-bottom:5px">
				<div id="cmdAddAbsence" class="ui mini green labeled icon button" onclick="addAbsence()">Absensi Baru<i class="plus square icon"></i></div>
			</div>
			<table id="grdAbsence"></table>
		</div>

		<div class="ui bottom attached tab segment" data-tab="overtime">
			<div class="ui icon mini input" style="padding-bottom:5px">
				<input id="txtFind" class="prompt" placeholder="Cari text" type="text" onchange="searchOvr()">
				<i class="search link icon" onclick="searchOvr()"></i>
			</div>
			<div id="cmdAddOvr" class="ui mini green labeled icon button" onclick="addOvr()">Lembur Baru<i class="plus square icon"></i></div>
			<table id="grdOvr"></table>
		</div>


    <!--/div-->
</div>

<div class="ui small modal" id="mdlShift">
	<div id="title" class="header">
		Shift Kerja
	</div>
	<div class="content small text">
		<form id="frmShift" class="ui mini form">
			<div class="two fields">
				<div class="field">
					<label>Kode</label>
					<input name="CODE" placeholder="Kode">
				</div>
				<div class="field">
					<label>Deskripsi</label>
					<input name="DESCRIPTION" placeholder="Deskripsi">
				</div>
			</div>
			<div class="three fields">
				<div class="field">
					<label>Jam Mulai</label>
					<div class="ui calendar" id="start_time" data-type="time">
					<div class="ui input left icon">
						<i class="time icon"></i>
						<input name="START_TIME" placeholder="Jam Mulai" type="text">
					</div>
					</div>
				</div>
				<div class="field">
					<label>Jam Akhir</label>
					<div class="ui calendar" id="end_time" data-type="time">
					<div class="ui input left icon">
						<i class="time icon"></i>
						<input name="END_TIME" placeholder="Jam Akhir" type="text">
					</div>
					</div>
				</div>
				<div class="field">
					<label>Min. Jam Kerja</label>
					<input name="MIN_WORK_HOUR" placeholder="Min. Jam Kerja" data-tag="int">
				</div>
			</div>
			<input name="ID" type="hidden">
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
				<div id="cmdDelete" class="ui mini negative labeled icon button">Hapus<i class="trash icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>

<div class="ui small modal" id="mdlAbsence">
	<div id="title" class="header">
		Kode Absensi
	</div>
	<div class="content small text">
		<form id="frmAbsence" class="ui mini form">
			<div class="two fields">
				<div class="field">
					<label>Kode</label>
					<input name="CODE" placeholder="Kode">
				</div>
				<div class="field">
					<label>Deskripsi</label>
					<input name="DESCRIPTION" placeholder="Nama">
				</div>
			</div>
			<div class="field">
				<label>Jenis</label>
				<select id="cmbAbsence" class="ui dropdown" name="TYPE">
					<option value="0">Ijin/Cuti</option>
					<option value="1">Lembur</option>
				</select>
			</div>
			<input name="ID" type="hidden">
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
				<div id="cmdDelete" class="ui mini negative labeled icon button">Hapus<i class="trash icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>

<div class="ui small modal" id="mdlOvr">
	<div id="title" class="header">
		Divisi
	</div>
	<div class="content small text">
		<form id="frmOvr" class="ui mini form">
			<div class="two fields">
				<div class="field">
					<label>Kode</label>
					<input name="CODE" placeholder="Kode">
				</div>
				<div class="field">
					<label>Deskripsi</label>
					<input name="DESCRIPTION" placeholder="Deskripsi">
				</div>
			</div>
			<div class="field">
				<label>Departemen</label>
				<select id="cmbDept" class="ui dropdown" name="DEPT_ID">
				</select>
			</div>
			<input name="ID" type="hidden">
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
				<div id="cmdDelete" class="ui mini negative labeled icon button">Hapus<i class="trash icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>