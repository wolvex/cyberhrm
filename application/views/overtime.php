<script src="<?php echo base_url('assets/js/overtime.js')?>"></script>

<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/overtime.png')?>">
			<div class="content">
				Lembur
			<div class="sub header">Permintaan lembur pegawai</div>
		  </div>
		</h3>
		<div class="ui mini form">
			<div class="fields">
				<!--div class="three wide field">
					<select id="cmbSelect" class="ui dropdown" placeholder="Jenis Komponen">
					</select>
				</div-->
				<div class="three wide field">		
					<div class="ui calendar" id="month_at">
						<div class="ui input left icon">
							<i class="calendar icon"></i>
							<input name="month_at" placeholder="Periode" type="text">
						</div>
					</div>
				</div>
				<div class="three wide field">
					<div class="ui icon input">
						<div class="ui icon input">
							<input id="txtFind" class="prompt" placeholder="Cari text" type="text" onchange="search()">
							<i class="search link icon" onclick="search()"></i>
						</div>
					</div>
				</div>
				<div class="ten wide field" style="align: left">
					<div id="cmdExport" class="ui mini labeled icon button">Export<i class="download icon"></i></div>
					<div id="cmdAdd" class="ui mini green labeled icon button">Lembur Baru<i class="plus square icon"></i></div>
				</div>
			</div>
		</div>
		<table id="grdOvertime"></table>
    <!--/div-->
</div>

<div class="ui small modal" id="mdlOvertime">
	<div id="title" class="header">
		Lembur pegawai
	</div>
	<div class="content small text">
		<form id="frmOvertime" class="ui mini form">
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
					<label>Keperluan Lembur</label>
					<select id="cmbCategory" class="ui dropdown" name="OVERTIME_CAT">
					</select>
				</div>
			</div>
			<div class="fields">
				<div class="five wide field">
					<label>Jam Masuk</label>
					<div class="ui calendar" id="start_clock" data-tag="nodraw">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input name="START_CLOCK" placeholder="Jam Masuk" type="text">
					</div>
					</div>
				</div>
				<div class="five wide field">
					<label>Jam Pulang</label>
					<div class="ui calendar" id="end_clock" data-tag="nodraw">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input name="END_CLOCK" placeholder="Jam Pulang" type="text">
					</div>
					</div>
				</div>
				<div class="two wide field">
					<label>Durasi (jam)</label>
					<input name="DURATION" value="0" data-tag="int">
				</div>
				<div class="four wide field">
					<label>Jenis Lembur</label>
					<select id="cmbOvertime" class="ui dropdown" name="ABSENCE_ID">
					</select>
				</div>
			</div>
			<div class="field">
				<label>Catatan</label>
				<textarea name="REMARKS" rows="1"></textarea>
			</div>
			<div class="fields">
				<div class="twelve wide field">
					<label>Absen Aktual</label>
					<select id="cmbAbsent" class="ui dropdown" name="ABSENT_ID"></select>
				</div>
				<div class="four wide field">
					<label>Durasi Disetujui (jam)</label>
					<input name="WORK_HOUR">
				</div>
			</div>
			<input name="STATUS" type="hidden" value="A">
			<input name="ID" type="hidden">

			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
				<div id="cmdDelete" class="ui mini negative labeled icon button">Hapus<i class="trash icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>