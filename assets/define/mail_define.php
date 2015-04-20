<?php

/**
 * 共通メール設定
 */
 

// 基本設定 //
define('ROOT_URL', 'http://task-all.net/');									// サイトURL
define('MAIL_FROM', '"タスカル" <info@task-all.net>');				// システムから送られるメールの送信元
define('MAIL_FROM_ADDRESS', '<info@task-all.net>');							// システムから送られるメールの送信元(アドレスのみ)
define('ADMIN_ADDRESS', 'info@task-all.net, info@techfund.jp');								// 実際に送られる管理者のアドレス
define('RETURN_PATH', '-f info@task-all.net');									// Return-path
define('ORG', mb_internal_encoding());	// 元のエンコーディングを保存
mb_language("japanese");		// エンコーディング
mb_internal_encoding("UTF-8");// エンコーディング

define('MAIL_HEADERS', 			// メールヘッダ
	"MIME-Version: 1.0\r\n"
	  . "Message-Id: <" . md5(uniqid(microtime())) . "@task-all.net>\r\n"
	  . 'From: ' . MAIL_FROM_ADDRESS . "\r\n"
	  .	'Reply-To: ' . MAIL_FROM_ADDRESS . "\r\n"
	  .	'X-Mailer: PHP/' . phpversion()
);

define('MAIL_COMMON_FOOTER', 			// メール共通フッタ
		"\n" . 
		"～みんなで助ける みんなが助かる～\n" . 
		"くらしのお手伝いマッチングサービス『タスカル』\n" . 
		"\n" . 
		"*************************\n" . 
		"タスカル\n" . 
		ROOT_URL . "\n" .
		"■お問い合わせ先 \n" . 
		MAIL_FROM . "\n" .
		"*************************"
);

// ユーザーへのメール関連(登録) //
define('R1_SUBJECT', "【タスカル】ご登録ありがとうございます！");
define('R1_SUBJECT_ADMIN', "admin【タスカル】ID登録がありました");
define('R1_BODY', 
		"こんにちは！タスカル事務局です。\n" . 
		"\n" . 
		"この度は「タスカル」に事前登録いただきありがとうございます。\n" . 
		"サービスが開始されましたらメールにてご連絡させていただきます。\n" . 
		"\n" . 
		"お名前:%name%\n" . 
		"メールアドレス:%mail_address%\n" . 
		"%body%\n" . 
		"\n" . 
		"━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" . 
		"本メールは事務局から自動配信しております。\n" . 
		"こちらのメールへは返信できませんのでご注意ください。\n" . 
		"サイトの利用に関しまして、ご不明な点、お気づきの点がありましたら、\n" . 
		"タスカル事務局までお気軽にご連絡ください。\n" . 
		"今後共タスカルをご愛顧いただけますよう、よろしくお願いします。\n" . 
		"\n" . 
		"\n" . 
		"※万一、このメールにお心当たりの無い場合は、\n" . 
		"どなたかがメールアドレスを間違って入力してしまった可能性がありますのでお手数ですがメールを削除してくださいますようお願いいたします。\n" . 
		"\n" . 
		MAIL_COMMON_FOOTER
);

// ユーザーへのメール関連(登録) //
define('REGIST_SUBJECT', "【タスカル】ご登録ありがとうございます！");
define('REGIST_SUBJECT_ADMIN', "admin【タスカル】ID登録がありました");
define('REGIST_BODY', 
		"タスカルにご登録いただきありがとうございます。\n" . 
		"今後の情報は、こちらのメールアドレス宛にご連絡させていただきます。\n" . 
		"\n" . 
		"ご登録いただいた内容は下記の通りです。\n" . 
		"\n" . 
		"メールアドレス:%mail_address%\n" . 
		"\n" . 
		"\n" . 
		"※万一、このメールにお心当たりの無い場合は、\n" . 
		"どなたかがメールアドレスを間違って入力してしまった可能性がありますのでお手数ですがメールを削除してくださいますようお願いいたします。\n" . 
		"\n" . 
		MAIL_COMMON_FOOTER
);


// ユーザーへのメール関連(依頼決定) //
define('APPLY_SUBJECT', "【タスカル】依頼に応募がありました！");
define('APPLY_BODY', 
		"こんにちは、タスカルです。\n" . 
		"あなたが募集していた依頼に応募がありました。\n" . 
		"\n" . 
		"確認は下記よりお願いいたします。\n" . 
		ROOT_URL . "job.php?job_id=%job_id%\n" . 
		"\n" . 
		"\n" . 
		"※万一、このメールにお心当たりの無い場合は、\n" . 
		"どなたかがメールアドレスを間違って入力してしまった可能性がありますのでお手数ですがメールを削除してくださいますようお願いいたします。\n" . 
		"\n" . 
		MAIL_COMMON_FOOTER
);


// ユーザーへのメール関連(依頼決定) //
define('DECIDE_SUBJECT', "【タスカル】依頼があなたに決定しました！");
define('DECIDE_BODY', 
		"こんにちは、タスカルです。\n" . 
		"応募していた依頼が、あなたに決定しました。\n" . 
		"\n" . 
		"確認は下記よりお願いいたします。\n" . 
		ROOT_URL . "job.php?job_id=%job_id%\n" . 
		"\n" . 
		"\n" . 
		"※万一、このメールにお心当たりの無い場合は、\n" . 
		"どなたかがメールアドレスを間違って入力してしまった可能性がありますのでお手数ですがメールを削除してくださいますようお願いいたします。\n" . 
		"\n" . 
		MAIL_COMMON_FOOTER
);


// ユーザーへのメール関連(お仕事完了) //
define('DONE_SUBJECT', "【タスカル】お仕事が完了しました！");
define('DONE_BODY', 
		"こんにちは、タスカルです。\n" . 
		"あなたが依頼していたお仕事が完了しました。\n" . 
		"\n" . 
		"確認は下記よりお願いいたします。\n" . 
		ROOT_URL . "job.php?job_id=%job_id%\n" . 
		"\n" . 
		"\n" . 
		"※万一、このメールにお心当たりの無い場合は、\n" . 
		"どなたかがメールアドレスを間違って入力してしまった可能性がありますのでお手数ですがメールを削除してくださいますようお願いいたします。\n" . 
		"\n" . 
		MAIL_COMMON_FOOTER
);


// ユーザーへのメール関連(予約) //
define('RESARVATION_SUBJECT', "【タスカル】予約がありました。");
define('RESARVATION_SUBJECT_ADMIN', "admin【タスカル】予約がありました");
define('RESARVATION_BODY', 
		"こんにちは、タスカルです。\n" . 
		"あなた宛に予約がありました。\n" . 
		"\n" . 
		"預け日:%start_dt%\n" . 
		"迎え日:%end_dt%\n" . 
		"頭数:%dog_num%\n" . 
		"メッセージ:%message%\n" . 
		"\n" . 
		"詳細は下記よりご確認ください。\n" . 
		ROOT_URL . "message_detail.php?user_id=%user_id%\n" . 
		"\n" . 
		"※万一、このメールにお心当たりの無い場合は、\n" . 
		"どなたかがメールアドレスを間違って入力してしまった可能性がありますのでお手数ですがメールを削除してくださいますようお願いいたします。\n" . 
		"\n" . 
		MAIL_COMMON_FOOTER
);


// ユーザーへのメール関連(メッセージ) //
define('MESSAGE_SUBJECT', "【タスカル】メッセージが届きました！");
define('MESSAGE_SUBJECT_ADMIN', "admin【タスカル】メッセージ送付がありました");	
define('MESSAGE_BODY', 
		"こんにちは、タスカルです。\n" . 
		"あなた宛にメッセージが届きました。\n" . 
		"\n" . 
		"確認は下記よりお願いいたします。\n" . 
		ROOT_URL . "message_detail.php?id=%user_id%\n" . 
		"\n" . 
		"\n" . 
		"※万一、このメールにお心当たりの無い場合は、\n" . 
		"どなたかがメールアドレスを間違って入力してしまった可能性がありますのでお手数ですがメールを削除してくださいますようお願いいたします。\n" . 
		"\n" . 
		MAIL_COMMON_FOOTER
);


// ユーザーへのメール関連(予約承認) //
define('OK_SUBJECT', "【タスカル】予約が承認されました！");
define('OK_SUBJECT_ADMIN', "admin【タスカル】予約が承認されました");
define('OK_BODY', 
		"こんにちは、タスカルです。\n" . 
		"予約が承認されました。\n" . 
		"\n" . 
		"預け日:%start_dt%\n" . 
		"迎え日:%end_dt%\n" . 
		"\n" . 
		"確認は下記よりお願いいたします。\n" . 
		ROOT_URL . "message_detail.php?id=%user_id%\n" . 
		"\n" . 
		"\n" . 
		"※万一、このメールにお心当たりの無い場合は、\n" . 
		"どなたかがメールアドレスを間違って入力してしまった可能性がありますのでお手数ですがメールを削除してくださいますようお願いいたします。\n" . 
		"\n" . 
		MAIL_COMMON_FOOTER
);

// ユーザーへのメール関連(予約拒否) //
define('NG_SUBJECT', "【タスカル】予約が拒否されました");
define('NG_SUBJECT_ADMIN', "admin【タスカル】予約が拒否されました");
define('NG_BODY', 
		"こんにちは、タスカルです。\n" . 
		"予約が拒否されました。\n" . 
		"\n" . 
		"預け日:%start_dt%\n" . 
		"迎え日:%end_dt%\n" . 
		"\n" . 
		"確認は下記よりお願いいたします。\n" . 
		ROOT_URL . "message_detail.php?id=%user_id%\n" . 
		"\n" . 
		"\n" . 
		"※万一、このメールにお心当たりの無い場合は、\n" . 
		"どなたかがメールアドレスを間違って入力してしまった可能性がありますのでお手数ですがメールを削除してくださいますようお願いいたします。\n" . 
		"\n" . 
		MAIL_COMMON_FOOTER
);


// ユーザーへのメール関連(決済完了) //
define('PAY_SUBJECT', "【タスカル】予約の決済が完了しました！");
define('PAY_SUBJECT_ADMIN', "admin【タスカル】予約の決済が完了しました");	
define('PAY_BODY', 
		"こんにちは、タスカルです。\n" . 
		"あなた宛の予約が決済されました。\n" . 
		"\n" . 
		"確認は下記よりお願いいたします。\n" . 
		ROOT_URL . "message_detail.php?id=%user_id%\n" . 
		"\n" . 
		"\n" . 
		"※万一、このメールにお心当たりの無い場合は、\n" . 
		"どなたかがメールアドレスを間違って入力してしまった可能性がありますのでお手数ですがメールを削除してくださいますようお願いいたします。\n" . 
		"\n" . 
		MAIL_COMMON_FOOTER
);


// ユーザーへのメール関連(pushメール) //
define('PUSH_BODY', 
		"%body%\n" . 
		"\n" . 
		MAIL_COMMON_FOOTER
);


// 出金申請 //
define('SETTLE_SUBJECT_ADMIN', "【タスカル】出金申請がありました");
define('SETTLE_BODY_ADMIN', 
		"出金申請がありました。\n" . 
		ROOT_URL . "admin/transfer_list.php\n" . 
		"\n" . 
		"\n" 
);


// パスワード忘れ //
define('FORGET_PASSWORD_SUBJECT', "【タスカル】パスワード忘れ");
define('FORGET_PASSWORD_BODY', 
		"%user_name% 様\n" . 
		"\n" . 
		"こんにちは、タスカルです。\n" . 
		"パスワードをリセットしました。\n" . 
		"\n" . 
		"メールアドレス：%mail_address%\n" . 
		"仮パスワード：%password%\n" . 
		"\n" . 
		"下記より仮パスワードにて再度ログインしていただき、パスワードを再設定してください。\n" . 
		ROOT_URL . "login.php\n" . 
		"\n" . 
		MAIL_COMMON_FOOTER
);

?>