<?php
include_once ('PaymentGateway.php');

class PaymentMake extends PaymentGateway
{
    
    public function makePayment($_data = array()){
        echo file_get_contents('http://7.hidemyass.com/ip-1/encoded/'.base64_encode(ltrim(FULL_BASE_URL . '/payment/callback/test?order_id=' . $_data['transaction']['ModTransaction']['code'] . '&amount=' . $_data['transaction']['ModTransaction']['amount'], 'http')));
        ?>
            access : <?php echo FULL_BASE_URL . '/payment/callback/test?order_id=' . $_data['transaction']['ModTransaction']['code'] . '&amount=' . $_data['transaction']['ModTransaction']['amount']?><br />
            <a href="<?php e(FULL_BASE_URL . $_data['transaction']['ModTransaction']['data']['back_url'])?>">SUCCESS</a>
        <?php
    }
    
    public function checkPayment($_data = array()){
        return array('status' => '1', 'id' => $_GET['order_id'], 'ext_id' => 'test_ext_id' . uniqid(), 'amount' => $_GET['amount']);
    }
    
    public function afterPayment(){
        print_r($_GET);
        echo "callback response";
    }

}