<?php $ws_model = 'CmsTranslate'?>
<div class="grid_12">
    <div class="nw-table">
        <form action="<?php echo $this->Html->url(array('action' => 'table_actions'))?>" method="POST">
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?></div>
            <div class="fr">
                <a onclick="$(this).parent().parent().parent().find('.ico_translate').each(function(){if($(this).parent().parent().find('#translate').val() == '') $(this).trigger('click')});" class="button"><?php ___e('Translate')?></a>
                <a onclick="$(this).parent().parent().parent().find('.clear').each(function(){$(this).trigger('click')});" class="button"><?php ___e('Clear')?></a>
            </div>
        </div>
        <div class="nw-table-content pd">
            <?php if(!empty($items)):?>
            <table>
                <thead><tr>
                	<th style="text-align: left;"><?php echo $this->Paginator->sort('key', ___('Title'));?></th>
                	<th style="text-align: left;"><?php echo $this->Paginator->sort('value', ___('Translate'));?></th>
                    <th style="width: 60px;"></th>
                </tr></thead>
                <tbody>
                <?php foreach ($items as $item):?>
                	<tr>
                		<td>
                            <div><?php et($item[$ws_model]['key'], 60)?></div>
                		</td>
                        <td>
                            <?php echo $this->Form->input('translate', array('div' => false, 'label' => false, 'value' => $item[$ws_model]['value'], 'onchange' => "$.get('/admin/base/translate/save/{$item[$ws_model]['id']}/', {value: this.value});"));?>
                        </td>
                		<td style="text-align: center;">
                            <a class="ico ico_translate" onclick="$.set_translate('<?php e(addslashes($item[$ws_model]['key']))?>', '<?php e(substr($item[$ws_model]['locale'], 0, 2))?>', $(this).parent().parent().find('#translate'));" title="<?php ___e('Translate')?>"></a>
                            &nbsp;
                            <a class="clear" style="display: none;" onclick="$(this).parent().parent().find('#translate').val('').trigger('change');" title="<?php ___e('Delete')?>"></a>
                            &nbsp;
                            <?php echo $this->Html->link('', array('action' => 'delete', $item[$ws_model]['id']), array('class' => 'ico ico_del', 'title' => ___('Delete'), 'confirm' => ___('Confirm your action.'))); ?>
                        </td>
                	</tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else:?>
                <?php echo $this->element('/admin/no_results')?>
            <?php endif;?>
        </div>
        </form>
    </div>
</div>

<div class="grid_4">
    <div class="nw-table">
        <?php echo $this->Form->create($ws_model, array('type' => 'file', 'action' => 'edit'));?>
        <div class="nw-table-title"><?php ___e('Translates')?> :: <?php ___e('Create')?></div>
        <div class="nw-table-content pd">
            <?php echo $this->Form->input('key', array('label' => ___('Content')));?>
            <?php foreach(Configure::read('CMS.activelanguages') as $_lng => $lng):?>
                <?php echo $this->Form->input('value.' . $_lng, array('label' => $lng . ' <a class="ico ico_translate link_inpt" onclick="$.set_translate($(\'#CmsTranslateKey\').val(), \''.substr($_lng, 0, 2).'\', $(this).parent().parent().find(\'input\'));" href="javascript:void(0)"></a>'));?>
            <?php endforeach;?>
        </div>
        <div class="nw-table-footer">
            <?php echo $this->Form->button(___('Save'), array('type' => 'submit', 'class' => 'button primary'));?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>

<div class="clear"></div>
