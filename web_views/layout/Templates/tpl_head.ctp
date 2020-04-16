<?php echo $this->Html->charset(); ?>
<title><?php echo $title_for_layout; ?></title>
<meta name="title" content="<?php echo $meta_title_for_layout; ?>" />
<meta name="description" content="<?php echo $meta_desc_for_layout; ?>" />
<meta name="keywords" content="<?php echo $meta_keyw_for_layout; ?>" />
<link rel="shortcut icon" href="/favicon.ico" />
<?php if(!empty($item['ObjItemList']['title'])):?>
    <meta property="og:title" content="<?php e($item['ObjItemList']['title'])?>"/>
    <meta property="og:image" content="<?php e("http://" . $_SERVER['HTTP_HOST'])?>/getimages/0x0/large/<?php e($item['ObjOptAttachDef']['attach'])?>"/>
    <meta property="og:description" content="<?php eth(!empty($item['ObjItemList']['short_body']) ? nl2br($item['ObjItemList']['short_body']) : $item['ObjItemList']['body'], 100)?>"/>
<?php endif;?>
<base href="<?php e($themepath)?>" />
<script>
DocReadyFunction = function() {
    console.log('DocReadyFunctionInit');
}
</script>
<?php echo $cfg['base']['head_meta']?>