<?php
$sys_payments_full = $this->ObjItemList->find('allindex', array('tid' => 'payment'));
foreach($sys_payments_full as $key => $val){
    if(Configure::read("CMS.payment_type.{$val['ObjItemList']['code']}.internal") == '1') unset($sys_payments_full[$key]);
}
$this->set('sys_payments_full', $sys_payments_full);
if(!empty($sys_payments_full)) foreach($sys_payments_full as $sys_payment_full){
    $sys_payments[$sys_payment_full['ObjItemList']['id']] = $sys_payment_full['ObjItemList']['title'];
}
$this->set('sys_payments', $sys_payments);
if(!empty($sys_payments_full)) foreach($sys_payments_full as $sys_payment_full){
    $sys_payments_ready[$sys_payment_full['ObjItemList']['code']] = $sys_payment_full['ObjItemList']['title'];
}
$this->set('sys_payments_ready', $sys_payments_ready);
