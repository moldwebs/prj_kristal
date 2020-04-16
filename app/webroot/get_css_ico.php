<?php header('Content-Type: text/css');?>
<?php foreach(scandir('ico') as $file):?>
<?php if($file == '.' || $file == '..') continue;?>
<?php $path_parts = pathinfo($file);?>
<?php if(strpos($path_parts['filename'], '_off')):?>
.ico_<?php echo str_replace('_off', '', $path_parts['filename'])?>.off{
    background: url('/ico/<?php echo $file?>') no-repeat !important;
}
<?php else:?>
.ico_<?php echo $path_parts['filename']?>{
    background: url('/ico/<?php echo $file?>') no-repeat !important;
}
<?php endif;?>
<?php endforeach;?>