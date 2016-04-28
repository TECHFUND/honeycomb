<?php
// # Authorize Payment
// This sample code demonstrates how you can authorize a payment.
// API used: /v1/payments/authorization
// https://developer.paypal.com/webapps/developer/docs/integration/direct/capture-payment/#authorize-the-payment

//require __DIR__ . '/../bootstrap.php';

use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;

// The biggest difference between creating a payment, and authorizing a payment is to set the intent of payment
// to correct setting. In this case, it would be 'authorize'

$card = new CreditCard();
$card->setType($card_type_arr[$params["card_type"]])
    ->setNumber($params["number"])
    ->setExpireMonth($params["exp_month"])
    ->setExpireYear($params["exp_year"])
    ->setCvv2($params["cvc"]);

$fi = new FundingInstrument();
$fi->setCreditCard($card);

$payer = new Payer();
$payer->setPaymentMethod("credit_card")
    ->setFundingInstruments(array($fi));

$amount = new Amount();
$amount->setCurrency("JPY")
    ->setTotal($price);

$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setDescription("Payment description.");

$payment = new Payment();

// Setting intent to authorize creates a payment
// authorization. Setting it to sale creates actual payment
$payment->setIntent("authorize")
    ->setPayer($payer)
    ->setTransactions(array($transaction));

// For Sample Purposes Only.
$request = clone $payment;

// ### Create Payment
// Create a payment by calling the payment->create() method
// with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
// The return object contains the state.
try {
    $payment->create($apiContext);
                error_log("[" . date("Y-m-d h:i:s") . "]PayPal決済完了 ユーザーID=" . $_SESSION["id"] . " カード番号下4桁:" . substr($params["number"], -4) . " 金額=" . $price . "\n", 3, PAY_LOG_PATH);
                    $ret_pay["ret"] = true;
} catch (Exception $ex) {
    
            // 例外の内容をlogに記載
            error_log("[" . date("Y-m-d h:i:s") . "]PayPal決済失敗 ユーザーID=" . $_SESSION["id"] . " 金額=" . $price . " カード番号=" . $params["number"] . " エラーメッセージ=：" . 1 . "\n", 3, PAY_ERR_LOG_PATH);
                    $ret_pay["ret"] = true;
}
