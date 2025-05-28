<script src="<?php echo base_url('assets/chartjs/dist/Chart.min.js')?>"></script>
<script src="<?php echo base_url('assets/chartjs/plugins/chartjs-plugin-labels.js')?>"></script>
<script src="<?php echo base_url('assets/js/dashboard.js')?>"></script>

<div class="ui left aligned segment" style="margin-top:50px; min-height:500px">	
    <div class="ui grid">
        <div class="eight wide column">
            <div class="ui label">Komposisi Pegawai</div>
            <div id="canvas-holder" style="width:100%;font-family:Calibri;font-size:8pt">
                <canvas id="cvsDept" style="font-family:Calibri;font-size:8pt"></canvas>
            </div>
        </div>
        <div class="eight wide column">
            <div class="ui label" style="margin:10px">Pertumbuhan Jumlah Pegawai</div>
            <div id="canvas-holder" style="width:100%;font-family:Calibri;font-size:8pt">
                <canvas id="cvsGrowth" style="font-family:Calibri;font-size:8pt"></canvas>
            </div>
        </div>
    </div>
    <div class="ui grid">
        <div class="eight wide column">
        
        <div class="ui label">5 Pegawai Terbaru:</div>
        <table id="grdNewEmployee" class="ui selectable celled very compact table" style="font-size:8pt">
			<thead>
				<tr>
					<th style="width:10%">N I P</th>
					<th>Nama</th>
                    <th>Bergabung</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>

        </div>
        <div class="eight wide column">
        
        <div class="ui label">5 Pegawai Usia Pensiun:</div>
        <table id="grdExpiryEmployee" class="ui selectable celled very compact table" style="font-size:8pt">
			<thead>
				<tr>
					<th style="width:10%">N I P</th>
					<th>Nama</th>
                    <th>Bergabung</th>
                    <th>Usia</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>

        </div>
    </div>
</div>