<?php if(!empty($block['data'])):?>
<div class="post-comments">
	<header class="post-comments-header">
		<span class="post-comments-count"><i data-total-counter=""><?php e(count($block['data']))?></i> <?php ___e('comment(s)')?></span>
	
	</header>
	<ul class="commentsWrap" id="comments-placeholder">
        <?php foreach($block['data'] as $item):?>
    		<li class="userComment">
    			<img src="<?php if(!empty($item['User']['ObjOptAttachDef']['attach'])):?>/getimages/75x75x1/thumb/<?php e($item['User']['ObjOptAttachDef']['attach'])?><?php else:?>http://www.gravatar.com/avatar/<?php e(md5($item['User']['email']))?>?s=75<?php endif;?>" class="avatarImage">
    			<div class="coment">
                <header class="nickNameInfo">
    				<span class="nickName"><?php e($item['User']['username'])?></span>
                    &nbsp;&nbsp;
    				<time class="time"><?php e(date("d.m.Y H:i:s", strtotime($item['ObjOptComment']['created'])))?></time>
    			</header>
    
    			<article class="thisUserText">
    				<p><?php e($item['ObjOptComment']['comment'])?></p>
    			</article>
    			</div>
    			<div class="cl"></div>
    		</li>	
        <?php endforeach;?>
	</ul>
	<div class="paginator-wrap hidden">
		<div class="paginator m-paginator-scroll">
			<div class="paginator-pages">
				<div class="paginator-pages-scroll ">
				<div class="paginator-pages-scroll-handler"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif;?>

<?php if($this->Session->check('Auth.User.id')):?>
<form action="/<?php e(Configure::read('Config.tid'))?>/comment/add/" method="POST" enctype="multipart/form-data" class="ajx_validate">
    <input type="hidden" name="data[item_id]" value="<?php e($cms['active_item'])?>" />
    <textarea name="data[comment]" class="com_add" rows="4"></textarea>
    <br>
    <input value="<?php ___e('leave comment')?>" type="submit" class="btn">
</form>
<?php endif;?>