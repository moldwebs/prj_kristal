<div>

 <div><h1 class="page-heading"><?php e($title_for_action)?></h1></div>
    
<?php if(!empty($bases)):?>
<div>
    <?php foreach($bases as $key => $val):?>
        <span class="page-heading-link"><a href="/catalog/compare/<?php echo $key; ?>"><?php echo $val['title']; ?> <span>(<?php echo $val['count']; ?>)</span></a></span>
        &nbsp;&nbsp;&nbsp;
    <?php endforeach;?>
</div>
<?php endif;?>

<div>&nbsp;</div>
<?php if(!empty($items)):?>
<div class="compare-table">
   <table  class="table table-compare">
        <tr>
            <td style="width: 200px; padding-top: 308px;">
                <table class="table table-bordered">
                    <tr><td style="width: 200px;" class="compare-label"><?php ___e('Product Name')?></td></tr>
                    <tr><td class="compare-label"><?php ___e('Price')?></td></tr>
                    <tr><td class="compare-label"><?php ___e('Manufacturer')?></td></tr>
                    <tr><td class="compare-label"><?php ___e('Code')?></td></tr>
                    <?php foreach($specifications as $specification):?>
                        <tr><td class="compare-label <?php if($specification['Specification']['extra_1'] == '1') echo 'compare-label-big';?>"><?php e($specification['Specification']['title'])?></td></tr>
                    <?php endforeach;?>
                    <tr style="height: 50px;"><td class="compare-label"><?php ___e('Action')?></td></tr>
                </table>
            </td>
            <td style="max-width: 650px; overflow-x: auto;"><table style="width: 100%;"><tr>
            <?php foreach($items as $item):?>
                <td style="padding-right: 15px; border-top: none;">
                    <table class="table table-bordered">
                        <tr style="height: 300px;"><td style="text-align: center;"><a href="<?php eurl($item['ObjItemList']['alias'])?>"><img src="/getimages/200x200x0/thumb/<?php e($item['ObjOptAttachDef']['attach'])?>" alt="<?php eth($item['ObjItemList']['title'], 500)?>" /></a></td></tr>
                        <tr><td><?php eth($item['ObjItemList']['title'], 500)?></td></tr>
                        <tr><td class="price"><?php e($item['Price']['html_value'])?> <?php e($item['Price']['html_currency'])?></td></tr>
                        <tr><td><?php e($manufacturers[$item['ObjItemList']['extra_2']])?></td></tr>
                        <tr><td><?php e($item['ObjItemList']['code'])?></td></tr>
                        <?php foreach($specifications as $specification):?>
                            <tr><td title="<?php e($item['specifications_vals'][$specification['Specification']['id']])?>"><?php eth($item['specifications_vals'][$specification['Specification']['id']], 40)?></td></tr>
                        <?php endforeach;?>
                        <tr style="height: 50px;">
                            <td class="action">
                                <a class="button" href="/shop/basket/add/<?php e($item['ObjItemList']['id'])?>/0/1">
                                    <?php ___e('Add to cart')?>
                                </a>
                                &nbsp;&nbsp;&nbsp;
                                <a class="button" href="/users/collections/catalog_compare/<?php e($item['ObjItemList']['id'])?>">
                                    <?php ___e('Remove')?>
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            <?php endforeach;?>
            </tr></table></td>
        </tr>
    
    </table>
</div>
<?php endif;?>

</div>


 <style>
    .table-compare td{
        white-space: nowrap;
        overflow: hidden;
        height: 37px;
    }
</style>