<?php if(!empty($menu['links'])):?>
<script type="text/javascript">
//<![CDATA[
var CUSTOMMENU_POPUP_WIDTH = 0;
var CUSTOMMENU_POPUP_TOP_OFFSET = 0;
var CUSTOMMENU_POPUP_RIGHT_OFFSET_MIN = 0;
var CUSTOMMENU_POPUP_DELAY_BEFORE_DISPLAYING = 0;
var CUSTOMMENU_POPUP_DELAY_BEFORE_HIDING = 0;
var megnorCustommenuTimerShow = {};
var megnorCustommenuTimerHide = {};
//]]>
</script>
 <script type="text/javascript">
//<![CDATA[
function toggleMenu(el, over)
{
	if (over) {
		Element.addClassName(el, 'over');
	}
	else {
		Element.removeClassName(el, 'over');
	}
}
//]]>
</script>
<?php foreach($menu['links'] as $key => $val):?>
    <?php if(!empty($val['child'])):?>
        <div id="menu<?php e($key)?>" class="menu parrent-arrow <?php e($val['data']['css_class'])?> <?php e($val['active'] == '1' ? 'active' : null)?>" onmouseover="megnorShowMenuPopup(this, 'popup<?php e($key)?>');" onmouseout="megnorHideMenuPopup(this, event, 'popup<?php e($key)?>', 'menu<?php e($key)?>')">
           <div class="parentMenu arrow">
              <a href="<?php e($val['data']['url'])?>">
              <span><?php e($val['title'])?></span>
              </a>
           </div>
        </div>
        <div id="popup<?php e($key)?>" class="megnor-advanced-menu-popup" onmouseout="megnorHideMenuPopup(this, event, 'popup<?php e($key)?>', 'menu<?php e($key)?>')" onmouseover="megnorPopupOver(this, event, 'popup<?php e($key)?>', 'menu<?php e($key)?>')">
           <div class="megnor-advanced-menu-popup_inner">
              <div class="block1">
                 <?php $i = 0?>
                 <?php foreach($val['child'] as $_key => $_val):?>
                 <?php if(!($i++ % 4) && $i > 1):?>
                    </div>
                    <div class="clearBoth"></div>
                    <div class="block1">
                 <?php endif;?>
                 <div class="column">
                    <div class="itemMenu level1">
                       <a class="itemMenuName level1" href="<?php e($_val['data']['url'])?>"><span><?php e($_val['title'])?></span></a>
                       <div class="itemSubMenu level1">
                          <div class="itemMenu level2">
                          <?php foreach($_val['child'] as $__key => $__val):?>
                          <a class="itemMenuName level2 <?php e($__val['data']['css_class'])?> <?php e($__val['active'] == '1' ? 'active' : null)?>" href="<?php e($__val['data']['url'])?>"><span><?php e($__val['title'])?></span></a>
                          <?php if($__key >= 3):?>
                          <a class="itemMenuName level2 <?php e($_val['data']['css_class'])?> " href="<?php e($_val['data']['url'])?>"><span><?php e('More...')?></span></a>
                          <?php break;?>
                          <?php endif;?>
                          <?php endforeach;?>
                          </div>
                       </div>
                    </div>
                 </div>
                 <?php endforeach;?>
                 <div class="clearBoth"></div>
              </div>
              <div class="clearBoth"></div>
              <?php if(!empty($val['item']['ObjOptAttachDef']['attach'])):?>
              <div id="tm_advanced_menu_<?php e($key)?>" class="block2">
                 <p><a href="#"> <img src="/getimages/0x0/large/<?php e($val['item']['ObjOptAttachDef']['attach'])?>" /> </a></p>
              </div>
              <?php endif;?>
           </div>
        </div>
    <?php else:?>
        <div id="menu<?php e($key)?>" class="menu <?php e($val['data']['css_class'])?> <?php e($val['active'] == '1' ? 'active' : null)?>">
           <div class="parentMenu arrow">
              <a href="<?php e($val['data']['url'])?>">
              <span><?php e($val['title'])?></span>
              </a>
           </div>
        </div>
    <?php endif;?>
<?php endforeach;?>
<?php endif;?>