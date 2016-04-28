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
 * @package		TECHFUND
 * @author	 	松山 雄太 <yuta_matsuyama@techfund.jp>
 * @copyright	TECHFUND
 * @license		別紙契約内容を参照
 * @version		$Id$
 * @link		http://techfund.jp/
 * @see
 * @since
 * @deprecated
 *
 * 修正履歴:
 * 2016/02/01 新規作成
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

		// ユーザー名　入力　50文字以内
		if ("" == $post['user_name']) {
			$err_arr['user_name'] = "お名前を入力してください。";
		} elseif (50 < mb_strlen($post['user_name'])) {
			$err_arr['user_name'] = "お名前は50文字以内で入力してください。";
		}

		// 性別　入力　性別配列にマッチ
		if ("" == $post['sex']) {
			$err_arr['sex'] = "性別を選択してください。";
		} elseif (array_key_exists($post['sex'], $sex_arr)) {
			$err_arr['sex'] = "性別を正しく選択してください。";
		}

		// 住所　入力　50文字チェック
		if ("" == $post['address']) {
			$err_arr['address'] = "住所を入力してください。";
		} elseif (50 < mb_strlen($post['address'])) {
			$err_arr['address'] = "住所は50文字以内で入力してください。";
		}

		// 携帯番号　入力　電話番号チェック 8~16文字
		if ("" == $post['tel']) {
			$err_arr['tel'] = "携帯番号を入力してください。";
		} elseif (!preg_match('/^[0-9,\+,-]+$/', $post['tel'])) {
			$err_arr['tel'] = "携帯番号を正しく入力してください（半角数字もしくはプラス,ハイフン以外の入力はできません）。";
		} elseif (mb_strlen($post['tel']) < 8 or 16 < mb_strlen($post['tel'])) {
			$err_arr['tel'] = "携帯番号を正しく入力してください。";
		}

		// メールアドレス　入力　メールアドレスチェック　！一意　256文字
		if ("" == $post['email']) {
			$err_arr['email'] = "メールを入力してください。";
		} elseif (!preg_match(MAIL_REGEX, $post['email'])) {
			// PCのアドレスではないと判別されたとき
			$err_arr['email'] = "メールを正しく入力してください。";
		} elseif (256 < mb_strlen($post['email'])) {
			$err_arr['email'] = "メールを正しく入力してください。";
		} else {
			// 登録済みかチェック
			$row = $db->simpleSelect("users", array("email" => $post['email']));

			if (0 != count($row)) {
				$err_arr['email'] = "メールを正しく入力してください。";
			}
		}

		// パスワード　入力　数値　8以上16文字以内
		if (!$post['password']) {
			$err_arr['password'] = "パスワードを入力してください。";
		} elseif (!preg_match("/([0-9].*[a-zA-Z]|[a-zA-Z].*[0-9])/", $post['password'])) {
			$err_arr['password'] = "パスワードを正しく入力してください。";
		} elseif (mb_strlen($post['password']) < 8 or 16 < mb_strlen($post['password'])) {
			$err_arr['password'] = "パスワードは8文字以上16文字以内で入力してください。";
		}

		if (!$post['terms'] or 1 != $post['terms']) {
			$err_arr['terms'] = "利用規約への同意が必要です。";
		}

		return $err_arr;
	}


	/**
	 * オーナー登録時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @param	ins		$db						DBアクセス
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckRegistClient($post, $db, $account_type_arr) {

		$err_arr = array();

		// 店舗名　入力　50文字以内
		if ("" == $post['client_name']) {
			$err_arr['client_name'] = "店舗名を入力してください。";
		} elseif (50 < mb_strlen($post['client_name'])) {
			$err_arr['client_name'] = "店舗名は50文字以内で入力してください。";
		}

		// 説明　入力　800文字以内
		if ("" == $post['description']) {
			$err_arr['description'] = "説明を入力してください。";
		} elseif (800 < mb_strlen($post['description'])) {
			$err_arr['description'] = "説明は800文字以内で入力してください。";
		}

		// 携帯電話　入力　電話番号チェック 8~16文字
		if ("" == $post['tel']) {
			$err_arr['tel'] = "携帯電話番号を入力してください。";
		} elseif (!preg_match('/^[0-9,\+,-]+$/', $post['tel'])) {
			$err_arr['tel'] = "携帯電話番号を正しく入力してください（半角数字もしくはプラス,ハイフン以外の入力はできません）。";
		} elseif (mb_strlen($post['tel']) < 8 or 16 < mb_strlen($post['tel'])) {
			$err_arr['tel'] = "携帯電話番号を正しく入力してください。";
		}

		// 固定電話　入力　電話番号チェック 8~16文字
		if ("" == $post['landline']) {
			$err_arr['landline'] = "固定電話番号を入力してください。";
		} elseif (!preg_match('/^[0-9,\+,-]+$/', $post['landline'])) {
			$err_arr['landline'] = "固定電話番号を正しく入力してください（半角数字もしくはプラス,ハイフン以外の入力はできません）。";
		} elseif (mb_strlen($post['landline']) < 8 or 16 < mb_strlen($post['landline'])) {
			$err_arr['landline'] = "固定電話番号を正しく入力してください。";
		}

		// 郵便番号　郵便番号チェック
		if ("" == $post['zipcode']) {
			$err_arr['zipcode'] = "郵便番号を入力してください。";
		} elseif (!preg_match("/^[0-9,-]+$/", $post['zipcode'])) {
			$err_arr['zipcode'] = "郵便番号を正しく入力してください。";
		}

		// 住所　入力　50文字チェック
		if ("" == $post['address']) {
			$err_arr['address'] = "住所を入力してください。";
		} elseif (50 < mb_strlen($post['address'])) {
			$err_arr['address'] = "住所は50文字以内で入力してください。";
		}

		// メールアドレス　入力　メールアドレスチェック　！一意　256文字
		if ("" == $post['email']) {
			$err_arr['email'] = "メールを入力してください。";
		} elseif (!preg_match(MAIL_REGEX, $post['email'])) {
			// 正しいメールアドレスではないと判別されたとき
			$err_arr['email'] = "メールを正しく入力してください。";
		} elseif (256 < mb_strlen($post['email'])) {
			$err_arr['email'] = "メールを正しく入力してください。";
		} else {
			// 登録済みかチェック
			$row = $db->simpleSelect("clients", array("email" => $post['email']));
			if (0 != count($row)) {
				$err_arr['email'] = "メールを正しく入力してください。";
			}
		}

		// パスワード　入力　数値　8以上16文字以内
		if (!$post['password']) {
			$err_arr['password'] = "パスワードを入力してください。";
		} elseif (!preg_match("/([0-9].*[a-zA-Z]|[a-zA-Z].*[0-9])/", $post['password'])) {
			$err_arr['password'] = "パスワードを正しく入力してください。";
		} elseif (mb_strlen($post['password']) < 8 or 16 < mb_strlen($post['password'])) {
			$err_arr['password'] = "パスワードは8文字以上16文字以内で入力してください。";
		} elseif ($post['password'] != $post['password_re']) {
			$err_arr['password'] = "パスワードが一致しません。";
		}

		// 営業時間(開始)　時間
		if ($post['business_hours_start']) {
			if (!preg_match(PREG_TIME, $post["business_hours_start"])) {
				$err_arr['business_hours_start'] = "営業時間を正しく選択してください。";
			}
		}

		// 営業時間(終了)　時間
		if ($post['business_hours_end']) {
			if (!preg_match(PREG_TIME, $post["business_hours_end"])) {
				$err_arr['business_hours_end'] = "営業時間を正しく選択してください。";
			}
		}

		// 受付可能時間(開始)　時間
		if ($post['accepted_hours_start']) {
			if (!preg_match(PREG_TIME, $post["accepted_hours_start"])) {
				$err_arr['accepted_hours_start'] = "受付可能時間を正しく選択してください。";
			}
		}

		// 受付可能時間(終了)　時間
		if ($post['accepted_hours_end']) {
			if (!preg_match(PREG_TIME, $post["accepted_hours_end"])) {
				$err_arr['accepted_hours_end'] = "受付可能時間を正しく選択してください。";
			}
		}

		// 対応可能時間 数値 0~120
		if ($post['limit_time']) {
			if (!preg_match("/^[0-9]+$/", $post['limit_time'])) {
				$err_arr['limit_time'] = "対応可能時間を正しく選択してください。";
			} elseif ($post['limit_time'] < 0 or 120 < $post['limit_time']) {
				$err_arr['limit_time'] = "対応可能時間を正しく選択してください。";
			}
		}

		// 銀行名 50文字チェック
		if ($post['bank_name']) {
			if (50 < mb_strlen($post['bank_name'])) {
				$err_arr['bank_name'] = "銀行名は50文字以内で入力してください。";
			}
		}

		// 支店名 50文字チェック
		if ($post['branch_name']) {
			if (50 < mb_strlen($post['branch_name'])) {
				$err_arr['branch_name'] = "支店名は50文字以内で入力してください。";
			}
		}

		// 口座種別 数値 口座種別配列マッチ
		if ($post['account_type']) {
			if (!preg_match("/^[0-9]+$/", $post['account_type'])) {
				$err_arr['account_type'] = "口座種別を正しく選択してください。";
			} elseif (!array_key_exists($post['account_type'], $account_type_arr)) {
				$err_arr['account_type'] = "口座種別を正しく選択してください。";
			}
		}

		// 口座番号 数値 7桁
		if ($post['account_number']) {
			if (!preg_match("/^[0-9]+$/", $post['account_number'])) {
				$err_arr['account_number'] = "口座番号を正しく入力してください。";
			} elseif (7 != mb_strlen($post['account_number'])) {
				$err_arr['account_number'] = "口座番号は7桁で入力してください。";
			}
		}

		// 名義人名 50文字チェック
		if ($post['account_name']) {
			if (50 < mb_strlen($post['account_name'])) {
				$err_arr['account_name'] = "名義人名は50文字以内で入力してください。";
			}
		}

		return $err_arr;
	}


	/**
	 * 揉み手登録時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @param	ins		$db						DBアクセス
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckRegistPro($post, $db) {

		$err_arr = array();

		// 名前　入力　50文字以内
		if ("" == $post['pro_name']) {
			$err_arr['pro_name'] = "名前を入力してください。";
		} elseif (50 < mb_strlen($post['pro_name'])) {
			$err_arr['pro_name'] = "名前は50文字以内で入力してください。";
		}

		// 携帯番号　入力　電話番号チェック 8~16文字
		if ("" == $post['tel']) {
			$err_arr['tel'] = "携帯番号を入力してください。";
		} elseif (!preg_match('/^[0-9,\+,-]+$/', $post['tel'])) {
			$err_arr['tel'] = "携帯番号を正しく入力してください（半角数字もしくはプラス,ハイフン以外の入力はできません）。";
		} elseif (mb_strlen($post['tel']) < 8 or 16 < mb_strlen($post['tel'])) {
			$err_arr['tel'] = "携帯番号を正しく入力してください。";
		}

		// メールアドレス　入力　メールアドレスチェック　！一意　256文字
		if ("" == $post['email']) {
			$err_arr['email'] = "メールを入力してください。";
		} elseif (!preg_match(MAIL_REGEX, $post['email'])) {
			// 正しいメールアドレスではないと判別されたとき
			$err_arr['email'] = "メールを正しく入力してください。";
		} elseif (256 < mb_strlen($post['email'])) {
			$err_arr['email'] = "メールを正しく入力してください。";
		} else {
			// 登録済みかチェック
			$row = $db->simpleSelect("pros", array("email" => $post['email']));
			if (0 != count($row)) {
				$err_arr['email'] = "メールを正しく入力してください。";
			}
		}

		// パスワード　入力　数値　8以上16文字以内
		if (!$post['password']) {
			$err_arr['password'] = "パスワードを入力してください。";
		} elseif (!preg_match("/([0-9].*[a-zA-Z]|[a-zA-Z].*[0-9])/", $post['password'])) {
			$err_arr['password'] = "パスワードを正しく入力してください。";
		} elseif (mb_strlen($post['password']) < 8 or 16 < mb_strlen($post['password'])) {
			$err_arr['password'] = "パスワードは8文字以上16文字以内で入力してください。";
		} elseif ($post['password'] != $post['password_re']) {
			$err_arr['password'] = "パスワードが一致しません。";
		}

		// 価格　入力　数値　MAX_PRICE_DIGIT桁以内
		if (!$post['price']) {
			$err_arr['price'] = "価格を入力してください。";
		} elseif (!preg_match("/^[0-9]+$/", $post['price'])) {
			$err_arr['price'] = "価格を正しく入力してください。";
		} elseif (MAX_PRICE_DIGIT < mb_strlen($post['price'])) {
			$err_arr['price'] = "価格は" . MAX_PRICE_DIGIT . "桁以内で入力してください。";
		}

		// 形態 選択 数値 揉み手種別配列マッチ
		if ($post['kind']) {
			foreach ($post['kind'] as $key => $value) {
				if (!preg_match("/^[0-9]+$/", $value)) {
					$err_arr['kind'] = "訪問・来店を正しく選択してください。";
				} else {
					if (in_array($value, $kind_arr)) {
						$err_arr['kind'] = "訪問・来店を正しく選択してください。";
					}
				}
			}
		} else {
			$err_arr['kind'] = "訪問・来店を選択してください。";
		}

		// 施術方法　入力　800文字以内
		if ("" == $post['menu']) {
			$err_arr['menu'] = "施術方法を入力してください。";
		} elseif (800 < mb_strlen($post['menu'])) {
			$err_arr['menu'] = "施術方法は800文字以内で入力してください。";
		}

		// 自己PR　入力　800文字以内
		if ("" == $post['description']) {
			$err_arr['description'] = "自己PRを入力してください。";
		} elseif (800 < mb_strlen($post['description'])) {
			$err_arr['description'] = "自己PRは800文字以内で入力してください。";
		}

		// 資格　0か1(フラグ)
		foreach ($license_arr as $key => $value) {
			if ($post['license' . $key . '_holder_flg']) {
				if (0 != $post['license' . $key . '_holder_flg'] and 1 != $post['license' . $key . '_holder_flg']) {
					$err_arr['license'] = "資格を正しく選択してください。";
				}
			}
		}

		return $err_arr;
	}


	/**
	 * 揉み手編集時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @param	ins		$db						DBアクセス
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckRegistProEdit($post, $db) {

		$err_arr = array();

		// 名前　入力　50文字以内
		if ("" == $post['pro_name']) {
			$err_arr['pro_name'] = "名前を入力してください。";
		} elseif (50 < mb_strlen($post['pro_name'])) {
			$err_arr['pro_name'] = "名前は50文字以内で入力してください。";
		}

		// 携帯番号　入力　電話番号チェック 8~16文字
		if ("" == $post['tel']) {
			$err_arr['tel'] = "携帯番号を入力してください。";
		} elseif (!preg_match('/^[0-9,\+,-]+$/', $post['tel'])) {
			$err_arr['tel'] = "携帯番号を正しく入力してください（半角数字もしくはプラス,ハイフン以外の入力はできません）。";
		} elseif (mb_strlen($post['tel']) < 8 or 16 < mb_strlen($post['tel'])) {
			$err_arr['tel'] = "携帯番号を正しく入力してください。";
		}

		// メールアドレス　入力　メールアドレスチェック　！一意　256文字
		if ("" == $post['email']) {
			$err_arr['email'] = "メールを入力してください。";
		} elseif (!preg_match(MAIL_REGEX, $post['email'])) {
			// 正しいメールアドレスではないと判別されたとき
			$err_arr['email'] = "メールを正しく入力してください。";
		} elseif (256 < mb_strlen($post['email'])) {
			$err_arr['email'] = "メールを正しく入力してください。";
		}

		// パスワード　入力　数値　8以上16文字以内
		if (!$post['password']) {
			$err_arr['password'] = "パスワードを入力してください。";
		} elseif (!preg_match("/([0-9].*[a-zA-Z]|[a-zA-Z].*[0-9])/", $post['password'])) {
			$err_arr['password'] = "パスワードを正しく入力してください。";
		} elseif (mb_strlen($post['password']) < 8 or 16 < mb_strlen($post['password'])) {
			$err_arr['password'] = "パスワードは8文字以上16文字以内で入力してください。";
		} elseif ($post['password'] != $post['password_re']) {
			$err_arr['password'] = "パスワードが一致しません。";
		}

		// 価格　入力　数値　MAX_PRICE_DIGIT桁以内
		if (!$post['price']) {
			$err_arr['price'] = "価格を入力してください。";
		} elseif (!preg_match("/^[0-9]+$/", $post['price'])) {
			$err_arr['price'] = "価格を正しく入力してください。";
		} elseif (MAX_PRICE_DIGIT < mb_strlen($post['price'])) {
			$err_arr['price'] = "価格は" . MAX_PRICE_DIGIT . "桁以内で入力してください。";
		}

		// 形態 選択 数値 揉み手種別配列マッチ
		if ($post['kind']) {
			foreach ($post['kind'] as $key => $value) {
				if (!preg_match("/^[0-9]+$/", $value)) {
					$err_arr['kind'] = "訪問・来店を正しく選択してください。";
				} else {
					if (in_array($value, $kind_arr)) {
						$err_arr['kind'] = "訪問・来店を正しく選択してください。";
					}
				}
			}
		} else {
			$err_arr['kind'] = "訪問・来店を選択してください。";
		}

		// 施術方法　入力　800文字以内
		if ("" == $post['menu']) {
			$err_arr['menu'] = "施術方法を入力してください。";
		} elseif (800 < mb_strlen($post['menu'])) {
			$err_arr['menu'] = "施術方法は800文字以内で入力してください。";
		}

		// 自己PR　入力　800文字以内
		if ("" == $post['description']) {
			$err_arr['description'] = "自己PRを入力してください。";
		} elseif (800 < mb_strlen($post['description'])) {
			$err_arr['description'] = "自己PRは800文字以内で入力してください。";
		}

		// 資格　0か1(フラグ)
		foreach ($license_arr as $key => $value) {
			if ($post['license' . $key . '_holder_flg']) {
				if (0 != $post['license' . $key . '_holder_flg'] and 1 != $post['license' . $key . '_holder_flg']) {
					$err_arr['license'] = "資格を正しく選択してください。";
				}
			}
		}

		return $err_arr;
	}


	/**
	 * ログイン時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @param	ins		$db						DBアクセス
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckLogin($post, $db) {

		$err_arr = array();

	 	// 画像認証 (表示されている場合は)入力 captchaチェック
		if (isset($post['captcha_code'])) {
			if ($post['captcha_code'] == '') {
				$err_arr["captcha"] = "画像認証を入力してください。";
			} elseif ($post['captcha_code'] != $_SESSION["securimage_code_disp"]["default"]) {
				$err_arr["captcha"] = "入力された画像認証をご確認ください。";
			}
		}

		// メールアドレス　入力　メールアドレスチェック　！一意　256文字
		if ("" == $post['email']) {
			$err_arr['email'] = "メールを入力してください。";
		} elseif (!preg_match(MAIL_REGEX, $post['email'])) {
			// PCのアドレスではないと判別されたとき
			$err_arr['email'] = "メールを正しく入力してください。";
		} elseif (256 < mb_strlen($post['email'])) {
			$err_arr['email'] = "メールを正しく入力してください。";
		}

		// パスワード　入力　数値　8以上16文字以内
		if (!$post['password']) {
			$err_arr['password'] = "パスワードを入力してください。";
		} elseif (!preg_match("/([0-9].*[a-zA-Z]|[a-zA-Z].*[0-9])/", $post['password'])) {
			$err_arr['password'] = "パスワードを正しく入力してください。";
		} elseif (mb_strlen($post['password']) < 8 or 16 < mb_strlen($post['password'])) {
			$err_arr['password'] = "パスワードを正しく入力してください。";
		}

		// ログインチェック
		if (0 == count($err_arr)) {
			// ユーザー情報取得
			$user = $db->simpleSelect("users", array("email" => $post["email"]));
			// ユーザー登録されているか
			if (1 != count($user)) {
				// エラー
				$err_arr["email"] = "メールもしくはパスワードが間違っています。";
			} else {
				// パスワード認証
				if (!password_verify($post["password"], $user[0]["password"])) {
					$err_arr["password"] = "メールもしくはパスワードが間違っています。";
				}
			}
		}

		return $err_arr;
	}


	/**
	 * 決済情報の登録時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @param	ins		$db						DBアクセス
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckPay($post, $db) {

		$err_arr = array();

		// カード種別 数値 カード種別配列マッチ
		if ($post['card_type']) {
			if (!preg_match("/^[0-9]+$/", $post['card_type'])) {
				$err_arr['card_type'] = "カード種別を正しく選択してください。";
			} else {
				if (in_array($post['card_type'], $card_type_arr)) {
					$err_arr['card_type'] = "カード種別を正しく選択してください。";
				}
			}
		}

		// カード番号 入力 数値チェック 14~16文字
		if ("" == $post['card_number']) {
			$err_arr['card_number'] = "カード番号を入力してください。";
		} elseif (!preg_match('/^[0-9]+$/', $post['card_number'])) {
			$err_arr['card_number'] = "カード番号は半角数字で入力してください。";
		} elseif (mb_strlen($post['card_number']) < 14 or 16 < mb_strlen($post['card_number'])) {
			$err_arr['card_number'] = "カード番号は14〜16桁で入力してください。";
		}

		// カード名義 入力 50文字以内
		if ("" == $post['card_name']) {
			$err_arr['card_name'] = "カード名義を入力してください。";
		} elseif (50 < mb_strlen($post['card_name'])) {
			$err_arr['card_name'] = "カード名義は50文字以内で入力してください。";
		}

		// カード有効期限(年) 数値 現在〜6年以内
		if (!preg_match("/^[0-9]+$/", $post['card_expiration_year'])) {
			$err_arr['card_expiration_year'] = "カード有効期限(年)を正しく選択してください。";
		} elseif ($post['card_expiration_year'] < intval(date("Y")) or intval(date("Y", strtotime("+6 year"))) < $post['card_expiration_year']) {
			$err_arr['card_expiration_year'] = "カード有効期限(年)を正しく選択してください。";
		}

		// カード有効期限(月) 数値 1〜12
		if (!preg_match("/^[0-9]+$/", $post['card_expiration_month'])) {
			$err_arr['card_expiration_month'] = "カード有効期限(月)を正しく選択してください。";
		} elseif ($post['card_expiration_month'] < 1 or 12 < $post['card_expiration_month']) {
			$err_arr['card_expiration_month'] = "カード有効期限(月)を正しく選択してください。";
		}

		// カードセキュリティ番号 入力 4文字以内
		if ("" == $post['card_security_number']) {
			$err_arr['card_security_number'] = "セキュリティ番号を入力してください。";
		} elseif (4 < mb_strlen($post['card_security_number'])) {
			$err_arr['card_security_number'] = "セキュリティ番号を正しく入力してください。";
		}

		// キャンペーンコード 英数字チェック 8文字以内 存在確認(DB接続)
		if ("" != $post['pr_code']) {
			if (!preg_match('/^[0-9a-zA-Z]+$/', $post['pr_code'])) {
				$err_arr['pr_code'] = "キャンペーンコードは半角英数字で入力してください。";
			} elseif (8 < mb_strlen($post['pr_code'])) {
				$err_arr['pr_code'] = "キャンペーンコードを正しく入力してください。";
			} else {
				// キャンペーンコード検索
				$pr_code = $db->simpleSelect("pr_codes", array("pr_code" => $post["pr_code"]));
				if (1 != count($pr_code)) {
					$err_arr['pr_code'] = "キャンペーンコードを正しく入力してください。";
				}
			}
		}

		return $err_arr;
	}


	/**
	 * Stripe決済情報の登録時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @param	ins		$db						DBアクセス
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckPayStripe($post, $db) {

		$err_arr = array();

		// カード番号 入力 数値チェック 14~16文字
		if (false == isset($_SESSION["booking"]["customer_id"])) {
			if ("" == $post['card_number']) {
				$err_arr['card_number'] = "カード番号を入力してください。";
			} elseif (!preg_match('/^[0-9]+$/', $post['card_number'])) {
				$err_arr['card_number'] = "カード番号は半角数字で入力してください。";
			} elseif (mb_strlen($post['card_number']) < 14 or 16 < mb_strlen($post['card_number'])) {
				$err_arr['card_number'] = "カード番号は14〜16桁で入力してください。";
			}
		} else {
			if ("*" != substr($post['card_number'], 0, 1)) {
				// カード番号に変更がある場合
				if (!preg_match('/^[0-9]+$/', $post['card_number'])) {
					$err_arr['card_number'] = "カード番号は半角数字で入力してください。";
				} elseif (mb_strlen($post['card_number']) < 14 or 16 < mb_strlen($post['card_number'])) {
					$err_arr['card_number'] = "カード番号は14〜16桁で入力してください。";
				}
				if (0 == count($err_arr)) {
					// 変更がありエラーが無ければセッションを削除
					unset($_SESSION["booking"]["customer_id"]);
				}
			}
		}

		// カード有効期限(年) 数値 現在〜6年以内
		if (!preg_match("/^[0-9]+$/", $post['card_expiration_year'])) {
			$err_arr['card_expiration_year'] = "カード有効期限(年)を正しく選択してください。";
		} elseif ($post['card_expiration_year'] < intval(date("Y")) or intval(date("Y", strtotime("+6 year"))) < $post['card_expiration_year']) {
			$err_arr['card_expiration_year'] = "カード有効期限(年)を正しく選択してください。";
		}

		// カード有効期限(月) 数値 1〜12
		if (!preg_match("/^[0-9]+$/", $post['card_expiration_month'])) {
			$err_arr['card_expiration_month'] = "カード有効期限(月)を正しく選択してください。";
		} elseif ($post['card_expiration_month'] < 1 or 12 < $post['card_expiration_month']) {
			$err_arr['card_expiration_month'] = "カード有効期限(月)を正しく選択してください。";
		}

		// カードセキュリティ番号 入力 4文字以内
		if ("" == $post['card_security_number']) {
			$err_arr['card_security_number'] = "セキュリティ番号を入力してください。";
		} elseif (4 < mb_strlen($post['card_security_number'])) {
			$err_arr['card_security_number'] = "セキュリティ番号を正しく入力してください。";
		}

		// キャンペーンコード 英数字チェック 8文字以内 存在確認(DB接続)
		if ("" != $post['pr_code']) {
			if (!preg_match('/^[0-9a-zA-Z]+$/', $post['pr_code'])) {
				$err_arr['pr_code'] = "キャンペーンコードは半角英数字で入力してください。";
			} elseif (8 < mb_strlen($post['pr_code'])) {
				$err_arr['pr_code'] = "キャンペーンコードを正しく入力してください。";
			} else {
				// キャンペーンコード検索
				$pr_code = $db->simpleSelect("pr_codes", array("pr_code" => $post["pr_code"]));
				if (1 != count($pr_code)) {
					$err_arr['pr_code'] = "キャンペーンコードを正しく入力してください。";
				}
			}
		}

		return $err_arr;
	}



	/**
	 * 予約時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckReserve($post) {

		$err_arr = array();

		// 料金　数値　MAX_PRICE_DIGIT桁以内
		if (!preg_match("/^[0-9]+$/", $post['amount'])) {
			$err_arr['amount'] = "料金が正しく設定されていません。";
		} elseif (MAX_PRICE_DIGIT < mb_strlen($post['amount'])) {
			$err_arr['amount'] = "料金が正しく設定されていません。";
		}

		return $err_arr;
	}


	/**
	 * お問い合わせ時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckContact($post, $contact_arr) {

		$err_arr = array();


		// 要件 数値 要件配列マッチ
		if ($post['requirement']) {
			if (!preg_match("/^[0-9]+$/", $post['requirement'])) {
				$err_arr['requirement'] = "要件を正しく選択してください。";
			} else {
				if (!array_key_exists($post['requirement'], $contact_arr)) {
					$err_arr['requirement'] = "要件を正しく選択してください。";
				}
			}
		}

		// 名前　入力　50文字以内
		if ("" == $post['name']) {
			$err_arr['name'] = "お名前を入力してください。";
		} elseif (50 < mb_strlen($post['name'])) {
			$err_arr['name'] = "お名前は50文字以内で入力してください。";
		}

		// メールアドレス　入力　メールアドレスチェック
		if ("" == $post['email']) {
			$err_arr['email'] = "メールを入力してください。";
		} elseif (!preg_match(MAIL_REGEX, $post['email'])) {
			// 正しいメールアドレスではないと判別されたとき
			$err_arr['email'] = "メールを正しく入力してください。";
		}

		// 内容　入力　800文字以内
		if ("" == $post['body']) {
			$err_arr['body'] = "内容を入力してください。";
		} elseif (800 < mb_strlen($post['body'])) {
			$err_arr['body'] = "内容は800文字以内で入力してください。";
		}

		return $err_arr;
	}


	/**
	 * レビュー時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckReview($post, $review_arr) {

		$err_arr = array();


		// 評価 数値 評価配列マッチ
		if ($post['score']) {
			if (!preg_match("/^[0-9]+$/", $post['score'])) {
				$err_arr['score'] = "評価を正しく選択してください。";
			} else {
				if (!array_key_exists($post['score'], $review_arr)) {
					$err_arr['score'] = "評価を正しく選択してください。";
				}
			}
		}

		// コメント　800文字以内
		if ("" != $post['body']) {
			if (800 < mb_strlen($post['body'])) {
				$err_arr['body'] = "コメントは800文字以内で入力してください。";
			}
		}

		// 運営へのコメント　800文字以内
		if ("" != $post['body_inside']) {
			if (800 < mb_strlen($post['body_inside'])) {
				$err_arr['body_inside'] = "運営へのコメントは800文字以内で入力してください。";
			}
		}

		return $err_arr;
	}


	/**
	 * パスワード再発行時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @param	ins		$db						DBアクセス
	 * @param	str		$type_table		アカウントの種類(ユーザーor店舗or揉み手)ごとのテーブル
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckPassword($post, $db, $type_table) {

		$err_arr = array();

		// メールアドレス　入力　メールアドレスチェック　！一意　256文字
		if ("" == $post['email']) {
			$err_arr['email'] = "メールを入力してください。";
		} elseif (!preg_match(MAIL_REGEX, $post['email'])) {
			// PCのアドレスではないと判別されたとき
			$err_arr['email'] = "メールを正しく入力してください。";
		} elseif (256 < mb_strlen($post['email'])) {
			$err_arr['email'] = "メールを正しく入力してください。";
		}

		// ログインチェック
		if (0 == count($err_arr)) {
			// ユーザー情報取得
			$data = $db->simpleSelect($type_table, array("email" => $post["email"]));
			// ユーザー登録されているか
			if (1 != count($data)) {
				// エラー
				$err_arr["email"] = "メールを正しく入力してください。";
			}
		}

		return $err_arr;
	}


	/**
	 * パスワード再発行登録時エラーチェックメソッド
	 * @param	arr		$post					登録内容
	 * @return	array							成功：空配列　失敗：エラー配列
	 */
	function errCheckPasswordReInput($post) {

		$err_arr = array();

		// パスワード　入力　数値　8以上16文字以内
		if (!$post['password']) {
			$err_arr['password'] = "パスワードを入力してください。";
		} elseif (!preg_match("/([0-9].*[a-zA-Z]|[a-zA-Z].*[0-9])/", $post['password'])) {
			$err_arr['password'] = "パスワードを正しく入力してください。";
		} elseif (mb_strlen($post['password']) < 8 or 16 < mb_strlen($post['password'])) {
			$err_arr['password'] = "パスワードは8文字以上16文字以内で入力してください。";
		} elseif ($post['password'] != $post['password_re']) {
			$err_arr['password'] = "パスワードが一致しません。";
		}

		return $err_arr;
	}

}

?>
