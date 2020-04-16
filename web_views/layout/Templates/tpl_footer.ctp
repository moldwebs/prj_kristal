<script type="text/javascript" src="/plugins/fancybox/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/plugins/fancybox/jquery.fancybox.css" />
<script type="text/javascript" src="/plugins/jquery-migrate.min.js"></script>
<script type="text/javascript" src="/plugins/jquery-ui/ui/minified/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script type="text/javascript" src="/plugins/jquery-ui/ui/minified/jquery.ui.autocomplete.min.js"></script>
<script type="text/javascript" src="/system/js"></script>
<script>
    DocReadyFunction();
</script>
<?php echo $cfg['base']['counter_script']?>
<?php echo $this->Session->flash(); ?>
<?php echo $this->element('sql_dump'); ?>