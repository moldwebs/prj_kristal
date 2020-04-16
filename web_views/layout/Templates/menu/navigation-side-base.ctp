<?php if(!empty($menu['links'])):?>
    <?php foreach($menu['links'] as $key => $val):?>
        <?php if(!empty($val['child'])):?>
            <li class="level0 <?php e($val['data']['css_class'])?> <?php e($val['active'] == '1' ? 'active' : null)?>">
             <a href="<?php e($val['data']['url'])?>" class="activSub"><?php e($val['title'])?></a> 					
             <ul>
                <?php foreach($val['child'] as $_key => $_val):?>
                    <?php if(!empty($_val['child'])):?>
                        <li class="<?php e($_val['data']['css_class'])?> <?php e($_val['active'] == '1' ? 'active' : null)?>">
                           <a href="<?php e($_val['data']['url'])?>" class="activSub"><?php e($_val['title'])?></a> 					
                           <ul>
                                <?php foreach($_val['child'] as $__key => $__val):?>
                                    <li class="<?php e($__val['data']['css_class'])?> <?php e($__val['active'] == '1' ? 'active' : null)?>"><a href="<?php e($__val['data']['url'])?>"><?php e($__val['title'])?></a></li>
                                <?php endforeach;?>
                           </ul>
                        </li>
                    <?php else:?>
                        <li class="<?php e($_val['data']['css_class'])?> <?php e($_val['active'] == '1' ? 'active' : null)?>">
                           <a href="<?php e($_val['data']['url'])?>"><?php e($_val['title'])?></a>
                        </li>
                    <?php endif;?>
                <?php endforeach;?>
             </ul>
            </li>
        <?php else:?>
            <li class="level0 <?php e($val['data']['css_class'])?> <?php e($val['active'] == '1' ? 'active' : null)?>">
             <a href="<?php e($val['data']['url'])?>"><?php e($val['title'])?></a> 	
            </li>
        <?php endif;?>
    <?php endforeach;?>
<?php endif;?>