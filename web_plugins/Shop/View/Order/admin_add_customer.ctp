<?php if(!empty($append)):?>
    $('[name="data[ModOrder][userid]"]').val('<?php e($user['User']['id'])?>');
    $('[name="data[ModOrder][data_checkout][lang]"]').val('<?php e(!empty($checkout['ModOrder']['data']['data_checkout']['lang']) ? $checkout['ModOrder']['data']['data_checkout']['lang'] : Configure::read('Config.language'))?>');
    <?php if(!empty($checkout)):?>
        <?php foreach($checkout['ModOrder']['data']['data_checkout'] as $key => $val):?>
            $('[name="data[ModOrder][data_checkout][<?php e($key)?>]"]').val('<?php e($val)?>');
        <?php endforeach;?>
        <?php foreach($checkout['ModOrder']['data']['data_shipping'] as $key => $val):?>
            $('[name="data[ModOrder][data_shipping][<?php e($key)?>]"]').val('<?php e($val)?>');
        <?php endforeach;?>
        
    <?php else:?>
        $('[name="data[ModOrder][data_checkout][name]"]').val('<?php e($user['User']['username'])?>');
        $('[name="data[ModOrder][data_checkout][email]"]').val('<?php e($user['User']['email'])?>');
    <?php endif;?>
    ajx_win_close();
<?php elseif(!empty($search)):?>
    <table>
    <?php foreach($items as $item):?>
        <tr>
            <td style="width: 200px; text-align: left;"><?php et($item['User']['username'], 30)?></td>
            <td style="width: 200px; text-align: left;"><?php et($item['User']['usermail'], 30)?></td>
            <td></td>
            <td style="width: 20px;">
                <a onclick="$.get('/admin/shop/order/add_customer?id=<?php e($item['User']['id'])?>', function(data){eval(data);});" class="ico ico_add"></a>
            </td>
        </tr>
    <?php endforeach;?>
    </table>
<?php else:?>
    <div style="width: 800px;" class="nw-table">
        <div class="nw-table-content">
        <form class="no-enter">
        <div class="n3 cl">
            <?php echo $this->Form->input('customer_fltr1', array('label' => ___('Name')));?>
            <?php echo $this->Form->input('customer_fltr2', array('label' => ___('Email')));?>
            <div style="padding-top: 24px;">
                <input type="button" class="button primary" id="FltrSearch" value="<?php ___e('Search')?>" />
            </div>
        </div>
        <div id="customer_results" class="input text" style="height: 400px; overflow-y: scroll; margin-top: 5px;"></div>
        <script>
            $('#FltrSearch').click(function(){
                $('#customer_results').load('/admin/shop/order/add_customer?search=1' + '&fltr_lk__username=' + encodeURIComponent($('[name="data[customer_fltr1]"]').val()) + '&fltr_lk__usermail=' + encodeURIComponent($('[name="data[customer_fltr2]"]').val()));
            });
        </script>
        </form>
        </div>
    </div>
<?php endif;?>