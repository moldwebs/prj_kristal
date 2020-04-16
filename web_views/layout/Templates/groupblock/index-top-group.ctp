<div class="col-xs-12 col-sm-12 page-top-left <?php e($css)?>" id="<?php e($code)?>">
    <div class="popular-tabs">
          <ul class="nav-tab">
            <?php foreach($titles as $key => $title):?>
                <li class="<?php e($key < 1 ? 'active' : '')?>">
                    <?php if(!empty($title['url'])):?>
                        <a href="<?php e($title['url'])?>"><?php e($title['title'])?></a>
                    <?php else:?>
                        <a data-toggle="tab" href="#tab-<?php e($code)?>-<?php e($key+1)?>"><?php e($title['title'])?></a>
                    <?php endif;?>
                </li>
            <?php endforeach;?>
          </ul>
          <div class="tab-container">
                <?php foreach($bodys as $key => $body):?>
                <div id="tab-<?php e($code)?>-<?php e($key+1)?>" class="tab-panel <?php e($key < 1 ? 'active' : '')?>">
                    <?php e($body)?>
                </div>
                <?php endforeach;?>
          </div>
    </div>
</div>