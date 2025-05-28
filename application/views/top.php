<script>
	function logout() {
		$.post('<?php echo base_url('login/logout') ?>',
			function(data, status) {
				var res = JSON.parse(data);
				window.location = '<?php echo base_url('login') ?>';
			}
		);
	}
</script>

<!-- Sticky/fixed panel on top of page -->
<div class="ui fixed menu" style="background-color:#0D0740">
    <div class="ui container">
      	<a href="#" class="header item" style="color:white;">
		  	<img class="logo" src="<?php echo base_url('assets/images/logo.png')?>">
        	CyberHRM (Human Resource Management)
      	</a>
		<div class="ui simple right item">
			<?php
				if (isset($this->session->userdata['profile'])) {
					echo "<div style='padding-right:50px;color:white;'><i class='user circle inverted icon'></i>".$_SESSION['profile']['user_name']."</div>";
					echo "<div class='ui negative button' onclick='logout()'>Logout</div>";
				} else {

				}
			?>
			
		</div>
    </div>
</div>