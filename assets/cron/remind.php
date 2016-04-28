<?php

require_once('path.php');
require_once(ROOT . 'assets/define/common_define.php');

// 共通クラス読み込み
$cf = new CommonFunctions();
$db = new DatabaseAccess();

// SMSキュー情報の取得
$sms_queues = $db->simpleSelect("sms_queues");
if (0 != count($sms_queues)) {
	foreach ($sms_queues as $key => $value) {
		// 送信日が現在~5分後以前
		if ($value["action_dt"] <= strtotime("+5 minute")) {
			// ///////// SMS送信 /////////
			require_once(ROOT . 'assets/define/sms_define.php');
			
			// パラメータ作成
			$params = array(
				'To'				=> $cf->smsTel($value["tel"]), 
				'From'			=> SMS_FROM, 
				'Body'			=> $value["body"]
			);

			// SMS送信
			$ret_sms = $cf->sendSMSTwilio($params);
			
			// 送信に成功したか(SMS失敗ログはCommonFunction内で実行)
			if (true == $ret_sms) {
				// 成功していればキューを削除
				$delete_ids[] = $value["id"];
			} else {
				// キューのエラーログを残す
				error_log("[" . date("Y-m-d h:i:s") . "]キュー実行失敗 キューID=" . $value["id"] . "\n", 3, LOG_PATH);
			}
		}
	}
	if (0 != count($delete_ids)) {
		// キューを削除
		$ret = $db->simpleSQL("DELETE FROM sms_queues WHERE id IN (" . implode(",", $delete_ids) . ");");
	}
}

echo("done");

?>