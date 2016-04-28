<?php

/**
 * 共通SMS設定
 */

// Logパス
define("SMS_LOG_PATH", ROOT . "assets/log/mail_err.log");

// SMS基本設定
require_once(ROOT . 'extension/twilio/Services/Twilio.php');

define("ACCOUNT_SID", "");
define("AUTH_TOKEN", "");
define("SMS_FROM", "+14242756774");		// Twilioで購入した電話番号
define("ADMIN_TEL", "080-4045-4560");		// 運営の電話番号

// SMSフッタ
define('SMS_COMMON_FOOTER', 
		""
);

$building = "";
if (isset($_SESSION["booking"]) and $_SESSION["booking"]["building_id"]) {
	$building = "?building_id=" . $_SESSION["booking"]["building_id"];
}

/* SMS文言 */

// 登録
define('SMS_REGIST_BODY', 
		"【" . SERVICE_TITLE . "】ご登録をありがとうございます。ご利用の際にはこちらの電話番号にSMSが届きますので、予約通知などご確認を宜しくお願いいたします。" . ROOT_URL . $building .  
		SMS_COMMON_FOOTER
);

// 店舗登録
define('SMS_CLIENT_REGIST_BODY', 
		"【" . SERVICE_TITLE . "】ご登録ありがとうございます！今後こちらにお知らせをお送りいたします。" . ROOT_URL . "admin/pro/regist.php" .
		SMS_COMMON_FOOTER
);

// 揉み手登録
define('SMS_PRO_REGIST_BODY', 
		"【" . SERVICE_TITLE . "】%client_name%様があなたを施術師として登録しました。ご利用の際にはこちらの電話番号にSMSが届きますので、予約通知などご確認を宜しくお願いいたします。施術師ログイン画面 : " . ROOT_URL . "admin/pro/login.php" .
		SMS_COMMON_FOOTER
);

// 依頼:揉み手
define('SMS_REQUEST_BODY', 
		"【" . SERVICE_TITLE . "】予約依頼が届きました。\nお客様氏名：%user_name%　施術場所：%address%　開始時間：%start%　終了時間：%end%　料金：%amount%円\n5分以内に下記リンクから「承諾」か「拒否」を選択ください。\n・予約を確認する\n%url%" . 
		SMS_COMMON_FOOTER
);

// 依頼:オーナー
define('SMS_REQUEST_CLIENT_BODY', 
		"【" . SERVICE_TITLE . "】%name%様に予約依頼が届きました。\nお客様氏名：%user_name%　施術場所：%address%　開始時間：%start%　終了時間：%end%　料金：%amount%円\n%name%さんに代わって「承諾」か「拒否」を回答する場合は、5分以内に下記リンクからお願いいたします。\n・予約を確認する\n%url%" . 
		SMS_COMMON_FOOTER
);

// 依頼:ユーザー
define('SMS_REQUEST_USER_BODY', 
		"【" . SERVICE_TITLE . "】施術師に予約依頼を投げました(予約番号: %booking_id%)\n5分以内に結果が届きますので少々お待ちください。\n予約状況の確認はこちら。 %url%" . 
		SMS_COMMON_FOOTER
);


// 依頼OK
define('SMS_OK_BODY', 
		"【" . SERVICE_TITLE . "】ご予約が成立しました。\n------------------------------\n施術師：%pro_name%\n施術場所：%address%\n開始時間：%start_dt%\n終了時間：%end_dt%\n料金：%amount%円の決済が完了しました。\n------------------------------\n\n予約の詳細はこちら\n%url%" . 
		SMS_COMMON_FOOTER
);

// 依頼NG
define('SMS_NG_BODY', 
		"【" . SERVICE_TITLE . "】残念ながら、時間内にご予約の確認が取れませんでした。現在、施術中かもしれません。\nすぐに予約可能な別の施術師がスタンバイしている場合があります。こちらからご確認ください" . ROOT_URL . "reserve/ng.php" . 
		SMS_COMMON_FOOTER
);


// 別時間提案
define('SMS_PROPOSAL_BODY', 
		"別の時間帯(%time%〜)を提案されました。" . ROOT_URL . "index.php?id=%pro_id%&start_time=%start_time%" . 
		SMS_COMMON_FOOTER
);


// 予約時間に達した際:ユーザー
define('SMS_QUEUE_USER_BODY', 
		"【" . SERVICE_TITLE . "】ご予約のお時間の5分前となりました(予約番号: %booking_id%)\n予約の詳細と施術師の連絡先はこちら " . ROOT_URL . "reserve/ok.php" . 
		SMS_COMMON_FOOTER
);

// 予約時間に達した際:揉み手
define('SMS_QUEUE_PRO_BODY', 
		"【" . SERVICE_TITLE . "】ご予約のお時間の5分前となりました。\nお客様氏名：%user_name%　施術場所：%address%　開始時間：%start_dt%　終了時間：%end_dt%　料金：%amount%円\n予約の詳細とお客様の連絡先はこちら %url%" . 
		SMS_COMMON_FOOTER
);

// キャンセル:揉み手
define('SMS_CANCEL_BODY', 
		"残念ながら、%user_name% 様からご予約のキャンセルがありました。お客様氏名：%user_name%　施術場所：%address%　開始時間：%start_dt%　終了時間：%end_dt%　料金：%amount%円" . 
		SMS_COMMON_FOOTER
);

// キャンセル:オーナー
define('SMS_CANCEL_CLIENT_BODY', 
		"残念ながら、%name% 様宛に %user_name% 様からご予約のキャンセルがありました。お客様氏名：%user_name%　施術場所：%address%　開始時間：%start_dt%　終了時間：%end_dt%　料金：%amount%円" . 
		SMS_COMMON_FOOTER
);

// キャンセル:ユーザー
define('SMS_CANCEL_USER_BODY', 
		"ご予約をキャンセルいたしました。履歴はこちらからご確認ください " . ROOT_URL . "user/history.php" . 
		SMS_COMMON_FOOTER
);

// キャンセル(許可後):ユーザー
define('SMS_DONE_CANCEL_USER_BODY', 
		"残念ながら、時間内にご予約の確認が取れませんでした。現在、施術中かもしれません。\n現在予約可能な別の施術師がスタンバイしている場合があります。こちらからご確認ください " . ROOT_URL . "reserve/ng.php" . 
		SMS_COMMON_FOOTER
);

// レビュー
define('SMS_QUEUE_REVIEW_BODY', 
		"【" . SERVICE_TITLE . "】施術師 %pro_name% さんへのレビューにご協力をお願いいたします。\n同僚の皆様のご参考として、ぜひご協力ください。\nレビュー記入はこちらから" . ROOT_URL . "reserve/review.php?booking_id=%booking_id%" . 
		SMS_COMMON_FOOTER
);

define('SMS_RECOMMEND_BODY', 
		"肩がこったので、このあと %start_dt% から施術師さんをオフィスに呼びました。まだ前後の時間に空きがあるそうなので、よかったら仕事の合間にどうぞ。%url%"
);

?>