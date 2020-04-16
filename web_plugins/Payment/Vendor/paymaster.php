<?php
include_once ('PaymentGateway.php');

class PaymentMake extends PaymentGateway
{
    
    public function makePayment($_data = array()){
        
        header("Location: /shop/checkout/pay/{$_data['pay_id']}/{$_data['transaction']['ModTransaction']['extra_id']}/{$_data['transaction']['ModTransaction']['code']}");
        exit;
    }
    
    public function getTransId(){
        return trim($_GET['account']);
    }

    public function checkPayment($_data = array()){
        // http://rozetka.ro/payment/callback/paymaster/?command=pay&txn_id=12345679&txn_date=20050815120133&account=1602502378347176&sum=10.45
        // http://rozetka.ro/payment/callback/paymaster/?command=check&txn_id=12345679&txn_date=20050815120133&account=1602502378347176&sum=10.45
        
        $check_ip = explode(',', $_data['pay']['ip']);

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $error = null;
        if(!in_array($ip, $check_ip)) $error = 'Wrong IP';
        if(strpos($_data['transaction']['ModTransaction']['paid_ext_id'], "[{$_GET['txn_id']},") !== false) $error = 'Already used txn_id';
        if($_data['transaction']['ModTransaction']['status'] != '0') $error = 'Already paid';
        if((($_data['transaction']['ModTransaction']['amount'] - $_data['transaction']['ModTransaction']['paid_amount']) + 50) < $_GET['sum']) $error = 'Wrong amount';
        if(empty($_data['transaction'])) $error = 'Wrong account';
        
        if(!$error){
            if($_GET['command'] == 'check'){
                return array('status' => '0');
            }
            if($_GET['command'] == 'pay'){
                if(($_data['transaction']['ModTransaction']['amount'] - $_data['transaction']['ModTransaction']['paid_amount']) <= $_GET['sum']){
                    return array('status' => '1', 'id' => $_data['transaction']['ModTransaction']['code'], 'ext_id' => $_GET['txn_id'], 'amount' => $_GET['sum']);
                } else {
                    return array('status' => '2', 'id' => $_data['transaction']['ModTransaction']['code'], 'ext_id' => $_GET['txn_id'], 'amount' => $_GET['sum']);
                }
            }
        } else {
            exit("<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <response>
            <osmp_txn_id>{$_GET['txn_id']}</osmp_txn_id>
            <result>7</result>
            <comment>{$error}</comment>
            </response>");            
        }
    }
    
    public function afterPayment(){
        exit("<?xml version=\"1.0\" encoding=\"UTF-8\"?>
        <response>
        <osmp_txn_id>{$_GET['txn_id']}</osmp_txn_id>
        <result>0</result>
        <sum>{$_GET['sum']}</sum>
        <comment>{$error}</comment>
        </response>");  
    }

}