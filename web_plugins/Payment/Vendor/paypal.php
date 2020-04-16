<?php
include_once ('PaymentGateway.php');

class PaymentMake extends PaymentGateway
{
    
    public function makePayment($_data = array()){
        
        parent::submitPayment('https://www.paypal.com/cgi-bin/webscr', array(
            'rm' => '2', 
            'cmd' => '_xclick',
            'business' => $_data['pay']['id'],
            'return' => htmlspecialchars(FULL_BASE_URL . $_data['transaction']['ModTransaction']['data']['back_url']),
            'cancel_return' => htmlspecialchars(FULL_BASE_URL . $_data['transaction']['ModTransaction']['data']['fail_url']),
            'notify_url' => htmlspecialchars(FULL_BASE_URL . '/payment/callback/paypal'),
            'amount' => $_data['transaction']['ModTransaction']['amount'],
            'currency_code' => $_data['transaction']['ModTransaction']['currency'],
            'item_name' => $_data['transaction']['ModTransaction']['description'],
            'item_number' => $_data['transaction']['ModTransaction']['code'],
        ));
        
    }
    
    public function checkPayment($_data = array()){
        
        $urlParsed = parse_url('https://www.paypal.com/cgi-bin/webscr');
        
		$postString = '';

		foreach ($_POST as $field=>$value)
		{
			$ipnData["$field"] = $value;
			$postString .= $field .'=' . urlencode(stripslashes($value)) . '&';
		}

		$postString .="cmd=_notify-validate"; // append ipn command

		// open the connection to paypal
		$fp = fsockopen($urlParsed[host], "80", $errNum, $errStr, 30);

		if(!$fp){
		   return array('status' => '0');
		} else {
		    $response = null;
			fputs($fp, "POST $urlParsed[path] HTTP/1.1\r\n");
			fputs($fp, "Host: $urlParsed[host]\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: " . strlen($postString) . "\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $postString . "\r\n\r\n");
			while(!feof($fp)) $response .= fgets($fp, 1024);
		 	fclose($fp);
		}
        
        if (eregi("VERIFIED", $response) && $ipnData['payment_status'] == 'Completed'){
            return array('status' => '1', 'id' => $ipnData['item_number'], 'ext_id' => $ipnData['invoice'], 'amount' => $ipnData['mc_gross']);
        } else {
            return array('status' => '0');
        }
    }
    
    public function afterPayment(){

    }

}