<?php if(count(Configure::read('CMS.activelanguages')) > 1) foreach(Configure::read('CMS.activelanguages') as $_lng => $lng):?>
    <a onclick="$(this).parents('form:first').find('[div_lng]').hide();$(this).parents('form:first').find('[div_lng=\'<?php e(substr($_lng, 0, 2))?>\']').show();" class="button primary" style="text-transform: uppercase;"><?php ___e(substr($_lng, 0, 2))?></a>
<?php endforeach;?>
&nbsp;