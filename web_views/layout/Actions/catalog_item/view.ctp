<?php if(!empty($_GET['modal'])):?>
   <?php e($this->aelement('modal_view', array('item' => $item)))?>
<?php else:?>
   <section class="page_single">
      <div class="container">
            <div class="row padTB20">
               <?php e($this->aelement('modal_view', array('item' => $item)))?>
            </div>
      </div>
   </section>

   <?php if(!empty($item['Specification'])):?>
   <section class="page_single">
      <div class="container">
            <div class="row">
               <div class="prod-info-section">
                  <div class="clearfix">
                        <div class="col-md-12">
                           <div class="block-caption">
                              <div class="left-title">
                                    <h2><?php ___e('Specifications')?> <span class="heading-border"></span></h2>
                                    <div class="clear"></div>
                              </div>
                              
                              <div>
                              <div class="col-md-6 col-xs-12">
                                 <table class="table-data-sheet">
                                    <?php foreach($item['Specification'] as $spec):?>

                                    <?php if($i++ >= floor((count($item['Specification']) / 2)) && !is_array($spec) && 1==1):?>
                                       <?php $i = 0;?>
                                       </table></div><div class="col-md-6 col-xs-12"><table class="table-data-sheet">
                                    <?php endif;?>

                                    <?php if(!is_array($spec)):?>
                                    <tr class="odd">
                                       <td colspan="2"><strong class="label-big"><?php e($spec)?></strong></td>
                                    </tr>
                                    <?php else:?>
                                    <tr class="even dott_param">
                                       <td style="width: 50%; font-weight: normal;"><?php e($spec['title'])?></td>
                                       <td><?php e($spec['value'])?></td>
                                    </tr>
                                    <?php endif;?>

                                    <?php endforeach;?>
                                 </table>
                                 </div>
                                 <div style="clear: both;"></div>                              
                              </div>

                           </div>
                        </div>
                  </div>
               </div>
            </div>
      </div>
   </section>
   <?php endif;?>

   <?php if(!empty($item['ObjItemList']['body'])):?>
   <section class="page_single">
      <div class="container">
            <div class="row padTB10">
               <div class="prod-info-section">
                  <div class="clearfix">
                        <div class="col-md-12">
                           <div class="block-caption">
                              <div class="left-title">
                                    <h2><?php ___e('Product Description')?> <span class="heading-border"></span></h2>
                                    <div class="clear"></div>
                              </div>
                              <p><?php e($item['ObjItemList']['body'])?></p>
                           </div>
                        </div>
                  </div>
               </div>
            </div>
      </div>
   </section>
   <?php endif;?>

   <section class="page_single">
      <div class="container">
            <div class="row padTB10">
               <div class="prod-info-section">
                  <div class="clearfix">
                        <div class="col-md-12 padT0">
                           <div class="left-title">
                              <h2><?php ___e('Product Reviews')?> <span class="heading-border"></span></h2>
                              <div class="clear"></div>
                           </div>

                           <?php e($this->telement('reviews', array('item' => $item)))?>

                        </div>
                  </div>
               </div>
            </div>
      </div>
   </section>
<?php endif;?>