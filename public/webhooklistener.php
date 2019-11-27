<?php
require_once(__DIR__ . "/../app/bootstrap.php");

use App\Lib\Logger;
use PayPal\Api\Payment as PayPalPayment;
use App\Models\Payment;
use PayPal\Api\VerifyWebhookSignature;
use PayPal\Exception\PayPalConnectionException;

$data = file_get_contents('php://input');

$headers = getallheaders();
$headers = array_change_key_case($headers, CASE_UPPER);

$signatureVerification = new VerifyWebhookSignature();
$signatureVerification->setAuthAlgo($headers['PAYPAL-AUTH-ALGO']);
$signatureVerification->setTransmissionId($headers['PAYPAL-TRANSMISSION-ID']);
$signatureVerification->setCertUrl($headers['PAYPAL-CERT-URL']);
$signatureVerification->setWebhookId(WEBHOOK_ID);
$signatureVerification->setTransmissionSig($headers['PAYPAL-TRANSMISSION-SIG']);
$signatureVerification->setTransmissionTime($headers['PAYPAL-TRANSMISSION-TIME']);

$signatureVerification->setRequestBody($data);

try {
	$output = $signatureVerification->post($apiContext);
} catch(Exception $e) {
	Logger::getLogger()->error("PayPal: failed to attempt to verify", ['exception' => $e]);
	die();
}

if($output->getVerificationStatus() != 'SUCCESS') {
	Logger::getLogger()->error("PayPal: failed to verify", ['Status:' => $output->getVerificationStatus()]);
	die();
}

$json_data = json_decode($data);
$obj = $json_data->resource;
$paymentId = $obj->parent_payment;

try {
	$payment = PayPalPayment::get($paymentId, $apiContext);
} catch(PayPalConnectionException $e) {
	Logger::getLogger()->error("PayPal: Payment failed to get ", ['exception' => $e]);
	echo "Transaction Failed!";
	die();
}

//Item info
$item_number = $payment->getTransactions()[0]->getItemList()->items[0]->getSku();
$item_name = $payment->getTransactions()[0]->getItemList()->items[0]->getName();

//Transaction info
$payment_gross = $payment->getTransactions()[0]->getAmount()->getTotal();
$currency_code = $payment->getTransactions()[0]->getAmount()->getCurrency();

//Payer Info
$payer_id = $payment->getPayer()->getPayerInfo()->getPayerId();
$payer = $payment->getPayer()->getPayerInfo()->getEmail();

//Shipping info
$first_name = $payment->getPayer()->getPayerInfo()->getFirstName();
$last_name = $payment->getPayer()->getPayerInfo()->getLastName();
$addressStreet = $payment->getTransactions()[0]->getItemList()->getShippingAddress()->getLine1();
$addressCity = $payment->getTransactions()[0]->getItemList()->getShippingAddress()->getCity();
$addressProvince = $payment->getTransactions()[0]->getItemList()->getShippingAddress()->getState();
$addressPostal = $payment->getTransactions()[0]->getItemList()->getShippingAddress()->getPostalCode();
$addressCountry = $payment->getTransactions()[0]->getItemList()->getShippingAddress()->getCountryCode();

//Transaction info
$payment_status = $payment->getTransactions()[0]->getRelatedResources()[0]->getSale()->getState();
$txn_id = $payment->getTransactions()[0]->getRelatedResources()[0]->getSale()->getId();
$date_obj = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $payment->getCreateTime());
$payment_date = $date_obj->format("Y-m-d H:i:s");

$paymentObj = new Payment(
	$txn_id,
	$payment_gross,
	$payment_status,
	$item_number,
	$item_name,
	$payer_id,
	$payer,
	$first_name,
	$last_name,
	$addressStreet,
	$addressCity,
	$addressProvince,
	$addressPostal,
	$addressCountry,
	$payment_date);

$result = $paymentObj->create();

if($result) {
	Logger::getLogger()->debug("PayPal: Payment has been made & successfully inserted into the Database");
} else {
	Logger::getLogger()->alert("PayPal: Error inserting into DB ", ["POST" => $_POST]);
}
