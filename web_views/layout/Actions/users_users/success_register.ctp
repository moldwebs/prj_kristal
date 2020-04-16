<?php $this->extend('layout');?>

<script>
    window.location = '<?php e(!empty($redirect) ? $redirect : '/')?>';
</script>