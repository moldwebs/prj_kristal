<section class="page_single blogs_main padTB100">
   <div class="container">
         <div class="row pad-s15">
            <div class="col-md-12 col-sm-12">
               <div class="row">
                     <!--//==Blog Item==//-->
                     <?php foreach($items as $key => $item):?>
                     <div class="col-md-4 col-sm-6 marB30">
                        <div class="wa-theme-design-block">
                           <!--//==Blog Thumbnail Start==//-->
                           <figure class="dark-theme">
                                 <img src="/getimages/356x247/large/<?php e($item['ObjOptAttachDef']['attach'])?>" alt="<?php eth($item['ObjItemList']['title'], 100)?>">	
                                 <span class="hover-style"></span>
                           </figure>
                           <!--//==Blog Caption Start==//-->
                           <div class="block-caption">
                                 <h4><a href="<?php eurl($item['ObjItemList']['alias'])?>"><?php eth($item['ObjItemList']['title'], 100)?></a></h4>
                                 <p>
                                    <?php eth($item['ObjItemList']['list_body'], 150)?>
                                 </p>
                                 <a href="<?php eurl($item['ObjItemList']['alias'])?>" class="read-more"><?php ___e('read more')?></a>
                           </div>
                        </div>
                        <div class="clear"></div>
                     </div>
                     <?php endforeach;?>
               </div>
               <div class="clear"></div>
               <!--//==Pagination Start==//-->
               <div class="styled-pagination text-center">
                  <?php echo $this->telement('pages')?>
               </div>
               <!--//==Pagination End==//-->
            </div>
         </div>
   </div>
</section>
