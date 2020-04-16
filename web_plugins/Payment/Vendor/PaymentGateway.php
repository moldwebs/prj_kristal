<?php
abstract class PaymentGateway
{
    public function submitPayment($url = null, $fields = array())
    {

        echo "<html>\n";
        echo "<head><title>Processing Payment...</title></head>\n";
        echo "<body align=\"center\" onLoad=\"document.forms['gateway_form'].submit();\">\n";
        echo "<p style=\"text-align:center;\"><h2>Please wait, your order is being processed and you";
        echo " will be redirected to the payment website.</h2></p>\n";
        echo "<form method=\"POST\" name=\"gateway_form\" ";
        echo "action=\"" . $url . "\">\n";

        foreach ($fields as $name => $value)
        {
             echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
        }


        echo "<p style=\"text-align:center;\"><br/><br/>If you are not automatically redirected to ";
        echo "payment website within 5 seconds...<br/><br/>\n";
        echo "<input type=\"submit\" value=\"Click Here\"></p>\n";

        echo "</form>\n";
        echo "</body></html>\n";
    }
}
