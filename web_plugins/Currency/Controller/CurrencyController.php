<?php
class CurrencyController extends CurrencyAppController {
    
    public function beforeFilter() {
        parent::beforeFilter();

        $this->set('currencies', array('AMD' => 'Armenian Dram', 'AUD' => 'Australian Dollar', 'AZN' => 'Azerbaijanian Manat', 'BYR' => 'Belarussian Ruble', 'BGN' => 'Bulgarian Lev', 'CAD' => 'Canadian Dollar', 'CNY' => 'Chinese Yuan', 'HRK' => 'Croatian Kuna', 'CZK' => 'Czech Koruna', 'DKK' => 'Danish Krone', 'EEK' => 'Estonian Kroon', 'EUR' => 'Euro', 'GEL' => 'Georgian Lar', 'HUF' => 'Hungarian Forint', 'ISK' => 'Iceland Krona', 'JPY' => 'Japanese Yen', 'KZT' => 'Kazakhstan Tenge', 'KWD' => 'Kuwaiti Dinar', 'KGS' => 'Kyrgyzstan Som', 'LVL' => 'Latvian Lats', 'LTL' => 'Lithuanian Litas', 'MDL' => 'Moldavian Leu', 'NZD' => 'New Zealand Dollar', 'NOK' => 'Norwegian Krone', 'PLN' => 'Polish Zloty', 'GBP' => 'Pound Sterling', 'RON' => 'Romanian Leu', 'RUB' => 'Russian Ruble', 'RSD' => 'Serbian Dinar', 'ILS' => 'Shekel Israelit', 'XDR' => 'Special Drawing Rights', 'SEK' => 'Swedish Krona', 'CHF' => 'Swiss Franc', 'TJS' => 'Tajikistan Somoni', 'TRY' => 'Turkish Lira', 'TMT' => 'Turkmenistan Manat', 'AED' => 'U.A.E. Dirham', 'UAH' => 'Ukraine Hryvnia', 'USD' => 'US Dollar', 'UZS' => 'Uzbekistan Sum'));
    
    }
    
    public function admin_table_actions(){
        $this->ModCurrency->table_actions($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Currency') . ' :: ' . ___('List'));
        
        $this->set('items', $this->paginate('ModCurrency', $this->Basic->filters('ModCurrency')));
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ModCurrency, true);
        
        if($id){
            $this->set('page_title', ___('Currency') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Currency') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }
    
	public function admin_delete($id = null){
	    $this->ModCurrency->delete($id);
        $this->Basic->back();
	}

	public function admin_set_default($id = null){
        $this->ModCurrency->updateAll(
            array("is_default" => '0')
        );
        
        $this->ModCurrency->toggle($id, 'is_default');

        exit();
	}

}
