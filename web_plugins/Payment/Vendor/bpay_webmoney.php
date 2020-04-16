<?php
include_once ('PaymentGateway.php');

class PaymentMake extends PaymentGateway
{
    
    public function makePayment($_data = array()){
        
        $xmldata = "<payment> 
        <type>1.2</type> 
        <merchantid>{$_data['pay']['id']}</merchantid> 
        <amount>{$_data['transaction']['ModTransaction']['amount']}</amount> 
        <description>{$_data['transaction']['ModTransaction']['description']}</description> 
        <method>webmoney</method> 
        <order_id>{$_data['transaction']['ModTransaction']['code']}</order_id> 
        <success_url>" . htmlspecialchars(FULL_BASE_URL . $_data['transaction']['ModTransaction']['data']['back_url']) . "</success_url> 
        <fail_url>" . htmlspecialchars(FULL_BASE_URL . $_data['transaction']['ModTransaction']['data']['fail_url']) . "</fail_url> 
        <callback_url>" . htmlspecialchars(FULL_BASE_URL . '/payment/callback/bpay') . "</callback_url> 
        <lang></lang> 
        <advanced1>bpay</advanced1> 
        <advanced2></advanced2> 
        </payment>";
        
        parent::submitPayment('https://www.bpay.md/user-api/payment1', array('key' => md5(md5($xmldata) . md5($_data['pay']['key'])), 'data' => base64_encode($xmldata)));
        
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