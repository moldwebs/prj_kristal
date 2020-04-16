<?php
class ModCurrency extends CurrencyAppModel {
    
    public $useTable = 'wb_mod_currency';
    
    public $actsAs = array('Translate' => array('title'), 'Fields');
}