
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $this->template->title?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  
  <?php echo $this->template->meta; ?>

  <?php echo $this->template->css_setting ;?>
  <?php echo $this->template->stylesheet ;?>

  <script src="<?php echo base_url() . 'public/templates/common/jquery/jquery.min.js' ?>" type="text/javascript"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- ============= Header ============= -->
  <?php echo $this->template->header->view('layout/header'); ?>

  <!-- ============= Sidebar ============= -->
  <?php echo $this->template->sidebar->view('layout/sidebar'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <?php echo $this->template->content; ?>
  </div>

  <!-- ============= Footer ============= -->
  <?php echo $this->template->footer->view('layout/footer'); ?>
</div>
<!-- ./wrapper -->
</body>
</html>
