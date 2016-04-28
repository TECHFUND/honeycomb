<?php

// セッションスタート
session_start();

// Logパス
define("LOG_PATH", ROOT . "assets/log/err.log");

// サービスタイトル(メールにも反映)
define("SERVICE_TITLE", "ポチもみ");

// URL
define("PUBLIC_URL", "pochimomi.com");		// 本番
define("DEVELOP_URL", "dev.pochimomi.com");		// dev環境
define("TEST_URL", "test.pochimomi.com");		// dev環境

// サービスURL(メールにも反映)
if (PUBLIC_URL == $_SERVER['SERVER_NAME']) {
	// 本番
	define('ROOT_URL', 'https://' . PUBLIC_URL . '/');
	define('ROOT_URL_T', 'https://' . PUBLIC_URL);
} elseif (DEVELOP_URL == $_SERVER['SERVER_NAME']) {
	// dev環境
	define('ROOT_URL', 'https://' . DEVELOP_URL . '/');
	define('ROOT_URL_T', 'https://' . DEVELOP_URL);
} else {
	// test環境
	define('ROOT_URL', 'http://' . TEST_URL . '/');
	define('ROOT_URL_T', 'http://' . TEST_URL);
}

// DB情報
if (PUBLIC_URL == $_SERVER['SERVER_NAME']) {
	// 本番
	define("DB_HOST", "pochimomi.cotbhsocsonj.ap-northeast-1.rds.amazonaws.com:3306");
	define("DB_USERNAME", "");
	define("DB_PASSWORD", "");
	define("DB_NAME", "pochimomi");
} elseif (DEVELOP_URL == $_SERVER['SERVER_NAME']) {
	// dev環境
	define("DB_HOST", "dev.cotbhsocsonj.ap-northeast-1.rds.amazonaws.com:3306");
	define("DB_USERNAME", "");
	define("DB_PASSWORD", "");
	define("DB_NAME", "pochimomi");
} else {
	// test環境
	define("DB_HOST", "db.cotbhsocsonj.ap-northeast-1.rds.amazonaws.com:3306");
	define("DB_USERNAME", "");
	define("DB_PASSWORD", "");
	define("DB_NAME", "pochimomi");
}

// DatabaseAccessClass読み込み
require_once(ROOT . 'extension/DatabaseAccess.php');

// 画像関連
define("IMAGE_DIR", ROOT . "assets/img/user_images/");			// 画像フォルダ
define("UPLOAD_MAX_IMAGE_SIZE", 1048576000);			// アップロード画像の最大サイズ(bite)	// 1048576000=1000MB
define("UPLOAD_MAX_IMAGE_WIDTH", 5000);			// アップロード画像の最大横幅(px)
define("UPLOAD_MAX_IMAGE_HEIGHT", 5000);			// アップロード画像の最大縦幅(px)

// ファイル関連
define("FILE_DIR", ROOT . "assets/files/user_files/");			// ファイルフォルダ

// 共通処理Class読み込み
require_once(ROOT . 'extension/CommonFunctions.php');

define('DURATION', 30);		// 施術時間(フィジビリは30分に固定)
define('CARFARE', 0);		// 出張費(単位：円)
define('TAX', 0.08);		// 消費税
define('MAX_PRICE_DIGIT', 5);		// 価格の上限桁数
define('CANCEL_ALLOW_TIME', 60);			// キャンセル許可時間(デフォルト60分)

define('PASSWORD_LOCK', 10);		// ミスログイン時、画像認証が表示されるまでのログイン可能回数(デフォルト3回)
define('PASSWORD_UNLOCK_TIME', time() + 60 * 10);		// ミスログイン判定解除までの待ち時間(デフォルト10分)

define('PREG_DATE', "/^([1-9][0-9]{3})\-(0[1-9]{1}|1[0-2]{1})\-(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/");		// 日付の書式：2000-01-01
define('PREG_TIME', "/^(0[0-9]{1}|1{1}[0-9]{1}|2{1}[0-3]{1}):(0[0-9]{1}|[1-5]{1}[0-9]{1}):(0[0-9]{1}|[1-5]{1}[0-9]{1})$/");		// 時間の書式：00:00:00～23:59:59

define('MAIL_REGEX', '/^[a-zA-Z0-9\._\/\?\+-]+\@[a-zA-Z0-9\._-]+\.+[A-Za-z]{2,4}$/');		// メールアドレス判定用正規表現

if (PUBLIC_URL == $_SERVER['SERVER_NAME']) {
	// 本番
	define('ROOT_ID', 'support@pochimomi.com');		// 運営ログイン用ID
	define('ROOT_PASSWORD', '');		// 運営ログイン用パスワード
} else {
	// dev環境、test環境
	define('ROOT_ID', 'support@pochimomi.com');		// 運営ログイン用ID
	define('ROOT_PASSWORD', '');		// 運営ログイン用パスワード
}

// エラーチェックClass読み込み
require_once(ROOT . 'extension/ErrorCheck.php');

// facebookログイン
require_once(ROOT . 'extension/facebook_login/facebook.php');

// facebookアプリ情報
if (PUBLIC_URL == $_SERVER['SERVER_NAME']) {
	// 本番
	$config = array(
		'appId' => '964157263669180',
		'secret' => '',
	  'cookie' => true, 
    'trustForwarded' =>  true
	);
} elseif (DEVELOP_URL == $_SERVER['SERVER_NAME']) {
	// dev環境
	$config = array(
		'appId' => '115534255501704',
		'secret' => '',
	  'cookie' => true, 
    'trustForwarded' =>  true
	);
} else {
	// test環境
	$config = array(
		'appId' => '1661838154065402',
		'secret' => '',
	  'cookie' => true
	);
}

$facebook = new Facebook($config);

if ("" == $facebook_redirect) {
	$facebook_redirect = "user/login.php";
}

//未ログインならログイン URL を取得してリンクを出力
$parameters = array(
	'scope' => 'public_profile,email', 
	'redirect_uri' => ROOT_URL . $facebook_redirect
);

// urlをパースして、悪意あるURL(keyもしくはvalueが英数字とアンダースコア,コロン,ハイフン以外)の場合はQueryを削除してリダイレクトをかける
$url = ROOT_URL_T . $_SERVER["REQUEST_URI"];
$parse_url_arr = parse_url ($url);
parse_str($parse_url_arr['query'], $query_arr);
$new_url = ROOT_URL_T . $_SERVER['SCRIPT_NAME'];
if (0 != count($query_arr)) {
	foreach ($query_arr as $key => $value) {
		if (preg_match('/^[a-zA-Z0-9_:-]+$/', $key) and preg_match('/^[a-zA-Z0-9_:-]+$/', $value)) {
			if (ROOT_URL_T . $_SERVER['SCRIPT_NAME'] == $new_url) {
				$new_url .= "?";
			} else {
				$new_url .= "&";
			}
			$new_url .= $key . "=" . $value;
		}
	}
}
if ($url != $new_url) {
	header("Location: " . $new_url);		// 指定ページに移動
	exit;
}


/*
 定数（配列）
 */

// ユーザータイプ(ログインしているユーザーの判定に利用)
$type_arr = array();
$type_arr["user"] = 1;		// 1:ユーザー
$type_arr["client"] = 2;	// 2:店舗オーナー
$type_arr["pro"] = 3;			// 3:揉み手
$type_arr["root"] = 4;			// 4:運営

// ユーザータイプごとのホームURL
$type_url_arr = array();
$type_url_arr[0] = "";		// nologin
$type_url_arr[1] = "user/";		// 1:ユーザー
$type_url_arr[2] = "admin/client/";	// 2:店舗オーナー
$type_url_arr[3] = "admin/pro/";			// 3:揉み手
$type_url_arr[4] = "root/";			// 4:運営

// ユーザータイプごとのNamespace
$type_namespace_arr = array();
$type_namespace_arr[1] = "";		// 1:ユーザー
$type_namespace_arr[2] = "CLIENT_";	// 2:店舗オーナー
$type_namespace_arr[3] = "PRO_";			// 3:揉み手
$type_namespace_arr[4] = "ROOT_";			// 4:運営

// 性別
$sex_arr = array();
$sex_arr[1] = "男性";
$sex_arr[2] = "女性";

// 評価の表記
$review_arr = array();
$review_arr[1] = "★☆☆☆☆";
$review_arr[2] = "★★☆☆☆";
$review_arr[3] = "★★★☆☆";
$review_arr[4] = "★★★★☆";
$review_arr[5] = "★★★★★";

// 都道府県
$location = array();
$location[1] = "北海道";
$location[2] = "青森県";
$location[3] = "岩手県";
$location[4] = "宮城県";
$location[5] = "秋田県";
$location[6] = "山形県";
$location[7] = "福島県";
$location[8] = "茨城県";
$location[9] = "栃木県";
$location[10] = "群馬県";
$location[11] = "埼玉県";
$location[12] = "千葉県";
$location[13] = "東京都";
$location[14] = "神奈川県";
$location[15] = "新潟県";
$location[16] = "富山県";
$location[17] = "石川県";
$location[18] = "福井県";
$location[19] = "山梨県";
$location[20] = "長野県";
$location[21] = "岐阜県";
$location[22] = "静岡県";
$location[23] = "愛知県";
$location[24] = "三重県";
$location[25] = "滋賀県";
$location[26] = "京都府";
$location[27] = "大阪府";
$location[28] = "兵庫県";
$location[29] = "奈良県";
$location[30] = "和歌山県";
$location[31] = "鳥取県";
$location[32] = "島根県";
$location[33] = "岡山県";
$location[34] = "広島県";
$location[35] = "山口県";
$location[36] = "徳島県";
$location[37] = "香川県";
$location[38] = "愛媛県";
$location[39] = "高知県";
$location[40] = "福岡県";
$location[41] = "佐賀県";
$location[42] = "長崎県";
$location[43] = "熊本県";
$location[44] = "大分県";
$location[45] = "宮崎県";
$location[46] = "鹿児島県";
$location[47] = "沖縄県";

// 都道府県(郵便番号からの検索用)
$zip_location = array();
$zip_location[1] = "(zipcode LIKE '00%' OR zipcode LIKE '04%' OR zipcode LIKE '05%' OR zipcode LIKE '06%' OR zipcode LIKE '07%' OR zipcode LIKE '08%' OR zipcode LIKE '09%')";
$zip_location[2] = "zipcode LIKE '03%'";
$zip_location[3] = "zipcode LIKE '02%'";
$zip_location[4] = "zipcode LIKE '98%'";
$zip_location[5] = "zipcode LIKE '01%'";
$zip_location[6] = "zipcode LIKE '99%'";
$zip_location[7] = "(zipcode LIKE '96%' OR zipcode LIKE '97%')";
$zip_location[8] = "(zipcode LIKE '30%' OR zipcode LIKE '31%')";
$zip_location[9] = "zipcode LIKE '32%'";
$zip_location[10] = "zipcode LIKE '37%'";
$zip_location[11] = "(zipcode LIKE '33%' OR zipcode LIKE '34%' OR zipcode LIKE '35%' OR zipcode LIKE '36%')";
$zip_location[12] = "(zipcode LIKE '26%' OR zipcode LIKE '27%' OR zipcode LIKE '28%' OR zipcode LIKE '29%')";
$zip_location[13] = "(zipcode LIKE '10%' OR zipcode LIKE '11%' OR zipcode LIKE '12%' OR zipcode LIKE '13%' OR zipcode LIKE '14%' OR zipcode LIKE '15%' OR zipcode LIKE '16%' OR zipcode LIKE '17%' OR zipcode LIKE '18%' OR zipcode LIKE '19%' OR zipcode LIKE '20%')";
$zip_location[14] = "(zipcode LIKE '21%' OR zipcode LIKE '22%' OR zipcode LIKE '23%' OR zipcode LIKE '24%' OR zipcode LIKE '25%')";
$zip_location[15] = "(zipcode LIKE '94%' OR zipcode LIKE '95%')";
$zip_location[16] = "zipcode LIKE '93%'";
$zip_location[17] = "zipcode LIKE '92%'";
$zip_location[18] = "zipcode LIKE '91%'";
$zip_location[19] = "zipcode LIKE '40%'";
$zip_location[20] = "(zipcode LIKE '38%' OR zipcode LIKE '39%')";
$zip_location[21] = "zipcode LIKE '50%'";
$zip_location[22] = "(zipcode LIKE '41%' OR zipcode LIKE '42%' OR zipcode LIKE '43%')";
$zip_location[23] = "(zipcode LIKE '44%' OR zipcode LIKE '45%' OR zipcode LIKE '46%' OR zipcode LIKE '47%' OR zipcode LIKE '48%' OR zipcode LIKE '49%')";
$zip_location[24] = "zipcode LIKE '51%'";
$zip_location[25] = "zipcode LIKE '52%'";
$zip_location[26] = "(zipcode LIKE '60%' OR zipcode LIKE '61%' OR zipcode LIKE '62%')";
$zip_location[27] = "zipcode LIKE '53%'";
$zip_location[28] = "(zipcode LIKE '65%' OR zipcode LIKE '66%' OR zipcode LIKE '67%')";
$zip_location[29] = "zipcode LIKE '63%'";
$zip_location[30] = "zipcode LIKE '64%'";
$zip_location[31] = "zipcode LIKE '68%'";
$zip_location[32] = "zipcode LIKE '69%'";
$zip_location[33] = "(zipcode LIKE '70%' OR zipcode LIKE '71%')";
$zip_location[34] = "(zipcode LIKE '72%' OR zipcode LIKE '73%')";
$zip_location[35] = "(zipcode LIKE '74%' OR zipcode LIKE '75%')";
$zip_location[36] = "zipcode LIKE '77%'";
$zip_location[37] = "zipcode LIKE '76%'";
$zip_location[38] = "zipcode LIKE '79%'";
$zip_location[39] = "zipcode LIKE '78%'";
$zip_location[40] = "(zipcode LIKE '80%' OR zipcode LIKE '81%' OR zipcode LIKE '82%' OR zipcode LIKE '83%')";
$zip_location[41] = "zipcode LIKE '84%'";
$zip_location[42] = "zipcode LIKE '85%'";
$zip_location[43] = "zipcode LIKE '86%'";
$zip_location[44] = "zipcode LIKE '87%'";
$zip_location[45] = "zipcode LIKE '88%'";
$zip_location[46] = "zipcode LIKE '89%'";
$zip_location[47] = "zipcode LIKE '90%'";

// 曜日
$weekday_arr = array();
$weekday_arr[1] = "月";
$weekday_arr[2] = "火";
$weekday_arr[3] = "水";
$weekday_arr[4] = "木";
$weekday_arr[5] = "金";
$weekday_arr[6] = "土";
$weekday_arr[7] = "日";

// 口座種別
$account_type_arr = array();
$account_type_arr[1] = "普通";
$account_type_arr[2] = "当座";

// カード種別
$card_type_arr = array();
$card_type_arr[1] = "visa";
$card_type_arr[2] = "mastercard";
$card_type_arr[3] = "jcb";

// 承認
$exam_flg_arr = array();
$exam_flg_arr[0] = "非承認";
$exam_flg_arr[1] = "承認";
$exam_flg_arr[2] = "非承認";

?>