<script src="<?php echo base_url('assets/js/holiday.js')?>"></script>

<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/travelbag.png')?>">
			<div class="content">
				Hari Libur
			<div class="sub header">Hari libur dan cuti bersama nasional</div>
		  </div>
		</h3>

		<div class="ui icon mini input" style="padding-bottom:5px">
			<div class="ui calendar" id="year_at">
				<div class="ui input left icon">
					<i class="calendar icon"></i>
					<input name="year_at" placeholder="Tahun" type="text">
				</div>
			</div>
		</div>
		<div id="cmdAdd" class="ui mini green labeled icon button" onclick="addHoliday()">
			Hari Libur Baru<i class="plus square icon"></i>
		</div>
		<table id="grdHoliday"></table>
    <!--/div-->
</div>

<div class="ui small modal" id="mdlHoliday">
	<div id="title" class="header">
		Hari Libur
	</div>
	<div class="content small text">
		<form id="frmHoliday" class="ui mini form">
			<div class="two fields">
				<div class="field">
					<label>Deskripsi</label>
					<input name="DESCRIPTION" placeholder="Deskripsi">
				</div>
				<div class="field">
					<label>Jenis</label>
					<select id="cmbType" class="ui dropdown" name="TYPE">
						<option value="0">Normal</option>
						<option value="1">Istimewa</option>
					</select>
				</div>
			</div>
			<div class="two fields">
				<div class="field">
					<label>Tanggal</label>
					<div class="ui calendar" id="holiday_at" data-type="date">
						<div class="ui input left icon">
							<i class="calendar icon"></i>
							<input name="HOLIDAY_AT" placeholder="Tanggal" type="text">
						</div>
					</div>
				</div>
				<div class="field">
					<label>Durasi</label>
					<select id="cmbDay" class="ui dropdown" name="FULL_DAY">
						<option value="0">Setengah hari</option>
						<option value="1">Sehari penuh</option>
					</select>
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