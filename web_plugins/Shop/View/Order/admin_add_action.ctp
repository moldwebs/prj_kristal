<?php if(!empty($append)):?>
        <tr>
            <td style="width: 70px;" align="center"><?php e(date_stl_1($_item['created']))?></td>
            <td style="width: 110px;"><?php eth($_item['Operator']['username'], 50)?></td>
            <td style="width: 200px;">
                <?php if(!empty($_item['data']['action'])):?>
                    <?php ___e($_item['data']['action'])?>: <?php ___e($_item['data']['value'])?>
                <?php endif;?>
            </td>
            <td title="<?php eth($_item['message'], 500)?>">
                <?php eth($_item['message'], 100)?>
            </td>
            <td style="text-align: center; width: 40px;">
                <?php echo $this->Form->input('ModOrder.actions.' . $_item['id'] . '.message', array('type' => 'hidden', 'value' => $_item['message']));?>
                <?php echo $this->Form->input('ModOrder.actions.' . $_item['id'] . '.created', array('type' => 'hidden', 'value' => $_item['created']));?>
                <?php echo $this->Form->input('ModOrder.actions.' . $_item['id'] . '.operator_id', array('type' => 'hidden', 'value' => $_item['operator_id']));?>
                <?php echo $this->Form->input('ModOrder.actions.' . $_item['id'] . '.type', array('type' => 'hidden', 'value' => $_item['type']));?>
                <?php echo $this->Form->input('ModOrder.actions.' . $_item['id'] . '.data', array('type' => 'hidden', 'value' => json_encode($_item['data'])));?>
                <a onclick="$(this).parent().parent().remove();" class="ico ico_del" href="javascript:void(0)"></a>
            </td>
        </tr>
<?php else:?>
    <div style="width: 400px;">
        <div class="nw-table-content">
        <form class="no-enter" id="add_action">
        <div class="n1 cl">
            <?php echo $this->Form->input('status', array('label' => ___('Status'), 'options' => $sys_order_statuses, 'empty' => ''));?>
            <?php echo $this->Form->input('comment', array('label' => ___('Comment'), 'required' => 'required'));?>
            <div style="clear: both;"></div>
            <div class="submit">
                <input type="button" class="button primary" id="BtnSubmit" value="<?php ___e('Submit')?>" />
            </div>
        </div>
        <script>
            $('#BtnSubmit').click(function(){
                if($('#add_action [name="data[comment]"]').val() != ''){
                    $.post('/admin/shop/order/add_action', $('#add_action').serialize(), function(data){$('#order_action').prepend(data);ajx_win_close();});
                }
                if($('#add_action [name="data[status]"]').val() != ''){
                    $('#ModOrderOnstatus').val($('#add_action [name="data[status]"]').val());
                }
            });
        </script>
        </form>
        </div>
    </div>
<?php endif;?>