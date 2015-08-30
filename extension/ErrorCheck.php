<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * エラーチェッククラス
 *
 * エラーチェックを行うメソッドを定義する
 *
 * Developed on PHP versions 5.2.5
 *
 * @category	extension
 * @package		techfund
 * @author	 	松山 雄太 <yuta_matsuyama@techfund.jp>
 * @copyright	techfund
 * @license		別紙契約内容を参照
 * @version		$Id$
 * @link		http://techfund.jp/
 * @see
 * @since
 * @deprecated
 *
 * 修正履歴:
 * 2015/03/10 新規作成
 */

/**
 * エラーチェッククラス
 */
class ErrorCheck {

	/**
	 * 登録時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @param	ins		$db						DBアクセス
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckRegist($post, $db) {

		$err_arr = array();

		/* ユーザー登録 */
		if ("" == $post['mail_address']) {
			$err_arr['mail_address'] = "メールアドレスを入力してください。";
		} elseif (!preg_match(MAIL_REGEX, $post['mail_address'])) {
			// PCのアドレスではないと判別されたとき
			$err_arr['mail_address'] = "メールアドレスを正しく入力してください。";
		} else {
			// 登録済みかチェック
			$row = $db->simpleSelect("user_tbl", array("mail_address" => $post['mail_address']));

			if (0 != count($row)) {
				$err_arr['mail_address'] = "このメールアドレスは既に登録されています。";
			}
		}

		// パスワード　入力　数値　4以上16文字以内
		if (!$post['password']) {
			$err_arr['password'] = "パスワードを入力してください。";
		} elseif (!preg_match("/^[0-9a-zA-Z]+$/", $post['password'])) {
			$err_arr['password'] = "パスワードを正しく入力してください。";
		} elseif (4 > mb_strlen($post['password']) or 16 < mb_strlen($post['password'])) {
			$err_arr['password'] = "パスワードは4文字以上16文字以内で入力してください。";
		}

		return $err_arr;
	}


	/**
	 * パスワード変更チェックメソッド
	 * @param	arr		$post					登録内容
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckPassword($post) {

		$err_arr = array();

		// パスワード　入力　数値　4以上16文字以内　再入力
		if (!$post['password']) {
			$err_arr['password'] = "パスワードを入力してください。";
		} elseif (!preg_match("/^[0-9a-zA-Z]+$/", $post['password'])) {
			$err_arr['password'] = "パスワードを正しく入力してください。";
		} elseif (4 > mb_strlen($post['password']) or 16 < mb_strlen($post['password'])) {
			$err_arr['password'] = "パスワードは4文字以上16文字以内で入力してください。";
		} elseif ($post['password'] != $post['re_password']) {
			$err_arr['password'] = "パスワードが一致しません。";
		}

		return $err_arr;
	}


	/**
	 * 案件登録時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckNewRequest($post) {

		$err_arr = array();

		// タイトル　入力　100文字以内
		if ("" == $post['title']) {
			$err_arr['title'] = "タイトルを入力してください。";
		} elseif (100 < mb_strlen($post['title'])) {
			$err_arr['title'] = "タイトルは100文字以内で入力してください。";
		}

		// 仕事内容　入力　800文字以内
		if ("" == $post['body']) {
			$err_arr['body'] = "仕事内容を入力してください。";
		} elseif (800 < mb_strlen($post['body'])) {
			$err_arr['body'] = "仕事内容は800文字以内で入力してください。";
		}

		// 郵便番号　郵便番号チェック
		if ("" == $post['zipcode']) {
			$err_arr['zipcode'] = "郵便番号を入力してください。";
		} elseif (!preg_match("/^[0-9,-]+$/", $post['zipcode'])) {
			$err_arr['zipcode'] = "郵便番号を正しく入力してください。";
		}

		// 日程　数値　日付　今日以降の日付　9時〜24時 00分〜59分
		if ($post['start_dt']) {
			list($start_date, $start_time) = explode(" ", $post['start_dt']);
			list($year, $month, $day)  = explode("-", $start_date);
			list($hour, $minute, $second)  = explode(":", $start_time);
			if (strtotime(date('Y-m-d')) >= strtotime($post['start_dt'])) {
				$err_arr['start_dt'] = "日程は今日以降の日付を選択してください。";
			}
			if (!checkdate($month, $day, $year)) {
				$err_arr['start_dt'] = "日程は存在する日付を選択してください。";
			}
			if (!preg_match("/^[0-9]+$/", $year)) {
				$err_arr['start_dt'] = "日程を正しく選択してください。";
			}
			if (!preg_match("/^[0-9]+$/", $month)) {
				$err_arr['start_dt'] = "日程を正しく選択してください。";
			}
			if (!preg_match("/^[0-9]+$/", $day)) {
				$err_arr['start_dt'] = "日程を正しく選択してください。";
			}
			if (!preg_match("/^[0-9]+$/", $hour)) {
				$err_arr['start_dt'] = "時間帯を正しく入力してください。";
			}
			if (9 > intval($hour) and 24 < intval($hour)) {
				$err_arr['start_dt'] = "時間帯を正しく入力してください。";
			}
			if (!preg_match("/^[0-9]+$/", $minute)) {
				$err_arr['start_dt'] = "時間帯を正しく入力してください。";
			}
			if (0 > intval($minute) and 59 < intval($minute)) {
				$err_arr['start_dt'] = "時間帯を正しく入力してください。";
			}
		}

		// 予算　入力　数値　6桁以内　0以上
		if ("" === $post['price']) {
			$err_arr['price'] = "予算を入力してください。";
		} elseif (!preg_match("/^[0-9]+$/", $post['price'])) {
			$err_arr['price'] = "予算を正しく選択してください。";
		} elseif (6 < mb_strlen($post['price'])) {
			$err_arr['price'] = "予算は6桁以内で入力してください。";
		} elseif (0 > $post['price']) {
			$err_arr['price'] = "予算を正しく入力してください。";
		}

		// 工数　入力　数値　3桁以内　1以上
		if (!$post['workload']) {
			$err_arr['workload'] = "工数を入力してください。";
		} elseif (!preg_match("/^[0-9]+$/", $post['workload'])) {
			$err_arr['workload'] = "工数は整数で入力してください。";
		} elseif (3 < mb_strlen($post['workload'])) {
			$err_arr['workload'] = "工数は3桁以内で入力してください。";
		} elseif (1 > $post['workload']) {
			$err_arr['workload'] = "工数は1時間以上で入力してください。";
		}

		return $err_arr;

	}


	/**
	 * 応募時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @param	ins		$db						DBアクセス
	 * @param	int		$job_id				仕事ID
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckApply($post, $db, $job_id) {

		$err_arr = array();

		// 応募金額　入力　数値
		if (!$post['amount']) {
			$err_arr['amount'] = "応募金額を入力してください。";
		} elseif (!preg_match("/^[0-9]+$/", $post['amount'])) {
			$err_arr['amount'] = "応募金額は半角数値で入力してください。";
		} elseif (6 < mb_strlen($post['amount'])) {
			$err_arr['amount'] = "応募金額は6桁以内で入力してください。";
		}

		if (0 == count($err_arr)) {
			$data = $db->simpleSelect("job_tbl", array("job_id" => $job_id));
			if (1 == $data[0]["apply_end_flg"]) {
				$err_arr['apply_end_flg'] = "募集が終了している案件です。";
			}
		}

		return $err_arr;

	}


	/**
	 * 削除時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckJobDelete($post) {

		$err_arr = array();

		// 応募金額　入力　数値
		if (!$post['job_id']) {
			$err_arr['job_id'] = "サーバーエラーが発生しました。大変お手数ですが、数分後に再度ご登録お願いします。";
		} elseif (!preg_match("/^[0-9]+$/", $post['job_id'])) {
			$err_arr['job_id'] = "サーバーエラーが発生しました。大変お手数ですが、数分後に再度ご登録お願いします。";
		}

		return $err_arr;

	}


	/**
	 * 依頼時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @param	ins		$db						DBアクセス
	 * @param	int		$job_id				仕事ID
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckDecide($post, $db, $job_id) {

		$err_arr = array();

		// ユーザーID　入力　数値
		if (!$post['apply_user_id']) {
			$err_arr['apply_user_id'] = "依頼するユーザーを選択してください。";
		} elseif (!preg_match("/^[0-9]+$/", $post['apply_user_id'])) {
			$err_arr['apply_user_id'] = "依頼するユーザーを選択してください。";
		}

		if (0 == count($err_arr)) {
			$data = $db->simpleSelect("apply_tbl", array("job_id" => $job_id, "apply_user_id" => $post["apply_user_id"]));
			if (1 == $data[0]["decide_flg"]) {
				$err_arr['apply_user_id'] = "依頼済みのユーザーです。";
			}
		}

		if (0 == count($err_arr)) {
			$data = $db->simpleSelect("job_tbl", array("job_id" => $job_id));
			if (1 == $data[0]["apply_end_flg"]) {
				$err_arr['apply_end_flg'] = "募集が終了している案件です。";
			}
		}

		return $err_arr;

	}


	/**
	 * 完了時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @param	ins		$db						DBアクセス
	 * @param	int		$job_id				仕事ID
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckDone($post, $db, $job_id) {

		$err_arr = array();

		if (0 == count($err_arr)) {
			$data = $db->simpleSelect("job_tbl", array("job_id" => $job_id));
			if (1 == $data[0]["done_flg"]) {
				$err_arr['done_flg'] = "既に完了している案件です。";
			}
		}

		return $err_arr;

	}


	/**
	 * レビュー時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @param	ins		$db						DBアクセス
	 * @param	int		$job_id				仕事ID
	 * @param	str		$type_str			client もしくは tasker
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckReview($post, $db, $job_id, $type_str) {

		$err_arr = array();

		// 評価値　入力　数値
		if (!$post['review_value']) {
			$err_arr['review_value'] = "スター数を選択してください。";
		} elseif (!preg_match("/^[0-9]+$/", $post['review_value'])) {
			$err_arr['review_value'] = "スター数を正しく選択してください。";
		}

		// メッセージ　入力　800文字以内
		if (800 < mb_strlen($post['body'])) {
			$err_arr['body'] = "コメントは800文字以内で入力してください。";
		}

		if (0 == count($err_arr)) {
			$data = $db->simpleSelect("job_tbl", array("job_id" => $job_id));
			if (1 == $data[0][$type_str]) {
				$err_arr['review_flg'] = "既にレビューが完了しています。";
			}
		}

		return $err_arr;

	}


	/**
	 * プロフィール登録時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @param	ins		$db						DBアクセス
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckProfile($post, $db) {

		$err_arr = array();

		// メールアドレス　入力　メールアドレスチェック　！一意
		if ("" == $post['mail_address']) {
			$err_arr['mail_address'] = "メールアドレスを入力してください。";
		} elseif (!preg_match(MAIL_REGEX, $post['mail_address'])) {
			// PCのアドレスではないと判別されたとき
			$err_arr['mail_address'] = "メールアドレスを正しく入力してください。";
		} else {
			// 登録済みかチェック
			$row = $db->simpleSelect("user_tbl", array("mail_address" => $post['mail_address']));
			if ($row[0]["mail_address"] != $_POST["mail_address"]) {
				if (0 != count($row)) {
					$err_arr['mail_address'] = "このメールアドレスは既に登録されています。";
				}
			}
		}

		// 氏名　入力　20文字
		if ("" == $post['name']) {
			$err_arr['name'] = "お名前を入力してください。";
		} elseif (20 < mb_strlen($post['name'])) {
			$err_arr['name'] = "お名前は20文字以内で入力してください。";
		}

		// 性別　入力　数字
		if ("" == $post['sex']) {
			$err_arr['sex'] = "性別を選択してください。";
		} elseif (!preg_match("/^[0-9]+$/", $post['sex'])) {
			$err_arr['sex'] = "性別を正しく選択してください。";
		}
		
		// 都道府県　入力　数値
		if (!$post['location']) {
			$err_arr['location'] = "都道府県を選択してください。";
		} elseif (!preg_match("/^[0-9]+$/", $post['location'])) {
			$err_arr['location'] = "都道府県を正しく選択してください。";
		}

		// 市区町村　入力　数値
		if (!$post['area']) {
			$err_arr['area'] = "市区町村を選択してください。";
		} elseif (!preg_match("/^[0-9]+$/", $post['area'])) {
			$err_arr['area'] = "市区町村を正しく選択してください。";
		}

		// 住所　入力　150文字チェック
		if ("" == $post['address']) {
			$err_arr['address'] = "住所を入力してください。";
		} elseif (500 < mb_strlen($post['address'])) {
			$err_arr['address'] = "住所は150文字以内で入力してください。";
		}

		// 電話番号　入力　電話番号チェック（簡易版）
		if ("" == $post['tel']) {
			$err_arr['tel'] = "電話番号を入力してください。";
		} elseif (!preg_match('/^[0-9,-]+$/', $post['tel'])) {
			$err_arr['tel'] = "電話番号を正しく入力してください（半角数字もしくはハイフン以外の入力はできません）。";
		} elseif ((8 > mb_strlen($post['tel'])) or (16 < mb_strlen($post['tel']))) {
			$err_arr['tel'] = "電話番号を正しく入力してください。";
		}

		// プロフィール　5000文字以内
		if ("" != $post['profile']) {
			if (5000 < mb_strlen($post['profile'])) {
				$err_arr['profile'] = "プロフィールは5000文字以内で入力してください。";
			}
		}

		// 生年月日　数値　日付
		if ($post['birthday']) {
			// 今日よりも後の日付になってないか
			$d = new Datetime($post['birthday']);
			if (strtotime(date('Y-m-d')) < $d->format('Y-m-d')) {
				$err_arr['birthday'] = "生年月日を正しく選択してください。";
			}
			// 日付チェック用に分解
			list($year, $month, $day) = explode("/", $post['birthday']);
			if (!checkdate($month, $day, $year)) {
				// 存在する年月日か
				$err_arr['birthday'] = "生年月日は存在する日付を選択してください。";
			}
			// 数字チェック
			if (!preg_match("/^[0-9]+$/", $year)) {
				$err_arr['birthday'] = "生年月日を正しく選択してください。";
			}
			if (!preg_match("/^[0-9]+$/", $month)) {
				$err_arr['birthday'] = "生年月日を正しく選択してください。";
			}
			if (!preg_match("/^[0-9]+$/", $day)) {
				$err_arr['birthday'] = "生年月日を正しく選択してください。";
			}
		}

		return $err_arr;

	}


	/**
	 * メッセージ登録時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckMessage($post) {

		$err_arr = array();

		// メッセージ　入力　800文字以内
		if (800 < mb_strlen($post['body'])) {
			$err_arr['body'] = "メッセージは800文字以内で入力してください。";
		}

		return $err_arr;

	}

}

?>
