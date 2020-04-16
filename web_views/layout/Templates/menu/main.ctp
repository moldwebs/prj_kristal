<?php if(!empty($menu['links'])):?>    
    <div id="main-menu" class="col-sm-9 main-menu">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="#"><?php e($menu['data']['title'])?></a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <?php foreach($menu['links'] as $key => $val):?>
                        <li class="<?php e($val['data']['css_class'])?> <?php e($val['active'] == '1' ? 'active' : null)?> <?php e(!empty($val['child']) ? 'dropdown' : null)?>">
                            <a href="<?php e($val['data']['url'])?>" <?php e(!empty($val['child']) ? 'class="dropdown-toggle" data-toggle="dropdown"' : null)?>><?php e($val['title'])?></a>
                            <?php if(!empty($val['child'])):?>
                                <ul class="dropdown-menu container-fluid">
                                    <?php foreach($val['child'] as $_key => $_val):?>
                                        <?php if(!empty($_val['child'])):?>
                                            <li class="block-container">
                                                <ul class="block">
                                                    <?php if(!empty($_val['item']['ObjOptAttachDef']['file'])):?>
                                                    <li class="img_container">
                                                        <a href="<?php e($_val['data']['url'])?>">
                                                            <img class="img-responsive" src="/getimages/0x0/large/<?php e($val['item']['ObjOptAttachDef']['attach'])?>" alt="<?php e($val['item']['ObjOptAttachDef']['title'])?>">
                                                        </a>
                                                    </li>
                                                    <?php endif;?>
                                                    <li class="link_container group_header">
                                                        <a href="<?php e($_val['data']['url'])?>"><?php e($_val['title'])?></a>
                                                    </li>
                                                    <?php foreach($_val['child'] as $__key => $__val):?>
                                                        <li class="link_container"><a class="<?php e($__val['data']['css_class'])?> <?php e($__val['active'] == '1' ? 'active' : null)?>" href="<?php e($__val['data']['url'])?>"><?php e($__val['title'])?></a></li>
                                                    <?php endforeach;?>
                                                </ul>
                                            </li>
                                        <?php else:?>
                                            <li class="link_container"><a class="<?php e($_val['data']['css_class'])?> <?php e($_val['active'] == '1' ? 'active' : null)?>" href="<?php e($_val['data']['url'])?>"><?php e($_val['title'])?></a></li>
                                        <?php endif;?>
                                    <?php endforeach;?>

                                    <?php if(!empty($val['item']['ObjOptAttachDef']['file'])):?>
                                    <li class="block-container">
                                        <ul class="block">
                                            <li class="img_container">
                                                <img src="/getimages/0x0/large/<?php e($val['item']['ObjOptAttachDef']['attach'])?>" alt="<?php e($val['item']['ObjOptAttachDef']['title'])?>" />
                                            </li>
                                        </ul>
                                    </li>
                                    <?php endif;?>
                                </ul>
                            <?php endif;?>
                        </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
<?php endif;?>