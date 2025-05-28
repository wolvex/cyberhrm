<script src="<?php echo base_url('assets/js/simpleAjaxUploader.js')?>"></script>
<script src="<?php echo base_url('assets/js/timesheet.js')?>"></script>

<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/punch_clock.png')?>">
			<div class="content">
				Kehadiran
			<div class="sub header">Jadwal kerja dan absensi pegawai</div>
		  </div>
		</h3>

		<div class="ui icon mini input" style="padding-bottom:5px">
			<div class="ui calendar" id="month_at">
				<div class="ui input left icon">
					<i class="calendar icon"></i>
					<input name="month_at" placeholder="Periode" type="text">
				</div>
			</div>
		</div><br><br>
		<div class="ui icon mini input" style="padding-bottom:5px">
			<div class="ui icon input" style="padding-bottom:5px">
				<input id="txtFind" class="prompt" placeholder="Cari text" type="text" onchange="searchTimesheet()">
				<i class="search link icon" onclick="searchTimesheet()"></i>
			</div>
		</div>
		<div id="cmdAdd" class="ui mini green labeled icon button" onclick="uploadTimesheet()">Upload Jadwal Kerja<i class="plus square icon"></i></div>
		<table id="grdTimesheet"></table>
    <!--/div-->
</div>

<div class="ui small modal" id="mdlTimesheet">
	<div id="ttlTimesheet" class="header">
		Hari Libur
	</div>
	<div class="content small text">
		<form id="frmTimesheet" class="ui mini form">
			<div class="two fields">
				<div class="field">
					<label>Pegawai</label>
					<select id="cmbEmployee" class="ui dropdown" name="EMPLOYEE_ID">
					</select>
				</div>				
				<div class="field">
					<label>Tanggal</label>
					<div class="ui calendar" id="work_at">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input name="WORK_AT" placeholder="Tanggal" type="text">
					</div>
					</div>
				</div>
			</div>
			<div class="three fields">
				<div class="field">
					<label>Shift</label>
					<select id="cmbShift" class="ui dropdown" name="SHIFT_ID">
					</select>
				</div>
				<div class="field">
					<label>Jam Masuk</label>
					<input name="START_TIME" readonly placeholder="Jam Masuk">
				</div>
				<div class="field">
					<label>Jam Pulang</label>
					<input name="END_TIME" readonly placeholder="Jam Pulang">
				</div>
			</div>
			<div class="four fields">
				<div class="field">
					<label>Absen Masuk</label>
					<input name="CLOCK_IN" readonly placeholder="Absen Masuk">
				</div>
				<div class="field">
					<label>Absen Pulang</label>
					<input name="CLOCK_OUT" readonly placeholder="Absen Pulang">
				</div>
				<div class="field">
					<label>Telat (menit)</label>
					<input name="LATE_MINUTE" readonly placeholder="Telat (menit)">
				</div>
				<div class="field">
					<label>Kode Absensi</label>
					<input name="ABSENCE_CODE" readonly placeholder="Kode Absensi">
				</div>
			</div>
			<input name="ID" type="hidden">
		</form>
	</div>
	<div class="actions">
		<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
		<div id="cmdDelete" class="ui mini negative labeled icon button">Hapus<i class="trash icon"></i></div>
		<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
	</div>
</div>

<div class="ui small modal" id="mdlUpload">
	<div id="ttlUpload" class="header">
		Dokumen
	</div>
	<div class="content small text">
		<form id="frmUpload" class="ui mini form">
			<div class="field">
				File yang diupload harus dalam format Tab-delimited text file.<br>
				1. Pada menu File Excel, pilih 'Save As'<br>
				2. Pilih format 'Tab Delimited (*.txt)'<br>
				Setelah upload berhasil, <strong>tunggu beberapa menit untuk melihat hasilnya</strong>.
			</div>
			<div class="field">
				<button id="cmdUpload" name="cmdUpload">Pilih File</button>
			</div>
			<div class="field">
				<div id="progressOuter" class="progress progress-striped active" style="display:none;">
					<div id="progressBar" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 10%">
				</div>
			</div>
		</form>
	</div>
</div>