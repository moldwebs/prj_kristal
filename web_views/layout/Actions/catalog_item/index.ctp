<section class="wa-products-main padTB20">
   <div class="container">
         <div class="row">
            <?php if(!empty($items)):?>


               <div id="products" class="col-md-12 col-sm-12 col-xs-12">
                  <?php e($this->telement('sort', array('item' => $item)))?>
               </div>

               <div class="col-md-9 col-sm-8 col-xs-12 pull-right">
               <div class="row <?php e($tpltoggle['catalog_view'] == 'list' ? 'product-list' : '')?>">
                  <?php foreach($items as $key => $item):?>
                     <?php if($tpltoggle['catalog_view'] == 'grid' || empty($tpltoggle['catalog_view'])):?>
                     <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="wa-products">
                              <div class="wa-products-thumbnail wa-item">
                                 <img src="/getimages/510x600x0/large/<?php e($item['ObjOptAttachDef']['attach'])?>" alt="<?php eth($item['ObjItemList']['title'], 500)?>">
                                 <?php e($this->telement('quick-view', array('item' => $item)))?>
                              </div>
                              <div class="wa-products-caption">
                                 <h2><a href="<?php eurl($item['ObjItemList']['alias'])?>"><?php eth($item['ObjItemList']['title'], 100)?></a></h2>
                                 <div class="clear"></div>
                                 <?php e($this->telement('quick-stars', array('item' => $item)))?>
                                 <span class="price">
                                    <?php if($item['Price']['old'] > 0):?><del><?php e($item['Price']['html_old'])?> <?php e($item['Price']['html_currency'])?></del><?php endif;?>
                                    <?php e($item['Price']['html_value'])?> <?php e($item['Price']['html_currency'])?>
                                 </span>
                              </div>
                        </div>
                     </div>
                     <?php else:?>
                     <div class="col-md-12">
                        <div class="wa-products">
                           <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                 <div class="row">
                                    <div class="wa-products-thumbnail wa-item">
                                       <img src="/getimages/510x600x0/large/<?php e($item['ObjOptAttachDef']['attach'])?>" alt="<?php eth($item['ObjItemList']['title'], 500)?>">
                                       <div class="caption"></div>
                                    </div>
                                 </div>
                           </div>
                           <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                 <div class="row">
                                    <div class="wa-products-caption wa-list-caption text-left">
                                       <h2><a href="<?php eurl($item['ObjItemList']['alias'])?>"><?php eth($item['ObjItemList']['title'], 100)?></a></h2>
                                       <div class="clear"></div>
                                       <?php e($this->telement('quick-stars', array('item' => $item)))?>
                                       <span class="price">
                                          <?php if($item['Price']['old'] > 0):?><del><?php e($item['Price']['html_old'])?> <?php e($item['Price']['html_currency'])?></del><?php endif;?>
                                          <?php e($item['Price']['html_value'])?> <?php e($item['Price']['html_currency'])?>
                                       </span>
                                       <div class="clear"></div>
                                       <p class="hidden-xs hidden-sm">
                                          <?php eth($item['ObjItemList']['list_body'], 175)?>
                                       </p>
                                       <p class="hidden-lg hidden-md">
                                          <?php eth($item['ObjItemList']['list_body'], 25)?>
                                       </p>
                                       <?php e($this->telement('quick-view-list', array('item' => $item)))?>
                                    </div>
                                 </div>
                           </div>
                        </div>
                     </div>                  
                     <?php endif;?>
                  <?php endforeach;?>

                  <div class="clear"></div>
                  <div class="col-md-12">
                     <!--//==Pagination Start==//-->
                     <div class="styled-pagination padB30 text-center">
                        <?php echo $this->telement('pages')?>
                     </div>
                     <!--//==Pagination End==//-->
                  </div>

               </div>
               </div>

               <div class="col-md-3 col-sm-4 col-xs-12">
                  <div class="row">
                        <div class="sidebar">
                           <div class="col-md-12">
                              <?php echo $this->element('catalog/filter')?>
                           </div>
                        </div>
                  </div>
               </div>

            <?php else:?>
               <div class="no_results"><?php ___e('No results')?></div>
            <?php endif?>
         </div>
   </div>
</section>
 