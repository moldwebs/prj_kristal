<?php if(!empty($menu['links'])):?>
   <ul class="nav navbar-nav">
      <?php foreach($menu['links'] as $key => $val):?>
         <?php if(!empty($val['child'])):?>
            <?php if($val['data']['css_class'] == 'megamenu' || 1==1):?>
            <li class="mega-menu">
               <a href="<?php e($val['data']['url'])?>" class="has-submenu"><?php e($val['title'])?> <span class="caret menu-arrow"></span><span class="sub-arrow">...</span></a>
               <ul class="dropdown-menu wv_menu_color sm-nowrap">
                     <li>
                        <div class="row">
                           <?php foreach($val['child'] as $_key => $_val):?>
                           <div class="col-sm-4 hidden-lg">
                                 <a href="<?php e($_val['data']['url'])?>"><h6 class="title"><?php e($_val['title'])?></h6></a>
                                 <div class="page-links">
                                    <?php foreach($_val['child'] as $__key => $__val):?>
                                    <div>
                                       <a href="<?php e($__val['data']['url'])?>"><?php e($__val['title'])?></a>
                                    </div>
                                    <?php endforeach;?>
                                 </div>
                           </div>
                           <div class="col-sm-4 menu-items hidden-xs text-center">
                                 <a href="<?php e($_val['data']['url'])?>"><h6 class="title"><?php e($_val['title'])?></h6></a>
                                 <div class="page-links text-center">
                                    <div><a href="<?php e($_val['data']['url'])?>"><img src="/getimages/200x200x0/large/0_<?php e($_val['image'])?>"></a></div>
                                    <?php foreach($_val['child'] as $__key => $__val):?>
                                    <div>
                                       <a href="<?php e($__val['data']['url'])?>"><?php e($__val['title'])?></a>
                                    </div>
                                    <?php endforeach;?>
                                 </div>
                           </div>
                           <?php endforeach;?>
                        </div>
                     </li>
               </ul>
            </li>
            <?php else:?>
            <li>
               <a href="<?php e($val['data']['url'])?>"><?php e($val['title'])?> <span class="caret menu-arrow"></span><span class="sub-arrow">...</span></a>
               <ul class="dropdown-menu">
                  <?php foreach($val['child'] as $_key => $_val):?>
                     <li><a href="<?php e($_val['data']['url'])?>"><?php e($_val['title'])?></a></li>
                  <?php endforeach;?>
               </ul>
            </li>
            <?php endif;?>
         <?php else:?>
            <li><a href="<?php e($val['data']['url'])?>"><?php e($val['title'])?></a></li>
         <?php endif;?>
      <?php endforeach;?>
   </ul>
 <?php endif;?>