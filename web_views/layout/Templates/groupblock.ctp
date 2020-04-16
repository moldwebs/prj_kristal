<div class="hometab <?php e($css)?>" id="<?php e($code)?>">
    <div id="tabs-<?php e($code)?>" class="htabs">
        <ul class='etabs'>
            <?php foreach($titles as $key => $title):?>
                <li class="tab">
                    <?php if(!empty($title['url'])):?>
                        <a href="<?php e($title['url'])?>"><?php e($title['title'])?></a>
                    <?php else:?>
                        <a href="#tab-<?php e($code)?>-<?php e($key+1)?>"><?php e($title['title'])?></a>
                    <?php endif;?>
                </li>
            <?php endforeach;?>
        </ul>
    </div>
 
    <?php foreach($bodys as $key => $body):?>
        <div id="tab-<?php e($code)?>-<?php e($key+1)?>" class="tab-content">
            <div class="box">
                <div class="box-content">
                    <?php e($body)?>
                </div>
            </div>
            <span class="tabspecial_default_width" style="display:none; visibility:hidden"></span> 
        </div>
    <?php endforeach;?>
</div>

<script type="text/javascript">
    $('#tabs-<?php e($code)?> a').tabs();
</script> 