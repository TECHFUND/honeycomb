<?php

// セッションスタート
session_start();

// Logパス
define("LOG_PATH", ROOT . "assets/log/err.log");

// DB情報
if ("jobglass.net" == $_SERVER['SERVER_NAME']) {
	// 本番(jobglass.net)
	define("DB_HOST", "localhost");
	define("DB_USERNAME", "mwb_jobglass");
	define("DB_PASSWORD", "M4uWiHqt");
	define("DB_NAME", "mwb_jobglass");
} else {
	// dev環境(jobglass.techfund.jp)
	define("DB_HOST", "localhost");
	define("DB_USERNAME", "mwb_jobglass");
	define("DB_PASSWORD", "M4uWiHqt");
	define("DB_NAME", "mwb_jobglass");
}

// DatabaseAccessClass読み込み
require_once(ROOT . 'extension/DatabaseAccess.php');

// 画像関連
define("IMAGE_DIR", ROOT . "assets/img/user_images/");			// 画像フォルダ
define("UPLOAD_MAX_IMAGE_SIZE", 104857600);			// アップロード画像の最大サイズ(bite)	// 104857600=100MB
define("UPLOAD_MAX_IMAGE_WIDTH", 5000);			// アップロード画像の最大横幅(px)
define("UPLOAD_MAX_IMAGE_HEIGHT", 5000);			// アップロード画像の最大縦幅(px)

// ファイル関連
define("FILE_DIR", ROOT . "assets/files/user_files/");			// ファイルフォルダ
define("UPLOAD_MAX_IMAGE_SIZE", 104857600);			// アップロードファイルの最大サイズ(bite)	// 104857600=100MB

// 共通処理Class読み込み
require_once(ROOT . 'extension/CommonFunctions.php');

define('MAIL_REGEX', '/^[a-zA-Z0-9\._\/\?\+-]+\@[a-zA-Z0-9\._-]+\.+[A-Za-z]{2,4}$/');		// メールアドレス判定用正規表現
define("SOLT_STR", "Fi8Eg3LV");			// パスワード暗号化用付加文字列

// エラーチェックClass読み込み
require_once(ROOT . 'extension/ErrorCheck.php');


// 決済情報(webpay)
const PUBLIC_KEY = "";
const SECRET_KEY = "";

// facebookログイン
require_once(ROOT . 'extension/facebook_login/facebook.php');

// facebookアプリ情報
if ("jobglass.net" == $_SERVER['SERVER_NAME']) {
	// 本番(jobglass.net)
	$config = array(
		'appId' => '665067740260213',
		'secret' => '7c8801c750abb6db9aebafd18bed598e',
	  'cookie' => true
	);
} else {
	// dev環境(jobglass.techfund.jp)
	$config = array(
		'appId' => '1136707276356128',
		'secret' => '92b27c9aa81e4005dcbd2f9fa61a04dd',
	  'cookie' => true
	);
}

$facebook = new Facebook($config);

if ("" == $facebook_redirect) {
	$facebook_redirect = "user/login.php";
}

//未ログインならログイン URL を取得してリンクを出力
if ("jobglass.net" == $_SERVER['SERVER_NAME']) {
	// 本番(jobglass.net)
	$parameters = array(
		'scope' => 'public_profile,email,user_birthday,user_work_history,user_likes,user_friends', 
		'redirect_uri' => 'http://jobglass.net/' . $facebook_redirect
	);
} else {
	// dev環境(jobglass.techfund.jp)
	$parameters = array(
		'scope' => 'public_profile,email,user_birthday,user_work_history,user_likes,user_friends', 
		'redirect_uri' => 'http://jobglass.techfund.jp/' . $facebook_redirect
	);
}

// メッセージヘッダーアイコンのための処理
$badge_flg = false;
if ($_SESSION["user_id"]) {
	// ログインしていれば、未読メッセージを取得
	$db = new DatabaseAccess();
	$badge = $db->simpleSQL("SELECT * FROM message_tbl WHERE read_flg = 0 AND receiver_user_id = " . $_SESSION["user_id"] . ";");
	if (0 != count($badge)) {
		$badge_flg = true;
	}
}

/*
 定数（配列）
 */

// workpanel
$workpanel = array();
$workpanel[1] = array("title" => "家事手伝い", "class" => "homeservice", "body" => "記入例：
依頼内容（部屋の掃除、風呂・トイレの掃除、食事の下ごしらえなど）
部屋数（　　）
掃除道具は持っている（はい・いいえ）

応募してくれたタスカーとはチャットで打ち合わせができます
このカテゴリーの目安予算：2,000円/1時間");
$workpanel[2] = array("title" => "プロの専門清掃", "class" => "cleaning", "body" => "記入例：
依頼内容（エアコン、風呂、トイレ、換気扇など）
台数・個数（　　）

応募してくれたタスカーとはチャットで打ち合わせができます
このカテゴリーの目安予算：4,000円");
$workpanel[3] = array("title" => "ペットのお世話", "class" => "doghuggy", "body" => "記入例：
依頼内容（預かり・宿泊・散歩など）
対象（犬・猫・その他）
数（　　）匹
時間/泊数（　　　）

応募してくれたタスカーとはチャットで打ち合わせができます");
$workpanel[4] = array("title" => "日曜大工", "class" => "diy", "body" => "記入例：
依頼内容（IKEA組立て、製作、リフォームなど）
種類（ベッド、家具、棚、壁など）
大工道具は持っている（はい・いいえ）

応募してくれたタスカーとはチャットで打ち合わせができます
このカテゴリーの目安予算：2,500円/1時間");
$workpanel[5] = array("title" => "引越し/荷物運搬", "class" => "moving", "body" => "記入例：
運搬の種類（引越し、当日配送、指定日配送）
どのような荷物ですか？（書類、単身者荷物など）
どこからどこに運びますか？（　　　　　）

応募してくれたタスカーとはチャットで打ち合わせができます
このカテゴリーの目安予算：2,000円/1時間");
$workpanel[6] = array("title" => "庭のお手入れ", "class" => "garden", "body" => "記入例：
依頼内容（庭掃除、草刈り、水やり、庭木剪定など）
掃除の道具は持っている（はい・いいえ）

応募してくれたタスカーとはチャットで打ち合わせができます
このカテゴリーの目安予算：3,000円/1時間");
$workpanel[7] = array("title" => "PC/スマホサポート", "class" => "pcspsupport", "body" => "記入例：
対象機器（PC/スマートフォン/タブレット/TVなど）
機種の名前・メーカーの名前（　　　）
サポート内容・症状（インターネット接続、メール設定、トラブルなど）

応募してくれたタスカーとはチャットで打ち合わせができます
このカテゴリーの目安予算：3,000円/1時間");
$workpanel[8] = array("title" => "ボランティア", "class" => "volunteer", "body" => "記入例：
主催者名（　　　）
募集・イベント内容（　　　　　）
応募方法（　　　）
備考（　　　）
参加が有償の場合はその旨ご記入ください

応募してくれたタスカーとはチャットで話しができます");
$workpanel[9] = array("title" => "何でもやります", "class" => "anything", "body" => "記入例：
依頼内容（カテゴリーに当てはまらない依頼など）
何でも依頼してみましょう！
手伝ってくれるタスカーが見つかります

応募してくれたタスカーとはチャットで打ち合わせができます");

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

// 都道府県
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
$weekday_arr[0] = "日";
$weekday_arr[1] = "月";
$weekday_arr[2] = "火";
$weekday_arr[3] = "水";
$weekday_arr[4] = "木";
$weekday_arr[5] = "金";
$weekday_arr[6] = "土";


// ユーザータイプ
$type_flg_array = array();
$type_flg_array[1] = "依頼者";
$type_flg_array[2] = "タスカー";
$type_str_array = array();
$type_str_array[1] = "client_user_review_flg";
$type_str_array[2] = "apply_user_review_flg";


// 評価星
$review_star_array = array();
$review_star_array[5] = "★★★★★";
$review_star_array[4] = "★★★★☆";
$review_star_array[3] = "★★★☆☆";
$review_star_array[2] = "★★☆☆☆";
$review_star_array[1] = "★☆☆☆☆";
$review_star_array[0] = "☆☆☆☆☆";


// 評価
$review_array = array();
$review_array[1][5] = "★★★★★(最高！)";
$review_array[1][4] = "★★★★☆(よかった！)";
$review_array[1][3] = "★★★☆☆(助かった！！)";
$review_array[1][2] = "★★☆☆☆(微妙！)";
$review_array[1][1] = "★☆☆☆☆(もう頼まないっ！)";
$review_array[2][5] = "★★★★★(最高！)";
$review_array[2][4] = "★★★★☆(よかった！)";
$review_array[2][3] = "★★★☆☆(まあまあ！！)";
$review_array[2][2] = "★★☆☆☆(微妙！)";
$review_array[2][1] = "★☆☆☆☆(もう仕事依頼ほしくない！)";


// 拡散コメント
$viral_array = array();
$viral_array[1] = "助かった！！";
$viral_array[2] = "ママ友ができた！！";
$viral_array[3] = "楽しく仕事できた！";


// 認証フラグ
$certificate_flg_array = array();
$certificate_flg_array[0] = "未登録";
$certificate_flg_array[1] = "認証済み";

?>