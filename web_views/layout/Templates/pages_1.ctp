<?php $pages = $this->Paginator->numbers(array('array' => true, 'modulus' => 4))?>
<?php //echo $this->Paginator->counter('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}');?>
<?php if(!empty($pages)):?>
  <label><?php ___e('Page')?> :</label>
  <ul class="pagination">

        <?php if(!empty($pages['prev'])):?>
         <li id="pagination_next_bottom" class="pagination_previous">
            <a  href="<?php e($pages['prev']['url'])?>">
            <i class="fa fa-angle-left"></i>
            </a>
         </li>
        <?php endif;?>
        <?php foreach($pages['pages'] as $page):?>
            <?php if($page['active']):?>
                 <li class="active current">
                    <span>
                    <span><?php e($page['number'])?></span>
                    </span>
                 </li>
            <?php else:?>
                 <li>
                    <a  href="<?php e($page['url'])?>">
                    <span><?php e($page['number'])?></span>
                    </a>
                 </li>
            <?php endif;?>
        <?php endforeach;?>
     <?php if(!empty($pages['next'])):?>
     <li id="pagination_next_bottom" class="pagination_next">
        <a  href="<?php e($pages['next']['url'])?>">
        <i class="fa fa-angle-right"></i>
        </a>
     </li>
     <?php endif;?>
  </ul>

<?php endif;?>