<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * データベースアクセスクラス
 *
 * データベースへのアクセスを行うクラス
 *
 * Developed on PHP versions 5.2.5
 *
 * @category	extension
 * @package		TECHFUND
 * @author		松山 雄太 <yuta_matsuyama@techfund.jp>
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
 * データベースアクセスクラス
 */
class DatabaseAccess {

	var $database;

	// コンストラクタ
	function __construct() {
		// DBアクセス
		$database = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

		if (mysqli_connect_errno()) {
			error_log("[" . date("Y-m-d h:i:s") . "]ConnectFailed status=" . mysqli_connect_error() . "\n", 3, LOG_PATH);
			CommonFunctions::redirect(ROOT . "static/err.php");
		}

		//$database = null;
		if ($database == null) {
			error_log("[" . date("Y-m-d h:i:s") . "]DB接続失敗 status=" . mysqli_connect_error() . "\n", 3, LOG_PATH);
			CommonFunctions::redirect(ROOT . "static/err.php");
		}
		$this->database =& $database;
		$this->database->set_charset('utf8');
	}

	// DB切断
	function close() {
		$this->database->close();
	}

	/**
	 * シンプルセレクトメソッド
	 * 汎用的なセレクト文
	 * @param		str		$sql						SQL文
	 * @param		arr		$where_arr			WHERE配列
	 * @param		arr		$order_arr			ORDER BY配列
	 * @return		array								成功時：取得データ配列　失敗時：空配列
	 */
	function simpleSQL($sql_str, $where_arr = array(), $order_arr = array()) {

		// 初期化
		$rows = array();

		// order句作成
		$order = $this->makeOrder($order_arr);

		// sql文作成
		$sql = $sql_str . $order . ";";

		// プリペアドステートメント作成開始
		$stmt = $this->database->prepare($sql);
		if (0 != count($where_arr)) {
			$args = $this->makeBind($where_arr);
			call_user_func_array(array($stmt, 'bind_param'), $args);
			//$stmt->bind_param($args);
		}
		// 実行
		$stmt->execute();
		$hits = $this->fetchAll($stmt);

		// 1件以上データがあるとき
		if (0 != count($hits)) {
			// データを取り出して配列に格納
			$rows = $hits;
		}

		$stmt->close();

		return $rows;
	}


	/**
	 * シンプルセレクトメソッド（IDの値がキーになった版：中身はほぼsimpleSQLと同様）
	 * 汎用的なセレクト文
	 * @param		str		$sql				SQL文
	 * @param		str		$key_name			ID名("user_id"や"content_id"等)
	 * @param		arr		$where_arr			WHERE配列
	 * @param		arr		$order_arr			ORDER BY配列
	 * @return		array						成功時：取得データ配列　失敗時：空配列
	 */
	function simpleSQLIdInKey($sql_str, $key_name, $where_arr = array(), $order_arr = array()) {
		
		// 初期化
		$rows = array();

		// order句作成
		$order = $this->makeOrder($order_arr);

		// sql文作成
		$sql = $sql_str . $order . ";";

		// プリペアドステートメント作成開始
		$stmt = $this->database->prepare($sql);
		if (0 != count($where_arr)) {
			$args = $this->makeBind($where_arr);
			call_user_func_array(array($stmt, 'bind_param'), $args);
			//$stmt->bind_param($args);
		}

		// 実行
		$stmt->execute();
		$hits = $this->fetchAllIdInKey($stmt, $key_name);

		// 1件以上データがあるとき
		if (0 != count($hits)) {
			// データを取り出して配列に格納
			$rows = $hits;
		}

		$stmt->close();
		
		return $rows;
	}


	/**
	 * シンプルセレクトメソッド
	 * 汎用的なセレクト文
	 * @param		str		$tblname			テーブル名
	 * @param		arr		$where_arr			WHERE配列
	 * @param		arr		$order_arr			ORDER BY配列
	 * @return		array						成功時：取得データ配列　失敗時：空配列
	 */
	function simpleSelect($tblname, $where_arr = array(), $order_arr = array()) {

		// 初期化
		$rows = array();

		// where句作成
		$where = $this->makeWhere($where_arr);

		// order句作成
		$order = $this->makeOrder($order_arr);

		// sql文作成
		$sql = "SELECT * FROM " . $tblname . $where . $order . ";";

		// プリペアドステートメント作成開始
		$stmt = $this->database->prepare($sql);
		if (0 != count($where_arr)) {
			$args = $this->makeBind($where_arr);
			call_user_func_array(array($stmt, 'bind_param'), $args);
			//$stmt->bind_param($args);
		}

		// 実行
		$stmt->execute();
		$hits = $this->fetchAll($stmt);

		// 1件以上データがあるとき
		if (0 != count($hits)) {
			// データを取り出して配列に格納
			$rows = $hits;
		}

		$stmt->close();

		return $rows;
	}


	/**
	 * シンプルセレクトメソッド（IDの値がキーになった版：中身はほぼsimpleSelectと同様）
	 * 汎用的なセレクト文
	 * @param		str		$tblname			テーブル名
	 * @param		str		$key_name			ID名("user_id"や"content_id"等)
	 * @param		arr		$where_arr			WHERE配列
	 * @param		arr		$order_arr			ORDER BY配列
	 * @return		array						成功時：取得データ配列　失敗時：空配列
	 */
	function simpleSelectIdInKey($tblname, $key_name, $where_arr = array(), $order_arr = array()) {

		// 初期化
		$rows = array();

		// where句作成
		$where = $this->makeWhere($where_arr);

		// order句作成
		$order = $this->makeOrder($order_arr);

		// sql文作成
		$sql = "SELECT * FROM " . $tblname . $where . $order . ";";

		// プリペアドステートメント作成開始
		$stmt = $this->database->prepare($sql);
		if (0 != count($where_arr)) {
			$args = $this->makeBind($where_arr);
			call_user_func_array(array($stmt, 'bind_param'), $args);
			//$stmt->bind_param($args);
		}

		// 実行
		$stmt->execute();
		$hits = $this->fetchAllIdInKey($stmt, $key_name);

		// 1件以上データがあるとき
		if (0 != count($hits)) {
			// データを取り出して配列に格納
			$rows = $hits;
		}

		$stmt->close();

		return $rows;
	}


	/**
	 * シンプルインサートメソッド
	 * 汎用的なインサート文
	 * @param		str		$tblname				テーブル名
	 * @param		arr		$data					登録するデータ
	 * @return		bool							true：インサート成功　false：インサート失敗
	 */
	function simpleInsert($tblname, $data = array()) {

		// 初期化
		$insert_str = $values_str = "";
		$sql_param = array();
		$return_flg = false;

		// インサート内容があるか
		if (0 != count($data)) {
			// データ内容の作成
			foreach ($data as $key => $value) {
				if ("" != $insert_str) {
					$insert_str .= ", ";
					$values_str .= ", ";
				}
				$insert_str .= $key;
				$values_str .= "?";
				$sql_param[] = $data[$key];
			}

			// sql文の作成
			$sql = 'INSERT INTO ' . $tblname . ' (' . $insert_str . ') values (' . $values_str . ');';

			// 自動コミットをOFF
			$this->database->autocommit(FALSE);

			// 実行
			$stmt = $this->database->prepare($sql);
			if (0 != count($sql_param)) {
				$args = $this->makeBind($sql_param);
				call_user_func_array(array($stmt, 'bind_param'), $args);
			}
			$stmt->execute();

			$err = $stmt->error;

			if (1 != $stmt->affected_rows) {
				// 影響のあった行が1行でないならばロールバック
				$this->database->rollback();
				error_log("[" . date("Y-m-d h:i:s") . "]simpleInsert失敗 sql文:" . $sql . "　エラー文: " . $err . "\n", 3, LOG_PATH);
			} else {
				// 影響のあった行が1行ならばコミット
				$this->database->commit();
				$return_flg = true;
			}

			$stmt->close();
		}
		
		return $return_flg;
	}


	/**
	 * シンプルアップデートメソッド
	 * 汎用的なアップデート文
	 * @param		str		$tblname			テーブル名
	 * @param		arr		$data					登録するデータ
	 * @param		arr		$where_arr		WHERE配列
	 * @return		bool							true：アップデート成功　false：アップデート失敗
	 */
	function simpleUpdate($tblname, $data = array(), $where_arr = array()) {

		// 初期化
		$where = "";
		$update_str = "";
		$return_flg = false;

		// アップデート内容があるか
		if (0 != count($data)) {
			// データ内容の作成
			foreach ($data as $key => $value) {
				if ("" != $update_str) {
					$update_str .= ", ";
				}
				$update_str .= $key . " = ?";
				$sql_param[] = $data[$key];
			}

			if (0 != count($where_arr)) {
				// where句作成
				foreach ($where_arr as $key => $value) {
					if ("" != $where) {
						$where .= " AND ";
					} else {
						$where .= " WHERE ";
					}
					$where .= $key . " = ?";
					$sql_param[] = $where_arr[$key];
				}
			}

			// sql文の作成
			$sql = 'UPDATE ' . $tblname . ' SET ' . $update_str . $where . ';';

			// 自動コミットをOFF
			$this->database->autocommit(FALSE);

			// 実行
			$stmt = $this->database->prepare($sql);
			if (0 != count($sql_param)) {
				$args = $this->makeBind($sql_param);
				call_user_func_array(array($stmt, 'bind_param'), $args);
			}
			$stmt->execute();

			$err = $stmt->error;

			if (1 != $stmt->affected_rows) {
				// クエリ失敗の場合ロールバック
				$this->database->rollback();
				error_log("[" . date("Y-m-d h:i:s") . "]simpleUpdate失敗 sql文:" . $sql . "　エラー文: " . $err . "\n", 3, LOG_PATH);
			} else {
				// 影響のあった行が1行ならばコミット
				$this->database->commit();
				$return_flg = true;
			}

			$stmt->close();
		}

		return $return_flg;
	}


	/**
	 * シンプルデリートメソッド
	 * 汎用的なデリート文
	 * @param		str		$tblname				テーブル名
	 * @param		arr		$where_arr				WHERE配列
	 * @return		bool							true：インサート成功　false：インサート失敗
	 */
	function simpleDelete($tblname, $where_arr = array()) {

		// 初期化
		$where = "";
		$return_flg = false;

		if (0 != count($where_arr)) {
			// where句作成
			foreach ($where_arr as $key => $value) {
				if ("" != $where) {
					$where .= " AND ";
				} else {
					$where .= " WHERE ";
				}
				$where .= $key . " = ?";
				$sql_param[] = $where_arr[$key];
			}
		}

		// sql文の作成
		$sql = 'DELETE FROM ' . $tblname . $where . ';';

		// 自動コミットをOFF
		$this->database->autocommit(FALSE);

		// 実行
		$stmt = $this->database->prepare($sql);
		if (0 != count($sql_param)) {
			$args = $this->makeBind($sql_param);
			call_user_func_array(array($stmt, 'bind_param'), $args);
		}
		$stmt->execute();

		$err = $stmt->error;

		if (1 != $stmt->affected_rows) {
			// 影響のあった行が1行でないならばロールバック
			$this->database->rollback();
			error_log("[" . date("Y-m-d h:i:s") . "]simpleDelete失敗 sql文:" . $sql . "　エラー文: " . $err . "\n", 3, LOG_PATH);
		} else {
			// 影響のあった行が1行ならばコミット
			$this->database->commit();
			$return_flg = true;
		}

		$stmt->close();

		return $return_flg;
	}


	/**
	 * シンプルデリートメソッド(複数行削除)
	 * 汎用的なデリート文
	 * @param		str		$tblname				テーブル名
	 * @param		arr		$where_arr				WHERE配列
	 * @return		bool							true：インサート成功　false：インサート失敗
	 */
	function simpleMultiDelete($tblname, $where_arr = array()) {

		// 初期化
		$where = "";
		$return_flg = false;

		if (0 != count($where_arr)) {
			// where句作成
			foreach ($where_arr as $key => $value) {
				if ("" != $where) {
					$where .= " AND ";
				} else {
					$where .= " WHERE ";
				}
				$where .= $key . " = ?";
				$sql_param[] = $where_arr[$key];
			}
		}

		// sql文の作成
		$sql = 'DELETE FROM ' . $tblname . $where . ';';

		// 自動コミットをOFF
		$this->database->autocommit(FALSE);

		// 実行
		$stmt = $this->database->prepare($sql);
		if (0 != count($sql_param)) {
			$args = $this->makeBind($sql_param);
			call_user_func_array(array($stmt, 'bind_param'), $args);
		}
		$stmt->execute();

		$err = $stmt->error;

		if ($err) {
			// エラーがあればロールバック
			$this->database->rollback();
			error_log("[" . date("Y-m-d h:i:s") . "]simpleDelete失敗 sql文:" . $sql . "　エラー文: " . $err . "\n", 3, LOG_PATH);
		} else {
			// エラーがなければコミット
			$this->database->commit();
			$return_flg = true;
		}

		$stmt->close();

		return $return_flg;
	}


	/**
	 * SELECT句作成メソッド
	 * @param		arr		$select_arr	SELECT配列
	 * @return	str		$select			SELECT文
	 */
	function makeSelect($select_arr = array()) {
		// 初期化
		$select = "";

		// SELECT句作成
		foreach ($select_arr as $key => $value) {
			if ("" != $select) {
				$select .= "SELECT ";
			} else {
				$select .= ", ";
			}
			$select .= $key;
		}

		return $select;
	}


	/**
	 * WHERE句作成メソッド
	 * @param		arr		$where_arr	WHERE配列
	 * @return	str		$where			WHERE文
	 */
	function makeWhere($where_arr = array()) {
		// 初期化
		$where = "";

		// WHERE句作成
		foreach ($where_arr as $key => $value) {
			if ("" != $where) {
				$where .= " AND ";
			} else {
				$where .= " WHERE ";
			}
			$where .= $key . " = ?";
		}

		return $where;
	}


	/**
	 * ORDER句作成メソッド
	 * @param		arr		$order_arr	ORDER配列
	 * @return	str		$order			ORDER文
	 */
	function makeOrder($order_arr = array()) {
		// 初期化
		$order = "";

		foreach ($order_arr as $key => $value) {
			if ("" != $order) {
				$order .= ", ";
			} else {
				$order .= " ORDER BY ";
			}
			$order .= $key . " " . $value;
		}

		return $order;
	}


	/**
	 * バインド配列作成メソッド
	 * @param		arr		$param_arr	パラメータ配列
	 */
	function makeBind(&$param_arr) {

		$args = array("");
		
		foreach($param_arr as $key => $param){
			if (is_int($param)) {
				$args[0] .= "i";
			} elseif (is_double($param)) {
				$args[0] .= "d";
			} else {
				if (strpos($param, "\0") === false) {
					$args[0] .= "s";
					$param = (string) $param;
				} else {
					$args[0] .= "b";
				}
			}
			$args[] = &$param_arr[$key];
		}

		return $args;
	}


	/**
	 * プリペアドステートメント利用時の結果全取得メソッド
	 * @param		obj		$stmt				プリペアドステートメント
	 * @return	arr		$hits				結果配列
	 */
	function fetchAll(& $stmt) {
		$hits = array();
		$params = array();
		$meta = $stmt->result_metadata();
		while ($field = $meta->fetch_field()) {
				$params[] = &$row[$field->name];
		}
		call_user_func_array(array($stmt, 'bind_result'), $params);
		while ($stmt->fetch()) {
			$c = array();
			foreach($row as $key => $val) {
				$c[$key] = $val;
			}
			$hits[] = $c;
		}
		return $hits;
	}


	/**
	 * プリペアドステートメント利用時の結果全取得メソッド
	 * @param		obj		$stmt				プリペアドステートメント
	 * @param		str		$key_name		ID名("user_id"や"content_id"等)
	 * @return	arr		$hits				結果配列
	 */
	function fetchAllIdInKey(& $stmt, $key_name) {
		$hits = array();
		$params = array();
		$meta = $stmt->result_metadata();
		while ($field = $meta->fetch_field()) {
				$params[] = &$row[$field->name];
		}
		call_user_func_array(array($stmt, 'bind_result'), $params);
		while ($stmt->fetch()) {
			$c = array();
			foreach($row as $key => $val) {
				$c[$key] = $val;
			}
			$hits[$c[$key_name]] = $c;
		}
		return $hits;
	}

}

?>