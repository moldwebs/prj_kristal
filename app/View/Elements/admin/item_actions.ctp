<?php if($type != '1'):?>
<?php echo $this->Html->link('', array('action' => $ws_act . 'status', $item[$ws_model]['id']), array('class' => 'ajx_toggle ico ico_visible' . ($item[$ws_model]['status'] == '1' ? '' : ' off'), 'title' => ___('Show/Hide'))); ?>
&nbsp;
<?php endif;?>
<?php echo $this->Html->link('', array('action' => $ws_act . 'edit', $item[$ws_model]['id']), array('class' => 'ico ico_edit', 'title' => ___('Edit'))); ?>
&nbsp;
<?php echo $this->Html->link('', array('action' => $ws_act . 'delete', $item[$ws_model]['id']), array('class' => 'ico ico_del', 'title' => ___('Delete'), 'confirm' => ___('Confirm your action.'))); ?>
