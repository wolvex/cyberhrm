<!DOCTYPE html>
<html>
<head>
  <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/logo4.png')?>">
  
  <script>
      var base_url = '<?php echo base_url(); ?>';
  </script>

  <?php require(ROOT_PATH.'/views/styles.php'); ?>  
  <?php require(ROOT_PATH.'/views/scripts.php'); ?>
</head>
<body style="background-color:#eff4fc">

<?php require(ROOT_PATH.'/views/top.php'); ?>
<?php require(ROOT_PATH.'/views/menu.php'); ?>

<?php $this->load->view($view); ?>

</body>

</html>