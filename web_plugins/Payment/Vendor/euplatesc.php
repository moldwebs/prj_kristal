<?php
include_once ('PaymentGateway.php');

class PaymentMake extends PaymentGateway
{
    
    public function makePayment($_data = array()){

        $dataAll = array(
			'amount'      => $_data['transaction']['ModTransaction']['amount'],                                                   //suma de plata
			'curr'        => 'RON',                                                   // moneda de plata
			'invoice_id'  => $_data['transaction']['ModTransaction']['extra_id'],  // numarul comenzii este generat aleator. inlocuiti cuu seria dumneavoastra
			'order_desc'  => $_data['transaction']['ModTransaction']['description'],                                            //descrierea comenzii
                     // va rog sa nu modificati urmatoarele 3 randuri
			'merch_id'    => $_data['pay']['id'],                                                    // nu modificati
			'timestamp'   => gmdate("YmdHis"),                                        // nu modificati
 			'nonce'       => md5(microtime() . mt_rand()),                            //nu modificati
			//'ExtraData'  => $_data['transaction']['ModTransaction']['code'],  // numarul comenzii este generat aleator. inlocuiti cuu seria dumneavoastra
        ); 
        
        $dataAll['fp_hash'] = strtoupper($this->euplatesc_mac($dataAll, $_data['pay']['key']));
        $dataAll['ExtraData'] = $_data['transaction']['ModTransaction']['code'];

        //print_r("KEY:" . $_data['pay']['key']);
        //print_r($dataAll);
        //print_r($_data);
        //exit;

        $dataAll['email'] = $_data['Checkout']['email'];


        //completati cu valorile dvs
        $dataBill = array(
                    'fname'	   => 'billing nume',      // nume
                    'lname'	   => 'billing prenume',   // prenume
                    'country'  => 'billing tara',      // tara
                    'company'  => 'billing company',   // firma
                    'city'	   => 'billing city',      // oras
                    'add'	   => 'billing adresa',    // adresa
                    'email'	   => 'billing email',     // email
                    'phone'	   => 'billing telefon',   // telefon
                    'fax'	   => 'billing fax',       // fax
        );
        $dataShip = array(
                    'sfname'       => 'shipping nume',     // nume
                    'slname'       => 'shipping prenume',  // prenume
                    'scountry'     => 'shipping tara',     // tara
                    'scompany'     => 'shipping company',  // firma
                    'scity'	       => 'shipping city',     // oras
                    'sadd'         => 'shipping add',      // adresa
                    'semail'       => 'shipping email',    // email
                    'sphone'       => 'shipping telefon',  // telefon
                    'sfax'	       => 'shipping fax',      // fax
        );

        //$data = array_merge($dataAll, $dataBill, $dataShip);
        $data = array_merge($dataAll);
        
        parent::submitPayment('https://secure.euplatesc.ro/tdsprocess/tranzactd.php', $data);
        
    }
    
    // http://url.com/payment/callback/euplatesc
    public function checkPayment($_data = array()){

        $zcrsp =  array (
            'amount'     => addslashes(trim(@$_POST['amount'])),  //original amount
            'curr'       => addslashes(trim(@$_POST['curr'])),    //original currency
            'invoice_id' => addslashes(trim(@$_POST['invoice_id'])),//original invoice id
            'ep_id'      => addslashes(trim(@$_POST['ep_id'])), //Euplatesc.ro unique id
            'merch_id'   => addslashes(trim(@$_POST['merch_id'])), //your merchant id
            'action'     => addslashes(trim(@$_POST['action'])), // if action ==0 transaction ok
            'message'    => addslashes(trim(@$_POST['message'])),// transaction responce message
            'approval'   => addslashes(trim(@$_POST['approval'])),// if action!=0 empty
            'timestamp'  => addslashes(trim(@$_POST['timestamp'])),// meesage timestamp
            'nonce'      => addslashes(trim(@$_POST['nonce'])),
            //'ExtraData'      => addslashes(trim(@$_POST['ExtraData'])),
        );
             
        $zcrsp['fp_hash'] = strtoupper($this->euplatesc_mac($zcrsp, $key));
        $zcrsp['ExtraData'] = addslashes(trim(@$_POST['ExtraData']));
    
        $fp_hash = addslashes(trim(@$_POST['fp_hash']));
        if($zcrsp['fp_hash'] === $fp_hash)	{
        // start facem update in baza de date
            if($zcrsp['action']=="0") {
                return array('status' => '1', 'id' => $zcrsp['ExtraData'], 'ext_id' => $zcrsp['ep_id'], 'amount' => $zcrsp['amount']);
                echo "Successfully completed";
            }
            else {
                return array('status' => '0');
                echo "Tranzaction failed" . $zcrsp['message'];
            }
        // end facem update in baza de date
        } else {
            return array('status' => '0');
            echo "Invalid signature";
        }
    }
    
    public function afterPayment(){
        
    }

     // ===========================================================================================
     function hmacsha1($key,$data) {
        $blocksize = 64;
        $hashfunc  = 'md5';
        
        if(strlen($key) > $blocksize)
          $key = pack('H*', $hashfunc($key));
        
        $key  = str_pad($key, $blocksize, chr(0x00));
        $ipad = str_repeat(chr(0x36), $blocksize);
        $opad = str_repeat(chr(0x5c), $blocksize);
        
        $hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $data))));
        return bin2hex($hmac);
     
     }
     function euplatesc_mac($data, $key)
     {
       $str = NULL;
     
       foreach($data as $d)
       {
            if($d === NULL || strlen($d) == 0)
             $str .= '-'; // valorile nule sunt inlocuite cu -
           else
             $str .= strlen($d) . $d;
       }
          
       // ================================================================
       $key = pack('H*', $key); // convertim codul secret intr-un string binar
       // ================================================================
     
     // echo " $str " ;
     
       return $this->hmacsha1($key, $str);
     }
     // ===========================================================================================

}