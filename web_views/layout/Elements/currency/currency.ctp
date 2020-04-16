<?php if(!empty($sys_currency) && count($sys_currency) > 1):?>
   <select onchange="window.location = this.value">
      <?php foreach($sys_currency as $key => $val):?>
         <option <?php echo ($cms['currency']['currency'] == $val['ModCurrency']['currency'] ? 'selected="selected"' : '')?> value="/system/toggle/currency/<?php e($val['ModCurrency']['currency'])?>"><?php e($val['ModCurrency']['currency'])?></option>
      <?php endforeach;?>
   </select>
<?php endif;?>