<script src="<?php echo base_url('assets/js/simpleAjaxUploader.js')?>"></script>
<script src="<?php echo base_url('assets/js/empdoc.js')?>"></script>
<div class="ui small modal" id="mdlDoc">
	<div id="ttlDoc" class="header">
		Dokumen
	</div>
	<div class="content small text">
		<form id="frmDoc" class="ui mini form">
			<div class="field">
				<label>Deskripsi</label>
				<textarea rows="1" id="fileDescription" placeholder="Deskripsi"></textarea>
			</div>
			<div class="field">
				<button id="cmdUpload" name="cmdUpload">Pilih File</button>
			</div>
			<div class="field">
				<div id="progressOuter" class="progress progress-striped active" style="display:none;">
					<div id="progressBar" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 10%">
				</div>
			</div>
			<input name="EMP_ID" type="hidden">
			<input name="ID" type="hidden">
		</form>
	</div>
</div>