<?php
include_once ('PaymentGateway.php');

class PaymentMake extends PaymentGateway
{
    
    public function makePayment($_data = array()){
        
        parent::submitPayment('https://www.2checkout.com/checkout/purchase', array(
            'sid' => $_data['pay']['id'], 
            'cart_order_id' => $_data['transaction']['ModTransaction']['code'],
            'total' => $_data['transaction']['ModTransaction']['amount'],
            'tco_currency' => $_data['transaction']['ModTransaction']['currency'],
            'x_Receipt_Link_URL' => htmlspecialchars(FULL_BASE_URL . '/payment/callback/2co'),
        ));
        
    }
    
    public function checkPayment($_data = array()){

        foreach ($_POST as $field=>$value)
        {
            $ipnData["$field"] = $value;
        }

        $vendorNumber   = ($ipnData["vendor_number"] != '') ? $ipnData["vendor_number"] : $ipnData["sid"];
        $orderNumber    = $ipnData["order_number"];
        $orderTotal     = $ipnData["total"];

        $key = strtoupper(md5($_data['pay']['key'] . $vendorNumber . $orderNumber . $orderTotal));

        if($ipnData["key"] == $key || $ipnData["x_MD5_Hash"] == $key)
        {
            return array('status' => '1', 'id' => $ipnData['cart_order_id'], 'ext_id' => $ipnData['order_number'], 'amount' => $ipnData['total']);
        } else {
            return array('status' => '0');
        }
    }
    
    public function afterPayment(){

    }

}