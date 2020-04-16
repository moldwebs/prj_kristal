<?php $pages = $this->Paginator->numbers(array('array' => true, 'modulus' => 4))?>
<?php //echo $this->Paginator->counter('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}');?>
<?php if(!empty($pages)):?>
    <ul>
    <?php if(!empty($pages['prev'])):?>
    <li class="prev"><a href="<?php e($pages['prev']['url'])?>" >&lt;</a></li>
    <?php endif;?>
    <?php foreach($pages['pages'] as $page):?>
        <?php if($page['active']):?>
            <li><a class="active"><?php e($page['number'])?></a></li>
        <?php else:?>
            <li><a href="<?php e($page['url'])?>"><?php e($page['number'])?></a></li>
        <?php endif;?>
    <?php endforeach;?>
    <?php if(!empty($pages['next'])):?>
    <li class="next"><a href="<?php e($pages['next']['url'])?>" >&gt;</a></li>
    <?php endif;?>
    </ul>
<?php endif;?>