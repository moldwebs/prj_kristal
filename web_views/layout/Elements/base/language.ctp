<?php if(!empty($cms['languages']) && count($cms['languages']) > 1):?>
   <select onchange="window.location = this.value">
      <?php foreach($cms['languages'] as $key => $val):?>
         <option <?php echo ($cms['language']['code'] == $val['code'] ? 'selected="selected"' : '')?> value="<?php e($val['url'])?>"><?php e($val['ltitle'])?></option>
      <?php endforeach;?>
   </select>
<?php endif;?>
