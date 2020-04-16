<div id="top_menu_box">
	<div id="top_menu" style="float: left;"><?php echo $this->Layout->adminMenus(CmsNav::items());?></div>
    <div id="top_menu_r" style="float: right;">
        <?php if(Configure::read('CMS.uids')):?>
            <?php echo $this->Form->input('uid', array('style' => 'float: left; margin: 9px;', 'div' => false, 'label' => false, 'options' => Configure::read('CMS.uids'), 'selected' => CMS_UID_REL, 'onchange' => 'window.location="/admin/uid?uid=" + this.value'));?>
        <?php endif;?>
        
        <?php if(defined('CMS_UID_ADMIN')):?>
            <a style="margin: 9px;" class="button small" href="/admin/?uid=0"><?php e(CMS_UID_ADMIN_DOMAIN)?></a>
        <?php endif;?>
        <a class="tipsy_bottom" title="<?php ___e('View Site')?>" target="_blank" href="<?php e(defined('CMS_UID_ADMIN') ? str_replace('://', '://' . CMS_UID_ADMIN_DOMAIN . '.' , FULL_BASE_URL) : '/')?>"><img height="25px" src="/img/ico/home_sticker.png" /></a>
        <!--
        <a href="/admin/feedback/messages/index?tm=0"><img height="25px" src="/img/ico/mail_sticker.png" /></a>
        -->
        <a class="tipsy_bottom" title="<?php ___e('Statistics')?>" href="/admin/stats/stats/index?tm=0"><img height="25px" src="/img/ico/chart-bar_sticker.png" /></a>
        <a class="tipsy_bottom" title="<?php ___e('Clear Cache')?>" href="/admin/system/clear"><img height="25px" src="/img/ico/button-synchronize_sticker.png" /></a>
        <a class="tipsy_bottom" title="<?php ___e('Settings')?>" href="/admin/base/system/settings?tm=0"><img height="25px" src="/img/ico/gears_sticker.png" /></a>
        <a class="tipsy_bottom" title="<?php ___e('Logout')?> [<?php e($this->Session->read('Auth.User.usermail'))?>]" href="/admin/users/users/logout?tm=0"><img height="25px" src="/img/ico/button-power_sticker.png" /></a>
    </div>
</div>