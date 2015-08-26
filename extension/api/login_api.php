<?php

/**
 * ログイン処理API
 * ログインチェックを行い、メールアドレスが登録されているかも同時に調べるAPI
 * @param	string	$mail_address		メールアドレス
 * @param	string	$password				パスワード
 * @return	void
 */

header('Content-Type: application/javascript; charset=utf-8');
 
$json = file_get_contents('php://input');
$data = json_decode($json, true);

require_once('path.php');
require_once(ROOT . 'assets/define/common_define.php');

// 共通クラス読み込み
$cf = new CommonFunctions();
$db = new DatabaseAccess();

// データ取得
$mail_address = isset($data['mail_address']) ? $data['mail_address'] : 0;
$password = isset($data['password']) ? $data['password'] : 0;

// パスワード暗号化
$password = md5($password . SOLT_STR);

// ログインを試みる
$ret = $db->simpleSelect("user_tbl", array("mail_address" => $mail_address, "password" => $password, "banned" => NULL));

if (1 == count($ret)) {
	// ログインできた場合
	print(json_encode(array("ret" => true, "exist_mail_address" => true)));
} else {
	// できなかった場合、メールアドレスは既に登録されているか？(ban関係なく)
	$ret = $db->simpleSelect("user_tbl", array("mail_address" => $mail_address));
	if (1 == count($ret)) {
		// メールアドレスが登録されている
		print(json_encode(array("ret" => false, "exist_mail_address" => true)));
	} else {
		// メールアドレスが登録されていない
		print(json_encode(array("ret" => false, "exist_mail_address" => false)));
	}
}


?>