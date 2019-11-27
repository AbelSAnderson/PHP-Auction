<?php

require_once(__DIR__ . "/../app/bootstrap.php");

use App\Lib\Logger;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Exception\PayPalConnectionException;

$paymentId = $_GET['paymentId'];
try {
    $payment = Payment::get($paymentId, $apiContext);
} catch (PayPalConnetionException $e) {
    Logger::getLogger()->error("PayPal: Payment failed to get ", ['exception' => $e]);
    echo "Transaction Failed!";
    die();
}

$payerId = $_GET['PayerID'];

$execution = new PaymentExecution();
$execution->setPayerId($payerId);

try {
    $result = $payment->execute($execution, $apiContext);

    $item_name = $result->getTransactions()[0]->getDescription();
    $payment_gross = $result->getTransactions()[0]->getAmount()->getTotal();
    $currency_code = $result->getTransactions()[0]->getAmount()->getCurrency();
    $recipient = $result->getTransactions()[0]->getItemList()->getShippingAddress()->getRecipientName();
    $addressStreet = $result->getTransactions()[0]->getItemList()->getShippingAddress()->getLine1();
    $addressCity = $result->getTransactions()[0]->getItemList()->getShippingAddress()->getCity();
    $addressProvince = $result->getTransactions()[0]->getItemList()->getShippingAddress()->getState();
    $addressPostal = $result->getTransactions()[0]->getItemList()->getShippingAddress()->getPostalCode();
    $addressCountry = $result->getTransactions()[0]->getItemList()->getShippingAddress()->getCountryCode();
    $paidTo = $result->getTransactions()[0]->getPayee()->getEmail();
    $payment_status = $result->getTransactions()[0]->getRelatedResources()[0]->getSale()->getState();
    $txn_id = $result->getTransactions()[0]->getRelatedResources()[0]->getSale()->getId();

    require(__DIR__ . "/../app/Layouts/header.php");

    echo <<<RECEIPTPAYMENT
<h1>Receipt of Payment</h1>
<table cellpadding="5">
<tr>
<td>Item Name: </td><td>$item_name</td>
</tr>
<tr>
<td>Amount Paid: </td><td>$payment_gross $currency_code</td>
</tr>
<tr>
<td>Shipping address: </td><td>
$recipient<br/>
$addressStreet<br/>
$addressCity $addressProvince $addressPostal<br/>
$addressCountry<br/>
</td>
</tr>
<tr>
<td>Paid to: </td><td>$paidTo</td>
</tr>
<tr>
<td>Payment Status: </td><td>$payment_status</td>
</tr>
<tr>
<td>Transaction ID: </td><td>$txn_id</td>
</tr>
</table>

<p>Your payment was successful.<br/>Thank you for your business!</p>
RECEIPTPAYMENT;


    require(__DIR__ . "/../app/Layouts/footer.php");
} catch (Exception $e) {
    Logger::getLogger()->error("PayPal: Transaction failed", ['exception' => $e]);
    echo "Transaction Failed!";
    die();
}