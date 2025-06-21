<script src="<?php echo base_url('assets/js/payday.js')?>"></script>

<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/payroll.png')?>">
			<div class="content">
				<div id="divTitle" class="header">Proses Penggajian</div>
				<div id="divSubtitle" class="sub header">Proses penggajian pegawai</div>
		  </div>
		</h3>

		<div id="divCmd" class="ui mini buttons">
			<div id="cmdProcess" class="ui mini green labeled icon button" onclick="processPayroll()">Proses<i class="checkmark icon"></i></div>
			<div id="cmdDelete" class="ui mini red labeled icon button" onclick="removePayroll()">Hapus<i class="trash icon"></i></div>
			<div id="cmdRefresh" class="ui mini labeled icon button" onclick="location.reload()">Refresh<i class="undo icon"></i></div> 
		</div>
		<div id="divPrint" class="ui mini buttons" style="display: none">
			<div id="cmdPrintSlip" class="ui mini grey labeled icon button" onclick="printSlip()">Cetak Slip<i class="print icon"></i></div>
			<div id="cmdPrintSummary" class="ui mini grey labeled icon button" onclick="printSummary()">Summary<i class="print icon"></i></div> 
			<div id="cmdPrintDetail" class="ui mini grey labeled icon button" onclick="printDetail()">Detail<i class="print icon"></i></div> 
		</div>

		<div class="ui segment">
			<form id="frmPayroll" class="ui mini form">
				<div class="fields">
					<div class="one wide field">
						<label>Proses ID</label>
						<input name="ID" readonly value="<?php echo (isset($ID) ? $ID : '0') ?>">
					</div>				
					<div class="four wide field">
						<label>Proses</label>
						<select id="cmbPayroll" class="ui dropdown" name="CODE" disabled></select>
					</div>
					<div class="three wide field">
						<label>Status</label>
						<select id="cmbStatus" class="ui dropdown" name="STATUS" disabled>
							<option value="Q">Akan Diproses</option>
							<option value="P">Sedang Proses</option>
							<option value="C">Selesai Proses</option>
						</select>
					</div>
					<div class="three wide field">
						<label>Periode</label>
						<div class="ui calendar" id="period" data-type="month">
							<div class="ui input left icon">
								<i class="calendar icon"></i>
								<input name="PERIOD" placeholder="Bulan" type="text" readonly>
							</div>
						</div>
					</div>
					<div class="three wide field">
						<label>Mulai</label>
						<div class="ui calendar" id="start_at">
							<div class="ui input left icon">
								<i class="calendar icon"></i>
								<input name="START_AT" placeholder="Kehadiran Mulai" type="text" readonly>
							</div>
						</div>
					</div>
					<div class="three wide field">
						<label>Sampai Dengan</label>
						<div class="ui calendar" id="end_at">
							<div class="ui input left icon">
								<i class="calendar icon"></i>
								<input name="END_AT" placeholder="Sampai Dengan" type="text" readonly>
							</div>
						</div>
					</div>
				</div>
				<div class="fields">
					<div class="three wide field">
						<label>Tanggal Jatuh Tempo</label>
						<input name="DUE_AT" readonly placeholder="Tanggal Jatuh Tempo">
					</div>
					<div class="three wide field">
						<label>Tanggal Proses</label>
						<input name="CREATED_AT" readonly placeholder="Tanggal Proses">
					</div>
					<div class="two wide field">
						<label>Diproses Oleh</label>
						<input name="CREATED_BY" readonly placeholder="Diproses Oleh">
					</div>
					<div class="three wide field">
						<label>Tanggal Cetak</label>
						<input name="PRINTED_AT" readonly placeholder="Tanggal Cetak">
					</div>
					<div class="two wide field">
						<label>Dicetak Oleh</label>
						<input name="PRINTED_BY" readonly placeholder="Dicetak Oleh">
					</div>
					<div class="three wide field">
						<label>Catatan</label>
						<textarea name="REMARKS" rows="1"></textarea>
					</div>
				</div>
			</form>

			<div class="ui icon mini input" style="padding-bottom:5px">
				<input id="txtFind" class="prompt" placeholder="Cari pegawai" type="text" onchange="searchEmployee()">
				<i class="search link icon" onclick="searchEmployee()"></i>
			</div>
			<table id="grdPayslip"></table>
		</div>		
    <!--/div-->
</div>