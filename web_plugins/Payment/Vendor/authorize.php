<?php
include_once ('PaymentGateway.php');

class PaymentMake extends PaymentGateway
{
    
    public function makePayment($_data = array()){
        
        $x_fp_timestamp = time();
        
        parent::submitPayment('https://secure.authorize.net/gateway/transact.dll', array(
            'x_Receipt_Link_URL' => htmlspecialchars(FULL_BASE_URL . $_data['transaction']['ModTransaction']['data']['back_url']),
            'x_Relay_URL' => htmlspecialchars(FULL_BASE_URL . '/payment/callback/authorize'),
            'x_Description' => $_data['transaction']['ModTransaction']['description'],
            'x_Amount' => $_data['transaction']['ModTransaction']['amount'],
            'x_Invoice_num' => $_data['transaction']['ModTransaction']['code'],
            'x_Version' => '3.0',
            'x_Show_Form' => 'PAYMENT_FORM',
            'x_Relay_Response' => 'TRUE',
            'x_Login' => $_data['pay']['id'],
            'x_fp_sequence' => $_data['transaction']['ModTransaction']['code'],
            'x_fp_timestamp' => $x_fp_timestamp,
            'x_fp_hash' => $this->hmac($_data['pay']['key'], $_data['pay']['id'] . '^' . $_data['transaction']['ModTransaction']['code'] . '^' . $x_fp_timestamp . '^' . $_data['transaction']['ModTransaction']['amount'] . '^'),
        ));
        
    }
    
    public function checkPayment($_data = array()){
        
	    foreach ($_POST as $field=>$value)
		{
			$ipnData["$field"] = $value;
		}

        $invoice    = intval($ipnData['x_invoice_num']);
        $pnref      = $ipnData['x_trans_id'];
        $amount     = doubleval($ipnData['x_amount']);
        $result     = intval($ipnData['x_response_code']);
        $respmsg    = $ipnData['x_response_reason_text'];

        $md5source  = $_data['pay']['key'] . $_data['pay']['id'] . $ipnData['x_trans_id'] . $ipnData['x_amount'];
        $md5        = md5($md5source);

		if ($result == '1' && strtoupper($md5) == $ipnData['x_MD5_Hash']){
		 	return array('status' => '1', 'id' => $ipnData['x_invoice_num'], 'ext_id' => $ipnData['x_trans_id'], 'amount' => $ipnData['x_amount']);
		} else {
            return array('status' => '0');
		}
    }
    
    public function afterPayment(){

    }

    private function hmac ($key, $data)
    {
       $b = 64; // byte length for md5

       if (strlen($key) > $b) {
           $key = pack("H*",md5($key));
       }

       $key  = str_pad($key, $b, chr(0x00));
       $ipad = str_pad('', $b, chr(0x36));
       $opad = str_pad('', $b, chr(0x5c));
       $k_ipad = $key ^ $ipad ;
       $k_opad = $key ^ $opad;

       return md5($k_opad  . pack("H*", md5($k_ipad . $data)));
    }

}