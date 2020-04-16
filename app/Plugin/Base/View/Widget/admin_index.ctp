<div class="grid_12">
    <div class="nw-table">
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?></div>
            <div class="fr">
            </div>
        </div>
        <div class="nw-table-content pd">
            <?php if(!empty($items)):?>
            <table>
                <tbody>
                <?php foreach ($items as $item):?>
                	<tr>
                		<td>
                            <div><?php et($item['title'], 60)?></div>
                		</td>
                		<td style="text-align: center; width: 60px;">
                            <?php echo $this->Html->link('', array('action' => 'status', $item['id']), array('class' => 'ajx_toggle ico ico_visible' . ($item['status'] == '1' ? '' : ' off'), 'title' => ___('Show/Hide'))); ?>
                            &nbsp;
                            <?php echo $this->Html->link('', array('action' => 'edit', $item['id']), array('class' => 'ico ico_edit', 'title' => ___('Edit'))); ?>
                        </td>
                	</tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else:?>
                <?php echo $this->element('/admin/no_results')?>
            <?php endif;?>
        </div>
    </div>
</div>

<div class="clear"></div>
