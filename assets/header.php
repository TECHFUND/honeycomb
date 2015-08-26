<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<title></title>
	<meta name="keywords" content=",,,,,,">
	<meta name="description" content="">
	<meta name="robots" content="index,follow">
	<meta property="og:title" content="">
	<meta property="og:type" content="website">
	<meta property="og:description" content="">
	<meta property="og:url" content="http://">
	<meta property="og:image" content="">
	<meta property="og:site_name" content="">
	<meta property="fb:app_id" content="">
	<link rel="shortcut icon" href="favicon">
	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<!--css-->
  <link rel="stylesheet" href="http://techfund.jp/peastrap/css/peastrap.css">
	<link rel="stylesheet" href="/assets/css/common.css">
	<link rel="stylesheet" href="/assets/css/base.css">
	<link rel="stylesheet" href="/assets/css/page.css">
	<!--js-->
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
	<!-- notification -->
	<p class="normal_notification"><?php echo($_POST["normal_notification_body"]); ?></p>
	<p class="err_notification"><?php echo($_POST["err_notification_body"]); ?></p>
	<!-- //notification -->

	<!-- header -->
	<header>
		<ul class="h_menu">
			<a href="/wp.php"><li class="home"><p>ホーム</p></li></a>
			<a href="/job/mytask.php"><li class="mytask"><p>マイリスト</p></li></a>
			<a href="/message/message_list.php"><li class="message"><i class="fa <?php if ($badge_flg) {echo('fa-check-circle ');} ?>fa-lg"></i><p>メッセージ</p></li></a>
			<?php
			if (!$_SESSION["user_id"]) {
				echo('
			<a href="/user/setting_index.php"><li class="login"><p>ログイン</p></li></a>
				');
			} else {
				echo('
			<a href="/user/setting_index.php"><li class="setting"><p>設定</p></li></a>
				');
			}
			?>
		</ul>
	</header>
	<!-- //header -->
