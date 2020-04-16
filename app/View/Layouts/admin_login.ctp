<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php ___e('Login')?>
	</title>
    
    <link rel="stylesheet" type="text/css" href="/css/reset.css" />
    <script type="text/javascript" src="/plugins/jquery-ui/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="/plugins/jquery-ui/jquery-ui-1.9.2.custom.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/plugins/jquery-ui/custom-theme/jquery-ui-1.10.1.custom.css" />
    
    <link rel="stylesheet" type="text/css" href="/get_css_ico.php" />
    <link rel="stylesheet" type="text/css" href="/css/960-fluid.css" />
    <link rel="stylesheet" type="text/css" href="/css/buttons.css" />
    <link rel="stylesheet" type="text/css" href="/css/sys.css" />
    <link rel="stylesheet" type="text/css" href="/css/admin.css" />

    <script type="text/javascript" src="/admin/system/js"></script>

	<?php
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
    <div class="container_16">
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->fetch('content'); ?>
    </div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
