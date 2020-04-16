<?php $ws_model = 'ObjItemList'?>

<div class="grid_4">
    <div class="nw-table">
        <?php echo $this->Form->create($ws_model, array('type' => 'get'));?>
        <div class="nw-table-title"><?php ___e('Filters')?></div>
        <div class="nw-table-content pd">
            <?php echo $this->Form->input('fltr_lk__title', array('label' => ___('Title')));?>
        </div>
        <div class="nw-table-footer">
            <?php echo $this->Form->button(___('Search'), array('type' => 'submit', 'class' => 'button primary'));?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>

<div class="grid_12">
    <div class="nw-table">
        <form action="<?php echo $this->Html->url(array('action' => 'table_actions'))?>" method="POST">
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?></div>
            <div class="fr">
            </div>
        </div>
        <div class="nw-table-content pd">
            <?php if(!empty($items)):?>
            <table>
                <thead><tr>
                    <th style="width: 25px;"><input type="checkbox" class="nw-table-check" /></th>
                    <th style="width: 25px;"></th>
                	<th style="text-align: left;"><?php ___e('Title');?></th>
                	<th style="width: 100px;"><?php ___e('Date');?></th>
                    <th style="width: 50px;"></th>
                </tr></thead>
                <tbody>
                <?php foreach ($items as $item):?>
                	<tr>
                        <td style="text-align: center;"><input type="checkbox" name="data[item][]" value="<?php e(base64_encode($item['title']))?>" /></td>
                		<td style="text-align: center;">
                            <?php if(in_array(ws_ext($item['title']), ws_ext_img())):?>
                                <a target="_blank" class="ico ico_image-resize img_preview" preview="/<?php e($item['title'])?>" href="/<?php e($item['title'])?>"></a>
                            <?php else:?>
                                <a target="_blank" class="ico img_preview" preview="/img/ext/<?php e(ws_ext($item['title']))?>.png" href="/<?php e($item['title'])?>"><img height="16px" src="/img/ext/<?php e(ws_ext($item['title']))?>.png" /></a>
                            <?php endif;?>
                        </td>
                		<td>
                            <div><?php et($item['title'], 60)?></div>
                		</td>
                        <td style="text-align: center;">
                            <?php e(date_stl_1($item['created']))?>
                        </td>
                		<td style="text-align: center;">
                            <?php echo $this->Html->link('', array('action' => 'delete', base64_encode($item['title'])), array('class' => 'ico ico_del', 'title' => ___('Delete'), 'confirm' => ___('Confirm your action.'))); ?>
                        </td>
                	</tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else:?>
                <?php echo $this->element('/admin/no_results')?>
            <?php endif;?>
        </div>
        <div class="nw-table-footer">
            <div class="fl">
                <select class="button-select" name="data[table-action]">
                    <option><?php ___e('Choose an action')?>...</option>
                    <option value="remove"><?php ___e('Remove')?></option>
                </select>
                <input type="submit" value="<?php ___e('Apply to selected')?>" class="button">
            </div>
            <div class="fr">
            </div>
        </div>
        </form>
    </div>
</div>

<div class="clear"></div>
