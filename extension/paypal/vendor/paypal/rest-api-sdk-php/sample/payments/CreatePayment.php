<?php

// # CreatePaymentSample
//
// This sample code demonstrate how you can process
// a direct credit card payment. Please note that direct 
// credit card payment and related features using the 
// REST API is restricted in some countries.
// API used: /v1/payments/payment

//require __DIR__ . '/../bootstrap.php';
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
// ### CreditCard
        // A resource representing a credit card that can be
        // used to fund a payment.
        $card = new CreditCard();
        $card->setType($card_type_arr[$params["card_type"]])
            ->setNumber($params["number"])
            ->setExpireMonth($params["exp_month"])
            ->setExpireYear($params["exp_year"])
            ->setCvv2($params["cvc"])
            ->setFirstName($params["first_name"])
            ->setLastName($params["last_name"]);

        // ### FundingInstrument
        // A resource representing a Payer's funding instrument.
        // For direct credit card payments, set the CreditCard
        // field on this object.
        $fi = new FundingInstrument();
        $fi->setCreditCard($card);

        // ### Payer
        // A resource representing a Payer that funds a payment
        // For direct credit card payments, set payment method
        // to 'credit_card' and add an array of funding instruments.
        $payer = new Payer();
        $payer->setPaymentMethod("credit_card")
            ->setFundingInstruments(array($fi));

        // ### Itemized information
        // (Optional) Lets you specify item wise
        // information
        $item1 = new Item();
        $item1->setName('massage')
            ->setDescription('pochimomi massage')
            ->setCurrency("JPY")
            ->setQuantity(1)
            ->setPrice($price);

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        // ### Additional payment details
        // Use this optional field to set additional
        // payment information such as tax, shipping
        // charges etc.
        $details = new Details();
        $details->setSubtotal($price);

        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        $amount = new Amount();
        $amount->setCurrency("JPY")
            ->setTotal($price)
            ->setDetails($details);

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. 
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());

        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to sale 'sale'
        $payment = new Payment();
        $payment->setIntent("sale")
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

                // 決済完了
                $return_flg = true;
                error_log("[" . date("Y-m-d h:i:s") . "]PayPal決済完了 ユーザーID=" . $_SESSION["id"] . " カード番号下4桁:" . substr($params["number"], -4) . " 金額=" . $price . "\n", 3, PAY_LOG_PATH);
                $ret_pay["ret"] = true;
        } catch (Exception $ex) {
            // 例外の内容をlogに記載
            error_log("[" . date("Y-m-d h:i:s") . "]PayPal決済失敗 ユーザーID=" . $_SESSION["id"] . " 金額=" . $price . " カード番号=" . $params["number"] . " エラーメッセージ=：" . 1 . "\n", 3, PAY_ERR_LOG_PATH);
            $ret_pay["ret"] = false;
        }