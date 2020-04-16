<?php
class ModTransaction extends PaymentAppModel {
    
    public $useTable = 'wb_mod_transaction';
    public $nocache = '1';
    
    public $actsAs = array('Tid', 'Data');

}