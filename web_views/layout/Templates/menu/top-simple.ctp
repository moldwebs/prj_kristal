<?php if(!empty($menu['links'])):?>
<div class="spcustom_html hidden-xs hidden-sm">
  <div>
     <div class="support-info">
        <ul>
            <?php foreach($menu['links'] as $key => $val):?>
                <li><a href="<?php e($val['data']['url'])?>"><span><?php if(!empty($val['data']['css_ico'])):?><i class="fa <?php e($val['data']['css_ico'])?>"></i>&nbsp;&nbsp;<?php endif;?><?php e($val['title'])?></span></a></li>
            <?php endforeach;?>
        </ul>
     </div>
  </div>
</div>
<?php endif;?>