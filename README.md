# 開発ポリシー
	## Code
		### MVP
			説明不要
		### minimumフレームワーク、minimumライブラリ
			フレームワーク無しのpurePHP
			=> どんなエンジニアにでも引き継げる形式を常に選択

	## DB
		### DBスキーマは最小限のリレーション(参照・制約は基本使わない)
 			初期サービスはDB設計も頻繁に変更されやすいため、完全なリレーショナルにはしない
		### 同様にマスタのみで管理
			プログラムとは疎結合にすることで変更コストを最小限にする
			=> ただし、プログラム側で厳密な登録時チェックをかける


# サーバ構成
	public	: EC2(AmazonLinux + Apache + PHP) + RDS(MySQL) + ELBで設計
		=> AutoScale(avg 60%>=n>=20%)で設計
	staging	: 通常は停止。
	mail	: AmazonSES:送信 + EC2(postfix):受信(転送設定のみ)で設計

	## デプロイ(基本形)
		1.publicをclone(staging生成)
		2.gitからpull
		3.ステージングURLでサイト動作チェック
		4.最新のAMIを作る
		5.Launch ConfigurationのAMI IDを書き換える
		6.スケーリングのテストをする（yes >> /dev/null等で擬似負荷をかけてみる）
		7.問題なければ本番とURL入れ替え(swap:beanstalk内機能)
		8.本番でサイト動作チェック
		9.stagingをkill


# DB設計
	assets/sql/に設計を記載

 