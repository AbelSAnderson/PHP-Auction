<?php
require_once(__DIR__ . "/../app/bootstrap.php");

use App\Exceptions\ClassException;
use App\Lib\Logger;
use App\Models\Item as Product;
use App\Models\User;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

$validid = pf_validate_number($_GET['id'], "redirect", CONFIG_URL);
try {
	$product = Product::findFirst(["id" => $validid]);
} catch(ClassException $e) {
	Logger::getLogger()->critical("Invalid Product: ", ['exception' => $e]);
	echo "Invalid Product";
	die();
}

if(!$product) {
	echo "Error retrieving item details!";
	die();
}

$product->getBids();

$item_name = $product->get('name');

$temp = $product->get('bidObjs');
$itemWinnerBidObj = array_shift($temp);
$item_amount = $itemWinnerBidObj->get("amount");
try {
	$itemOwnerObj = User::findFirst(["id" => $product->get("user_id")]);
} catch(ClassException $e) {
	Logger::getLogger()->critical("Invalid User: ", ['exception' => $e]);
	echo "Invalid User";
	die();
}

// Create new payer and method
$payer = new Payer();
$payer->setPaymentMethod("paypal");

$item1 = new Item();
$item1->setName($item_name)
	->setCurrency(PAYPAL_CURRENCY)
	->setQuantity(1)
	->setSku($product->get('id'))// Similar to `item_number` in Classic API
	->setPrice($item_amount);
/*$item2 = new Item();
$item2->setName('Granola bars')
	->setCurrency('USD')
	->setQuantity(5)
	->setSku("321321") // Similar to `item_number` in Classic API
	->setPrice(2);*/

$itemList = new ItemList();
$itemList->setItems([$item1]);

$details = new Details();
$details->setShipping(0)
	->setTax(round($item_amount * 0.13, 2))
	->setSubtotal($item_amount);

// Set payment amount
$amount = new Amount();
$amount->setCurrency(PAYPAL_CURRENCY)
	->setTotal(round($item_amount * 1.13, 2))
	->setDetails($details);

// Set transaction object
$transaction = new Transaction();
$transaction->setAmount($amount)
	->setItemList($itemList)
	->setDescription($item_name)
	->setInvoiceNumber(uniqid());

// Set redirect urls
$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl(PAYPAL_RETURNURL)
	->setCancelUrl(PAYPAL_CANCELURL);

// Create the full payment object
$payment = new Payment();
$payment->setIntent('sale')
	->setPayer($payer)
	->setRedirectUrls($redirectUrls)
	->setTransactions(array($transaction));

// Create payment with valid API context
$payment->create($apiContext);

// Get PayPal redirect URL and redirect user
$approvalUrl = $payment->getApprovalLink();

header('Location: ' . $approvalUrl);
die();
