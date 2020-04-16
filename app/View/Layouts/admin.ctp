<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php ___e('Administration')?>
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

    <script type="text/javascript" src="/plugins/ckeditor/ckeditor.js"></script>

    <script type="text/javascript" src="/admin/system/js"></script>

    <!-- cdn for modernizr, if you haven't included it already -->
    <script src="http://cdn.jsdelivr.net/webshim/1.12.4/extras/modernizr-custom.js"></script>
    <!-- polyfiller file to detect and load polyfills -->
    <script src="http://cdn.jsdelivr.net/webshim/1.12.4/polyfiller.js"></script>
    <script>
      //webshims.activeLang('ru');
      webshims.setOptions('waitReady', false);
      webshims.setOptions('forms-ext', {types: 'date', "widgets": {
		"startView": 2,
	}});
      webshims.polyfill('forms forms-ext');
    </script>

	<?php
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
    <div class="container_16">
        <div class="grid_16">
            <?php echo $this->element('/admin/top-menu'); ?>
            <?php echo $this->element('/admin/top-buttons'); ?>
        </div>
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->fetch('content'); ?>
    </div>
    <div style="height: 100px;"></div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
