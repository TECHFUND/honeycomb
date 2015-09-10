# 1.開発ポリシー
	## 1-1.Code
		### 1-1-1.全体構想
			MVP(説明省略)
		### 1-1-2.思想
			minimumフレームワーク、minimumライブラリ、枯れた技術。
			=> どんなエンジニアにでも引き継げる形式を常に選択(学習・引き継ぎコストの最小化)。
		### 1-1-3.選定技術
			言語はフレームワーク無しのpurePHP + Node.js(PubSub Server)を選定。
			PHPにしたのはデザイナーとの開発速度を上げるためが主。
			結果としてファウンダーの周りにPHPエンジニアが多かったことも途中技術を変更しなかった理由に挙げられる。
			NodeはSocket通信を行うチャットシステムという背景からモダンな技術を選定。

	## 1-2.DB
		### 1-2-1.スキーマ
			最小限のリレーション(参照・制約は基本使わない)。
 			初期サービスはDB設計も頻繁に変更されやすいため、完全なリレーショナルにはしない。
 			プログラムとは疎結合にし、プログラム側で厳密な登録時チェックをかける
		### 1-2-2.RAID
			スキーマ同様の思想にてマスタのみで管理
			変更コストを最小限にする
		### 1-2-3.選定技術
			DBはMySQLを選定。PHPとセットでは最もモダンな技術。
		### 1-2-4.DB設計
			/assets/sql/に設計を記載。ここでは説明は省略。

	# 1-3.Infrastructure
		## 1-3-1.構成
			public web					: EC2(AmazonLinux + Apache + PHP) + RDS(MySQL) + ELBで設計
			public chat(PubSub)	: EC2(AmazonLinux + Node)で設計、Socket通信利用(port:8001, 8002)
			staging							: 各自ローカル(TECHFUNDでは作業用サーバー)

		## 1-3-2.DNS
			ドメイン(bizlink.io)はRoute53にて取得、設定も同じくRoute53にて行っている。
			ElasticIP(54.64.199.69)を利用しAレコードを設定しているのみ。
			※ELBだとサーバから送信するメールが迷惑メールとなるため、ドメインの信頼性が向上するまでELB無し


# 2.ルーティング
	## 2-1.設計
		1-1-2.と同様の思想からテンプレートエンジンはあえて使用せず、phpファイルとして直書きするスタイル。
		現時点では変更が多々あることを想定し、厳密なディレクトリ配置も行っていない。
		タイミングを見て(リリースのタイミング等)でルーティングを見直すと良い。

	## 2-2.構成
		### 2-2-1.階層説明
			ROOT				･･･　各種ページファイル(index.php, ~~)、apiファイル(login_api.php, ~~)
			├admin			･･･　管理画面ファイル
				└tools		･･･　管理用ツール用ディレクトリ(現状はphpMyAdminのみ利用)
			├assets			･･･　commonファイル群
				├css			･･･　CSS
				├define		･･･　設定ファイル(common:サービスの設定、honeybase_config:チャットサーバーの設定、mail:メールの設定)
				├ex				･･･　Extension
				├fonts		･･･　フォント
				├img			･･･　画像
					└user_images	･･･　ユーザーの画像が格納されるディレクトリ
				├js				･･･　JS
				├log			･･･　ログファイル(batch:バッチ処理のログ、err:サービスのエラーログ、issue:相談依頼ログ)
				├sql			･･･　SQL, DB設計
				├svg			･･･　SVG
				└work			･･･　バッチファイル
			├assets_lp	･･･　TOP用commonファイル群。TOPのみ大幅にデザインが違うためcommonファイルもTOP用のものを用意、assetsと同義。
			├extension	･･･　ライブラリ群 ※2-2-1.に補足
			├lp					･･･　LP用ディレクトリ(現時点は未使用)
			└moc				･･･　デザインデータ保存用ディレクトリ(現時点は未使用)

		### 2-2-1.extension/ライブラリ補足説明
			CommonFunction	: ログインやリダイレクト等の汎用クラスまとめ
			DatabaseAccess	: データベースのアクセスクラス(シンプル接続クラスを独自に構築)まとめ
			ErrorCheck			: エラーチェック用クラスまとめ
			facebook_login	: Facebook API SDK
 			webpay					: webpay(決済)SDK(現時点は未使用)


# 3.バージョン
	## 3-1.サーバ,OS,言語,DB,フレームワーク
		Linux(web)		:	3.14.42-31.38.amzn1.x86_64 (mockbuild@gobi-build-64012) (gcc version 4.8.2 20140120 (Red Hat 4.8.2-16) (GCC))
		Linux(pubsub)	:	3.14.35-28.38.amzn1.x86_64 (mockbuild@gobi-build-64012) (gcc version 4.8.2 20140120 (Red Hat 4.8.2-16) (GCC))
		Apache				:	2.4.12
		Node					:	0.12.2
		PHP						:	5.6.9
		MySQL					:	5.6.23
		jQuery				:	1.11.0


# 4.その他
	## 4-1.バッチ処理
		毎日深夜4:00にアルゴリズム集計バッチ(/assets/work/batch.php)処理を行っています。
	## 4-2.Auto Scale
		ドメインの信頼性が向上し、システムからのメールがスパム扱いされなくなった際にELBを導入推奨。
		最新のサーバー情報をAMIに書き出し、AutoScale設定を行う。