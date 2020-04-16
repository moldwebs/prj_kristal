<div class="product-sorting">
      <div class="col-md-2 col-sm-3 col-xs-12">
         <ul class="sorting-product-option hidden-xs">
            <li>
                  <a class="<?php e($tpltoggle['catalog_view'] == 'grid' || empty($tpltoggle['catalog_view']) ? 'selected' : null)?>" href="/system/toggle/catalog_view/grid"><span class="fa fa-th"></span></a>
            </li>
            <li>
                  <a class="<?php e($tpltoggle['catalog_view'] == 'list' ? 'selected' : null)?>" href="/system/toggle/catalog_view/list"><span class="fa fa-th-list"></span></a>
            </li>
         </ul>
      </div>
      <div class="col-md-6 col-sm-5 col-xs-12">
         <div class="row">
            <form>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                     <select name="orderby" class="orderby" onchange="window.location = this.value">
                        <option <?php if($tpltoggle['catalog_order'] == 'created_desc' || empty($tpltoggle['catalog_order'])) echo 'selected="selected"'?> value="/system/toggle/catalog_order/created_desc"><?php ___e('Sort by newness')?></option>
                        <option <?php if($tpltoggle['catalog_order'] == 'price_asc') echo 'selected="selected"'?> value="/system/toggle/catalog_order/price_asc"><?php ___e('Price: Lowest first')?></option>
                        <option <?php if($tpltoggle['catalog_order'] == 'price_desc') echo 'selected="selected"'?> value="/system/toggle/catalog_order/price_desc"><?php ___e('Price: Highest first')?></option>
                        <option <?php if($tpltoggle['catalog_order'] == 'title_asc') echo 'selected="selected"'?> value="/system/toggle/catalog_order/title_asc"><?php ___e('Product Name: A to Z')?></option>
                        <option <?php if($tpltoggle['catalog_order'] == 'title_desc') echo 'selected="selected"'?> value="/system/toggle/catalog_order/title_desc"><?php ___e('Product Name: Z to A')?></option>
                     </select>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                     <select name="countby" class="countby" onchange="window.location = this.value">
                        <option <?php if($tpltoggle['catalog_limit'] == '9' || empty($tpltoggle['catalog_limit'])) echo 'selected="selected"'?> value="/system/toggle/catalog_limit/9">9</option>
                        <option <?php if($tpltoggle['catalog_limit'] == '18') echo 'selected="selected"'?> value="/system/toggle/catalog_limit/18">18</option>
                        <option <?php if($tpltoggle['catalog_limit'] == '100') echo 'selected="selected"'?> value="/system/toggle/catalog_limit/100">100</option>
                     </select>
                  </div>
            </form>
         </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
         <p class="product-result-count">
            <?php echo $this->Paginator->counter(___('Page {:page} of {:pages}, showing {:current} records out of {:count}'));?>
         </p>
      </div>
</div>