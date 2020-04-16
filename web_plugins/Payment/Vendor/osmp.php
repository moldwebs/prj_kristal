<?php
include_once ('PaymentGateway.php');

class PaymentMake extends PaymentGateway
{
    
    public function checkPayment($_data = array()){
        // http://3333.wrk.webs.md/payment/callback/osmp/?command=pay&txn_id=12345679&txn_date=20050815120133&account=069096764&sum=10.45
        
        $check_ip = explode(',', $_data['pay']['ip']);

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        if(!in_array($ip, $check_ip)) return array('status' => '0');
        
        if($_GET['command'] == 'pay' && !empty($_GET['txn_id']) && $_GET['sum'] > 0){
            $result = Classregistry::init('Payment.ModTransaction')->query("SELECT * FROM `wb_extra_data` WHERE `type` = 'board_contacts' AND (extra_5 = '+373.".sqls($_GET['account'])."' OR extra_5 = '+373.".sqls(ltrim($_GET['account'], '0'))."')");
            if(!empty($result[0]['wb_extra_data']['extra_1'])){
                $transaction_id = uniqid(rand());
                Classregistry::init('Payment.ModTransaction')->create();
                Classregistry::init('Payment.ModTransaction')->save(array(
                    'tid' => $_data['pay']['tid'],
                    'code' => $transaction_id,
                    'pay_type' => 'osmp',
                    'description' => ___('Wallet charge'),
                    'amount' => $_GET['sum'],
                    'currency' => reset(Configure::read('CMS.currency')),
                    'extra_id' => $result[0]['wb_extra_data']['extra_1'], 
                    'data' => array(
                        'back_url' => $_data['pay']['back_url'] . $transaction_id, 
                    )
                ));
                return array('status' => '1', 'id' => $transaction_id, 'ext_id' => $_GET['txn_id'] , 'amount' => $_GET['sum']);
            }
        }
    }
    
    public function afterPayment(){
        if(!($_GET['sum'] > 0)){
               exit("<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <response>
                <osmp_txn_id>{$_GET['txn_id']}</osmp_txn_id>
                <prv_txn>1</prv_txn>
                <sum>{$_GET['sum']}</sum>
                <result>5</result>
                </response>");
        }
        if($_GET['command'] == 'check'){
            $result = Classregistry::init('Payment.ModTransaction')->query("SELECT * FROM `wb_extra_data` WHERE `type` = 'board_contacts' AND (extra_5 = '+373.".sqls($_GET['account'])."' OR extra_5 = '+373.".sqls(ltrim($_GET['account'], '0'))."')");
            if(!empty($result[0]['wb_extra_data']['extra_1'])){
                exit("<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <response>
                <osmp_txn_id>{$_GET['txn_id']}</osmp_txn_id>
                <result>0</result>
                </response>");
            } else {
                exit("<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <response>
                <osmp_txn_id>{$_GET['txn_id']}</osmp_txn_id>
                <result>5</result>
                </response>");
            }
        }
        if($_GET['command'] == 'pay'){
            $result = Classregistry::init('Payment.ModTransaction')->query("SELECT * FROM `wb_extra_data` WHERE `type` = 'board_contacts' AND (extra_5 = '+373.".sqls($_GET['account'])."' OR extra_5 = '+373.".sqls(ltrim($_GET['account'], '0'))."')");
            if(!empty($result[0]['wb_extra_data']['extra_1'])){
                exit("<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <response>
                <osmp_txn_id>{$_GET['txn_id']}</osmp_txn_id>
                <prv_txn>{$_GET['txn_id']}</prv_txn>
                <sum>{$_GET['sum']}</sum>
                <result>0</result>
                </response>");
            } else {
                exit("<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <response>
                <osmp_txn_id>{$_GET['txn_id']}</osmp_txn_id>
                <prv_txn>1</prv_txn>
                <sum>{$_GET['sum']}</sum>
                <result>5</result>
                </response>");
            }
        }
        exit("<?xml version=\"1.0\" encoding=\"UTF-8\"?>
        <response>
        <osmp_txn_id>{$_GET['txn_id']}</osmp_txn_id>
        <result>5</result>
        </response>");
    }

}