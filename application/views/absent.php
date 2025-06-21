<script src="<?php echo base_url('assets/js/absent.js')?>"></script>

<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/workhour.png')?>">
			<div class="content">
				Absensi Kehadiran
			<div class="sub header">Data absensi dan kehadiran pegawai</div>
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
				<input id="txtFind" class="prompt" placeholder="Cari text" type="text" onchange="searchAbsent()">
				<i class="search link icon" onclick="searchAbsent()"></i>
			</div>
		</div>

		<div class="ui top attached tabular menu">
			<a class="active item" data-tab="absent">Kehadiran</a>
			<a class="item" data-tab="punchcard">Absensi</a>
		</div>

		<div class="ui bottom attached active tab segment" data-tab="absent">
			<div id="exportAbsent" class="ui mini labeled icon button" style="margin-bottom:5px">Export<i class="download icon"></i></div>
			<div id="cmdAdd" class="ui mini green labeled icon button" style="margin-bottom:5px">Absen Manual<i class="plus square icon"></i></div>
			<table id="grdAbsent"></table>
		</div>

		<div class="ui bottom attached tab segment" data-tab="punchcard">
			<div id="exportPunch" class="ui mini labeled icon button" style="margin-bottom:5px">Export<i class="download icon"></i></div>
			<table id="grdPunch"></table>
		</div>

    <!--/div-->
</div>

<div class="ui small modal" id="mdlAbsent">
	<div id="title" class="header">
		Absen Manual
	</div>
	<div class="content small text">
		<form id="frmAbsent" class="ui mini form">
			<input name="ID" type="hidden">			
			<div class="fields">
				<div class="six wide field">
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
				<div class="ten wide field">
					<label>Keterangan</label>
					<input name="REMARKS" placeholder="Keterangan, alasan">
				</div>
			</div>
			<div class="two fields">				
				<div class="field">
					<label>Jam Masuk</label>
					<div class="ui calendar" id="clock_in" data-tag="nodraw">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input name="CLOCK_IN" placeholder="Jam Masuk" type="text">
					</div>
					</div>
				</div>
				<div class="field">
					<label>Jam Pulang</label>
					<div class="ui calendar" id="clock_out" data-tag="nodraw">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input name="CLOCK_OUT" placeholder="Jam Pulang" type="text">
					</div>
					</div>
				</div>
			</div>			
			<input name="STATUS" type="hidden" value="A">
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
				<div id="cmdDelete" class="ui mini negative labeled icon button">Hapus<i class="trash icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>