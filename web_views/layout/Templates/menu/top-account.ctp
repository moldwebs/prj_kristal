<?php if(!empty($menu['links'])):?>
        <?php foreach($menu['links'] as $key => $val):?>
            <?php if(!empty($val['child'])):?>
                <div class="links">
                    <a><?php e($val['title'])?></a>
                    <ul class="userinfo-block_ul">
                        <?php foreach($val['child'] as $_key => $_val):?>
                           <li>
                              <a href="<?php e($_val['data']['url'])?>"><?php e($_val['title'])?></a>
                           </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            <?php else:?>
                 <a class="header_item" href="<?php e($val['data']['url'])?>"><?php e($val['title'])?></a>
            <?php endif;?>
        <?php endforeach;?>
<?php endif;?>