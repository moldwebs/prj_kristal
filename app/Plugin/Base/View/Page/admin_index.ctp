<div class="grid_16">
    <div class="nw-table">
        <div class="nw-table-title">
            <div class="fl">Quick access</div>
        </div>
        <div class="nw-table-content pd">
            <div class="admin_permissions">
            <?php if(count(Configure::read('CMS.activelanguages')) > 1):?>
                <div>
                    <fieldset>
                        <legend><?php ___e('Language')?></legend>
                        <div class="fieldset_content">
                        <?php foreach(Configure::read('CMS.activelanguages') as $key => $val):?>
                            <a href="/admin?tm=0&lang=<?php e($key)?>" class="button primary icon tag"><?php e($val)?></a>
                        <?php endforeach;?>
                        </div>
                    </fieldset>
                </div>
            <?php endif;?>
            <?php foreach(Hash::sort(CmsNav::items(), '{s}.weight', 'ASC') as $item):?>
                <div>
                    <fieldset>
                        <legend><?php e($item['title'])?></legend>
                        <div class="fieldset_content">
                        <?php foreach(Hash::sort($item['children'], '{s}.weight', 'ASC') as $action):?>
                            <?php if(!empty($action['buttons'])):?>
                                <?php foreach($action['buttons'] as $button):?>
                                    <a href="<?php e($this->Html->url($button['url']))?>" class="button primary icon tag"><?php e($button['title'])?></a>
                                <?php endforeach;?>
                            <?php else:?>
                                <a href="<?php e($this->Html->url($action['url']))?>" class="button primary icon tag"><?php e($action['title'])?></a>
                            <?php endif;?>
                            
                        <?php endforeach;?>
                        </div>
                    </fieldset>
                </div>
            <?php endforeach;?>
            </div>
        </div>
    </div>
</div>

<div class="clear"></div>
