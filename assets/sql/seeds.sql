
-- clients
INSERT INTO clients (`email`, `password`, `tel`, `client_name`, `description`, `limit_time`, `zipcode`, `address`, `location_x`, `location_y`, `business_hours_start`, `business_hours_end`, `accepted_hours_start`, `accepted_hours_end`, `exam_flg`, `created_at`, `updated_at`) VALUES ("matsuyama@techfund.jp", "test1234", "080-4045-4560", "松崎カイロプラクティック", "最先端のカイロプラティック 新橋整体院", 10, "105-0004", "東京都港区新橋5丁目12-9、ABCビル4F", 35.6630314, 139.7590818, "10:00:00", "02:00:00", "10:00:00", "02:00:00", 1, 1455447338, 1455447338);

INSERT INTO clients (`email`, `password`, `tel`, `client_name`, `description`, `limit_time`, `zipcode`, `address`, `location_x`, `location_y`, `business_hours_start`, `business_hours_end`, `accepted_hours_start`, `accepted_hours_end`, `exam_flg`, `created_at`, `updated_at`) VALUES ("matsuyama@techfund.jp", "test1234", "080-4045-4560", "ラフィネ ペディ汐留店", "最先端のカイロプラティック 新橋整体院", 10, "105-0004", "東京都港区新橋5丁目12-9、ABCビル4F", 35.6630314, 139.7590818, "10:00:00", "02:00:00", "10:00:00", "02:00:00", 1, 1455447338, 1455447338);

-- client_images
INSERT INTO client_images (`client_id`, `img_path`, `created_at`) VALUES (1, "sample.png", 1455447338);

-- pros
INSERT INTO pros (`client_id`, `email`, `password`, `tel`, `kind`, `pro_name`, `description`, `img_path`, `price`, `exam_flg`, `created_at`, `updated_at`) VALUES (1, "matsuyama@techfund.jp", "test1234", "080-4045-4560", 3, "佐藤 智子", "筋肉を余分な力みのないリラックスした状態にゆるめ行うコンディショニングストレッチです。自律で行うストレッチ、アーサナでは交感神経が働き本人では気づけない筋肉のロックが生じ、過緊張状態が継続することで張りや痛みにつながる。", "photo.png", 3000, 1, 1455447338, 1455447338);

INSERT INTO pros (`client_id`, `email`, `password`, `tel`, `kind`, `pro_name`, `description`, `price`, `exam_flg`, `created_at`, `updated_at`) VALUES (1, "takac_radcliffe@techfund.jp", "test1234", "080-3087-6129", 3, "山本 かずえ", "リンパ液を、体の各リンパ節に流し込んで排泄し、リンパの流れをスムーズにするトリートメント方法です。", 3000, 1, 1455447338, 1455447338);

INSERT INTO pros (`client_id`, `email`, `password`, `tel`, `kind`, `pro_name`, `description`, `price`, `exam_flg`, `created_at`, `updated_at`) VALUES (2, "peaske@techfund.jp", "test1234", "090-8201-3936", 1, "川端 宏", "冷え・むくみによるつらい脚をホットストーンで温めてから、じっくりアロマトリートメントでケア★リンパの流れを改善し、新陳代謝もUP♪脚のラインもキレイに♪で同じ料金で出来るクーポンだから安心", 3000, 1, 1455447338, 1455447338);

-- pro_works
INSERT INTO pro_works (`pro_id`, `week`, `accepted_hours_start`, `accepted_hours_end`, `created_at`, `updated_at`) VALUES (1, 1, "10:00:00", "20:00:00", 1455447338, 1455447338);
INSERT INTO pro_works (`pro_id`, `week`, `accepted_hours_start`, `accepted_hours_end`, `created_at`, `updated_at`) VALUES (1, 2, "10:00:00", "20:00:00", 1455447338, 1455447338);
INSERT INTO pro_works (`pro_id`, `week`, `accepted_hours_start`, `accepted_hours_end`, `created_at`, `updated_at`) VALUES (1, 3, "10:00:00", "20:00:00", 1455447338, 1455447338);
INSERT INTO pro_works (`pro_id`, `week`, `accepted_hours_start`, `accepted_hours_end`, `created_at`, `updated_at`) VALUES (1, 4, "10:00:00", "20:00:00", 1455447338, 1455447338);
INSERT INTO pro_works (`pro_id`, `week`, `accepted_hours_start`, `accepted_hours_end`, `created_at`, `updated_at`) VALUES (1, 5, "10:00:00", "20:00:00", 1455447338, 1455447338);
INSERT INTO pro_works (`pro_id`, `week`, `accepted_hours_start`, `accepted_hours_end`, `created_at`, `updated_at`) VALUES (1, 6, "10:00:00", "20:00:00", 1455447338, 1455447338);
INSERT INTO pro_works (`pro_id`, `week`, `accepted_hours_start`, `accepted_hours_end`, `created_at`, `updated_at`) VALUES (1, 7, "10:00:00", "20:00:00", 1455447338, 1455447338);

INSERT INTO pro_works (`pro_id`, `week`, `accepted_hours_start`, `accepted_hours_end`, `created_at`, `updated_at`) VALUES (2, 1, "00:00:00", "23:30:00", 1455447338, 1455447338);
INSERT INTO pro_works (`pro_id`, `week`, `accepted_hours_start`, `accepted_hours_end`, `created_at`, `updated_at`) VALUES (2, 2, "00:00:00", "23:30:00", 1455447338, 1455447338);
INSERT INTO pro_works (`pro_id`, `week`, `accepted_hours_start`, `accepted_hours_end`, `created_at`, `updated_at`) VALUES (2, 3, "00:00:00", "23:30:00", 1455447338, 1455447338);
INSERT INTO pro_works (`pro_id`, `week`, `accepted_hours_start`, `accepted_hours_end`, `created_at`, `updated_at`) VALUES (2, 4, "00:00:00", "23:30:00", 1455447338, 1455447338);
INSERT INTO pro_works (`pro_id`, `week`, `accepted_hours_start`, `accepted_hours_end`, `created_at`, `updated_at`) VALUES (2, 5, "00:00:00", "23:30:00", 1455447338, 1455447338);
INSERT INTO pro_works (`pro_id`, `week`, `accepted_hours_start`, `accepted_hours_end`, `created_at`, `updated_at`) VALUES (2, 6, "00:00:00", "23:30:00", 1455447338, 1455447338);
INSERT INTO pro_works (`pro_id`, `week`, `accepted_hours_start`, `accepted_hours_end`, `created_at`, `updated_at`) VALUES (2, 7, "00:00:00", "23:30:00", 1455447338, 1455447338);

-- reviews
INSERT INTO reviews (`user_id`, `pro_id`, `score`, `body`, `body_inside`, `created_at`) VALUES (1, 1, 4, "美人すぎる整体師だって？なんでも美人ってつければいいってもんじゃないよ！あたしゃ認めないよ。でも・・いい腕してんじゃないかあんた。", "素晴らしいマッサージ師さんでした。またお願いしたいです。", 1455447338);

-- companies
INSERT INTO companies (`company_name`, `contact_name`, `email`, `tel`, `created_at`, `updated_at`) VALUES ("株式会社ソフトバンク", "中沢 剛", "go@softbank.jp", "080-4045-4560", 1455447338, 1455447338);
INSERT INTO companies (`company_name`, `contact_name`, `email`, `tel`, `created_at`, `updated_at`) VALUES ("株式会社TECHFUND", "松山 雄太", "matsuyama@techfund.jp", "080-4045-4560", 1455447338, 1455447338);

-- buildings
INSERT INTO buildings (`company_id`, `building_name`, `default_address`, `admission_method`, `location_x`, `location_y`, `created_at`, `updated_at`) VALUES (1, "東京汐留ビル", "東京都港区東新橋1丁目9-1", "入館時に警備員にpochimomiですと伝え22Fへ。到着次第ユーザーに電話する。", 35.6630314, 139.7590818, 1455447338, 1455447338);

INSERT INTO buildings (`company_id`, `building_name`, `default_address`, `admission_method`, `location_x`, `location_y`, `created_at`, `updated_at`) VALUES (1, "汐留住友ビル", "東京都港区東新橋1丁目9-2 16F", "入り口から直接16Fへ。到着次第ユーザーに電話する。", 35.6630314, 139.7590818, 1455447338, 1455447338);

INSERT INTO buildings (`company_id`, `building_name`, `default_address`, `admission_method`, `location_x`, `location_y`, `created_at`, `updated_at`) VALUES (2, "松濤ハウス", "東京都渋谷区松濤1-24-3", "到着次第ユーザーに電話する。", 35.660197, 139.693204, 1455447338, 1455447338);

-- pr_code
INSERT INTO pr_codes (`pr_code`, `discount`, `created_at`) VALUES ("test1234", 500, 1455447338);