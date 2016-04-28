
​
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
​
--
-- Database: `pochimomi`
--
​
-- --------------------------------------------------------
​
--
-- テーブルの構造 `users` ユーザーテーブル
--
​
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,   -- ユーザーID
  `facebook_id` bigint(20) UNSIGNED DEFAULT NULL,     -- Facebook ID
  `user_name` varchar(50) NOT NULL,                   -- ユーザー名
  `sex` int(1) UNSIGNED NOT NULL,                     -- 性別(1:男性、2:女性)
  `email` varchar(256) NOT NULL,                      -- メールアドレス
  `password` varchar(72) NOT NULL,                    -- パスワード
  `tel` varchar(16) NOT NULL,                         -- 電話番号
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,      -- 会社コード
  `building_id` int(11) UNSIGNED DEFAULT NULL,        -- ビルコード
  `address` varchar(50) DEFAULT NULL,                 -- 住所
  `img_path` varchar(50) DEFAULT NULL,                -- プロフィール画像パス(プログラム側で表示の際「/assets/img/user_images/」が付く)
  `del_flg` int(1) UNSIGNED NOT NULL DEFAULT '0',     -- 論理削除フラグ(0:有効、1:無効)
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
​

-- --------------------------------------------------------
​
--
-- テーブルの構造 `user_cards` ユーザーのクレジットカード情報テーブル
--

CREATE TABLE IF NOT EXISTS `user_cards` (
  `user_id` bigint(20) UNSIGNED NOT NULL,             -- ユーザーID
  `card_type` int(1) UNSIGNED NOT NULL,               -- カード種別(1:VISA, 2:Master, 3:JCB)
  `card_number` varchar(16) NOT NULL,                 -- カード番号
  `card_name` varchar(50) NOT NULL,                   -- カード名義
  `card_expiration_dt` int(10) NOT NULL,              -- カード有効期限
  `card_security_number` int(4) UNSIGNED NOT NULL,    -- カードセキュリティ番号
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------
​
--
-- テーブルの構造 `user_card_tokens` ユーザーのクレジットカード情報テーブル
--

CREATE TABLE IF NOT EXISTS `user_card_tokens` (
  `user_id` bigint(20) UNSIGNED NOT NULL,             -- ユーザーID
  `token` varchar(50) NOT NULL,                       -- トークンID
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
​
--
-- テーブルの構造 `companies` 会社マスタ
--
​
CREATE TABLE IF NOT EXISTS `companies` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,   -- 会社ID
  `company_name` varchar(50) NOT NULL,                -- 会社名
  `contact_name` varchar(50) NOT NULL,                -- 担当者名
  `email` varchar(256) NOT NULL,                      -- 担当者メールアドレス
  `tel` varchar(16) NOT NULL,                         -- 担当者電話番号
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- --------------------------------------------------------
​
--
-- テーブルの構造 `buildings` ビルマスタ
--
​
CREATE TABLE IF NOT EXISTS `buildings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,   -- ビルID
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,      -- 会社コード
  `building_name` varchar(100) NOT NULL,              -- ビル名
  `default_address` varchar(50) DEFAULT NULL,         -- 選択時デフォルト入力される住所
  `admission_method` text DEFAULT NULL,               -- 入館方法
  `location_x` double(8,6) DEFAULT NULL,              -- 住所座標(x)
  `location_y` double(9,6) DEFAULT NULL,              -- 住所座標(y)
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- --------------------------------------------------------
​
--
-- テーブルの構造 `clients` 店舗テーブル
--
​
CREATE TABLE IF NOT EXISTS `clients` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,   -- 店舗ID
  `client_name` varchar(50) NOT NULL,                 -- 店舗名
  `description` text NOT NULL,                        -- 店舗紹介
  `zipcode` varchar(8) NOT NULL,                      -- 郵便番号
  `address` varchar(50) NOT NULL,                     -- 住所
  `location_x` double(8,6) DEFAULT NULL,              -- 住所座標(x)
  `location_y` double(9,6) DEFAULT NULL,              -- 住所座標(y)
  `email` varchar(256) NOT NULL,                      -- 店舗メールアドレス
  `password` varchar(72) NOT NULL,                    -- パスワード
  `tel` varchar(16) NOT NULL,                         -- 店舗電話番号(携帯)
  `landline` varchar(16) NOT NULL,                    -- 店舗電話番号(固定)
  `business_hours_start` time DEFAULT NULL,           -- 営業時間(開始)
  `business_hours_end` time DEFAULT NULL,             -- 営業時間(終了)
  `accepted_hours_start` time DEFAULT NULL,           -- 受付可能時間(開始)
  `accepted_hours_end` time DEFAULT NULL,             -- 受付可能時間(終了)
  `limit_time` int(3) UNSIGNED DEFAULT NULL,          -- 予約を受ける限界の時間（何分前まで受け付けるか）
  `bank_name` varchar(50) DEFAULT NULL,               -- 銀行名
  `branch_name` varchar(50) DEFAULT NULL,             -- 支店名
  `account_type` int(1) UNSIGNED DEFAULT NULL,        -- 口座種別(1:普通、2:当座)
  `account_number` int(7) UNSIGNED DEFAULT NULL,      -- 口座番号
  `account_name` varchar(50) DEFAULT NULL,            -- 名義人名
  `exam_flg` int(1) UNSIGNED NOT NULL DEFAULT '0',    -- 審査フラグ(0:審査中、1:許可)
  `del_flg` int(1) UNSIGNED NOT NULL DEFAULT '0',     -- 論理削除フラグ(0:有効、1:無効)
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
​

-- --------------------------------------------------------
​
--
-- テーブルの構造 `client_images` 店舗画像テーブル
-- 初期は4枚固定だが、増やすことを想定して別テーブルで管理
--
​
CREATE TABLE IF NOT EXISTS `client_images` (
  `client_id` bigint(20) UNSIGNED NOT NULL,           -- 店舗ID
  `img_path` varchar(50) DEFAULT NULL,                -- プロフィール画像パス(プログラム側で表示の際「/assets/img/client_images/」が付く)
  `created_at` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
​
--
-- テーブルの構造 `pros` 揉み手テーブル
--
​
CREATE TABLE IF NOT EXISTS `pros` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,   -- 揉み手ID
  `client_id` bigint(20) UNSIGNED NOT NULL,           -- 店舗ID
  `pro_name` varchar(50) NOT NULL,                    -- 名前
  `kind` int(1) UNSIGNED DEFAULT NULL,                -- 形態(1:出張のみ、2:店舗のみ、3:両方)
  `email` varchar(256) NOT NULL,                      -- 揉み手メールアドレス
  `password` varchar(72) NOT NULL,                    -- パスワード
  `tel` varchar(16) DEFAULT NULL,                     -- 携帯番号
  `price` int(5) UNSIGNED NOT NULL,                   -- 価格
  `menu` text DEFAULT NULL,                           -- 施術方法
  `description` text DEFAULT NULL,                    -- 自己PR
  `img_path` varchar(50) DEFAULT NULL,                -- 揉み手紹介画像パス(プログラム側で表示の際「/assets/img/pro_images/」が付く)
  `license1_holder_flg` int(1) DEFAULT NULL,          -- あん摩マッサージ指圧師の資格(0:なし、1:あり)
  `license2_holder_flg` int(1) DEFAULT NULL,          -- はり師の資格(0:なし、1:あり)
  `license3_holder_flg` int(1) DEFAULT NULL,          -- きゅう師の資格(0:なし、1:あり)
  `license4_holder_flg` int(1) DEFAULT NULL,          -- 柔道整復師の資格(0:なし、1:あり)
  `exam_flg` int(1) UNSIGNED NOT NULL DEFAULT '0',    -- 審査フラグ(0:審査中、1:許可)
  `review_avg` float(6) UNSIGNED NOT NULL DEFAULT '0',-- 評価平均値(並べ替え等で参照が多い要素なのであえて正規化せず保持)
  `del_flg` int(1) UNSIGNED NOT NULL DEFAULT '0',     -- 論理削除フラグ(0:有効、1:無効)
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
​
--
-- テーブルの構造 `pro_works` 揉み手出勤情報テーブル
-- 将来的に曜日ごと(もしくは日ごと)に出勤管理することを想定して別テーブルで管理
--
​
CREATE TABLE IF NOT EXISTS `pro_works` (
  `pro_id` bigint(20) UNSIGNED NOT NULL,              -- 揉み手ID
  `week` int(1) UNSIGNED DEFAULT NULL,                -- 曜日(ISO-8601 形式 1:月曜〜7:日曜)
  `accepted_hours_start` time DEFAULT NULL,           -- 受付可能時間(開始)
  `accepted_hours_end` time DEFAULT NULL,             -- 受付可能時間(終了)
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
​
-- --------------------------------------------------------
​
--
-- テーブルの構造 `bookings` 予約テーブル
--
​
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,   -- 予約ID
  `user_id` bigint(20) UNSIGNED NOT NULL,             -- ユーザーID
  `pro_id` bigint(20) UNSIGNED NOT NULL,              -- 揉み手ID
  `start_dt` int(10) NOT NULL,                        -- 予約日時(開始)
  `end_dt` int(10) NOT NULL,                          -- 予約日時(終了)
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,      -- 会社コード
  `building_id` bigint(20) UNSIGNED DEFAULT NULL,     -- ビルコード
  `address` varchar(50) NOT NULL,                     -- 住所
  `location_x` double(8,6) DEFAULT NULL,              -- 施術住所座標(x)
  `location_y` double(9,6) DEFAULT NULL,              -- 施術住所座標(y)
  `pr_code` varchar(8) DEFAULT NULL,                  -- 利用したプロモーションコード
  `price` int(11) UNSIGNED NOT NULL,                  -- 基本料金
  `carfare` int(11) UNSIGNED NOT NULL,                -- 出張費
  `tax` int(11) UNSIGNED NOT NULL,                    -- 消費税
  `discount` int(11) UNSIGNED NOT NULL,               -- 割引額
  `amount` int(11) UNSIGNED NOT NULL,                 -- 合計金額
  `pay_id` varchar(50) DEFAULT NULL,                  -- 支払いID(APIが配布するID)
  `status` int(1) UNSIGNED NOT NULL DEFAULT '0',      -- 状態(0:揉み手未回答、1:揉み手許可、2:拒否)
  `review_flg` int(1) UNSIGNED NOT NULL DEFAULT '0',  -- ユーザー評価フラグ(0:未レビュー、1:レビュー済み)
  `created_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
​
​

-- --------------------------------------------------------
​
--
-- テーブルの構造 `steads` 代替者テーブル
--
​
CREATE TABLE IF NOT EXISTS `steads` (
  `booking_id` bigint(20) UNSIGNED NOT NULL,          -- 予約ID
  `pro_id` bigint(20) UNSIGNED NOT NULL,              -- 揉み手ID
  `created_at` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------
​
--
-- テーブルの構造 `reviews` 評価テーブル
--
​
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,   -- レビューID
  `user_id` bigint(20) UNSIGNED NOT NULL,             -- レビュー者
  `pro_id` bigint(20) UNSIGNED NOT NULL,              -- 被レビュー者
  `score` int(1) UNSIGNED NOT NULL,                   -- 評価値
  `body` text DEFAULT NULL,                           -- 評価コメント
  `body_inside` text DEFAULT NULL,                    -- 運営へのコメント
  `created_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
​

-- --------------------------------------------------------
​
--
-- テーブルの構造 `pr_codes` キャンペーンコードテーブル
--
​
CREATE TABLE IF NOT EXISTS `pr_codes` (
  `pr_code` varchar(8) NOT NULL,                      -- キャンペーンコード
  `discount` int(11) UNSIGNED NOT NULL,               -- 割引額
  `created_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`pr_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
​
​
-- --------------------------------------------------------
​
--
-- テーブルの構造 `search_logs` 検索ログ
--
​
CREATE TABLE IF NOT EXISTS `search_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,         -- ユーザーID
  `search` varchar(50) NOT NULL,                      -- 検索内容
  `created_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
​
​
-- --------------------------------------------------------
​
--
-- テーブルの構造 `user_gps_logs` 位置情報ログ(ユーザー側)
-- カンパニーURLからのアクセスではない場合、位置情報を記録する
--
​
CREATE TABLE IF NOT EXISTS `user_gps_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,         -- ユーザーID
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,      -- 会社コード
  `building_id` bigint(20) UNSIGNED DEFAULT NULL,     -- ビルコード
  `location_x` int(11) DEFAULT NULL,                  -- 場所(x)
  `location_y` int(11) DEFAULT NULL,                  -- 場所(y)
  `created_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

​
-- --------------------------------------------------------
​
--
-- テーブルの構造 `password_forgets` パスワード忘れ
--
​
CREATE TABLE IF NOT EXISTS `password_forgets` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,         -- ユーザーID
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,       -- 店舗ID
  `pro_id` bigint(20) UNSIGNED DEFAULT NULL,          -- 揉み手ID
  `k` varchar(20) NOT NULL,                           -- 一時URL
  `created_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
​
​
-- --------------------------------------------------------
​
--
-- テーブルの構造 `sms_queues` SMS送信キュー
-- cronにより実行するためのテーブル
--
​
CREATE TABLE IF NOT EXISTS `sms_queues` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tel` varchar(16) NOT NULL,                         -- SMS送信先の携帯番号
  `body` text DEFAULT NULL,                           -- 送信内容
  `action_dt` int(10) NOT NULL,                       -- 送信日時
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,      -- 予約ID
  `created_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

​
-- --------------------------------------------------------
​
--
-- テーブルの構造 `tel_logs` 電話予約ボタン押下ログ
--
​
CREATE TABLE IF NOT EXISTS `tel_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,         -- ユーザーID
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,       -- 店舗ID
  `pro_id` bigint(20) UNSIGNED DEFAULT NULL,          -- 揉み手ID
  `created_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
​
​
-- --------------------------------------------------------
​
--
-- テーブルの構造 `hashs` 一時ログイン用ハッシュテーブル
--
​
CREATE TABLE IF NOT EXISTS `hashs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `k` varchar(50) DEFAULT NULL,         -- ハッシュ値
  `pro_id` bigint(20) UNSIGNED DEFAULT NULL,          -- 揉み手ID
  `created_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
​