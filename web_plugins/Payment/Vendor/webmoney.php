<?php
include_once ('PaymentGateway.php');

class PaymentMake extends PaymentGateway
{
    
    public function makePayment($_data = array()){
        // http://3333.wrk.webs.md/payment/callback/webmoney
        
        parent::submitPayment('https://merchant.webmoney.ru/lmi/payment.asp', array(
            'LMI_PAYMENT_AMOUNT' => $_data['transaction']['ModTransaction']['amount'],
            'LMI_PAYMENT_DESC' => $_data['transaction']['ModTransaction']['description'],
            'LMI_PAYMENT_NO' => $_data['transaction']['ModTransaction']['id'],
            'LMI_PAYEE_PURSE' => $_data['pay']['id'],
            'CS1' => $_data['transaction']['ModTransaction']['code']
        ));
    }
    
    public function checkPayment($_data = array()){
        if( isset($_POST['LMI_PREREQUEST']) && $_POST['LMI_PREREQUEST'] == 1){
            // NOTHING
        } else if(isset($_POST['LMI_PAYMENT_NO']) &&  preg_match('/^\d+$/', $_POST['LMI_PAYMENT_NO']) == 1){
            //$md5sum = strtoupper(md5($_data['pay']['id'].$_POST['LMI_PAYMENT_AMOUNT'].$_POST['LMI_PAYMENT_NO'].$_POST['LMI_MODE'].$_POST['LMI_SYS_INVS_NO'].$_POST['LMI_SYS_TRANS_NO'].$_POST['LMI_SYS_TRANS_DATE'].$_data['pay']['key'].$_POST['LMI_PAYER_PURSE'].$_POST['LMI_PAYER_WM']));

            $common_string = $_POST['LMI_PAYEE_PURSE'].$_POST['LMI_PAYMENT_AMOUNT'].$_POST['LMI_PAYMENT_NO'].
            $_POST['LMI_MODE'].$_POST['LMI_SYS_INVS_NO'].$_POST['LMI_SYS_TRANS_NO'].
            $_POST['LMI_SYS_TRANS_DATE'].$_data['pay']['key'].$_POST['LMI_PAYER_PURSE'].$_POST['LMI_PAYER_WM'];
            $hash = strtoupper(hash("sha256",$common_string));
            
            if($_POST['LMI_HASH'] == $hash){
                return array('status' => '1', 'id' => $_POST['CS1'], 'ext_id' => $_POST['LMI_SYS_TRANS_NO'], 'amount' => $_POST['LMI_PAYMENT_AMOUNT']);
            } else {
                return array('status' => '0');
            }
        }
    }
    
    public function afterPayment(){
      if(isset($_POST['LMI_PREREQUEST']) && $_POST['LMI_PREREQUEST'] == 1){
        echo 'YES';
      }
    }

}