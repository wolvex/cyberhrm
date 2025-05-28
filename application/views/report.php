<script src="<?php echo base_url('assets/js/report.js')?>"></script>

<style>
	.hidden {
		display: none;
	}
	.shown {
		display: inline;
	}
</style>

<div class="ui left aligned segment" style="min-height:550px">	
    <!--div class="ui small text left aligned column"-->		
		<h3 class="ui header">
			<img src="<?php echo base_url('assets/images/report.png')?>">
			<div class="content">
				Laporan
			<div class="sub header">Laporan-laporan</div>
		  </div>
		</h3>

		<table id="grdReport" class="ui selectable celled table" style="font-size:8pt">
			<thead>
				<tr>
					<th style="display:none"></th>
					<th style="width:10%">No</th>
					<th>Nama Laporan</th>
				</tr>
			</thead>
			<tbody id="content">
			</tbody>
		</table>
    <!--/div-->
</div>

<div class="ui tiny modal" id="mdlDialog">
	<div id="title" class="header" style="font-size:12pt">
	</div>
	<div class="content small text">
		<form id="frmDialog" class="ui mini form" target="reportViewer" action="report/view" method="POST">
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
				<div id="cmdPreview" class="ui mini positive labeled icon button">Preview<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>