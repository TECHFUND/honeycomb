<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * 共通処理クラス
 *
 * 各プログラムから利用される共通処理メソッドを定義する
 *
 * Developed on PHP versions 5.2.5
 *
 * @category	extension
 * @package		TECHFUND
 * @author	 	松山 雄太 <yuta_matsuyama@techfund.jp>
 * @copyright	TECHFUND
 * @license		別紙契約内容を参照
 * @version		$Id$
 * @link			http://techfund.jp/
 * @see
 * @since
 * @deprecated
 *
 * 修正履歴:
 * 2016/02/01 新規作成
 */

/**
 * 共通処理クラス
 */
class CommonFunctions {

	/**
	 * ログイン処理
	 * ログイン用のセッション情報を登録する
	 * @param	string	$id						ID
	 * @param	string	$facebook_id	facebookID
	 * @param	string	$name					名前
	 * @param	string	$img_path			プロフィール写真
	 * @param	string	$type					タイプ（defineにて定義）
	 * @return	void
	 */
	function login($id = "", $facebook_id = "", $name = "", $img_path = "", $type = 0) {
    session_regenerate_id();
		$_SESSION['id'] = $id;
		$_SESSION["facebook_id"] = $facebook_id;
		$_SESSION["name"] = $name;
		$_SESSION["img_path"] = $img_path;
		$_SESSION["type"] = $type;
	}


	/**
	 * ログアウト処理
	 * ログアウトのためにセッション情報を削除する
	 * @return	void
	 */
	function logout() {
		unset($_SESSION["id"]);
		unset($_SESSION["facebook_id"]);
		unset($_SESSION["name"]);
		unset($_SESSION["img_path"]);
		unset($_SESSION["type"]);
	}


	/**
	 * ログインチェック処理
	 * ログイン状態かをチェックし、ログイン状態でなければログインページへ飛ばす
	 * @param	string		$redirect_flg	true:ログインチェック後元ページにリダイレクト, false:リダイレクトせずにログインチェック
	 * @param	array		$redirect		リダイレクト先URL
	 * @return	bool(void)					true:ログイン済み, false:未ログイン, void:未ログイン＋リダイレクト
	 */
	function checkLogin($redirect_flg = true, $redirect = '') {
		
		// リダイレクト先初期値
		if ('' == $redirect) {
			$redirect = ROOT . 'login.php';
		}

		// セッションに格納されている情報を確認
		if (isset($_SESSION['id'])) {
			$ret = true;
		} else {
			$ret = false;
		}

		if (false == $ret) {
			// セッション情報が空の場合はログインページへリダイレクト
			if (true == $redirect_flg) {
				// ログインページにリダイレクト
				$this->redirect($redirect);
			}
		}

		return $ret;
	}


	/**
	 * ページ移動
	 * @param	string		$page		移動先画面
	 */
	function redirect($page) {

		$url = 'http:';

		if (PUBLIC_URL == $_SERVER['SERVER_NAME']) {
			// 本番の場合移動先URLを常にHTTPSにする
			$url = 'https:';
		}

		$url .= '//' . $_SERVER['HTTP_HOST'];
		if (preg_match("/^\//", $page)) {
			// 「/」で始まる場合は絶対パス扱い
		} else {
			// それ以外は同一ディレクトリにジャンプ扱い
			$url .= dirname($_SERVER['SCRIPT_NAME']);
			if ('/' != dirname($_SERVER['SCRIPT_NAME'])) {
				$url .= '/';
			}
		}
		$url .= $page;

		// セッションを引き継ぐ必要がある場合、引数を追加
		if ('' != SID and defined('SID')) {
			if (false === strpos($page, '?')) {
				$url .= '?' . SID;
			} else {
				$url .= '&' . SID;
			}
		}

		header("Location: " . $url);		// 指定ページに移動
		exit;
	}


	/**
	 * 現在時刻取得メソッド
	 * 現在の時刻を取得する。ただしキューからの実行の場合はキュー実行時刻が利用される。
	 * キュー実行時刻は引数を与えることでセットされる。
	 * @param	int			$set_time		セットしたい時刻を表す整数
	 * @return	int							時刻を表す整数
	 */
	function getNowTime($set_time = null) {

		static $now_time;		// 静的変数を利用

		if (isset($set_time)) {
			// 時刻の更新
			$now_time = $set_time;
		}

		if (0 == intval($now_time)) {
			$now_time = time();
		}

		return $now_time;
	}


	/**
	 * メール送付文字列変換メソッド
	 * 注意点 変換元文字列の前後の「%」は自動補完
	 * @param	arr		$replace_str_arr		key:変換元文字列 value:変換後文字列
	 * @param	str		$mail_body				変換元文字列を含むメール本文
	 * @return	str								変換後の文字列
	 */
	function mailStrReplace($replace_str_arr, $mail_body) {

		// 変換用配列の作成
		foreach ($replace_str_arr as $key => $value) {
			$body_key[] = "%" . $key . "%";
			$body_value[] = $value;
		}

		// 改行コードをメール用に変換
		$body_key[] = "<br />";
		$body_value[] = "";
		$body_key[] = "<br>";
		$body_value[] = "";

		// 変換
		$mail_body = str_replace($body_key, $body_value, $mail_body);

		return $mail_body;
	}


	/**
	 * ランダム文字列生成メソッド (英数字)
	 * @param	int		$length					文字列の長さ
	 * @return	str								ランダム文字列
	 */
	function makeRandStr($length) {

			$arr = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
			$str = array();
			$cnt = 0;
			foreach ($arr as $key => $value) {
				$str[$cnt] = $value;
				$cnt++;
			}
			
			$r_str = "";
			for ($i = 0; $i < $length; $i++) {
				$r = rand(0, count($str) - 1);
				$r_str .= $str[$r];
			}
			return $r_str;
	}


	/**
	 * 郵便番号から住所取得メソッド
	 * @param	str		$zipcode				郵便番号（000-0000）
	 * @return	arr		$address				失敗:空配列　成功:住所配列（state:都道府県、city:市区町村）
	 */
	function zipcodeToAddress($zipcode) {

		$ret = array();

		// 郵便番号から住所取得
		$google_maps_api_data = json_decode(@file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?sensor=false&language=ja&address=' . $zipcode), true);	// APIからデータを取得～配列に変換

		// APIから正しくデータを取得できたかチェック
		if ($google_maps_api_data['status'] === 'OK') {
			// 都道府県を取得
			$address = $google_maps_api_data['results'][0]['address_components'];
			unset($address[0]);	// 郵便番号部分を削除
			array_pop($address);	// 国部分を削除
			$address = array_reverse($address);	// 取得用に配列を逆順にする
			$state = $address[0]['long_name'];	// 都道府県
			unset($address[0]);	//市区町村の取得用に都道府県部分を削除
			//市区町村を取得
			$city = '';
			foreach($address as $v){
				$city .= $v['long_name'];	//市区町村部分の文字列を連結
			}
			$ret = array("state" => $state, "city" => $city);
		}

		return $ret;
	}


	/**
	 * 住所から位置情報取得メソッド
	 * @param	str		$address				住所
	 * @return	arr		$address				失敗:空配列　成功:GPS配列（lat:緯度、lng:経度）
	 */
	function addressToGPS($address = ''){
		
		$ret = array();

		// 住所から位置情報取得
		$xml = @simplexml_load_file('http://maps.google.com/maps/api/geocode/xml?address=' . urlencode($address) . '&sensor=false');

		// 情報取得に成功したか
		if ($xml->status == 'OK') {
			// 位置情報を取り出す
			$location = $xml->result->geometry->location;
			$ret = array("lat" => (string)$location->lat[0], "lng" => (string)$location->lng[0]);
		}

		return $ret;
	}


	/**
	 * 複数画像アップロードメソッド
	 * @param	arr			$file				アップロードファイル
	 * @param	string		$key				ファイルのキー（<img>に設定しているname）
	 * @param	bool		$through_flg		ファイル未選択を許すか（true:許す false:許さない）
	 * @param string	$image_dir		ファイルの保存先	
	 * @return	array()							成功：true（配列内にファイル名）　失敗：false（配列内にエラー文）
	 */
	function imageUploadArray($file, $key, $through_flg = true, $image_dir = IMAGE_DIR) {

		$data[0] = array("ret" => $through_flg);

		$cnt = 0;
		foreach ($file[$key]["name"] as $k => $v) {
			if ("" != $v) {
				$cnt++;
			}
		}

		if (1 <= $cnt) {
			foreach ($file[$key]['name'] as $k => $v) {
				// 初期値設定
				$maxfileize = UPLOAD_MAX_IMAGE_SIZE;
				$max_width = UPLOAD_MAX_IMAGE_WIDTH;
				$max_height = UPLOAD_MAX_IMAGE_HEIGHT;

				if ($file[$key]['error'][$k] == UPLOAD_ERR_NO_FILE) {
					// ファイル未選択
					if (false == $through_flg) {
						// ファイル未選択をスルーしない場合エラー
						$data[$k] = array('ret' => false, 'err' => "ファイルが選択されていません。");
					} else {
						$data[$k] = array('ret' => true, 'img_name' => NULL);
					}
				} else {
					try {
						$error = $file[$key]['error'][$k];

						// 配列は除外
						if (is_array($error)) {
							throw new RuntimeException('複数ファイルの同時アップロードは許可されていません。');
						}

						// エラーチェック
						switch ($error) {
							case UPLOAD_ERR_INI_SIZE:
								throw new RuntimeException('許可されている最大サイズを超過しました。');
							case UPLOAD_ERR_FORM_SIZE:
								throw new RuntimeException('フォームで許可されている最大サイズを超過しました。');
							case UPLOAD_ERR_PARTIAL:
								throw new RuntimeException('ファイルが壊れています。');
							case UPLOAD_ERR_NO_TMP_DIR:
								throw new RuntimeException('テンポラリディレクトリが見つかりません。');
							case UPLOAD_ERR_CANT_WRITE:
								throw new RuntimeException('テンポラリデータの生成に失敗しました。');
							case UPLOAD_ERR_EXTENSION:
								throw new RuntimeException('エクステンションでエラーが発生しました。');
						}

						// 一時ファイル名
						$tmp_name = $file[$key]['tmp_name'][$k];

						// ファイルサイズ
						$size = $file[$key]['size'][$k];

						// 不正なファイルでないかチェック
						if (!is_uploaded_file($tmp_name)) {
							throw new RuntimeException('不正なファイルです。');
						}

						// このスクリプトで定義されたサイズ上限のオーバーチェック
						if ($size > $maxfileize) {
							throw new RuntimeException("{$maxfileize}バイトを超過するファイルは受理できません。");
						}

						// 画像ファイル情報取得
						$info = getimagesize($tmp_name);

						// 取得に失敗したときは画像ファイルではない
						if ($info === false) {
							throw new RuntimeException('画像ファイルではありません。');
						}

						// MimeTypeを調べる
						switch ($info['mime']) {
							case 'image/gif':
								$mime = $ext = 'gif';
								break;
							case 'image/png':
								$mime = $ext = 'png';
								break;
							case 'image/jpeg':
								$mime = 'jpeg';
								$ext	= 'jpg';
								break;
							default:
								throw new RuntimeException('この種類の画像形式は受理できません。');
						}

						// もとの画像の幅と高さ
						$width	= $info[0];
						$height = $info[1];

						// ユニークなファイル名を拡張子を含めて生成
						$rand = sha1(mt_rand() . microtime());
						$name = "{$rand}.{$ext}";

						// 最大幅・高さを超過していないかチェック・縦横比を維持して新しいサイズを定義
						$resize = false;
						if ($width > $height && $width > $max_width) {
							$resize = true;
						} elseif ($height > $max_height) {
							$resize = true;
						}

						// 取得に失敗したときは画像ファイルではない
						if ($resize === true) {
							throw new RuntimeException('サイズを超過しています。サイズは縦' . $max_height . 'px, 横' . $max_width . 'pxまでです（アップロードした画像ファイルのサイズ：縦' . $height . 'px, 横' . $width . 'px）。');
						}

						// エラーが無ければアップロード
						if (move_uploaded_file($file[$key]["tmp_name"][$k], $image_dir . $name)) {
							chmod($image_dir . $name, 0644);
							$data[$k] = array('ret' => true, 'img_name' => $name);
						} else {
							throw new RuntimeException('ファイルをアップロードできませんでした。サーバーダウンの可能性がありますので後ほど再送してください。');
						}
					} catch (Exception $e) {
						// エラー
						$data[$k] = array('ret' => false, 'err' => $e->getMessage());
					}
				}
			}
		} else {
			// ファイルが選択されていないとき
			if (false == $through_flg) {
				// ファイル未選択をスルーしない場合エラー
				$data[0] = array('ret' => false, 'err' => "ファイルが選択されていません。");
			}
		}
		return $data;
	}


	/**
	 * 画像アップロードメソッド
	 * @param	arr			$file				アップロードファイル
	 * @param	string		$key				ファイルのキー（<img>に設定しているname）
	 * @param	bool		$through_flg		ファイル未選択を許すか（true:許す false:許さない）
	 * @param string	$image_dir		ファイルの保存先	
	 * @return	array()							成功：true（配列内にファイル名）　失敗：false（配列内にエラー文）
	 */
	function imageUploadExifRotateAndResizeSquare($file, $key, $through_flg = true, $image_dir = IMAGE_DIR) {

		$data = array("ret" => $through_flg);

		if ($file) {

			// 初期値設定
			$upload_key = $key;
			$maxfileize = UPLOAD_MAX_IMAGE_SIZE;
			$max_width = UPLOAD_MAX_IMAGE_WIDTH;
			$max_height = UPLOAD_MAX_IMAGE_HEIGHT;

			if ($file[$upload_key]['error'] == UPLOAD_ERR_NO_FILE) {
				// ファイル未選択
				if (false == $through_flg) {
					// ファイル未選択をスルーしない場合エラー
					$data = array('ret' => false, 'err' => "ファイルが選択されていません。");
				} else {
					$data = array('ret' => true, 'img_name' => NULL);
				}
			} else {
				try {
					$error = $file[$upload_key]['error'];

					// 配列は除外
					if (is_array($error)) {
						throw new RuntimeException('複数ファイルの同時アップロードは許可されていません。');
					}

					// エラーチェック
					switch ($error) {
						case UPLOAD_ERR_INI_SIZE:
							throw new RuntimeException('許可されている最大サイズを超過しました。');
						case UPLOAD_ERR_FORM_SIZE:
							throw new RuntimeException('フォームで許可されている最大サイズを超過しました。');
						case UPLOAD_ERR_PARTIAL:
							throw new RuntimeException('ファイルが壊れています。');
						case UPLOAD_ERR_NO_TMP_DIR:
							throw new RuntimeException('テンポラリディレクトリが見つかりません。');
						case UPLOAD_ERR_CANT_WRITE:
							throw new RuntimeException('テンポラリデータの生成に失敗しました。');
						case UPLOAD_ERR_EXTENSION:
							throw new RuntimeException('エクステンションでエラーが発生しました。');
					}

					// 一時ファイル名
					$tmp_name = $file[$upload_key]['tmp_name'];

					// ファイルサイズ
					$size = $file[$upload_key]['size'];

					// 不正なファイルでないかチェック
					if (!is_uploaded_file($tmp_name)) {
						throw new RuntimeException('不正なファイルです。');
					}

					// このスクリプトで定義されたサイズ上限のオーバーチェック
					if ($size > $maxfileize) {
						throw new RuntimeException("{$maxfileize}バイトを超過するファイルは受理できません。");
					}

					// 画像ファイル情報取得
					$info = getimagesize($tmp_name);

					// 取得に失敗したときは画像ファイルではない
					if ($info === false) {
						throw new RuntimeException('画像ファイルではありません。');
					}

					// MimeTypeを調べる
					switch ($info['mime']) {
						case 'image/gif':
							$mime = $ext = 'gif';
							break;
						case 'image/png':
							$mime = $ext = 'png';
							break;
						case 'image/jpeg':
							$mime = 'jpeg';
							$ext	= 'jpg';
							break;
						default:
							throw new RuntimeException('この種類の画像形式は受理できません。');
					}

					// もとの画像の幅と高さ
					$width	= $info[0];
					$height = $info[1];

					// ユニークなファイル名を拡張子を含めて生成
					$rand = sha1(mt_rand() . microtime());
					$name = "{$rand}.{$ext}";

					// 最大幅・高さを超過していないかチェック・縦横比を維持して新しいサイズを定義
					$resize = false;
					if ($width > $height && $width > $max_width) {
						$resize = true;
					} elseif ($height > $max_height) {
						$resize = true;
					}

					// 取得に失敗したときは画像ファイルではない
					if ($resize === true) {
						throw new RuntimeException('サイズを超過しています。サイズは縦' . $max_height . 'px, 横' . $max_width . 'pxまでです（アップロードした画像ファイルのサイズ：縦' . $height . 'px, 横' . $width . 'px）。');
					}

					// エラーが無ければアップロード
					if ($this->imageExifRotateAndResizeSquare($file[$upload_key]["tmp_name"], $image_dir . $name)) {
						chmod($image_dir . $name, 0644);
						$data = array('ret' => true, 'img_name' => $name);
					} else {
						throw new RuntimeException('ファイルをアップロードできませんでした。サーバーダウンの可能性がありますので後ほど再送してください。');
					}
				} catch (Exception $e) {
					// エラー
					$data = array('ret' => false, 'err' => $e->getMessage());
				}
			}
		} else {
			// ファイルが選択されていないとき
			if (false == $through_flg) {
				// ファイル未選択をスルーしない場合エラー
				$data = array('ret' => false, 'err' => "ファイルが選択されていません。");
			}
		}
		return $data;
	}


	/**
	 * 画像角度整形メソッドと画像正方形整形メソッドを1処理で行うメソッド
	 * @param	arr			$file				アップロードファイル
	 * @param	arr			$dir				保存先パス
	 * @return	bool							成功：true　失敗：false
	 */
	function imageExifRotateAndResizeSquare($file, $dir) {

		// 初期値
		$background_x = 0;	// 背景画像のx座標
		$background_y = 0;	// 背景画像のy座標
		$image_x = 0;	// コピー元画像のx座標
		$image_y = 0;	// コピー元画像のy座標

		// 画像サイズを判別
		list($width, $height, $image_type) = getimagesize($file);

		// 短い方に合わせる
		if ($width > $height) {
			// 縦が短い場合
			$square = $height;
			// x座標(横)の開始位置を合わせ、切り取り時に正方形になるように設定
			$image_x = ($width - $height) / 2;
		} else {
			// 横が短い場合
			$square = $width;
			// y座標(縦)の開始位置を合わせ、切り取り時に正方形になるように設定
			$image_y = ($height - $width) / 2;
		}

		// 画像のインスタンスを作成
		switch ($image_type) {
			case 1: $image = imagecreatefromgif($file); break;
			case 2: $image = imagecreatefromjpeg($file);	break;
			case 3: $image = imagecreatefrompng($file); break;
			default: //エラー処理 
				return false;
		}

		// Exif情報を読み込み、画像の向きを修正する
		$exif = exif_read_data($file);
		if(!empty($exif['Orientation'])) {
			switch($exif['Orientation']) {
				case 8:
					$image = imagerotate($image, 90, 0);
					$tmp = $image_x;
					$image_x = $image_y;
					$image_y = $tmp;
					break;
				case 3:
					$image = imagerotate($image, 180, 0);
					break;
				case 6:
					$image = imagerotate($image, -90, 0);
					$tmp = $image_x;
					$image_x = $image_y;
					$image_y = $tmp;
					break;
			}
		}

		$background = imagecreatetruecolor($square, $square);
		imagefill($background , 0 , 0 , 0xFFFFFF);	// 背景は白色

		// 画像をメモリ上にコピー
		imagecopy($background, $image, $background_x, $background_y, $image_x, $image_y, $square, $square);

		// 出力
		switch ($image_type) {
			case 1: imagegif($background, $dir); break;
			case 2: imagejpeg($background, $dir);	break;
			case 3: imagepng($background, $dir); break;
		}

		// メモリから解放
		imagedestroy($background);
		imagedestroy($image);

		return true;
	}


	/**
	 * SendGridメール送信メソッド
	 * @param	arr			$params			メール送信パラメータ
	 * @return	bool							成功：true　失敗：false
	 */
	function sendMailSendGrid($params) {
		
		$return_flg = false;
		
		// メール送信(cURLのパラメータ送信)
		try {
			// セッションを初期化
			$curl = curl_init(REQUEST);

			// パラメータを設定
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie');
			curl_setopt($curl, CURLOPT_COOKIEFILE, 'tmp');
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // Locationヘッダを追跡
			
			//転送を実行
			$output = curl_exec($curl);

			// エラー番号を取得
			$curl_errno = curl_errno($curl);
			if ($curl_errno) {
				// 例外処理
				$curl_error = curl_error($curl);
				throw new Exception($curl_error);
			}

			// セッションを終了
			curl_close($curl);
			/*
			// sendgridの成否を判定
			if ("success" == $output->message) {
				// 成功
				$return_flg = true;
			} else if ("error" == $output->message) {
				// 失敗の内容をlogに記載
				error_log("[" . date("Y-m-d h:i:s") . "]SendGridメール送信失敗 ユーザーID=" . $_SESSION["id"] . " タイトル=" . $params["subject"] . " 宛先=" . $params["to"] . " エラーメッセージ=" . $output->errors . "\n", 3, MAIL_LOG_PATH);
			}
			*/
			$return_flg = true;
		} catch (Exception $e) {
			// 例外の内容をlogに記載
			error_log("[" . date("Y-m-d h:i:s") . "]SendGridメール送信失敗 ユーザーID=" . $_SESSION["id"] . " タイトル=" . $params["subject"] . " 宛先=" . $params["to"] . " エラーメッセージ=cURL実行時エラー：" . $e->getMessage() . "\n", 3, MAIL_LOG_PATH);
		}

		
		return $return_flg;
	}
	

	/**
	 * 電話番号文字列SMS用変換メソッド
	 * @param	str			$tel			電話番号文字列
	 * @return	str							SMS送信用電話番号文字列
	 */
	function smsTel($tel) {
		$tel = str_replace(array('-', 'ー', '−', '―', '‐'), '', $tel);	// ハイフンを取り除く
		$tel = preg_replace('/^0/', '+81', $tel); // 国際電話発信になるため、080~~(ローカル番号)を+8180~~(グローバル番号)に
		return $tel;
	}


	/**
	 * Twilio SMS送信メソッド
	 * @param	arr			$params			SMS送信パラメータ
	 * @return	bool							成功：true　失敗：false
	 */
	function sendSMSTwilio($params) {
		
		$return_flg = false;
		
		// 初期化
		$twilio = new Services_Twilio(ACCOUNT_SID, AUTH_TOKEN); 
		
		// SMS送信
		try {
			$twilio->account->messages->create($params);
			// 成功
			$return_flg = true;
		} catch( Services_Twilio_RestException $e ) {
			// 例外の内容をlogに記載
			error_log("[" . date("Y-m-d h:i:s") . "]Twilio SMS送信失敗 ユーザーID=" . $_SESSION["id"] . " 内容=" . $params["Body"] . " 宛先=" . $params["To"] . " エラーメッセージ=：" . $e->getMessage() . "\n", 3, SMS_LOG_PATH);
		}

		return $return_flg;
	}


	/**
	 * PAY.JP決済チェックメソッド
	 * @param	arr			$params			カード情報
	 * @param	int			$price			価格
	 * @return	array()						成功：true（配列内にファイル名）　失敗：false（配列内にエラー文）
	 */
	function paymentCheck($params, $price) {

		$data = array();
		
		// 初期化
		require_once(ROOT . 'extension/payjp/init.php');

		try {
			// 新しい課金の作成
			\Payjp\Payjp::setApiKey(SECRET_KEY);
			
			// カードの認証と支払い額確保
			$result = \Payjp\Charge::create(array(
				"card" => $params,
				"amount" => $price,
				"currency" => CURRENCY,
				"capture" => false
			));

			if (isset($result['error'])) {
				// エラー発生
				throw new Exception();
			}

			// 決済完了
			$data = array('ret' => true, 'err_code' => 200);

		} catch (Exception $e) {
			// 例外の内容を返す
			$data = array('ret' => false, 'err_code' => $e->getHttpStatus());
		}

		return $data;
	}


	/**
	 * STRIPE決済前チェックメソッド
	 * @param	arr			$params			カード情報
	 * @param	int			$price			価格
	 * @param	int			$customer_id			顧客ID
	 * @param	int			$user_id			ユーザーID
	 * @return	array()						成功：true（配列内にファイル名）　失敗：false（配列内にエラー文）
	 */
	function paymentPreCheckStripe($params, $price, $customer_id = "", $user_id) {

		$data = array();
		
		// 初期化
		require_once(ROOT . 'extension/stripe/vendor/autoload.php');

		try {
			\Stripe\Stripe::setApiKey(SECRET_KEY_STRIPE);

			// 顧客登録されているか
			if ("" != $customer_id) {
				$param = array(
					'customer' => $customer_id, 
					'amount' => $price, 
					'currency' => CURRENCY, 
					'capture' => false
				);
			} else {
				// トークンの作成
				$token = \Stripe\Token::create($params);
				$param = array(
					'source' => $token->id, 
					'amount' => $price, 
					'currency' => CURRENCY, 
					'capture' => false
				);
			}

			// カードの認証と支払い額確保
			$result = \Stripe\Charge::create($param);

			if (isset($result['error'])) {
				// エラー発生
				throw new Exception();
			}

			// 決済できるかの事前確認なので一旦決済をキャンセル(確認画面で改めて決済を行う)
			$ret = $this->paymentCancelStripe($result["id"], $user_id);

			// 決済完了
			$data = array('ret' => true, 'err_code' => 200, 'id' => $result["id"]);

		} catch (Exception $e) {
			// 例外の内容を返す
			$data = array('ret' => false, 'err_code' => $e->getHttpStatus());
		}


		return $data;
	}


	/**
	 * STRIPE決済チェックメソッド
	 * @param	arr			$params			カード情報
	 * @param	int			$price			価格
	 * @param	int			$customer_id			顧客ID
	 * @return	array()						成功：true（配列内にファイル名）　失敗：false（配列内にエラー文）
	 */
	function paymentCheckStripe($params, $price, $customer_id = "") {

		$data = array();
		
		// 初期化
		require_once(ROOT . 'extension/stripe/vendor/autoload.php');

		try {
			\Stripe\Stripe::setApiKey(SECRET_KEY_STRIPE);

			// 顧客登録されているか
			if ("" != $customer_id) {
				$param = array(
					'customer' => $customer_id, 
					'amount' => $price, 
					'currency' => CURRENCY, 
					'capture' => false
				);
			} else {
				// トークンの作成
				$token = \Stripe\Token::create($params);
				$param = array(
					'source' => $token->id, 
					'amount' => $price, 
					'currency' => CURRENCY, 
					'capture' => false
				);
			}

			// カードの認証と支払い額確保
			$result = \Stripe\Charge::create($param);

			if (isset($result['error'])) {
				// エラー発生
				throw new Exception();
			}

			// 決済完了
			$data = array('ret' => true, 'err_code' => 200, 'id' => $result["id"]);

		} catch (Exception $e) {
			// 例外の内容を返す
			$data = array('ret' => false, 'err_code' => $e->getHttpStatus());
		}

		return $data;
	}

	/**
	 * STRIPE顧客情報保存メソッド
	 * @param	arr			$params			カード情報
	 * @return	array()						成功：true（配列内にファイル名）　失敗：false（配列内にエラー文）
	 */
	function setCustomerStripe($params) {

		$data = array();
		
		// 初期化
		require_once(ROOT . 'extension/stripe/vendor/autoload.php');

		try {
			\Stripe\Stripe::setApiKey(SECRET_KEY_STRIPE);

			// 顧客情報作成
			$result = \Stripe\Customer::create($params);

			if (isset($result['error'])) {
				// エラー発生
				throw new Exception();
			}

			// 決済完了
			$data = array('ret' => true, 'err_code' => 200, 'id' => $result['id']);

		} catch (Exception $e) {
			// 例外の内容を返す
			$data = array('ret' => false, 'err_code' => $e->getHttpStatus());
			// 例外の内容をlogに記載
			error_log("[" . date("Y-m-d h:i:s") . "]Stripe顧客登録失敗 カード番号=" . $params["card"]["number"] . " エラーコード=" . $e->getHttpStatus() . "\n", 3, PAY_ERR_LOG_PATH);
		}

		return $data;
	}


	/**
	 * STRIPE顧客情報取得メソッド
	 * @param	arr			$customer_id			顧客ID
	 * @return	array()						成功：true（配列内にファイル名）　失敗：false（配列内にエラー文）
	 */
	function getCustomerStripe($customer_id) {

		$data = array();
		
		// 初期化
		require_once(ROOT . 'extension/stripe/vendor/autoload.php');

		try {
			\Stripe\Stripe::setApiKey(SECRET_KEY_STRIPE);

			// 顧客情報取得
			$result = \Stripe\Customer::retrieve($customer_id);

			if (isset($result['error'])) {
				// エラー発生
				throw new Exception();
			}

			// 決済完了
			$data = array('ret' => true, 'err_code' => 200, 'customer' => $result);

		} catch (Exception $e) {
			// 例外の内容を返す
			$data = array('ret' => false, 'err_code' => $e);
		}

		return $data;
	}


	/**
	 * PAY.JP決済メソッド
	 * @param	arr			$params			カード情報
	 * @param	int			$price			価格
	 * @return	bool							成功：true　失敗：false
	 */
	function paymentProcessing($params, $price) {

		$return_flg = false;
		
		// 初期化
		require_once(ROOT . 'extension/payjp/init.php');

		try {
			\Payjp\Payjp::setApiKey(SECRET_KEY);
			
			// 決済
			$result = \Payjp\Charge::create(array(
				"card" => $params,
				"amount" => $price,
				"currency" => CURRENCY
			));

			if (isset($result['error'])) {
				// エラー発生
				throw new Exception();
			}

			// 決済完了
			$return_flg = true;
			error_log("[" . date("Y-m-d h:i:s") . "]決済完了 ユーザーID=" . $_SESSION["id"] . " カード番号下4桁:" . substr($params["number"], -4) . " 金額=" . $price . "\n", 3, PAY_LOG_PATH);

		} catch (Exception $e) {
			// 例外の内容をlogに記載
			error_log("[" . date("Y-m-d h:i:s") . "]PAY.JP決済失敗 ユーザーID=" . $_SESSION["id"] . " 金額=" . $price . " カード番号=" . $params["number"] . " エラーメッセージ=：" . $e->getMessage() . "\n", 3, PAY_ERR_LOG_PATH);
		}

		return $return_flg;
	}


	/**
	 * PayPal決済メソッド
	 * @param	arr			$params			カード情報
	 * @param	int			$price			価格
	 * @return	bool							成功：true　失敗：false
	 */
	function paymentProcessingPaypal($params, $price) {

		$return_flg = false;
		

		// ### CreditCard
		// A resource representing a credit card that can be
		// used to fund a payment.
		$card = new CreditCard();
		$card->setType($card_type_arr[$params["card_type"]])
		    ->setNumber($params["number"])
		    ->setExpireMonth($params["exp_month"])
		    ->setExpireYear($params["exp_year"])
		    ->setCvv2($params["cvc"])
		    ->setFirstName($params["first_name"])
		    ->setLastName($params["last_name"]);

		// ### FundingInstrument
		// A resource representing a Payer's funding instrument.
		// For direct credit card payments, set the CreditCard
		// field on this object.
		$fi = new FundingInstrument();
		$fi->setCreditCard($card);

		// ### Payer
		// A resource representing a Payer that funds a payment
		// For direct credit card payments, set payment method
		// to 'credit_card' and add an array of funding instruments.
		$payer = new Payer();
		$payer->setPaymentMethod("credit_card")
		    ->setFundingInstruments(array($fi));

		// ### Itemized information
		// (Optional) Lets you specify item wise
		// information
		$item1 = new Item();
		$item1->setName('massage')
		    ->setDescription('pochimomi massage')
		    ->setCurrency("JPY")
		    ->setQuantity(1)
		    ->setTax(8)
		    ->setPrice($price);

		$itemList = new ItemList();
		$itemList->setItems(array($item1));

		// ### Additional payment details
		// Use this optional field to set additional
		// payment information such as tax, shipping
		// charges etc.
		$details = new Details();
		$details->setShipping(0)
		    ->setTax($price * 0.08)
		    ->setSubtotal($price);

		// ### Amount
		// Lets you specify a payment amount.
		// You can also specify additional details
		// such as shipping, tax.
		$amount = new Amount();
		$amount->setCurrency("JPY")
		    ->setTotal($price)
		    ->setDetails($details);

		// ### Transaction
		// A transaction defines the contract of a
		// payment - what is the payment for and who
		// is fulfilling it. 
		$transaction = new Transaction();
		$transaction->setAmount($amount)
		    ->setItemList($itemList)
		    ->setDescription("Payment description")
		    ->setInvoiceNumber(uniqid());

		// ### Payment
		// A Payment Resource; create one using
		// the above types and intent set to sale 'sale'
		$payment = new Payment();
		$payment->setIntent("sale")
		    ->setPayer($payer)
		    ->setTransactions(array($transaction));

		// For Sample Purposes Only.
		$request = clone $payment;

		// ### Create Payment
		// Create a payment by calling the payment->create() method
		// with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
		// The return object contains the state.
		try {
		    $payment->create($apiContext);

				// 決済完了
				$return_flg = true;
				error_log("[" . date("Y-m-d h:i:s") . "]PayPal決済完了 ユーザーID=" . $_SESSION["id"] . " カード番号下4桁:" . substr($params["number"], -4) . " 金額=" . $price . "\n", 3, PAY_LOG_PATH);

		} catch (Exception $ex) {
			// 例外の内容をlogに記載
			error_log("[" . date("Y-m-d h:i:s") . "]PayPal決済失敗 ユーザーID=" . $_SESSION["id"] . " 金額=" . $price . " カード番号=" . $params["number"] . " エラーメッセージ=：" . $e->getMessage() . "\n", 3, PAY_ERR_LOG_PATH);
		}

		return $return_flg;
	}


	/**
	 * Stripe決済メソッド
	 * @param	str			$pay_id			支払いID
	 * @param	int			$user_id		ユーザーID
	 * @return	bool							成功：true　失敗：false
	 */
	function paymentProcessingStripe($pay_id, $user_id) {

		$return_flg = false;
		
		// 初期化
		require_once(ROOT . 'extension/stripe/vendor/autoload.php');

		try {
			\Stripe\Stripe::setApiKey(SECRET_KEY_STRIPE);

			// 決済
			$result = \Stripe\Charge::retrieve($pay_id);
			$result->capture();

			if (isset($result['error'])) {
				// エラー発生
				throw new Exception();
			}

			// 決済完了
			$return_flg = true;
			error_log("[" . date("Y-m-d h:i:s") . "]Stripe決済完了 ユーザーID=" . $user_id . " Stripe支払いID=" . $pay_id . "\n", 3, PAY_LOG_PATH);

		} catch (Exception $e) {
			// 例外の内容をlogに記載
			error_log("[" . date("Y-m-d h:i:s") . "]Stripe決済失敗 ユーザーID=" . $user_id . " Stripe支払いID=" . $pay_id . " エラーメッセージ=：" . $e->getMessage() . "\n", 3, PAY_ERR_LOG_PATH);
		}

		return $return_flg;
	}


	/**
	 * STRIPE決済キャンセルメソッド
	 * @param	int			$pay_id			支払いID
	 * @param	int			$user_id		ユーザーID
	 * @return	array()						成功：true（配列内にファイル名）　失敗：false（配列内にエラー文）
	 */
	function paymentCancelStripe($pay_id, $user_id) {

		$data = array();
		
		// 初期化
		require_once(ROOT . 'extension/stripe/vendor/autoload.php');

		try {
			\Stripe\Stripe::setApiKey(SECRET_KEY_STRIPE);

			// 決済キャンセル
			$result = \Stripe\Refund::create(array(
				"charge" => $pay_id
			));

			if (isset($result['error'])) {
				// エラー発生
				throw new Exception();
			}

			// 決済完了
			$return_flg = true;
			error_log("[" . date("Y-m-d h:i:s") . "]Stripe決済キャンセル完了 ユーザーID=" . $user_id . " Stripe支払いID=" . $pay_id . "\n", 3, PAY_LOG_PATH);

		} catch (Exception $e) {
			$return_flg = false;
			// 例外の内容をlogに記載
			error_log("[" . date("Y-m-d h:i:s") . "]Stripe決済決済失敗 ユーザーID=" . $user_id . " Stripe支払いID=" . $pay_id . " エラーメッセージ=：" . $e->getMessage() . "\n", 3, PAY_ERR_LOG_PATH);
		}

		return $return_flg;
	}


	/**
	 * STRIPEオーソリ解放メソッド
	 * @param	int			$pay_id			支払いID
	 * @param	int			$user_id		ユーザーID
	 * @return	array()						成功：true（配列内にファイル名）　失敗：false（配列内にエラー文）
	 */
	function authorizeCancelStripe($pay_id, $user_id) {

		$data = array();
		
		// 初期化
		require_once(ROOT . 'extension/stripe/vendor/autoload.php');

		try {
			\Stripe\Stripe::setApiKey(SECRET_KEY_STRIPE);

			// 決済キャンセル
			$result = \Stripe\Refund::create(array(
				"charge" => $pay_id
			));

			if (isset($result['error'])) {
				// エラー発生
				throw new Exception();
			}

			// 決済完了
			$return_flg = true;
			error_log("[" . date("Y-m-d h:i:s") . "]Stripe決済キャンセル完了 ユーザーID=" . $user_id . " Stripe支払いID=" . $pay_id . "\n", 3, PAY_LOG_PATH);

		} catch (Exception $e) {
			// 例外の内容をlogに記載
			error_log("[" . date("Y-m-d h:i:s") . "]Stripe決済決済失敗 ユーザーID=" . $user_id . " Stripe支払いID=" . $pay_id . " エラーメッセージ=：" . $e->getMessage() . "\n", 3, PAY_ERR_LOG_PATH);
		}

		return $return_flg;
	}


	/**
	 * 時間を指定分ごとに変換するメソッド
	 * @param	int			$punctuate	何分毎に変換するか
	 * @param	arr			$mode				モード(デフォルト:切り上げ)
	 * @param	date		$date				日時
	 * @return	bool							YYYY-mm-dd HH:ii:ss
	 * @assert ("2001-03-10 17:16:18") == "2001-03-10 17:30:00"
	 * @assert ("2001-03-10 17:00:00") == "2001-03-10 17:00:00"
	 * @assert ("2001-03-10 17:59:59") == "2001-03-10 18:00:00"
	 * @assert ("2001-03-10 17:40:18") == "2001-03-10 18:00:00"
	 */
	function dateByMinutes($punctuate = 30, $mode = 'ceil', $date = NULL) {
		
		// 初期化
		if (NULL == $date) {
			$date = date("Y-m-d H:i:s");
		}

		// 日時を分に分割
		$ymdh = date("Y-m-d H:", strtotime($date));
		$i = date("i", strtotime($date));

		// 変換
		if ($mode == 'ceil') {
			$i = sprintf("%02s", ceil($i / $punctuate) * $punctuate);
		} else {
			$i = sprintf("%02s", floor($i / $punctuate) * $punctuate);
		}

		$plus = "";
		// 切り上げ時に60になるか
		if (60 == $i) {
			$i = "00";
			$plus = " +1 hour";
		}
		// 日時の形式に戻す
		$date_str = date("Y-m-d H:i:s", strtotime($ymdh . $i . ":00" . $plus));

		return $date_str;
	}

	/**
	 * トークンをセッションにセットするメソッド
	 */
	function setToken() {
		$token = rtrim(base64_encode(openssl_random_pseudo_bytes(32)),'=');
		$_SESSION['token'] = $token;
	}

	/**
	 * トークンをセッションから取得するメソッド
	 * @param	str			$post_token			POSTされたトークン
	 * @param	str			$session_token	SESSIONに保存されたトークン
	 */
	function checkToken($post_token, $session_token) {
		//セッションが空か生成したトークンと異なるトークンでPOSTされたときは不正アクセス
		if(empty($session_token) || ($session_token != $post_token)) {
			error_log("[" . date("Y-m-d h:i:s") . "]不正なPOSTが行なわれました\n", 3, LOG_PATH);
			$this->redirect(ROOT . "static/err.php");
		}
	}

}

?>
