<?php //_pr($basket['items'])?>

<?php if(empty($basket['items'])):?>
<section class="cart-page padB20">
   <div class="container">
    <div>&nbsp;</div>
    <p id="emptyCartWarning" class="alert alert-warning "><?php ___e('Your shopping cart is empty.')?></p>
   </div>
</section>
<?php else:?>

<section class="cart-page padTB20">
   <div class="container">
         <div class="row wv-pads15">
            <div class="col-md-12 col-sm-12">
               <!--//==Section Heading Start==//-->
               <div class="left-title">
                     <h2><?php echo $title_for_action; ?> <span class="heading-border"></span></h2>
                     <div class="clear"></div>
               </div>
               <!--//==Section Heading End==//-->
               <div class="clear"></div>
               <!--//==Order Table titles Start==//-->
               <div class="hidden-xs">
                     <div class="col-md-2 col-sm-2 grey-bg">
                        <div class="order-data text-center pad15">
                           <strong>&nbsp;</strong>
                        </div>
                     </div>
                     <div class="col-md-4 col-sm-4 grey-bg">
                        <div class="order-data text-center pad15">
                           <strong><?php ___e('Product')?></strong>
                        </div>
                     </div>
                     <div class="col-md-2 col-sm-2 grey-bg">
                        <div class="order-data  text-center pad15">
                           <strong><?php ___e('Unit price')?></strong>
                        </div>
                     </div>
                     <div class="col-md-1 col-sm-1 grey-bg">
                        <div class="order-data text-center pad15">
                           <strong><?php ___e('Qty')?></strong>
                        </div>
                     </div>
                     <div class="col-md-2 col-sm-2 grey-bg">
                        <div class="order-data text-center pad15">
                           <strong><?php ___e('Total')?></strong>
                        </div>
                     </div>
                     <div class="col-md-1 col-sm-1 grey-bg">
                        <div class="order-data text-center pad15">
                           <strong>&nbsp;</strong>
                        </div>
                     </div>
               </div>
               <?php foreach($basket['items'] as $id => $item):?>
               <div class="row">
                     <div class="col-md-12">
                        <div class="order-row padTB20">
                           <div class="col-md-2 col-sm-2">
                                 <div class="order-data order-table text-center padTB15">
                                    <div class="order-table-cell">
                                       <a href="<?php eurl($item['data']['ObjItemList']['alias'])?>"><img src="/getimages/60x75x0/thumb/<?php e($item['data']['ObjOptAttachDef']['attach'])?>"></a>
                                       <div class="clear"></div>
                                    </div>
                                 </div>
                           </div>
                           <div class="col-md-4 col-sm-4">
                                 <div class="order-data order-table ">
                                    <div class="order-table-cell order-text">           
                                       <strong><a href="<?php eurl($item['data']['ObjItemList']['alias'])?>"><?php e(!empty($item['data']['ObjItemList']['rel_id']) ? $item['data']['ObjItemList']['orig_title'] : $item['data']['ObjItemList']['title'])?></a></strong>
                                       <div class="cart_ref"><?php ___e('Code')?>: <?php e($item['data']['ObjItemList']['code'])?></div>
                                       <?php if(!empty($item['infos'])) foreach($item['infos'] as $key => $val):?>
                                          <div class="cart_ref"><?php e($key)?> : <?php e($val)?></div>
                                       <?php endforeach;?>
                                       <?php if(!empty($item['data']['ObjItemList']['rel_id'])) foreach($item['data']['Specification'] as $key => $val):?>
                                          <div class="cart_ref"><?php e($key)?> : <?php e($val)?></div>
                                       <?php endforeach;?>
                                    </div>
                                 </div>
                           </div>
                           <div class="col-md-2 col-sm-2">
                                 <div class="order-data order-table text-center">
                                    <div class="order-table-cell order-text">
                                       <strong><?php e($item['html_price'])?> <?php e($item['html_currency'])?></strong>
                                    </div>
                                 </div>
                           </div>
                           <div class="col-md-1 col-sm-1">
                                 <div class="order-data order-table text-center">
                                    <div class="order-table-cell order-text">
                                       <input size="2" type="number" onclick="select()" onchange="window.location = '/shop/basket/qnt/<?php e($id)?>/' + this.value" id="data_qnt_<?php e($id)?>" name="data[qnt][<?php e($id)?>]" value="<?php e($item['qnt'])?>" autocomplete="off" class="qty" /> 
                                    </div>
                                 </div>
                           </div>
                           <div class="col-md-2 col-sm-2">
                                 <div class="order-data order-table text-center">
                                    <div class="order-table-cell order-text">
                                       <strong><?php e($item['html_price_total'])?> <?php e($item['html_currency'])?></strong>
                                    </div>
                                 </div>
                           </div>
                           <div class="col-md-1 col-sm-1">
                                 <div class="order-data order-table text-center">
                                    <div class="order-table-cell order-text">
                                       <strong><a href="/shop/basket/delete/<?php e($id)?>"><i class="fa fa-trash" aria-hidden="true"></i></a></strong>
                                    </div>
                                 </div>
                           </div>
                        </div>
                     </div>
               </div>
               <?php endforeach;?>
            </div>
         </div>
   </div>
</section>
<!--//==Order Section End==//-->
<!--//== Order Price Section Start ==//-->
<section class="cart-page padB100">
   <div class="container">
         <div class="row">
            <!--//== Apply Coupon Section Start ==//-->
            <div class="col-xs-12 col-sm-6 col-md-5 wv_shipping_outer">
               <!--//==Section Heading Start==//-->
               <div class="left-title">
                     <h2><?php ___e('Use Coupon Code')?><span class="heading-border"></span></h2>
                     <div class="clear"></div>
               </div>
               <!--//==Section Heading End==//-->
               <div class="wv_shipping responsive_coupon grey-bg">
                     <div class="wv_subtotaling marB20">
                        <div class="col-xs-12 col-md-12  wv_subtotal_left textL">
                           <p><?php ___e('Enter your coupon here')?></p>
                        </div>
                     </div>
                     <div class="row">
                        <form method="POST" action="/shop/basket/update" class="contact-form">
                           <!--//==Contact Input Field==//-->
                           <div class="col-md-12 form-group wv_form_field marB30">
                                 <input  type="text" id="discount_name" name="data[coupon]" value="" class="wv_form_focus" />
                           </div>
                           <div class="col-md-12 wv_form_field">
                                 <button type="submit" class="theme-button col-xs-12 marT0">OK</button>
                           </div>
                        </form>
                     </div>
               </div>
            </div>
            <!--//== Apply Coupon Section End ==//-->
            <!--//== Price Calculation Section Start ==//-->
            <div class="col-xs-12 col-sm-6 col-md-7 wv_subtotal">
               <!--//==Section Heading Start==//-->
               <div class="left-title">
                     <h2><?php ___e('Totals')?><span class="heading-border"></span></h2>
                     <div class="clear"></div>
               </div>
               <!--//==Section Heading End==//-->
               <div class="wv_shipping">

                  <form action="/shop/checkout/checkout" method="POST">

                  <div class="wv_subtotaling">
                     <div class="col-md-12 form-group">
                        <?php if(!empty($shipping)) echo $this->Form->input('shipping', array('label' => false, 'options' => $shipping, 'selected' => $basket['options']['shipping']['shipping'], 'empty' => __('Livrare'), 'class' => 'form-control req', 'required' => 'required', 'div' => false, 'onchange' => "window.location = '/shop/basket/update?shipping=' + this.value"));?>
                     </div>
                     <div class="col-md-12 form-group">
                        <?php if(!empty($zones)) echo $this->Form->input('zone_id', array('label' => false, 'options' => $zones, 'selected' => $basket['options']['shipping']['zone_id'], 'empty' => ___('Localitate'), 'class' => 'form-control req', 'required' => 'required', 'div' => false, 'onchange' => "window.location = '/shop/basket/update?zone=' + this.value"));?>
                     </div>

                     <div class="col-md-12 form-group">
                        <?php echo $this->Form->input('Checkout.name', array('label' => false, 'placeholder' => ___('Nume Prenume'), 'required' => 'required', 'class' => 'form-control req', 'div' => false, 'autocomplete' => 'off'));?>
                     </div>               
                     <div class="col-md-12 form-group">
                        <?php echo $this->Form->input('Checkout.street', array('label' => false, 'placeholder' => ___('Adresa'), 'required' => 'required', 'class' => 'form-control req', 'div' => false));?>
                     </div>               
                     <div class="col-md-12 form-group">
                        <?php echo $this->Form->input('Checkout.phone', array('label' => false, 'placeholder' => ___('Telefon'), 'required' => 'required', 'class' => 'form-control req', 'div' => false, 'autocomplete' => 'off'));?>
                     </div>               
                     <div class="col-md-12 form-group">
                        <?php echo $this->Form->input('Checkout.email', array('label' => false, 'placeholder' => ___('Email'), 'required' => 'required', 'class' => 'form-control req', 'div' => false, 'autocomplete' => 'off'));?>
                     </div>               

                     <div class="col-md-12 form-group">
                        <?php if(!empty($zones)) echo $this->Form->input('Payment.payment', array('label' => false, 'options' => $payments, 'empty' => ___('Achitare'), 'class' => 'form-control req', 'required' => 'required', 'div' => false));?>
                     </div>

                     <div class="clear"></div>
                  </div>


                  <?php if(!empty($basket['extra'])):?>
                     <div class="wv_subtotaling">
                        <div class="col-xs-6 col-md-6 wv_subtotal_left textL">
                           <p><?php ___e('Sub-Total')?></p>
                        </div>
                        <div class="col-xs-6 col-md-6 wv_subtotal_right textR">
                           <p><?php e($basket['html_sub_price'])?> <?php e($basket['html_currency'])?></p>
                        </div>
                     </div>
                  <?php foreach($basket['extra'] as $id => $item):?>
                     <div class="wv_subtotaling">
                        <div class="col-xs-6 col-md-6 wv_subtotal_left textL">
                           <p><?php ___e($item['title'])?></p>
                        </div>
                        <div class="col-xs-6 col-md-6 wv_subtotal_right textR">
                           <p><?php e($item['html_price'])?> <?php e($item['html_currency'])?></p>
                        </div>
                     </div>
                  <?php endforeach;?>
                  <?php endif;?>

                  <div class="wv_totaling">
                     <div class="col-xs-6 col-md-6 wv_total_left textL">
                        <p><?php ___e('Total')?></p>
                     </div>
                     <div class="col-xs-6 col-md-6 wv_total_right textR">
                        <p><?php e($basket['html_price'])?> <?php e($basket['html_currency'])?></p>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-xs-12 col-md-6 wv_checkout wv_form_field">		
                        <button type="submit" class="theme-button col-xs-12 marT0"><?php ___e('Checkout')?></button>					   
                        <!--<button onclick="window.location='/shop/checkout/index'" type="button" class="theme-button col-xs-12 marT0"><?php ___e('Checkout')?></button>-->
                     </div>
                     <div class="col-xs-12 col-md-6 wv_checkout wv_form_field1 responsive_field1">
                        <button onclick="window.location='<?php e($basket_back)?>'" type="button" class="theme-button col-xs-12 marT0"><?php ___e('Continue shopping')?> </button>
                     </div>
                  </div>

                  </form>

               </div>
            </div>
            <!--//== Price Calculation Section End ==//-->
         </div>
   </div>
</section>

<?php endif;?>