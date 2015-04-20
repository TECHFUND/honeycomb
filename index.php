<?php

require_once('path.php');
require_once(ROOT . 'assets/define/common_define.php');

// 共通クラス読み込み
$cf = new CommonFunctions();
$db = new DatabaseAccess();
$ec = new ErrorCheck();

// init
$user_name = "ゲスト";

// ログインチェック
$login_flg = $cf->checkLogin(false);

// ログインしている場合
if ($_SESSION["user_name"]) {
	$user_name = $_SESSION["user_name"];
}

// type(client or tasker)判別
$type_flg = 1;
if ($_GET["type_flg"]) {
	if (2 == $_GET["type_flg"]) {
		$type_flg = 2;
	}
}

// ヘッダー読み込み
include ROOT . 'assets/header.php';

?>
	<!-- mainClm -->
	<div class="mainClm">
		<h2><?php echo($user_name); ?>さん、おかえりなさい！</h2>
	  <div class="tab">
			<ul class="t_menu">
				<a href="/wp.php?type_flg=1"><li<?php if(1 == $type_flg){echo(' class="current"');} ?>><p>お仕事を依頼する</p></li></a>
				<a href="/wp.php?type_flg=2"><li<?php if(2 == $type_flg){echo(' class="current"');} ?>><p>お仕事を探す</p></li></a>
			</ul>
		</div>

		<!-- wpContents -->
		<div class="wpContents">
		
        <div class="wp">
			<div class="panel">
            <?php
            $cnt = 0;
            foreach ($workpanel as $key => $value) {
                $cnt++;
                if (1 == $cnt % 3) {
                    if (1 != $cnt) {
                        echo('</ul>');
                    }
                    echo('<ul>');
                }
                if (1 == $type_flg) {
                	echo('<a href="/job/new_request.php?wp=' . $key . '"><li class="' . $value["class"] . '"><p>' . $value["title"] . '</p></li></a>');
            	} else {
            		echo('<a href="/job/job_feed.php?wp=' . $key . '"><li class="' . $value["class"] . '"><p>' . $value["title"] . '</p></li></a>');
            	}
            }
            ?>
			</div>
		</div>
			
		<div class="howto">
			<h3><!--<img src="assets/img/feed/img01.jpg" width="184" height="131" alt=""/>-->タスカルの使い方</h3>
			<ul>
				<li class="flow1">
					<h4>1. 依頼を投稿<span>依頼を投稿簡単なフォームから依頼内容、条件、スケジュールを投稿してください。作業者から応募が来ます。</span></h4>
					<br style="clear:both">
				</li>
				<li class="flow2">
					<h4>2.作業者の選定<span>条件やプロフィールを見て作業者を決定してください。決定後、作業者からメッセージがきます。</span></h4>
					<br style="clear:both">
				</li>
				<li class="flow3">
					<h4>3.作業日<span>作業者が期日にお伺いしお手伝いします。ご確認後、作業者に現金にてお支払いください。</span></h4>
					<br style="clear:both">
				</li>
			</ul>
			<div class="btnContents">
			<?php
			if (!$_SESSION["user_id"]) {
				echo('
				<form action="/user/regist.php">
        	<input type="submit" value="新規登録"  class="btn01 transition">
        </form>
         ');
			}
			?>
			</div>
		</div>
	</div>
	<!-- //wpContents -->
</div>
<!-- //mainClm -->

<?php

// フッター読み込み
include ROOT . 'assets/footer.php';

?>