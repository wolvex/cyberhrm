<script src="<?php echo base_url('assets/js/onleave.js')?>"></script>

<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/backpack.png')?>">
			<div class="content">
				Ijin & Cuti
			<div class="sub header">Permintaan ijin dan cuti pegawai</div>
		  </div>
		</h3>

		<div class="ui icon mini input" style="padding-bottom:5px">
			<div class="ui calendar" id="month_at">
				<div class="ui input left icon">
					<i class="calendar icon"></i>
					<input name="month_at" placeholder="Periode" type="text">
				</div>
			</div>
		</div>
		<div class="ui icon mini input">
			<div class="ui icon input">
				<input id="txtFind" class="prompt" placeholder="Cari text" type="text" onchange="search()">
				<i class="search link icon" onclick="search()"></i>
			</div>
		</div>
		<div id="cmdExport" class="ui mini labeled icon button">Export<i class="download icon"></i></div>
		<div id="cmdAdd" class="ui mini green labeled icon button">Ijin/Cuti Baru<i class="plus square icon"></i></div>
		<table id="grdOnleave"></table>
    <!--/div-->
</div>

<div class="ui small modal" id="mdlOnleave">
	<div id="title" class="header">
		Permintaan Ijin/Cuti
	</div>
	<div class="content small text">
		<form id="frmOnleave" class="ui mini form">
			<input name="ID" type="hidden">			
			<div class="two fields">
				<div class="field">
					<label>Pegawai</label>
					<div id="findEmp" class="ui fluid dropdown search">
						<div class="ui left icon input">
							<input class="prompt" name="EMP_NAME" placeholder="Pegawai">
							<input type="hidden" name="EMP_ID">
							<i class="search icon"></i>
						</div>
						<div class="results flowing scrolling menu"></div>
					</div>
				</div>
				<div class="field">
					<label>Jenis Ijin/Cuti</label>
					<select id="cmbAbsence" class="ui dropdown" name="ABSENCE_ID">
					</select>
				</div>
			</div>
			<div class="three fields">				
				<div class="field">
					<label>Mulai</label>
					<div class="ui calendar" id="started_at">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input name="STARTED_AT" placeholder="Tanggal Mulai" type="text">
					</div>
					</div>
				</div>
				<div class="field">
					<label>Sampai Dengan</label>
					<div class="ui calendar" id="ended_at">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input name="ENDED_AT" placeholder="Sampai Dengan" type="text">
					</div>
					</div>
				</div>
				<div class="field">
					<label>Durasi (Hari)</label>
					<input name="QUOTA_TAKEN" readonly value="0">
				</div>
				<div id="QUOTA_AVAIL" class="field">
					<label>Quota Tersedia (Hari)</label>
					<input name="QUOTA_AVAIL" readonly value="0">
				</div>
			</div>
			<div class="field">
				<label>Keterangan</label>
				<input name="REASON" placeholder="Keterangan, keperluan, alasan">
			</div>
			<input name="STATUS" type="hidden" value="A">
			<input name="QUOTA_LEFT" type="hidden" value="0">
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
				<div id="cmdDelete" class="ui mini negative labeled icon button">Hapus<i class="trash icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>