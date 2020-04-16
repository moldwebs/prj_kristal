<?php
include_once ('PaymentGateway.php');

class PaymentMake extends PaymentGateway
{

    public function makePayment($_data = array()){

         parent::submitPayment('/shop/checkout/credit?payment=credit_simple&code=' . $_data['transaction']['ModTransaction']['code'], array());

    }

    public function checkPayment($_data = array()){

    }

    public function afterPayment(){

    }

    public function calcs($data){
        $price = $data['amount'];
        $price *= (1 + ($data['months'] * 2) / 100);
        //$price += (($data['amount']/100)*2);
        return round($price/$data['months'], 2);
    }

}