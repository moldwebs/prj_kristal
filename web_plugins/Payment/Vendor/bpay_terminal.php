<?php
include_once ('PaymentGateway.php');

class PaymentMake extends PaymentGateway
{
    
    public function makePayment($_data = array()){
        
        $min = '17100600';
        $max = '17100799';
        
        $dbi = ConnectionManager::getDataSource('default');
        
        $result = $dbi->query("SELECT description FROM wb_mod_transaction WHERE uid = '".CMS_UID."' AND pay_type = 'bpay_terminal' AND description > 0 ORDER BY id DESC LIMIT 1");
        if($result[0]['wb_mod_transaction']['description'] < $min || $result[0]['wb_mod_transaction']['description'] >= $max){
            $pay_id = $min;
        } else {
            $pay_id = $result[0]['wb_mod_transaction']['description'] + 1;
        }
        
        $dbi->query("UPDATE wb_mod_transaction SET description = '{$pay_id}' WHERE id = '{$_data['transaction']['ModTransaction']['id']}'");
        
        header("Location: /shop/checkout/payment/{$_data['pay_id']}/{$_data['transaction']['ModTransaction']['extra_id']}/{$_data['transaction']['ModTransaction']['code']}");
        exit;
    }
    
    public function checkPayment($_data = array()){
        $xmldata = base64_decode($_POST['data']);
        
        if($_POST['key'] != md5(md5($xmldata) . md5($_data['pay']['key']))) return false;
        
        $xmldata = simplexml_load_string($xmldata);
        
        if($xmldata->comand == "pay"){
            if($xmldata->advanced1 != 'bpay'){
                $dbi = ConnectionManager::getDataSource('default');
                $result = $dbi->query("SELECT `code` FROM wb_mod_transaction WHERE uid = '".CMS_UID."' AND pay_type = 'bpay_terminal' AND used = '0' AND description = '".mysql_escape_string($xmldata->order_id)."' ORDER BY id DESC LIMIT 1");
                if(!empty($result[0]['wb_mod_transaction']['code'])){
                    return array('status' => '1', 'id' => $result[0]['wb_mod_transaction']['code'], 'ext_id' => $xmldata->transid, 'amount' => $xmldata->amount);
                } else {
                    return array('status' => '0');
                }
            } else {
                return array('status' => '1', 'id' => $xmldata->order_id, 'ext_id' => $xmldata->transid, 'amount' => $xmldata->amount);
            }
        } else {
            return array('status' => '0');
        }
    }
    
    public function afterPayment(){
      echo "<?xml version='1.0' encoding=\"utf8\"?>"; 
      echo "<result>"; 
      echo "<code>100</code>"; 
      echo "<text>success</text>"; 
      echo "</result>"; 
    }

}