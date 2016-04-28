<?php

/**
 * 共通決済設定
 */

// Logパス
define("PAY_LOG_PATH", ROOT . "assets/log/pay.log");
define("PAY_ERR_LOG_PATH", ROOT . "assets/log/pay_err.log");

// 決済情報(PAY.JP)
if (PUBLIC_URL == $_SERVER['SERVER_NAME']) {
	// 本番
	define("SECRET_KEY", "");			// 秘密鍵
	define("PUBLIC_KEY", "");			// 公開鍵
} else {
	// dev環境
	define("SECRET_KEY", "sk_test_f74a0fef310a6b27cff20388");			// 秘密鍵
	define("PUBLIC_KEY", "pk_test_cd5dc51d4af62f0d26b8be5d");			// 公開鍵
}

// 決済情報(Paypal)
if (PUBLIC_URL == $_SERVER['SERVER_NAME']) {
	// 本番
	define("CLIENT_ID", "");			// ClientID
	define("CLIENT_SECRET", "");			// ClientSecret
} else {
	// dev環境
	define("CLIENT_ID", "AWwBnAid_oEp07BYVpSGH8gDX6ASU_3xnC3nb5X2enbZkIku1ZXyUNcR_lHKYnOXlJLXFSLO05DIG5xN");			// ClientID
	define("CLIENT_SECRET", "EKPghCTLhlVU2AQg1qfIG0vlPyYQEPGwCRBFyPYu8qA1Q-hsV02KDzRCEOMIhuMxm26lXzf44xVmtZ2Z");			// ClientSecret
}

// 決済情報(stripe)
if (PUBLIC_URL == $_SERVER['SERVER_NAME']) {
	// 本番
	define("SECRET_KEY_STRIPE", "");			// 秘密鍵
	define("PUBLIC_KEY_STRIPE", "");			// 公開鍵
} else {
	// dev環境
	define("SECRET_KEY_STRIPE", "sk_test_D1OwG3xq9chZpt4lCkxTewRD");			// 秘密鍵
	define("PUBLIC_KEY_STRIPE", "pk_test_wPZta4agVR24iDttSvUiNmf3");			// 公開鍵
}

define("CURRENCY", "JPY");			// 通貨(PAY.JPのフォーマットに従う)


require ROOT . 'extension/paypal/vendor/paypal/rest-api-sdk-php/sample/bootstrap.php';
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;

/*
 定数（配列）
 */

// エラーコードごとのエラー文章
// 参照元：https://pay.jp/docs/api/?php#error
$pay_err_str = array();
$pay_err_str[200] = "";
$pay_err_str[400] = "カード情報が間違っています。";		// カード情報入力ミス
$pay_err_str[401] = "予期しないエラーが発生いたしました。大変お手数おかけいたしますが、運営にお問い合わせください。";		// APIの間違い
$pay_err_str[402] = "このカードは利用できません。";		// 枠確保できず
$pay_err_str[404] = "予期しないエラーが発生いたしました。大変お手数おかけいたしますが、運営にお問い合わせください。";		// payjpアカウントに対するエラー
$pay_err_str[500] = "決済ネットワークで障害が発生いたしました。大変申し訳ございませんが数分後にやり直してください。";		// payjpダウンなどのエラー

?>