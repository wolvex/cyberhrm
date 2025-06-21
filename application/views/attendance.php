<script src="<?php echo base_url('assets/js/attendance.js')?>"></script>

<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/punch_clock.png')?>">
			<div class="content">
				<?php echo $NAME ?>
			<div class="sub header"><?php echo 'NIP: '.$CODE ?></div>
		  </div>
		</h3>

		<input type="hidden" id="EMP_ID" value="<?php echo $ID ?>">

		<div class="ui icon mini input" style="padding-bottom:5px">
			<div class="ui calendar" id="month_at">
				<div class="ui input left icon">
					<i class="calendar icon"></i>
					<input name="month_at" placeholder="Periode" type="text" value="<?php echo $PERIOD ?>">
				</div>
			</div>
		</div>

		<div class="ui top attached tabular menu">
			<a class="active item" data-tab="timesheet">Jadwal Kerja</a>
			<a class="item" data-tab="punchcard">Data Absen</a>
		</div>

		<div class="ui bottom attached active tab segment" data-tab="timesheet">
			<div id="cmdAdd" class="ui mini green labeled icon button" onclick="addTimesheet()" style="margin-bottom:5px">Jadwal Baru<i class="plus square icon"></i></div>
			<table id="grdTimesheet"></table>
		</div>

		<div class="ui bottom attached tab segment" data-tab="punchcard">
			<table id="grdPunch"></table>
		</div>
			
    <!--/div-->
</div>

<div class="ui small modal" id="mdlTimesheet">
	<div id="title" class="header">
		Jadwal Kerja
	</div>
	<div class="content small text">
		<form id="frmTimesheet" class="ui mini form">
			<div class="two fields">
				<div class="field">
					<label>Tanggal</label>
					<div class="ui calendar" id="work_at" data-type="date">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input name="WORK_AT" placeholder="Tanggal" type="text">
					</div>
					</div>
				</div>
				<div class="field">
					<label>Kode Shift</label>
					<select id="cmbShift" class="ui dropdown" name="SHIFT_ID">
					</select>
				</div>				
			</div>
			<div class="fields">
				<div class="five wide field">
					<label>Absen Masuk</label>
					<div class="ui calendar" id="clock_in" data-type="datetime">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input name="CLOCK_IN" placeholder="Jam Masuk" disabled type="text">
					</div>
					</div>
				</div>
				<div class="five wide field">
					<label>Absen Pulang</label>
					<div class="ui calendar" id="clock_out" data-type="datetime">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input name="CLOCK_OUT" placeholder="Jam Pulang" disabled type="text">
					</div>
					</div>
				</div>
				<div class="three wide field">
					<label>Telat Datang (menit)</label>
					<input name="LATE_MINUTE" disabled data-tag="int">
				</div>
				<div class="three wide field">
					<label>Cepat Pulang (menit)</label>
					<input name="EARLY_MINUTE" disabled data-tag="int">
				</div>
			</div>
			<div class="three fields">				
				<div class="field">
					<label>Lama Kerja (menit)</label>
					<input name="WORK_MINUTE" disabled data-tag="int">
				</div>
				<div class="field">
					<label>Nomor Cuti/Ijin</label>
					<input name="ONLEAVE_ID" disabled data-tag="int">
				</div>
				<div class="field">
					<label>Nomor Lembur</label>
					<input name="OVERTIME_ID" disabled data-tag="int">
				</div>
			</div>
			<input name="ID" type="hidden">
			<input name="EMP_ID" type="hidden">
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
				<div id="cmdDelete" class="ui mini negative labeled icon button">Hapus<i class="trash icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="save icon"></i></div>
			</div>
		</form>
	</div>
</div>