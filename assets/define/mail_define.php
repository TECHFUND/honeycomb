<?php

/**
 * 共通メール設定
 */

// Logパス
define("MAIL_LOG_PATH", ROOT . "assets/log/mail_err.log");

// メール基本設定
define('MAIL_FROM', '"' . SERVICE_TITLE . '" <support@pochimomi.com>');				// システムから送られるメールの送信元
define('MAIL_FROM_ADDRESS', '<support@pochimomi.com>');							// システムから送られるメールの送信元(アドレスのみ)
define('ADMIN_ADDRESS', 'support@pochimomi.com');								// 実際に送られる管理者のアドレス
define('RETURN_PATH', '-f support@pochimomi.com');									// Return-path
define('ORG', mb_internal_encoding());	// 元のエンコーディングを保存
mb_language("japanese");		// エンコーディング
mb_internal_encoding("UTF-8");	// エンコーディング

// SendGrid設定
define('SENDGRID_API_KEY', 'JY-NvJ-aR9qMoejV0WBXxA');									// SendGrid APIキー
define('REQUEST', 'https://api.sendgrid.com/api/mail.send.json');									// API URL
define('API_USER', '');									// ユーザーID(メールアドレス)
define('API_PASS', '');									// パスワード

// メールヘッダ
define('MAIL_HEADERS', 
	"MIME-Version: 1.0\r\n"
	  . "Message-Id: <" . md5(uniqid(microtime())) . "@" . $_SERVER['SERVER_NAME'] . ">\r\n"
	  . 'From: ' . MAIL_FROM_ADDRESS . "\r\n"
	  .	'Reply-To: ' . MAIL_FROM_ADDRESS . "\r\n"
	  .	'X-Mailer: PHP/' . phpversion()
);


$building = "";
if ($_SESSION and $_SESSION["booking"]["building_id"]) {
	$building = "?building_id=" . $_SESSION["booking"]["building_id"];
}

// メールフッタ
define('MAIL_COMMON_FOOTER', 
		"\r\n" . 
		"--\r\n" . 
		SERVICE_TITLE . " \r\n" . 
		ROOT_URL . $building . " \r\n" .
		"■お問い合わせ先 \r\n" . 
		MAIL_FROM . " \r\n"
);

/* メール文言 */

// 登録
define('REGIST_SUBJECT', "【" . SERVICE_TITLE . "】ご登録をありがとうございます！");
define('REGIST_SUBJECT_ADMIN', "admin【" . SERVICE_TITLE . "】新規のオフィスワーカーの登録がありました。");
define('REGIST_BODY', 
"%name%様

こんにちは、" . SERVICE_TITLE . "事務局です。
この度は、「" . SERVICE_TITLE . "」に下記の通りご登録いただきありがとうございます。

企業名:%company_name% 
氏名:%name% 
メールアドレス:%email% 
携帯電話番号:%tel% 

※今後の情報は、メールアドレスとSMS宛にご連絡させていただきます。 
※万一、このメールにお心当たりの無い場合は、
どなたかがメールアドレスを間違って入力してしまった可能性がありますのでお手数ですがメールを削除してくださいますようお願いいたします。
" . 
MAIL_COMMON_FOOTER
);
define('REGIST_BODY_ADMIN', 
		"新規のオフィスワーカー登録がありました。\r\n" . 
		"\r\n" . 
		"企業名:%company_name% \r\n" . 
		"氏名:%name% \r\n" . 
		"メールアドレス:%email% \r\n" . 
		"携帯電話番号:%tel% \r\n" . 
		ROOT_URL . $building . " \r\n" .
		"\r\n" . 
		MAIL_COMMON_FOOTER
);

// 店舗登録
define('REGIST_CLIENT_SUBJECT', "【" . SERVICE_TITLE . "】ご登録をありがとうございます。");
define('REGIST_CLIENT_SUBJECT_ADMIN', "admin【" . SERVICE_TITLE . "】新規の施術院登録がありました");
define('REGIST_CLIENT_BODY', 
"%client_name%様

こんにちは、" . SERVICE_TITLE . "事務局です。
この度は、「" . SERVICE_TITLE . "」にご登録いただきありがとうございます。

施術院名:%client_name%
メールアドレス:%email%
携帯電話番号:%tel%

※今後の情報は、メールアドレスとSMS宛にご連絡させていただきます。
※万一、このメールにお心当たりの無い場合は、
どなたかがメールアドレスを間違って入力してしまった可能性がありますのでお手数ですがメールを削除してくださいますようお願いいたします。

■オーナーログイン画面:" . ROOT_URL . "admin/client/login.php
" .
MAIL_COMMON_FOOTER
);
define('REGIST_CLIENT_BODY_ADMIN', 
		"新規の施術院登録がありました。\r\n" . 
		"\r\n" . 
		"施術院名:%client_name%\r\n" . 
		"メールアドレス:%email%\r\n" . 
		"携帯電話番号:%tel%\r\n" . 
		"\r\n" . 
		MAIL_COMMON_FOOTER
);

// 揉み手登録
define('REGIST_PRO_SUBJECT', "【" . SERVICE_TITLE . "】ご登録をありがとうございます。");
define('REGIST_PRO_SUBJECT_CLIENT', "【" . SERVICE_TITLE . "】ご登録をありがとうございます。");
define('REGIST_PRO_SUBJECT_ADMIN', "admin【" . SERVICE_TITLE . "】新規の施術師登録がありました。");
define('REGIST_PRO_BODY', 
"%pro_name%様

こんにちは、" . SERVICE_TITLE . "事務局です。

施術院 %client_name% 様があなたを施術師として登録しました。

施術院名:%client_name%
施術師名:%pro_name%
メールアドレス:%email%
携帯電話番号:%tel%

※今後の情報は、メールアドレスとSMS宛にご連絡させていただきます。
※万一、このメールにお心当たりの無い場合は、
どなたかがメールアドレスを間違って入力してしまった可能性がありますのでお手数ですがメールを削除してくださいますようお願いいたします。

■施術師ログイン画面:" . ROOT_URL . "admin/pro/login.php
" . 
MAIL_COMMON_FOOTER
);
define('REGIST_PRO_BODY_CLIENT', 
"%client_name%様

こんにちは、" . SERVICE_TITLE . "事務局です。
この度は、「" . SERVICE_TITLE . "」にご登録いただきありがとうございます。

施術院名:%client_name%
施術師名:%pro_name%
メールアドレス:%email%
携帯電話番号:%tel%

※今後の情報は、メールアドレスとSMS宛にご連絡させていただきます。
※万一、このメールにお心当たりの無い場合は、
どなたかがメールアドレスを間違って入力してしまった可能性がありますのでお手数ですがメールを削除してくださいますようお願いいたします。

■施術師ログイン画面:" . ROOT_URL . "admin/pro/login.php
" . 
MAIL_COMMON_FOOTER
);
define('REGIST_PRO_BODY_ADMIN', 
		"新規の施術師登録がありました。\r\n" . 
		"\r\n" . 
		"施術院名:%client_name%\r\n" . 
		"施術師名:%pro_name%\r\n" . 
		"メールアドレス:%email%\r\n" . 
		"携帯電話番号:%tel%\r\n" . 
		"\r\n" . 
		MAIL_COMMON_FOOTER
);

// お問い合わせ
define('CONTACT_SUBJECT', "【" . SERVICE_TITLE . "】お問い合わせありがとうございます。");
define('CONTACT_SUBJECT_ADMIN', "admin【" . SERVICE_TITLE . "】お問い合わせが届きました。");
define('CONTACT_BODY', 
		"%name% 様\r\n" . 
		"\r\n" . 
		"こんにちは、" . SERVICE_TITLE . "事務局です。\r\n" . 
		"下記の内容にてお問い合わせを承りました。\r\n" . 
		"\r\n" . 
		"要件：%requirement%\r\n" . 
		"氏名：%name%\r\n" . 
		"メールアドレス：%email%\r\n" . 
		"内容：%body%\r\n" . 
		"\r\n" . 
		"必要あれば、後日担当よりご連絡差し上げます。\r\n" . 
		"\r\n" . 
		MAIL_COMMON_FOOTER
);
define('CONTACT_BODY_ADMIN', 
		"管理者様\r\n" . 
		"\r\n" . 
		"こんにちは、" . SERVICE_TITLE . "事務局です。\r\n" . 
		"下記の内容にてお問い合わせを承りました。\r\n" . 
		"\r\n" . 
		"要件：%requirement%\r\n" . 
		"氏名：%name%\r\n" . 
		"メールアドレス：%email%\r\n" . 
		"内容：%body%\r\n" . 
		"\r\n" . 
		"ご対応よろしくお願いします。\r\n" . 
		"\r\n" . 
		MAIL_COMMON_FOOTER
);


// パスワード忘れ
define('FORGET_PASSWORD_SUBJECT', "【" . SERVICE_TITLE . "】パスワード再発行");
define('FORGET_PASSWORD_BODY', 
		"%name% 様\r\n" . 
		"\r\n" . 
		"こんにちは、" . SERVICE_TITLE . "事務局です。\r\n" . 
		"\r\n" . 
		"パスワード再設定フォーム\r\n" . 
		"%url%\r\n" . 
		"\r\n" . 
		"URLをクリックし、新しいパスワードを設定してください。\r\n" . 
		"※URLの有効期限は24時間です。" . 
		"\r\n" . 
		MAIL_COMMON_FOOTER
);

// パスワード再発行
define('RE_INPUT_PASSWORD_SUBJECT', "【" . SERVICE_TITLE . "】パスワード再発行");
define('RE_INPUT_PASSWORD_BODY', 
		"%name% 様\r\n" . 
		"\r\n" . 
		"こんにちは、" . SERVICE_TITLE . "事務局です。\r\n" . 
		"パスワードを再発行しました。\r\n" . 
		"\r\n" . 
		"■ログインURL\r\n" . 
		"%url%\r\n" . 
		"\r\n" . 
		MAIL_COMMON_FOOTER
);


// 依頼OK:管理者
define('OK_SUBJECT', "【" . SERVICE_TITLE . "】予約を許可しました");
define('OK_SUBJECT_CLIENT', "【" . SERVICE_TITLE . "】予約を許可しました");
define('OK_SUBJECT_ADMIN', "admin【" . SERVICE_TITLE . "】予約承認");
define('OK_BODY', 
		"%name%様\r\n" . 
		"\r\n" . 
		"こんにちは、" . SERVICE_TITLE . "事務局です。\r\n" . 
		"%name%様のご予約が成立しました。\r\n" . 
		"------------------------------\r\n" . 
		"お客様氏名：%user_name%\r\n" . 
		"施術師：%name%\r\n" . 
		"施術場所：%address%\r\n" . 
		"開始時間：%start_dt%\r\n" . 
		"終了時間：%end_dt%\r\n" . 
		"料金：%amount%円\r\n" . 
		"------------------------------\r\n" . 
		"\r\n" . 
		"予約の詳細はこちら\r\n" . 
		"%url%\r\n" . 
		"\r\n" . 
		MAIL_COMMON_FOOTER
);
define('OK_BODY_CLIENT', 
		"%name%様\r\n" . 
		"\r\n" . 
		"こんにちは、" . SERVICE_TITLE . "事務局です。\r\n" . 
		"%pro_name%様のご予約が成立しました。\r\n" . 
		"------------------------------\r\n" . 
		"お客様氏名：%user_name%\r\n" . 
		"施術師：%pro_name%\r\n" . 
		"施術場所：%address%\r\n" . 
		"開始時間：%start_dt%\r\n" . 
		"終了時間：%end_dt%\r\n" . 
		"料金：%amount%円\r\n" . 
		"------------------------------\r\n" . 
		"\r\n" . 
		"予約の詳細はこちら\r\n" . 
		"%url%\r\n" . 
		"\r\n" . 
		MAIL_COMMON_FOOTER
);
define('OK_BODY_ADMIN', 
		"新規の予約成立がありました。\r\n" . 
		"%pro_name%様のご予約が成立しました。\r\n" . 
		"------------------------------\r\n" . 
		"お客様氏名：%user_name%\r\n" . 
		"施術師：%pro_name%\r\n" . 
		"施術場所：%address%\r\n" . 
		"開始時間：%start_dt%\r\n" . 
		"終了時間：%end_dt%\r\n" . 
		"料金：%amount%円\r\n" . 
		"------------------------------\r\n" . 
		"\r\n" . 
		"予約の詳細はこちら\r\n" . 
		"%url%\r\n" . 
		"\r\n" . 
		MAIL_COMMON_FOOTER
);

// 依頼NG:管理者
define('NG_SUBJECT_ADMIN', "admin【" . SERVICE_TITLE . "】予約拒否");
define('NG_BODY_ADMIN', 
		"予約が成立しませんでした。\r\n" . 
		"\r\n" . 
		"------------------------------\r\n" . 
		"お客様氏名：%user_name%\r\n" . 
		"施術師：%pro_name%\r\n" . 
		"施術場所：%address%\r\n" . 
		"開始時間：%start_dt%\r\n" . 
		"終了時間：%end_dt%\r\n" . 
		"料金：%amount%円\r\n" . 
		"------------------------------\r\n" . 
		"\r\n" . 
		"スタンバイは%standby_num%件です。\r\n" . 
		"予約の詳細はこちら\r\n" . 
		"%url%\r\n" . 
		"\r\n" . 
		MAIL_COMMON_FOOTER
);

// 承認後キャンセル:管理者
define('CANCEL_SUBJECT_ADMIN', "admin【" . SERVICE_TITLE . "】キャンセル");
define('CANCEL_BODY_ADMIN', 
		"一度成立した予約がオフィスワーカーからキャンセルされました。\r\n" . 
		"\r\n" . 
		"------------------------------\r\n" . 
		"お客様氏名：%user_name%\r\n" . 
		"施術師：%pro_name%\r\n" . 
		"施術場所：%address%\r\n" . 
		"開始時間：%start_dt%\r\n" . 
		"終了時間：%end_dt%\r\n" . 
		"料金：%amount%円\r\n" . 
		"------------------------------\r\n" . 
		"\r\n" . 
		"%refund%\r\n" . 
		"予約の詳細はこちら\r\n" . 
		"%url%\r\n" . 
		"\r\n" . 
		MAIL_COMMON_FOOTER
);


// スタンバイ開始:施術師
define('STANDBY_SUBJECT', "【" . SERVICE_TITLE . "】スタンバイが開始されました。");
define('STANDBY_BODY', 
		"施術師様\r\n" . 
		"\r\n" . 
		"こんにちは、" . SERVICE_TITLE . "事務局です。\r\n" . 
		"近隣の施術師に予約依頼が入りました。第二候補としてスタンバイしますか？\r\n" . 
		"その施術師が依頼を受けなかった場合に、あなたが選ばれる可能性があります。予約詳細を確認してスタンバイしてください。\r\n" . 
		"\r\n" . 
		"施術場所：%address%\r\n" . 
		"開始時間：%start%\r\n" .
		"終了時間：%end%\r\n" .
		"料金：%amount%円\r\n" .
		"スタンバイはこちら\r\n" . 
		"%url%\r\n" . 
		"\r\n" . 
		"\r\n" . 
		MAIL_COMMON_FOOTER
);


// スタンバイ失敗:施術師
define('STANDBY_NG_SUBJECT', "スタンバイが失敗しました。");
define('STANDBY_NG_BODY', 
		"%name%様\r\n" . 
		"\r\n" . 
		"こんにちは、" . SERVICE_TITLE . "事務局です。\r\n" . 
		"スタンバイしていた予約が他の方に決定してしまいました。\r\n" . 
		"\r\n" . 
		"■スタンバイURL\r\n" . 
		"%url%\r\n" . 
		"\r\n" . 
		"\r\n" . 
		MAIL_COMMON_FOOTER
);



?>