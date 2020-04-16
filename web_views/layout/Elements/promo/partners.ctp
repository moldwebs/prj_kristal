<section class="wa-partners padT30 padB10">
    <div class="container">
        <div class="row">
            <!--//==Section Heading Start==//-->
            <div class="col-md-12">
                <div class="centered-title">
                    <h2><?php e($block['block']['title'])?><span class="heading-border"></span></h2>
                    <div class="clear"></div>
                    <em><?php e($block['block']['desc'])?></em>
                </div>
            </div>
            <!--//==Section Heading End==//-->
            <div class="col-md-12">
                <div class="row">
                    <div class="wa-partner-carousel owl-carousel-style1 text-center owl-carousel owl-theme">
                        <?php foreach($block['data']['items'] as $key => $item):?>
                        <div class="partener-item">
                            <div class="col-md-12">
                                <div class="wa-theme-design-block">
                                    <figure class="dark-theme">
                                        <img src="/getimages/270x154x1/large/<?php e($item['ObjOptAttachDef']['attach'])?>">						  
                                    </figure>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
