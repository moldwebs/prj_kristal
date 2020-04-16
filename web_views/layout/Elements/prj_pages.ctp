<?php $pages = $this->Paginator->numbers(array('array' => true, 'modulus' => 4))?>
<?php //echo $this->Paginator->counter('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}');?>
<?php if(!empty($pages)):?>
<div style="margin: 20px;" class="pull-left">
    <ul class="pagination">
        <?php if(!empty($pages['prev'])):?>
            <li><a href="<?php e($pages['prev']['url'])?>"><i class="fa fa-caret-left"></i></a></li>
        <?php endif;?>
        <?php foreach($pages['pages'] as $page):?>
            <?php if($page['active']):?>
                <li><a href="<?php e($page['url'])?>"><b><?php e($page['number'])?></b></a></li>
            <?php else:?>
                <li><a href="<?php e($page['url'])?>"><?php e($page['number'])?></a></li>
            <?php endif;?>
        <?php endforeach;?>
        <?php if(!empty($pages['next'])):?>
            <li><a href="<?php e($pages['next']['url'])?>"><i class="fa fa-caret-right"></i></a></li>
        <?php endif;?>
    </ul>
</div>
<?php endif;?>