<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('ObjItemList', array('url' => (!empty($el_params['form_url']) ? $el_params['form_url'] : null), 'type' => 'file', 'class' => 'ajx_validate'));?>
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?><?php echo $edit_obj?></div>
            <div class="fr">
                <?php echo $this->element('/admin/edit_langs')?>
            </div>
        </div>
        <div class="nw-table-content">
            <div class="tabs-left">
                <ul>
                    <li><a href="#node-1"><span><?php echo ___('Content')?></span></a></li>
                    <li><a href="#node-7"><span><?php echo ___('Description')?></span></a></li>
                    <?php if(!empty($types)):?>
                    <li><a href="#node-9-2"><span><?php echo ___('Types')?></span></a></li>
                    <?php endif;?>
                    <li><a href="#node-7-1"><span><?php echo ___('Extra')?></span></a></li>
                    <li><a href="#node-5"><span><?php echo ___('Attachments')?></span></a></li>
                    <?php if(Configure::read('PLUGIN.Specification') == '1'):?>
                    <li><a href="#node-8"><span><?php echo ___('Specifications')?></span></a></li>
                    <?php endif;?>
                    <?php if(Configure::read('PLUGIN.Vendor') == '1' && !empty($vendors)):?>
                    <li><a href="#node-8-1"><span><?php echo ___('Vendors')?></span></a></li>
                    <?php endif;?>
                    <li><a href="#node-9"><span><?php echo ___('Related Products')?></span></a></li>
                    <li><a href="#node-9-1"><span><?php echo ___('Similar Products')?></span></a></li>
                    <li><a href="#node-3"><span><?php echo ___('Settings')?></span></a></li>
                    <li><a href="#node-4"><span><?php echo ___('Meta')?></span></a></li>
                    <li><a href="#node-6"><span><?php echo ___('Template')?></span></a></li>
                </ul>
                <div id="node-1">
                    <?php if(!empty($bases) || !empty($types)):?>
                    <div class="n4 cl">
                        <?php if(!empty($bases)) echo $this->Form->input('base_id', array('options' => $bases, 'label' => ___('Category'), 'empty' => ___(' --- None --- ')));?>
                        <?php if(!empty($manufacturers)) echo $this->Form->input('extra_2', array('options' => $manufacturers, 'label' => ___('Manufacturer'), 'empty' => array('0' => ___(' --- None --- '))));?>
                        <?php //if(!empty($types)) echo $this->Form->input('extra_1', array('options' => $types, 'label' => ___('Type'), 'empty' => array('0' => ___(' --- None --- '))));?>
                        <?php if(!empty($bases)) echo $this->Form->input('Relation.base_id', array('options' => $bases, 'label' => ___('Related Category'), 'multiple' => 'multiple', 'class' => 'multiselect'));?>
                    </div>
                    <?php endif;?>
                    <div class="n2 cl">
                        <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
                        <?php echo $this->Layout->__input('alias', array('label' => ___('Alias')));?>
                    </div>
                    <div class="n5 cl">
                        <?php echo $this->Form->input('ObjOptPrice.price', array('label' => ___('Price'), 'type' => 'text'));?>
                        <?php echo $this->Form->input('ObjOptPrice.currency', array('label' => ___('Currency'), 'options' => $currencies));?>
                        <?php echo $this->Form->input('ObjOptPrice.old_price', array('label' => ___('Old price'), 'type' => 'text'));?>
                        <?php echo $this->Form->input('RelationValue.promo_price', array('label' => ___('Promo Price'), 'type' => 'text'));?>
                        <?php echo $this->Form->input('RelationExpire.promo_price', array('label' => ___('Promo Price Expire'), 'type' => 'text', 'class' => 'ui_date'));?>
                    </div>
                    <div class="n5 cl">
                        <?php echo $this->Form->input('ObjItemList.code', array('label' => ___('Code'), 'type' => 'text'));?>
                        <?php echo $this->Form->input('ObjItemList.qnt', array('label' => ___('Quantity'), 'type' => 'text'));?>
                        <?php if(!empty($deposits)) echo $this->Form->input('extra_3', array('options' => $deposits, 'label' => ___('Deposit'), 'empty' => array('0' => ___(' --- None --- '))));?>
                        <?php echo $this->Form->input('ObjItemList.data.weight', array('label' => ___('Weight (Kg)'), 'type' => 'text'));?>
                        <?php echo $this->Form->input('ObjItemList.data.wrnt', array('label' => ___('Warranty (months)'), 'type' => 'text'));?>
                    </div>
                    <?php echo $this->Layout->__input('short_body', array('label' => ___('Short description'), 'type' => 'textarea'));?>
                    <?php echo $this->Form->input('Tags.tags', array('label' => ___('Tags'), 'class' => 'tags', 'rel_url' => '/catalog/item/get_tags'));?>
                </div>
                <div id="node-7">
                    <?php echo $this->Layout->__input('body', array('label' => ___('Content'), 'type' => 'textarea', 'class' => 'redactor'));?>
                </div>
                <div id="node-7-1">
                    <?php echo $this->aelement('admin_edit_extra')?>
                </div>
                <?php if(!empty($types)):?>
                <div id="node-9-2">
                    <?php foreach($types as $key => $val):?>
                        <div class="n5 cl">
                            <?php echo $this->Form->input("Relation.extra_1.{$key}", array('label' => $val, 'options' => array('' => ___('No'), $key => ___('Yes'))));?>
                            <?php echo $this->Form->input("RelationExpire.extra_1.{$key}", array('label' => ___('Expire'), 'type' => 'text', 'class' => 'ui_date'));?>
                        </div>
                    <?php endforeach;?>
                </div>
                <?php endif;?>
                <div id="node-5">
                    <?php echo $this->Layout->__images('attachments', array('label' => false));?>
                </div>
                <?php if(Configure::read('PLUGIN.Specification') == '1'):?>
                <div id="node-8">
                </div>
                <script>
                    $(document).ready(function(){
                        $('#ObjItemListBaseId').on('change', function(){
                            $('#node-8').load('/catalog/specification/item/' + $(this).val() + '?data=<?php e(urlencode(base64_encode(json_encode($this->data['RelationValue']['specification']))))?>');
                        }).trigger('change');
                    });
                </script>
                <?php endif;?>
                <?php if(Configure::read('PLUGIN.Vendor') == '1' && !empty($vendors)):?>
                <div id="node-8-1">
                </div>
                <script>
                    $(document).ready(function(){
                        $('#ObjItemListBaseId').on('change', function(){
                            $('#node-8-1').load('/admin/vendor/vendor/item/<?php e($this->data['ObjItemList']['id'])?>?base_id=' + $(this).val() + '&data=<?php e(urlencode(base64_encode(json_encode(array('vendor_code' => $this->data['RelationValue']['vendor_code'], 'vendor_percent' => $this->data['RelationValue']['vendor_percent'], 'vendor_fix' => $this->data['RelationValue']['vendor_fix'])))))?>');
                        }).trigger('change');
                    });
                </script>
                <?php endif;?>
                <div id="node-9">
                    <div class="n4 cl">
                        <?php echo $this->Form->input('rel_prods_fltr1', array('label' => ___('Title')));?>
                        <?php if(!empty($bases)) echo $this->Form->input('rel_prods_fltr2', array('options' => $bases, 'label' => ___('Category'), 'empty' => ___(' --- None --- ')));?>
                        <div style="padding-top: 22px;">
                            <input type="button" class="button primary" id="ObjItemListRelProdsFltrSearch" value="<?php ___e('Search')?>" />
                        </div>
                    </div>
                    <div id="rel_prods_results" class="input text" style="height: 200px; overflow-y: scroll;"></div>
                    <div id="rel_prods_present" class="input text" style="height: 200px; overflow-y: scroll;"></div>
                    <script>
                        $('#ObjItemListRelProdsFltrSearch').click(function(){
                            $('#rel_prods_results').load('/admin/catalog/item/pbl_related/?fltr_eqorrel__base_id=' + $('#ObjItemListRelProdsFltr2').val() + '&fltr_lk__title=' + $('#ObjItemListRelProdsFltr1').val());
                        });
                        $('#rel_prods_present').load('/admin/catalog/item/pbl_related/1/<?php e($this->data['ObjItemList']['id'])?>');
                    </script>
                </div>
                <div id="node-9-1">
                    <div class="n4 cl">
                        <?php echo $this->Form->input('rel_prods_fltr1-1', array('label' => ___('Title')));?>
                        <?php if(!empty($bases)) echo $this->Form->input('rel_prods_fltr2-1', array('options' => $bases, 'label' => ___('Category'), 'empty' => ___(' --- None --- ')));?>
                        <div style="padding-top: 22px;">
                            <input type="button" class="button primary" id="ObjItemListRelProdsFltrSearch-1" value="<?php ___e('Search')?>" />
                        </div>
                    </div>
                    <div id="rel_prods_results_similar" class="input text" style="height: 200px; overflow-y: scroll;"></div>
                    <div id="rel_prods_present_similar" class="input text" style="height: 200px; overflow-y: scroll;"></div>
                    <script>
                        $('#ObjItemListRelProdsFltrSearch-1').click(function(){
                            $('#rel_prods_results_similar').load('/admin/catalog/item/pbl_similar/?fltr_eqorrel__base_id=' + $('#ObjItemListRelProdsFltr2-1').val() + '&fltr_lk__title=' + $('#ObjItemListRelProdsFltr1-1').val());
                        });
                        $('#rel_prods_present_similar').load('/admin/catalog/item/pbl_similar/1/<?php e($this->data['ObjItemList']['id'])?>');
                    </script>
                </div>
                <div id="node-3">
                    <?php echo $this->Form->input('order_id', array('label' => ___('Order'), 'type' => 'text'));?>
                    <?php echo $this->Form->input('status', array('label' => ___('Status'), 'options' => ws_vh()));?>
                    <?php echo $this->Form->input('created', array('label' => ___('Date')));?>
                </div>
                <div id="node-4">
                    <?php echo $this->Layout->__input('meta_title', array('label' => ___('Title'), 'type' => 'textarea'));?>
                    <?php echo $this->Layout->__input('meta_desc', array('label' => ___('Description'), 'type' => 'textarea'));?>
                </div>
                <div id="node-6">
                    <?php echo $this->Form->input('ObjItemList.data.template', array('label' => ___('Template'), 'options' => Configure::read('CMS.layouts')));?>
                    <?php echo $this->Form->input('ObjItemList.data.template_type', array('label' => ___('Template Type')));?>
                </div>
            </div>
        </div>
        <div class="nw-table-footer">
            <?php echo $this->Layout->form_save();?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>

<div class="clear"></div>

