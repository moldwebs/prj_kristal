<?php if(empty($block['data'])) $block['data'] = $tpl->get('/catalog/specification/get_list/active')?>

<form action="<?php echo $this->here . '#products'?>" method="GET">
    <input type="hidden" name="fltr_lk__title" value="<?php e($_GET["fltr_lk__title"])?>" />
    <!--<input type="hidden" name="fltr_eq__base_id" value="<?php e($_GET["fltr_eq__base_id"])?>" />-->
    <input type="hidden" name="fltr_eqorrel__extra_1" value="<?php e($_GET["fltr_eqorrel__extra_1"])?>" />


    <?php if(!empty($base)) $bases = $tpl->get('/catalog/base/get_list/' . $base['ObjItemTree']['id'])?>
    <?php if(!empty($bases)):?>
        <div class="widget">
            <h4><?php ___e('Category')?></h4>
            <ul class="links-lists">
                <?php foreach($bases as $key => $val):?>
                <li>
                    <a href="<?php eurl($val['ObjItemTree']['alias'])?>">
                        <div class="clearfix"><span class="pull-left"><?php e($val['ObjItemTree']['title'])?></span></div>
                    </a>
                </li>
                <?php endforeach;?>
            </ul>
        </div>
    <?php endif;?>

    <input type="hidden" name="fltr_eqmin__price_conv" value="<?php e($_GET["fltr_eqmin__price_conv"] ? $_GET["fltr_eqmin__price_conv"] : '0')?>" />
    <input type="hidden" name="fltr_eqmax__price_conv" value="<?php e($_GET["fltr_eqmax__price_conv"] ? $_GET["fltr_eqmax__price_conv"] : ($base['ObjItemTree']['data']['fltr_price_max'] > 0 ? $base['ObjItemTree']['data']['fltr_price_max'] : ($fltr_max_price > 0 ? $fltr_max_price : 1000)) )?>" />
    <div class="widget">
        <h4><?php ___e('Price Filter')?></h4>
            <div class="form-group clearfix">
                <div id="slider-price"></div>
                <p class="padT20">
                    <input type="text" id="price" readonly style="border:0; color:#f6931f; font-weight:bold;">
                </p>
            </div>
    </div>

    <?php if(!empty($fltr_manufacturers)):?>
        <div class="widget">
            <h4><?php ___e('Brand')?></h4>
            <ul class="links-lists">
                <?php foreach($fltr_manufacturers as $key => $val):?>
                    <li><input type="checkbox" id="fltr_eq__extra_2_<?php e($key)?>" value="<?php e($key)?>" name="fltr_eq__extra_2[]" <?php e(in_array($key, $_GET["fltr_eq__extra_2"]) ? 'checked="checked"' : null)?>><label for="fltr_eq__extra_2_<?php e($key)?>"> <?php e($val)?></label></li>
                <?php endforeach;?>
            </ul>
        </div>
    <?php endif;?>

    <?php if(!empty($block['data'])):?>
    
    <?php foreach($block['data'] as $specif):?>
        <?php if($specif['Specification']['data']['in_filter'] != '1') continue;?>
        <?php if($specif['Specification']['tp_fltr'] == 'select'):?>

            <div class="widget">
                <h4><?php e($specif['Specification']['ctitle'])?></h4>
                <ul class="links-lists">
                    <?php foreach($specif['SpecificationValue'] as $key => $val):?>
                        <li>
                        <input type="checkbox" id="fltr_<?php e("fltr_relval__specification{$specif['Specification']['id']}")?>_<?php e($key)?>" value="<?php e($key)?>" name="<?php e("fltr_relval__specification{$specif['Specification']['id']}")?>[]" <?php e(in_array($key, $_GET["fltr_relval__specification{$specif['Specification']['id']}"]) ? 'checked="checked"' : null)?> />
                        <label for="fltr_<?php e("fltr_relval__specification{$specif['Specification']['id']}")?>_<?php e($key)?>"> <?php e($val)?></label></li>
                    <?php endforeach;?>
                </ul>
            </div>

        <?php elseif($specif['Specification']['tp_fltr'] == 'color'):?>

            <div class="widget">
                <h4><?php e($specif['Specification']['ctitle'])?></h4>
                <ul class="links-lists">
                    <?php foreach($specif['SpecificationValueImage'] as $key => $val):?>
                        <li>
                        <input type="checkbox" id="fltr_<?php e("fltr_relval__specification{$specif['Specification']['id']}")?>_<?php e($key)?>" value="<?php e($key)?>" name="<?php e("fltr_relval__specification{$specif['Specification']['id']}")?>[]" <?php e(in_array($key, $_GET["fltr_relval__specification{$specif['Specification']['id']}"]) ? 'checked="checked"' : null)?> />
                        <label style="background: #<?php e($val['color'])?>;" for="fltr_<?php e("fltr_relval__specification{$specif['Specification']['id']}")?>_<?php e($key)?>"> <?php e($val)?></label></li>
                    <?php endforeach;?>
                </ul>
            </div>
        <?php elseif($specif['Specification']['tp_fltr'] == 'range' && 1==2):?>
            <?php if(empty($specif['SpecificationMaxValue'])) continue;?>
            <div class="list-group-item"><strong><?php e($specif['Specification']['title'])?></strong></div>
            <div class="list-group-item slider-range-box">
                <input type="hidden" name="fltr_relvalmin__specification<?php e($specif['Specification']['id'])?>" value="<?php e($_GET["fltr_relvalmin__specification{$specif['Specification']['id']}"])?>" />
                <input type="hidden" name="fltr_relvalmax__specification<?php e($specif['Specification']['id'])?>" value="<?php e($_GET["fltr_relvalmax__specification{$specif['Specification']['id']}"])?>" />
                <div data-change-min="fltr_relvalmin__specification<?php e($specif['Specification']['id'])?>" data-change-max="fltr_relvalmax__specification<?php e($specif['Specification']['id'])?>" data-label-reasult="" data-min="0" data-max="<?php e(!empty($specif['Specification']['data']['fltr_range_max']) ? $specif['Specification']['data']['fltr_range_max'] : (!empty($specif['SpecificationMaxValue']) ? $specif['SpecificationMaxValue'] : 10000))?>" data-unit="<?php e($specif['Specification']['measure'])?>" class="slider-range" data-value-min="<?php e($_GET["fltr_relvalmin__specification{$specif['Specification']['id']}"])?>" data-value-max="<?php e($_GET["fltr_relvalmax__specification{$specif['Specification']['id']}"])?>"></div>
                <div class="range-values"></div>
                <div class="range-values-textbox">
                <input type="text" id="fltr_relvalmin__specification<?php e($specif['Specification']['id'])?>" /> <?php e($specif['Specification']['measure'])?>
                -
                <input type="text" id="fltr_relvalmax__specification<?php e($specif['Specification']['id'])?>" /> <?php e($specif['Specification']['measure'])?>
                </div>
            </div>
        <?php endif;?>
    <?php endforeach;?>    

<?php endif;?>


</form>

<script>
document.addEventListener('DOMContentLoaded', function(){
    if ($("#slider-price").length) {
        $("#slider-price").slider({
            range: true,
            min: 0,
            max: <?php e($base['ObjItemTree']['data']['fltr_price_max'] > 0 ? $base['ObjItemTree']['data']['fltr_price_max'] : ($fltr_max_price > 0 ? $fltr_max_price : 1000))?>,
            values: [$('[name="fltr_eqmin__price_conv"]').val(), $('[name="fltr_eqmax__price_conv"]').val()],
            slide: function(event, ui) {
                $("#price").val(ui.values[0] + "<?php e(Configure::read('Obj.currency.title'))?>" + " - " + ui.values[1] + "<?php e(Configure::read('Obj.currency.title'))?>");
            },
            stop: function(event, ui) {
                $('[name="fltr_eqmin__price_conv"]').val(ui.values[0]);
                $('[name="fltr_eqmax__price_conv"]').val(ui.values[1]).trigger('change');
            }
        });

        $("#price").val($("#slider-price").slider("values", 0) + " <?php e(Configure::read('Obj.currency.title'))?>" +
            " - " + $("#slider-price").slider("values", 1) + " <?php e(Configure::read('Obj.currency.title'))?>");
    }
    $('[name^="fltr_"]').change(function() {
        $(this).parents('form:first').submit();
    });
});
</script>