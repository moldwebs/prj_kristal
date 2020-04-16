<?php if(!empty($item['ObjOptAttachDef']['file'])):?>
    <?php if($item['ObjOptAttachDef']['location'] == 'video'):?>
        <a target="_blank" class="ico img_preview" preview="http://img.youtube.com/vi/<?php e(rtrim($item['ObjOptAttachDef']['file'], '.video'))?>/default.jpg" href="http://www.youtube.com/watch?v=<?php e(rtrim($item['ObjOptAttachDef']['file'], '.video'))?>"><img width="16px" height="16px" src="http://img.youtube.com/vi/<?php e(rtrim($item['ObjOptAttachDef']['file'], '.video'))?>/default.jpg" /></a>
    <?php elseif(in_array(ws_ext($item['ObjOptAttachDef']['file']), ws_ext_img())):?>
        <!--<a target="_blank" class="ico ico_image-resize img_preview" preview="/getimages/100x100x2/thumb/<?php e($item['ObjOptAttachDef']['attach'])?>" href="/<?php e($item['ObjOptAttachDef']['location'])?>/large/<?php e($item['ObjOptAttachDef']['file'])?>"></a>-->
        <a target="_blank" class="ico img_preview" preview="/getimages/100x100x2/thumb/<?php e($item['ObjOptAttachDef']['attach'])?>" href="/<?php e($item['ObjOptAttachDef']['location'])?>/large/<?php e($item['ObjOptAttachDef']['file'])?>"><img height="16px" src="/getimages/16x16x1/thumb/<?php e($item['ObjOptAttachDef']['attach'])?>" /></a>
    <?php else:?>
        <a target="_blank" class="ico img_preview" preview="/img/ext/<?php e(ws_ext($item['ObjOptAttachDef']['file']))?>.png" href="/<?php e($item['ObjOptAttachDef']['location'])?>/large/<?php e($item['ObjOptAttachDef']['file'])?>"><img height="16px" src="/img/ext/<?php e(ws_ext($item['ObjOptAttachDef']['file']))?>.png" /></a>
    <?php endif;?>
<?php endif;?>