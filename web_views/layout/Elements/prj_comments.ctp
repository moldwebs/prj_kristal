<?php if(!empty($comments)):?>
<div class="comments">
    <ul>
        <?php foreach($comments as $item):?>
        <li class="comment clearfix" data-comment-id="1">
            <div class="comment-avatar">
                <img src="<?php if(!empty($item['User']['ObjOptAttachDef']['attach'])):?>/getimages/75x75x1/thumb/<?php e($item['User']['ObjOptAttachDef']['attach'])?><?php else:?>http://www.gravatar.com/avatar/<?php e(md5($item['User']['email']))?>?s=75<?php endif;?>" />
            </div>
            <div class="comment-content">
                <div class="comment-user">
                	<a href="#"><?php e($item['User']['username'])?></a>
                </div>
                <div class="comment-time">
                	<?php e(date("d.m.Y H:i", strtotime($item['ObjOptComment']['created'])))?>
                </div>
                <div class="comment-msg">
					<?php e($item['ObjOptComment']['comment'])?>
                </div>
                <?php if(!empty($item['ObjOptAttachs']['all'])):?>
                    <hr />
                    <?php foreach($item['ObjOptAttachs']['all'] as $attach):?>
                        <div class="comment-time">
                            <a target="_blank" href="/<?php e($attach['location'])?>/large/<?php e($attach['file'])?>"><?php e($attach['title'])?></a>
                        </div>
                    <?php endforeach;?>
                <?php endif;?>

            </div>            
  
        </li><!-- End .comment -->
        <?php endforeach;?>
       
	</ul>
</div><!-- End .comments -->   
<?php endif;?>