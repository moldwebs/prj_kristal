<?php if(!empty($menu['links'])):?>
<div class="col-sm-3" id="box-vertical-megamenus">
    <div class="box-vertical-megamenus">
        <h4 class="title">
            <span class="title-menu"><?php e($menu['data']['title'])?></span>
            <span class="btn-open-mobile pull-right home-page"><i class="fa fa-bars"></i></span>
        </h4>
    <div class="vertical-menu-content is-home">
        <ul class="vertical-menu-list">
            <?php foreach($menu['links'] as $key => $val):?>
                <li>
                    <a class="<?php e($val['data']['css_class'])?> <?php e($val['active'] == '1' ? 'active' : null)?> <?php e(!empty($val['child']) ? 'parent' : null)?>" href="<?php e($val['data']['url'])?>"><?php if(!empty($val['item']['ObjOptAttachType']['icon']['file'])):?><img class="icon-menu" src="/getimages/0x0/large/<?php e($val['item']['ObjOptAttachType']['icon']['attach'])?>"><?php endif;?><?php e($val['title'])?></a>
                    <?php if(!empty($val['child'])):?>
                        <div class="vertical-dropdown-menu">
                            <div class="vertical-groups col-sm-12">
                                <?php foreach($val['child'] as $_key => $_val):?>
                                    <div class="mega-group col-sm-4">
                                        <?php if(!empty($_val['child'])):?>
                                            <h4 class="mega-group-header"><span><?php e($_val['title'])?></span></h4>
                                            <ul class="group-link-default">
                                                <?php foreach($_val['child'] as $__key => $__val):?>
                                                    <li><a class="<?php e($__val['data']['css_class'])?> <?php e($__val['active'] == '1' ? 'active' : null)?>" href="<?php e($__val['data']['url'])?>"><?php e($__val['title'])?></a></li>
                                                <?php endforeach;?>
                                            </ul>
                                        <?php else:?>
                                            <h4 class="mega-group-header"><span><a class="<?php e($_val['data']['css_class'])?> <?php e($_val['active'] == '1' ? 'active' : null)?>" href="<?php e($_val['data']['url'])?>"><?php e($_val['title'])?></a></span></h4>
                                        <?php endif;?>
                                    </div>
                                <?php endforeach;?>
                                <?php if(!empty($val['item']['ObjOptAttachDef']['file'])):?>
                                <div class="mega-custom-html col-sm-12">
                                    <img src="/getimages/0x0/large/<?php e($val['item']['ObjOptAttachDef']['attach'])?>" alt="<?php e($val['item']['ObjOptAttachDef']['title'])?>" />
                                </div>
                                <?php endif;?>
                            </div>
                        </div>
                    <?php endif;?>
                </li>
            <?php endforeach;?>
        </ul>
       <!--<div class="all-category"><span class="open-cate"><?php ___e('All Categories')?></span></div>--> 
    </div>
</div>
</div>
<?php endif;?>