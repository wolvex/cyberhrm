<script src="<?php echo base_url('assets/js/carier.js')?>"></script>
<div class="ui small modal" id="mdlCarier">
	<div id="title" class="header">
		Penugasan
	</div>
	<div class="content small text">
		<form id="frmCarier" class="ui mini form">
			<div class="fields">
				<div class="two wide field">
					<label>ID</label>
					<input name="ID" readonly>
				</div>
				<div class="seven wide field">
					<label>Jabatan</label>
					<select id="cmbJob" class="ui search dropdown" name="JOB_ID">
					</select>				
				</div>
				<div class="seven wide field">
					<label>Departemen</label>
					<select id="cmbDept" class="ui search dropdown" name="DEPT_ID">					
					</select>				
				</div>
			</div>
			<div class="three fields">
				<div class="field">
					<label>Grade</label>
					<select id="cmbGrade" class="ui fluid dropdown" name="GRADE_ID">					
					</select>
				</div>			
				<div class="field">
					<label>Status</label>
					<select id="cmbStatus" class="ui fluid dropdown" name="EMP_STATUS">
						<option value="P" selected>Permanen</option>
						<option value="K">Kontrak</option>
						<option value="H">Harian</option>
					</select>
				</div>
				<div class="field">
					<label>Tanggal Efektif</label>
					<div class="ui calendar" id="effective_at" data-type="date">
						<div class="ui input left icon">
							<i class="calendar icon"></i>
							<input name="EFFECTIVE_AT" placeholder="Tanggal efektif" type="text" data-tag="date">
						</div>
					</div>
				</div>
				<!--div class="field">
					<label>Tanggal Berakhir</label>
					<div class="ui calendar" id="expire_at">
						<div class="ui input left icon">
							<i class="calendar icon"></i>
							<input name="EXPIRE_AT" placeholder="Tanggal berakhir" type="text" data-tag="date">
						</div>
					</div>
				</div-->
			</div>
			<div class="two fields">
				<div class="field">
					<label>Supervisor</label>
					<div id="findSpv" class="ui fluid dropdown search">
						<div class="ui left icon input">
							<input class="prompt" name="SPV_NAME" placeholder="Supervisor">
							<input type="hidden" name="APPROVER1">
							<i class="search icon"></i>
						</div>
						<div class="results flowing scrolling menu"></div>
					</div>
				</div>
				<div class="field">
					<label>Manager</label>
					<div id="findMgr" class="ui fluid dropdown search">
						<div class="ui left icon input">
							<input class="prompt" name="MGR_NAME" placeholder="Manager">
							<input type="hidden" name="APPROVER2">
							<i class="search icon"></i>
						</div>
						<div class="results flowing scrolling menu"></div>
					</div>
				</div>
			</div>
			<input name="EMP_ID" type="hidden">
			<div class="actions action-box">
				<div id="cmdCancel" class="ui mini cancel labeled icon button">Batal<i class="undo icon"></i></div>
				<div id="cmdDelete" class="ui mini negative labeled icon button">Hapus<i class="trash icon"></i></div>
				<div id="cmdSave" class="ui mini positive labeled icon button">Simpan<i class="checkmark icon"></i></div>
			</div>
		</form>
	</div>
</div>