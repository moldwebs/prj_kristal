<?php foreach($items as $specif):?>
    <?php if($specif['Specification']['extra_1'] == '1'):?>
        <?php if($box == 'open'):?></div><?php endif;?>
        <?php if($fieldset == 'open'):?></fieldset><?php endif;?>
        <fieldset><legend><?php e($specif['Specification']['title'])?></legend>
        <?php $fieldset = 'open'?>
        <div class="n5 cl">
        <?php $box = 'open'?>
    <?php else:?>
        <?php if($box != 'open'):?><div class="n5 cl"><?php $box = 'open'?><?php endif;?>
        <?php if($specif['Specification']['extra_2'] == '3'):?>
            <?php echo $this->Form->input("RelationValue.specification.{$specif['Specification']['id']}", array('label' => $specif['Specification']['mtitle'], 'empty' => '', 'options' => ws_ny()));?>
        <?php else:?>
            <?php echo $this->Form->input("RelationValue.specification.{$specif['Specification']['id']}", array('label' => $specif['Specification']['mtitle'], (in_array($specif['Specification']['extra_2'], array('7')) ? 'multiple' : 'empty') => (in_array($specif['Specification']['extra_2'], array('7')) ? 'multiple' : ''), 'class' => (in_array($specif['Specification']['extra_2'], array('7')) ? 'multiselect' : null) . ($specif['Specification']['extra_2'] == '4' ? 'n_cl_large' : ''), 'options' => (in_array($specif['Specification']['extra_2'], array('6')) ? (!empty($specif['SpecificationValue']) ? array('add_new_value' => ___('Add new value')) + $specif['SpecificationValue'] : array('add_new_value' => ___('Add new value'))) : (!empty($specif['SpecificationValue']) ? $specif['SpecificationValue'] : (in_array($specif['Specification']['extra_2'], array('7')) ? array() : null))), 'depend' => $specif['Specification']['extra_4'], 'initselected' => $this->data['RelationValue']['specification'][$specif['Specification']['id']]));?>
        <?php endif;?>
    <?php endif;?>
<?php endforeach;?>
<?php if($box == 'open'):?></div><?php endif;?>
<?php if($fieldset == 'open'):?></fieldset><?php endif;?>
<script>
    $("input[id^=_RelationValueSpecification]").on("keypress keyup blur",function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    if($(".multiselect").length > 0) $(".multiselect").multiselect({header: false, selectedList: 4, noneSelectedText: '<?php ___e('Choose...')?>'});
    $('[id^=RelationValueSpecification]').bind('change', function(){
        var obj = this;
       if($(this).val() == 'add_new_value'){
            if(result = prompt('<?php ___e('Add new value')?>')){
                $.post("<?php e($this->Html->url(array('action' => 'value_edit', 'admin' => true)))?>", {'data[SpecificationValue][base_id]': $(this).attr('id').replace('RelationValueSpecification', ''), 'data[SpecificationValue][extra_4]': $('[name="data[RelationValue][specification]['+$(this).attr('depend')+']"]').val(), 'data[SpecificationValue][title]': result, 'data[SpecificationValue][status]': '1'}).done(function(data){
                    $(obj).append($('<option></option>').val(data).html(result));
                    $(obj).val(data).trigger('change');
                });
            } else {
                $(this).val('');
            }
       } 
    });
</script>
<script>
    $('[depend]').each(function(){
        if($(this).attr('depend') > 0){
            console.log($(this).attr('id'));
            $('#' + $('[name="data[RelationValue][specification]['+$(this).attr('depend')+']"]').attr('id')+',[name="data[RelationValue][specification]['+$(this).attr('depend')+'][]"]').attr('depend_of', $(this).attr('id')).bind('change', function(){
                 var tmp_obj_id = $(this).attr('depend_of');
                if($(this).val() != '' && $(this).val() != null){
                    $.getJSON('/<?php e(Configure::read('Config.before_tid'))?>/specification/get_depends/' + $(this).val(), function(data){
                        $('#' + tmp_obj_id).removeAttr('disabled');    
                        $('#' + tmp_obj_id + ' option[value!=""][value!="add_new_value"]').remove();
                        $.each(data, function(index,item) {
                           $('#' + tmp_obj_id).append("<option value=" + item.id + ">" + item.title + "</option>"); 
                        });
                        if($('#' + tmp_obj_id).attr('initselected')){
                            $('#' + tmp_obj_id).val(($('#' + tmp_obj_id).attr('initselected')).split(' '));
                        }
                        $('#' + tmp_obj_id).trigger('change'); 
                        if($('#' + tmp_obj_id).hasClass('multiselect')) $('#' + tmp_obj_id).multiselect('refresh');
                    });
                } else {
                    $('#' + tmp_obj_id).html('').attr('disabled', 'disabled').trigger('change');
                    if($('#' + tmp_obj_id).hasClass('multiselect')) $('#' + tmp_obj_id).multiselect('refresh');
                }
            }).trigger('change');
        }
    });
</script>
<input type="hidden" name="data[RelationRemove][specification]" value="1" />