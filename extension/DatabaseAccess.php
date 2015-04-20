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
 * @package		techfund
 * @author		松山 雄太 <yuta_matsuyama@techfund.jp>
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
			exit;
		}

		//$database = null;
		if ($database == null) {
			error_log("[" . date("Y-m-d h:i:s") . "]DB接続失敗 status=" . mysqli_connect_error() . "\n", 3, LOG_PATH);
			exit;
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
	 * @param		str		$sql				SQL文
	 * @param		arr		$where_arr			WHERE配列
	 * @param		arr		$order_arr			ORDER BY配列
	 * @return		array						成功時：取得データ配列　失敗時：空配列
	 */
	function simpleSQL($sql_str, $where_arr = array(), $order_arr = array()) {

		// 初期化
		$where = $order = "";
		$rows = array();

		// where句作成
		foreach ($where_arr as $key => $value) {
			if ("" != $where) {
				$where .= " AND ";
			} else {
				$where .= " WHERE ";
			}
			if (NULL === $value) {
				$where .= $key . " IS NULL";
			} else {
				$where .= $key . " = '" . $value . "'";
			}
		}

		// order句作成
		foreach ($order_arr as $key => $value) {
			if ("" != $order) {
				$order .= ", ";
			} else {
				$order .= " ORDER BY ";
			}
			$order .= $key . " " . $value;
		}

		// sql文作成
		$sql = $sql_str . $where . $order . ";";
		// 実行
		$result = mysqli_query($this->database, $sql);

		// 1件以上データがあるとき
		if (0 != $result->num_rows) {
			// データを取り出して配列に格納
			while ($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}
		}

		return $rows;
	}


	/**
	 * シンプルセレクトメソッド（IDの値がキーになった版：中身はほぼsimpleSQLと同様）
	 * 汎用的なセレクト文
	 * @param		str		$sql				SQL文
	 * @param		str		$keyname			ID名("user_id"や"content_id"等)
	 * @param		arr		$where_arr			WHERE配列
	 * @param		arr		$order_arr			ORDER BY配列
	 * @return		array						成功時：取得データ配列　失敗時：空配列
	 */
	function simpleSQLIdInKey($sql_str, $keyname, $where_arr = array(), $order_arr = array()) {
		
		// 初期化
		$where = $order = "";
		$rows = array();
		
		// where句作成
		foreach ($where_arr as $key => $value) {
			if ("" != $where) {
				$where .= " AND ";
			} else {
				$where .= " WHERE ";
			}
			if (NULL === $value) {
				$where .= $key . " IS NULL";
			} else {
				$where .= $key . " = '" . $value . "'";
			}
		}
		
		// order句作成
		foreach ($order_arr as $key => $value) {
			if ("" != $order) {
				$order .= ", ";
			} else {
				$order .= " ORDER BY ";
			}
			$order .= $key . " " . $value;
		}
		
		// sql文作成
		$sql = $sql_str . $where . $order . ";";
		// 実行
		$result = mysqli_query($this->database, $sql);
		
		// 1件以上データがあるとき
		if (0 != $result->num_rows) {
			// データを取り出して配列に格納
			while ($row = $result->fetch_assoc()) {
				$rows[$row[$keyname]] = $row;
			}
		}
		
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
		$where = $order = "";
		$rows = array();

		foreach ($where_arr as $key => $value) {
			if ("" != $where) {
				$where .= " AND ";
			} else {
				$where .= "WHERE ";
			}
			if (NULL === $value) {
				$where .= $key . " IS NULL";
			} else {
				$where .= $key . " = '" . $value . "'";
			}
		}

		// order句作成
		foreach ($order_arr as $key => $value) {
			if ("" != $order) {
				$order .= ", ";
			} else {
				$order .= "ORDER BY ";
			}
			$order .= $key . " " . $value;
		}

		// sql文作成
		$sql = "SELECT * FROM $tblname $where $order";
		// 実行
		$result = mysqli_query($this->database, $sql);

		// 1件以上データがあるとき
		if (0 != $result->num_rows) {
			// データを取り出して配列に格納
			while ($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}
		}

		return $rows;
	}


	/**
	 * シンプルセレクトメソッド（IDの値がキーになった版：中身はほぼsimpleSelectと同様）
	 * 汎用的なセレクト文
	 * @param		str		$tblname			テーブル名
	 * @param		str		$keyname			ID名("user_id"や"content_id"等)
	 * @param		arr		$where_arr			WHERE配列
	 * @param		arr		$order_arr			ORDER BY配列
	 * @return		array						成功時：取得データ配列　失敗時：空配列
	 */
	function simpleSelectIdInKey($tblname, $keyname, $where_arr = array(), $order_arr = array()) {

		// 初期化
		$where = $order = "";
		$rows = array();

		// where句作成
		foreach ($where_arr as $key => $value) {
			if ("" != $where) {
				$where .= " AND ";
			} else {
				$where .= "WHERE ";
			}
			if (NULL === $value) {
				$where .= $key . " IS NULL";
			} else {
				$where .= $key . " = '" . $value . "'";
			}
		}

		// order句作成
		foreach ($order_arr as $key => $value) {
			if ("" != $order) {
				$order .= ", ";
			} else {
				$order .= "ORDER BY ";
			}
			$order .= $key . " " . $value;
		}

		// sql文作成
		$sql = "SELECT * FROM $tblname $where $order";
		// 実行
		$result = $this->database->query($sql);

		// 1件以上データがあるとき
		if (0 != $result->num_rows) {
			// データを取り出して配列に格納
			while ($row = $result->fetch_assoc()) {
				$rows[$row[$keyname]] = $row;
			}
		}

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
				if (NULL != $value) {
					$values_str .= "'" . $this->database->real_escape_string($value) . "'";
				} else {
					$values_str .= "NULL";
				}
			}

			// 自動コミットをOFF
			$this->database->autocommit(FALSE);

			// sql文の作成
			$sql = 'INSERT INTO ' . $tblname . ' (' . $insert_str . ') values (' . $values_str . ');';
			// 実行
			$result = mysqli_query($this->database, $sql);

			if (1 != $this->database->affected_rows) {
				// 影響のあった行が1行でないならばロールバック
				$this->database->rollback();
				error_log("[" . date("Y-m-d h:i:s") . "]simpleInsert失敗 sql文:" . $sql . "\n", 3, LOG_PATH);
			} else {
				// 影響のあった行が1行ならばコミット
				$this->database->commit();
				$return_flg = true;
			}
		}
		return $return_flg;
	}


	/**
	 * シンプルアップデートメソッド
	 * 汎用的なアップデート文
	 * @param		str		$tblname				テーブル名
	 * @param		arr		$data					登録するデータ
	 * @param		arr		$where_arr				WHERE配列
	 * @return		bool							true：インサート成功　false：インサート失敗
	 */
	function simpleUpdate($tblname, $data = array(), $where_arr = array()) {

		// 初期化
		$where = "";
		$update_str = "";
		$return_flg = false;

		if (0 != count($where_arr)) {
			// where句作成
			foreach ($where_arr as $key => $value) {
				if ("" != $where) {
					$where .= " AND ";
				} else {
					$where .= " WHERE ";
				}
				if (NULL != $value) {
					$where .= $key . " = '" . $this->database->real_escape_string($value) . "'";
				} else {
					$where .= $key . " IS NULL";
				}
			}
		}

		// アップデート内容があるか
		if (0 != count($data)) {
			// データ内容の作成
			foreach ($data as $key => $value) {
				if ("" != $update_str) {
					$update_str .= ", ";
				}
				if (NULL != $value) {
					$update_str .= $key . " = '" . $this->database->real_escape_string($value) . "'";
				} else {
					$update_str .= $key . " = NULL";
				}
			}

			// 自動コミットをOFF
			$this->database->autocommit(FALSE);

			// sql文の作成
			$sql = 'UPDATE ' . $tblname . ' SET ' . $update_str . $where . ';';

			// 実行
			$result = mysqli_query($this->database, $sql);

			if (1 != $this->database->affected_rows) {
				// 影響のあった行が1行でない場合
				if (0 == $this->database->affected_rows) {
					// 0行（更新無しまたはWHERE区に当てはまる行がなかった場合）、WHERE区に当てはまる行があるか確認
					$sql = "SELECT * FROM " . $tblname . $where;
					// 実行
					$result2 = $this->database->query($sql);
					if (0 != $result2->num_rows) {
						// 更新なし
						$return_flg = true;
					} else {
						// WHERE区に当てはまる行が無い場合(クエリ失敗)ロールバック
						$this->database->rollback();
						error_log("[" . date("Y-m-d h:i:s") . "]simpleUpdate失敗 sql文:" . $sql . "\n", 3, LOG_PATH);
					}
				} else {
					// クエリ失敗の場合ロールバック
					$this->database->rollback();
					error_log("[" . date("Y-m-d h:i:s") . "]simpleUpdate失敗 sql文:" . $sql . "\n", 3, LOG_PATH);
				}
			} else {
				// 影響のあった行が1行ならばコミット
				$this->database->commit();
				$return_flg = true;
			}
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
				$where .= $key . " = '" . $this->database->real_escape_string($value) . "'";
			}
		}


		// 自動コミットをOFF
		$this->database->autocommit(FALSE);

		// sql文の作成
		$sql = 'DELETE FROM ' . $tblname . $where . ';';

		// 実行
		$result = mysqli_query($this->database, $sql);

		if (1 != $this->database->affected_rows) {
			// 影響のあった行が1行でないならばロールバック
			$this->database->rollback();
			error_log("[" . date("Y-m-d h:i:s") . "]simpleDelete失敗 sql文:" . $sql . "\n", 3, LOG_PATH);
		} else {
			// 影響のあった行が1行ならばコミット
			$this->database->commit();
			$return_flg = true;
		}

		return $return_flg;
	}

}

?>
