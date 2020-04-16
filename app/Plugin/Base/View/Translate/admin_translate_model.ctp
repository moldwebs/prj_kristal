<div class="grid_16">
    <div class="nw-table">
        <div class="nw-table-content">
            <?php if(!empty($items)):?>
            <table class="lst form-tp-2">
                <thead><tr>
                    <?php foreach(fcms_languages() as $key => $val):?>
                        <th>&nbsp;&nbsp;<?php e($val)?></th>
                    <?php endforeach;?>
                    <th width="25px"></th>
                </tr></thead>
                <tbody>
                <?php foreach ($items as $i => $item):?>
                    <?php if($item[$obj_name]['title__' . Configure::read('Config.language')] == '') $item[$obj_name]['title__' . Configure::read('Config.language')] = $item[$obj_name]['title']?>
                	<tr class="<?php echo ($i % 2) ? '' : 'altrow';?>">
                        <?php foreach(fcms_languages() as $key => $val):?>
                            <td align="left">
                                <div class="input text" style="padding: 0;">
                                    <input class="save_translate" type="text" name="data[Translate][<?php e($item[$obj_name]['id'])?>][<?php e($key)?>]" value="<?php e($item[$obj_name]['title__' . $key])?>" translate="<?php e(substr($key, 0, 2))?>" />
                                </div>
                            </td>
                        <?php endforeach;?>
                        <td><a class="act trnslt" href="javascript:void(0)"></a></td>
                	</tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else:?>
                <?php echo $this->element('admin/no_results')?>
            <?php endif;?>
        </div>
    </div>
</div>

<div class="clear"></div>

<script language="javascript">
    $('.save_translate').bind('change', function(){
        var obj = this;
        $.ajax({
            async:false,
            type:'post',
            data: $(obj).parent().parent().parent().find('input').serialize(),
            url: '/admin/creation/translates/translate_model/<?php e($pass_model)?>/<?php e($pass_tid)?>/',
        });
    });
    $('.trnslt').bind('click', function(){
        var orig_val = '';
        var orig_lng = '';
        
        $(this).parent().parent().find('input').each(function(index) {
            if(index == 0){
                orig_val = $(this).val();
                orig_lng = $(this).attr('translate');
            } else {
                var trnsl_obj = $(this);
                $.translate(orig_val, orig_lng, $(this).attr('translate'), function(translation){
                    $(trnsl_obj).val(translation);
                    $(trnsl_obj).trigger('change');
                });
            }
        });
     });
</script>