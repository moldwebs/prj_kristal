<?php
include_once ('PaymentGateway.php');

class PaymentMake extends PaymentGateway
{
    
    public function makePayment($_data = array()){

        $data = array(
            'ExternalID' => $_data['transaction']['ModTransaction']['code'],
            'Currency' => '498',
            'Merchant' => $_data['pay']['id'],
            'Customer[Code]' => '',
            'Customer[Name]' => '',
            'Customer[Address]' => '',
            'ExpiryDate' => date("Y-m-d", strtotime('+1 day')) . 'T' . date("H:i:s"),
            'Services[0][Name]' => $_data['transaction']['ModTransaction']['description'],
            'Services[0][Description]' => $_data['transaction']['ModTransaction']['description'] . '__',
            'Services[0][Amount]' => ($_data['transaction']['ModTransaction']['amount'] * 100),
            'LinkUrlCancel' => htmlspecialchars(FULL_BASE_URL . $_data['transaction']['ModTransaction']['data']['fail_url']),
            'LinkUrlSuccess' => htmlspecialchars(FULL_BASE_URL . $_data['transaction']['ModTransaction']['data']['back_url']),
            'Lang' => 'en-US',
            'Signature' => '',
        );
        $data['Signature'] = base64_encode(md5($data['Currency'].$data['Customer[Address]'].$data['Customer[Code]'].$data['Customer[Name]'].$data['ExpiryDate'].$data['ExternalID'].$data['Merchant'].$data['Services[0][Amount]'].$data['Services[0][Name]'].$data['Services[0][Description]'].$_data['pay']['key'], true));
        
        parent::submitPayment('https://test.paynet.md/Acquiring/SetEcom', $data);
        
    }
    
    public function checkPayment($_data = array()){
        
        $data = json_decode(file_get_contents('php://input'), true); 

        $data['EventDate'] = substr($data['EventDate'], 0, 19);
        $data['Payment']['StatusDate'] = substr($data['Payment']['StatusDate'], 0, 19);
        
        $hash = base64_encode(md5($data['EventDate'].$data['Eventid'].$data['EventType'].$data['Payment']['Amount'].$data['Payment']['Customer'].$data['Payment']['ExternalId'].$data['Payment']['Id'].$data['Payment']['Merchant'].$data['Payment']['StatusDate'].$_data['pay']['key'], true));
        
        if(!empty($_SERVER['HTTP_HASH']) && $data['EventType'] == 'PAID' && $_SERVER['HTTP_HASH'] == $hash){
            return array('status' => '1', 'id' => $data['Payment']['ExternalId'], 'ext_id' => $data['Payment']['Id'], 'amount' => ($data['Payment']['Amount']/100));
        } else {
            return array('status' => '0');
        }
    }
    
    public function afterPayment(){
        
    }

}