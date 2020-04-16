<?php if(!empty($menu['links'])):?>
    <?php foreach($menu['links'] as $key => $val):?>
        <?php if(!empty($val['child'])):?>
            <li class="dropdown myaccount">
               <a href="<?php e($val['data']['url'])?>" title="<?php e($val['title'])?>" class="dropdown-toggle <?php e($val['data']['css_class'])?> <?php e($val['active'] == '1' ? 'active' : null)?>" data-toggle="dropdown"> <span><?php e($val['title'])?></span> <span class="caret"></span></a>
               <ul class="dropdown-menu dropdown-menu-right myaccount-menu">
                  <?php foreach($val['child'] as $_key => $_val):?>
                    <li><a class="<?php e($_val['data']['css_class'])?> <?php e($_val['active'] == '1' ? 'active' : null)?>" href="<?php e($_val['data']['url'])?>"><?php e($_val['title'])?></a></li>
                  <?php endforeach;?>
               </ul>
            </li>
        <?php else:?>
            <li><a class="<?php e($val['data']['css_class'])?> <?php e($val['active'] == '1' ? 'active' : null)?>" href="<?php e($val['data']['url'])?>" title="<?php e($val['title'])?>"><span ><?php e($val['title'])?></span></a></li>
        <?php endif;?>
    <?php endforeach;?>
<?php endif;?>