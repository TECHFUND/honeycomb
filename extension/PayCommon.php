<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * 決済関連共通処理クラス
 *
 * 各プログラムから利用される決済関連の共通処理メソッドを定義する
 *
 * Developed on PHP versions 5.2.5
 *
 * @category	extension
 * @package		TECHFUND
 * @author	 	松山 雄太 <yuta_matsuyama@techfund.jp>
 * @copyright	TECHFUND
 * @license		別紙契約内容を参照
 * @version		$Id$
 * @link			http://techfund.jp/
 * @see
 * @since
 * @deprecated
 *
 * 修正履歴:
 * 2016/07/07 新規作成
 */

/**
 * 決済関連の共通処理クラス
 */
class PayCommon {

	// ////////////// PAY.JP ////////////// //

	/**
	 * PAY.JP決済チェックメソッド
	 * @param	arr			$params			カード情報
	 * @param	int			$price			価格
	 * @return	array()						成功：true（配列内にファイル名）　失敗：false（配列内にエラー文）
	 */
	function paymentCheck($params, $price) {
		$data = array();
		
		// 初期化
		require_once(ROOT . 'extension/payjp/init.php');
		try {
			// 新しい課金の作成
			\Payjp\Payjp::setApiKey(SECRET_KEY);
			
			// カードの認証と支払い額確保
			$result = \Payjp\Charge::create(array(
				"card" => $params,
				"amount" => $price,
				"currency" => CURRENCY,
				"capture" => false
			));
			if (isset($result['error'])) {
				// エラー発生
				throw new Exception();
			}
			// 決済完了
			$data = array('ret' => true, 'err_code' => 200);
		} catch (Exception $e) {
			// 例外の内容を返す
			$data = array('ret' => false, 'err_code' => $e->getHttpStatus());
		}
		return $data;
	}


	/**
	 * PAY.JP決済メソッド
	 * @param	arr			$params			カード情報
	 * @param	int			$price			価格
	 * @return	bool							成功：true　失敗：false
	 */
	function paymentProcessing($params, $price) {
		$return_flg = false;
		
		// 初期化
		require_once(ROOT . 'extension/payjp/init.php');
		try {
			\Payjp\Payjp::setApiKey(SECRET_KEY);
			
			// 決済
			$result = \Payjp\Charge::create(array(
				"card" => $params,
				"amount" => $price,
				"currency" => CURRENCY
			));
			if (isset($result['error'])) {
				// エラー発生
				throw new Exception();
			}
			// 決済完了
			$return_flg = true;
			error_log("[" . date("Y-m-d h:i:s") . "]決済完了 ユーザーID=" . $_SESSION["id"] . " カード番号下4桁:" . substr($params["number"], -4) . " 金額=" . $price . "\n", 3, PAY_LOG_PATH);
		} catch (Exception $e) {
			// 例外の内容をlogに記載
			error_log("[" . date("Y-m-d h:i:s") . "]PAY.JP決済失敗 ユーザーID=" . $_SESSION["id"] . " 金額=" . $price . " カード番号=下4桁:" . substr($params["number"], -4) . " エラーメッセージ=：" . $e->getMessage() . "\n", 3, PAY_ERR_LOG_PATH);
		}
		return $return_flg;
	}


	// ////////////// PayPal ////////////// //

	/**
	 * PayPal決済メソッド
	 * @param	arr			$params			カード情報
	 * @param	int			$price			価格
	 * @return	bool							成功：true　失敗：false
	 */
	function paymentProcessingPaypal($params, $price) {
		$return_flg = false;
		
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
		    ->setTax(8)
		    ->setPrice($price);
		$itemList = new ItemList();
		$itemList->setItems(array($item1));
		// ### Additional payment details
		// Use this optional field to set additional
		// payment information such as tax, shipping
		// charges etc.
		$details = new Details();
		$details->setShipping(0)
		    ->setTax($price * 0.08)
		    ->setSubtotal($price);
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
		} catch (Exception $ex) {
			// 例外の内容をlogに記載
			error_log("[" . date("Y-m-d h:i:s") . "]PayPal決済失敗 ユーザーID=" . $_SESSION["id"] . " 金額=" . $price . " カード番号下4桁:" . substr($params["number"], -4) . " エラーメッセージ=：" . $e->getMessage() . "\n", 3, PAY_ERR_LOG_PATH);
		}
		return $return_flg;
	}


	// ////////////// STRIPE ////////////// //

	/**
	 * STRIPE決済前チェックメソッド
	 * @param	arr			$params			カード情報
	 * @param	int			$price			価格
	 * @param	int			$customer_id			顧客ID
	 * @param	int			$user_id			ユーザーID
	 * @return	array()						成功：true（配列内にファイル名）　失敗：false（配列内にエラー文）
	 */
	function paymentPreCheckStripe($params, $price, $customer_id = "", $user_id) {
		$data = array();
		
		// 初期化
		require_once(ROOT . 'extension/stripe/vendor/autoload.php');
		try {
			\Stripe\Stripe::setApiKey(SECRET_KEY_STRIPE);
			// 顧客登録されているか
			if ("" != $customer_id) {
				$param = array(
					'customer' => $customer_id, 
					'amount' => $price, 
					'currency' => CURRENCY, 
					'capture' => false
				);
			} else {
				// トークンの作成
				$token = \Stripe\Token::create($params);
				$param = array(
					'source' => $token->id, 
					'amount' => $price, 
					'currency' => CURRENCY, 
					'capture' => false
				);
			}
			// カードの認証と支払い額確保
			$result = \Stripe\Charge::create($param);
			if (isset($result['error'])) {
				// エラー発生
				throw new Exception();
			}
			// 決済できるかの事前確認なので一旦決済をキャンセル(確認画面で改めて決済を行う)
			$ret = $this->paymentCancelStripe($result["id"], $user_id);
			// 決済完了
			$data = array('ret' => true, 'err_code' => 200, 'id' => $result["id"]);
		} catch (Exception $e) {
			// 例外の内容を返す
			$data = array('ret' => false, 'err_code' => $e->getHttpStatus());
		}
		return $data;
	}


	/**
	 * STRIPE決済チェックメソッド
	 * @param	arr			$params			カード情報
	 * @param	int			$price			価格
	 * @param	int			$customer_id			顧客ID
	 * @return	array()						成功：true（配列内にファイル名）　失敗：false（配列内にエラー文）
	 */
	function paymentCheckStripe($params, $price, $customer_id = "") {
		$data = array();
		
		// 初期化
		require_once(ROOT . 'extension/stripe/vendor/autoload.php');
		try {
			\Stripe\Stripe::setApiKey(SECRET_KEY_STRIPE);
			// 顧客登録されているか
			if ("" != $customer_id) {
				$param = array(
					'customer' => $customer_id, 
					'amount' => $price, 
					'currency' => CURRENCY, 
					'capture' => false
				);
			} else {
				// トークンの作成
				$token = \Stripe\Token::create($params);
				$param = array(
					'source' => $token->id, 
					'amount' => $price, 
					'currency' => CURRENCY, 
					'capture' => false
				);
			}
			// カードの認証と支払い額確保
			$result = \Stripe\Charge::create($param);
			if (isset($result['error'])) {
				// エラー発生
				throw new Exception();
			}
			// 決済完了
			$data = array('ret' => true, 'err_code' => 200, 'id' => $result["id"]);
		} catch (Exception $e) {
			// 例外の内容を返す
			$data = array('ret' => false, 'err_code' => $e->getHttpStatus());
			error_log("[" . date("Y-m-d h:i:s") . "]Stripeオーソリ枠確保失敗 ユーザーID=" . $_SESSION["id"] . " 金額=" . $price . " 顧客番号=" . $customer_id . " カード番号=" . $params["number"] . " エラーメッセージ=：" . $e->getMessage() . "\n", 3, PAY_ERR_LOG_PATH);
		}
		return $data;
	}


	/**
	 * STRIPE顧客情報保存メソッド
	 * @param	arr			$params			カード情報
	 * @return	array()						成功：true（配列内にファイル名）　失敗：false（配列内にエラー文）
	 */
	function setCustomerStripe($params) {
		$data = array();
		
		// 初期化
		require_once(ROOT . 'extension/stripe/vendor/autoload.php');
		try {
			\Stripe\Stripe::setApiKey(SECRET_KEY_STRIPE);
			// 顧客情報作成
			$result = \Stripe\Customer::create($params);
			if (isset($result['error'])) {
				// エラー発生
				throw new Exception();
			}
			// 決済完了
			$data = array('ret' => true, 'err_code' => 200, 'id' => $result['id']);
		} catch (Exception $e) {
			// 例外の内容を返す
			$data = array('ret' => false, 'err_code' => $e->getHttpStatus());
			// 例外の内容をlogに記載
			error_log("[" . date("Y-m-d h:i:s") . "]Stripe顧客登録失敗 カード番号=下4桁:" . substr($params["card"]["number"], -4) . " エラーコード=" . $e->getHttpStatus() . "\n", 3, PAY_ERR_LOG_PATH);
		}
		return $data;
	}


	/**
	 * STRIPE顧客情報取得メソッド
	 * @param	arr			$customer_id			顧客ID
	 * @return	array()						成功：true（配列内にファイル名）　失敗：false（配列内にエラー文）
	 */
	function getCustomerStripe($customer_id) {
		$data = array();
		
		// 初期化
		require_once(ROOT . 'extension/stripe/vendor/autoload.php');
		try {
			\Stripe\Stripe::setApiKey(SECRET_KEY_STRIPE);
			// 顧客情報取得
			$result = \Stripe\Customer::retrieve($customer_id);
			if (isset($result['error'])) {
				// エラー発生
				throw new Exception();
			}
			// 決済完了
			$data = array('ret' => true, 'err_code' => 200, 'customer' => $result);
		} catch (Exception $e) {
			// 例外の内容を返す
			$data = array('ret' => false, 'err_code' => $e);
		}
		return $data;
	}


	/**
	 * Stripe決済メソッド
	 * @param	str			$pay_id			支払いID
	 * @param	int			$user_id		ユーザーID
	 * @return	bool							成功：true　失敗：false
	 */
	function paymentProcessingStripe($pay_id, $user_id) {
		$return_flg = false;
		
		// 0円決済か
		if (0 != $pay_id) {
			// 初期化
			require_once(ROOT . 'extension/stripe/vendor/autoload.php');
			try {
				\Stripe\Stripe::setApiKey(SECRET_KEY_STRIPE);
				// 決済
				$result = \Stripe\Charge::retrieve($pay_id);
				$result->capture();
				if (isset($result['error'])) {
					// エラー発生
					throw new Exception();
				}
				// 決済完了
				$return_flg = true;
				error_log("[" . date("Y-m-d h:i:s") . "]Stripe決済完了 ユーザーID=" . $user_id . " Stripe支払いID=" . $pay_id . "\n", 3, PAY_LOG_PATH);
			} catch (Exception $e) {
				// 例外の内容をlogに記載
				error_log("[" . date("Y-m-d h:i:s") . "]Stripe決済失敗 ユーザーID=" . $user_id . " Stripe支払いID=" . $pay_id . " エラーメッセージ=：" . $e->getMessage() . "\n", 3, PAY_ERR_LOG_PATH);
			}
		} else {
			// 0円決済の場合
			$return_flg = true;
			error_log("[" . date("Y-m-d h:i:s") . "]Stripe決済完了 ユーザーID=" . $user_id . " キャンペーンコードによる0円決済\n", 3, PAY_LOG_PATH);
		}
		return $return_flg;
	}


	/**
	 * STRIPE決済キャンセルメソッド
	 * @param	int			$pay_id			支払いID
	 * @param	int			$user_id		ユーザーID
	 * @return	array()						成功：true（配列内にファイル名）　失敗：false（配列内にエラー文）
	 */
 	function paymentCancelStripe($pay_id, $user_id) {
		$data = array();
		
		// 0円決済か
		if (0 != $pay_id) {
			// 初期化
			require_once(ROOT . 'extension/stripe/vendor/autoload.php');
			try {
				\Stripe\Stripe::setApiKey(SECRET_KEY_STRIPE);
				// 決済キャンセル
				$result = \Stripe\Refund::create(array(
					"charge" => $pay_id
				));
				if (isset($result['error'])) {
					// エラー発生
					throw new Exception();
				}
				// 決済完了
				$return_flg = true;
				error_log("[" . date("Y-m-d h:i:s") . "]Stripe決済キャンセル完了 ユーザーID=" . $user_id . " Stripe支払いID=" . $pay_id . "\n", 3, PAY_LOG_PATH);
			} catch (Exception $e) {
				$return_flg = false;
				// 例外の内容をlogに記載
				error_log("[" . date("Y-m-d h:i:s") . "]Stripe決済失敗 ユーザーID=" . $user_id . " Stripe支払いID=" . $pay_id . " エラーメッセージ=：" . $e->getMessage() . "\n", 3, PAY_ERR_LOG_PATH);
			}
		} else {
			// 0円決済の場合
			$return_flg = true;
			error_log("[" . date("Y-m-d h:i:s") . "]Stripe決済キャンセル完了 ユーザーID=" . $user_id . " キャンペーンコードによる0円決済\n", 3, PAY_LOG_PATH);
		}
		return $return_flg;
	}


	/**
	 * STRIPEオーソリ解放メソッド
	 * @param	int			$pay_id			支払いID
	 * @param	int			$user_id		ユーザーID
	 * @return	array()						成功：true（配列内にファイル名）　失敗：false（配列内にエラー文）
	 */
	function authorizeCancelStripe($pay_id, $user_id) {
		$data = array();
		
		// 初期化
		require_once(ROOT . 'extension/stripe/vendor/autoload.php');
		try {
			\Stripe\Stripe::setApiKey(SECRET_KEY_STRIPE);
			// 決済キャンセル
			$result = \Stripe\Refund::create(array(
				"charge" => $pay_id
			));
			if (isset($result['error'])) {
				// エラー発生
				throw new Exception();
			}
			// 決済完了
			$return_flg = true;
			error_log("[" . date("Y-m-d h:i:s") . "]Stripe決済キャンセル完了 ユーザーID=" . $user_id . " Stripe支払いID=" . $pay_id . "\n", 3, PAY_LOG_PATH);
		} catch (Exception $e) {
			// 例外の内容をlogに記載
			error_log("[" . date("Y-m-d h:i:s") . "]Stripe決済決済失敗 ユーザーID=" . $user_id . " Stripe支払いID=" . $pay_id . " エラーメッセージ=：" . $e->getMessage() . "\n", 3, PAY_ERR_LOG_PATH);
		}
		return $return_flg;
	}

}

?>
