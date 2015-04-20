<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<title>家事代行サービスのタスカル</title>
	<meta name="keywords" content=",,,,,,">
	<meta name="description" content="家事代行サービスのタスカル">
	<meta name="robots" content="index,follow">
	<meta property="og:title" content="タスカル">
	<meta property="og:type" content="website">
	<meta property="og:description" content="">
	<meta property="og:url" content="http://">
	<meta property="og:image" content="ogp">
	<meta property="og:site_name" content="タスカル">
	<meta property="fb:app_id" content="app_id">
	<link rel="shortcut icon" href="favicon">
	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<!--css-->
	<link rel="stylesheet" href="/assets/css/common.css">
	<link rel="stylesheet" href="/assets/css/base.css">
	<link rel="stylesheet" href="/assets/css/page.css">
	<!--js-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script type="text/javascript" src="/assets/js/exif.js"></script>
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script>
	/**
	 * thanks_popup
	 */
	function ok_popup(name) {
	  $("#" + name).fadeIn(500);
	  $("#" + name).delay(1700).fadeOut(2000);
	}


	/**
	 * complement images to local files
	 */
	$(function() {
		var angle = 0;
		$('input[type=file]')
		.change(function() {
			var __input = $(this);
			var path = __input.val();
			var name = __input.attr('name');
			var reader = new FileReader();
			reader.onload = function (e) {
				// exif情報があれば回転
				$('#' + name).css('-moz-transform', 'rotate(' + angle + 'deg)');
				$('#' + name).css('-webkit-transform', 'rotate(' + angle + 'deg)');
				$('#' + name).css('-o-transform', 'rotate(' + angle + 'deg)');
				$('#' + name).css('-ms-transform', 'rotate(' + angle + 'deg)');
				$('#' + name).css('transform', 'rotate(' + angle + 'deg)');

				// サムネイル表示
				$('#' + name).css('background-image', 'url(' + e.target.result + ')');
			}
			EXIF.getData(this.files[0], function() {
				// exif情報を取得
	        	var orientation = this.exifdata.Orientation;
	        	angle = 0;
	        	// exifの内容によって回転角度を決める
			    switch(orientation) {
			      case 8:
					angle = -90;
			        break;
			      case 3:
					angle = 180;
			        break;
			      case 6:
					angle = 90;
			        break;
			    }
			    // アップロードされた画像を判定
				reader.readAsDataURL(this);
			});
		});
	});

	</script>
</head>
<body onload="<?php if($_GET["ok_flg"]){echo("ok_popup('thanks');");}if($_GET["apply_done"]){echo("ok_popup('apply_done');");} ?>">
<p id="thanks" style="display:none;position: fixed !important;position: absolute;top: 30;left: 0;width: 100%;background-color: #1CAE8D;color: #ffffff; border-bottom:1px solid #006400;font-size:18px;padding: 5px;z-index: 10000;">更新が完了しました！</p>
<p id="apply_done" style="display:none;position: fixed !important;position: absolute;top: 30;left: 0;width: 100%;background-color: #1CAE8D;color: #ffffff; border-bottom:1px solid #006400;font-size:18px;padding: 5px;z-index: 10000;">応募が完了しました！</p>
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
