<?php if(!empty($block['data'])):?>
   <section class="blogs_main padTB30 grey-bg">
      <div class="container">
            <div class="row">
               <!--//==Section Heading Start==//-->
               <div class="col-md-12">
                  <div class="centered-title">
                        <h2><?php e($block['block']['title'])?> <span class="heading-border"></span></h2>
                        <div class="clear"></div>
                        <em><?php e($block['block']['desc'])?></em>
                  </div>
               </div>
               <!--//==Section Heading End==//-->
               <div class="col-md-12">
                  <div class="row">
                        <div class="owl-carousel owl-theme carousel-style-1" id="home-blog-carousel">
                           <?php foreach($block['data'] as $item):?>
                           <div class="home-blog-item">
                              <div class="col-md-12">
                                    <div class="wa-theme-design-block">
                                       <figure class="dark-theme">
                                          <img src="/getimages/356x247x0/thumb/<?php e($item['ObjOptAttachDef']['attach'])?>" alt="<?php eth($item['ObjItemList']['title'], 100)?>">	
                                          <span class="hover-style"></span>
                                       </figure>
                                       <div class="block-caption">
                                          <h4><a href="<?php eurl($item['ObjItemList']['alias'])?>"><?php eth($item['ObjItemList']['title'], 100)?></a></h4>
                                          <p>
                                             <?php eth($item['ObjItemList']['list_body'], 160)?>
                                          </p>
                                          <a href="<?php eurl($item['ObjItemList']['alias'])?>" class="read-more"><?php ___e('read more')?></a>
                                       </div>
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
<?php endif;?>