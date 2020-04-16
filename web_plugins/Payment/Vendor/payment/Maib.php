<?php

/**
 * 2CheckOut Class
 *
 * Integrate the 2CheckOut payment gateway in your site using this easy
 * to use library. Just see the example code to know how you should
 * proceed. Btw, this library does not support the recurring payment
 * system. If you need that, drop me a note and I will send to you.
 *
 * @package     Payment Gateway
 * @category    Library
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @link        http://www.phpfour.com
 */

include_once ('PaymentGateway.php');

class Maib extends PaymentGateway
{
    /**
     * Secret word to be used for IPN verification
     *
     * @var string
     */
    public $secret;

    /**
     * Initialize the 2CheckOut gateway
     *
     * @param none
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Some default values of the class
        $this->gatewayUrl = 'https://cpsbill.com/checkout/';
        $this->ipnLogFile = 'maib.ipn_results.log';
    }

    /**
     * Enables the test mode
     *
     * @param none
     * @return none
     */
    public function enableTestMode()
    {
        $this->testMode = TRUE;
        $this->addField('demo', 'Y');
    }

    /**
     * Set the secret word
     *
     * @param string the scret word
     * @return void
     */
    public function setSecret($word)
    {
        if (!empty($word))
        {
            $this->secret = $word;
        }
    }

    /**
     * Validate the IPN notification
     *
     * @param none
     * @return boolean
     */
    public function validateIpn()
    {
        if(!empty($_POST['data'])){
            $data = ws_xml2array(stripslashes($_POST['data']));
            foreach($data['Response']['Transaction'] as $field=>$value){
                $this->ipnData["$field"] = $value;
            }
        }

        // If demo mode, the order number must be forced to 1
        if(($this->demo == "Y" || $this->ipnData['demo'] == 'Y') && $this->ipnData["ResponseId"] != '1')
        {
            $this->ipnData["TransactionId"] = "11111";
            $this->ipnData["ResponseId"] = "1";
        }
        
        return true;
    }
}