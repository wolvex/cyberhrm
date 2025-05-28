<script src="<?php echo base_url('assets/js/menu.js')?>"></script>

<div class="ui fixed vertical accordion menu" style="margin-top:50px;min-height:550px">
    <div class="ui container">
		<a href="dashboard" class="header item">
			<img class="logo" src="<?php echo base_url('assets/images/kapsulindo.png')?>">
		</a>
				
		<div id="mnuAdmin" class="item">
			<a class="title">
				<i class="dropdown icon"></i>
				ADMINISTRASI
			</a>
			<div class="content">
				<div class="ui secondary vertical menu" style="width:180px">
					<?php 
					if (isGranted("company", "get"))
						echo '<a id="mnuCompany" class="item" href="'.site_url('company').'">Profil Perusahaan</a>';
					if (isGranted("structure", "get"))
						echo '<a id="mnuStructure" class="item" href="'.site_url('structure').'">Struktur Perusahaan</a>';
					if (isGranted("report", "get"))
						echo '<a id="mnuReport" class="item" href="'.site_url('report').'">Laporan</a>';
					
					echo '<a id="mnuUsers" class="item" href="'.site_url('users').'">Pengguna</a>';
					?>
				</div>
			</div>
		</div>
		<div id="mnuEmployee" class="item">
			<a class="active title">
				<i class="dropdown icon"></i>
				PERSONALIA
			</a>
			<div class="active content">
				<div class="ui secondary vertical menu" style="width:180px">
					<?php
					if (isGranted("workhour", "get"))
						echo '<a id="mnuWorkhour" class="item" href="'.site_url('workhour').'">Jam Kerja</a>';
					if (isGranted("holiday", "get"))
						echo '<a id="mnuHoliday" class="item" href="'.site_url('holiday').'">Hari Libur</a>';
					if (isGranted("employees", "get"))
						echo '<a id="mnuEmployees" class="item" href="'.site_url('employees').'">Pegawai</a>';
					if (isGranted("timesheet", "get"))
						echo '<a id="mnuTimesheet" class="item" href="'.site_url('timesheet').'">Jadwal Kerja</a>';
					if (isGranted("absent", "get"))
						echo '<a id="mnuAbsent" class="item" href="'.site_url('absent').'">Absensi Kehadiran</a>';
					if (isGranted("onleave", "get"))
						echo '<a id="mnuOnleave" class="item" href="'.site_url('onleave').'">Ijin dan Cuti</a>';
					if (isGranted("overtime", "get"))
						echo '<a id="mnuOvertime" class="item" href="'.site_url('overtime').'">Lembur</a>';
					?>
				</div>
			</div>
		</div>
		<div id="mnuPayroll" class="item">
			<a class="title">
				<i class="dropdown icon"></i>
				PENGGAJIAN
			</a>
			<div class="content">
				<div class="ui secondary vertical menu" style="width:180px">
					<?php
					if (isGranted("schemas", "get"))
						echo '<a id="mnuSchema" class="item" href="'.site_url('schemas').'">Skema Tunjangan</a>';
					if (isGranted("adjustment", "get"))
						echo '<a id="mnuAdjustment" class="item" href="'.site_url('adjustment').'">Potongan Pegawai</a>';
					if (isGranted("reimburse", "get"))
						echo '<a id="mnuReimburse" class="item" href="'.site_url('reimburse').'">Reimburse Pegawai</a>';
					if (isGranted("wages", "get"))
						echo '<a id="mnuWages"  class="item" href="'.site_url('wages').'">Gaji Pegawai</a>';
					if (isGranted("paydays", "get"))
						echo '<a id="mnuPayday" class="item" href="'.site_url('paydays').'">Proses Penggajian</a>';
					?>
				</div>
			</div>
		</div>
    </div>
</div>