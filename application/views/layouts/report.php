<!DOCTYPE html>
<html>
<head>
  <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/logo.png')?>">
  
  <script>
      var base_url = '<?php echo base_url(); ?>';
  </script>

  <?php require(ROOT_PATH.'/views/styles.php'); ?>  
  <?php require(ROOT_PATH.'/views/scripts.php'); ?>
</head>
<body style="background-color:white;margin-left:0px;margin-top:15px">

<?php require(ROOT_PATH.'/views/top.php'); ?>

<?php $this->load->view($view); ?>

</body>

</html>