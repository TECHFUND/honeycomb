$(function () {

		if ($('.progressArea .line').length) {
			
				function progressLine() {
					var active = $('.progressArea ul .active span');
					var active_position = active.position();

					if($(".progressArea ul .active ").is(".progressArea ul li:last") && !$(".progressArea ul .active ").hasClass('navi02')){
						$('.progressArea .line').css({
							'width': $('.progressArea .line').width()
						});				
					}else{
						$('.progressArea .line').css({
							'width': active_position.left + (active.width()/2)
						});
					}
				}			
			
				progressLine();



				$(window).resize(function () {
						progressLine();
				});
		}
		
		// 初回起動のポップアップがある場合の処理
		if ($('.first_popup').length) {
				$(".first_popup .btn_close").click(function () {
						$(".first_popup").fadeOut("slow");
						$(window).off('.noScroll');
				});
				
				if(!$(".first_popup").is(":hidden")){
					// スクロール禁止
					$(window).on('touchmove.noScroll', function(e) {
							e.preventDefault();
					});
				}
		}

		// badgeの説明
		if ($('.guide_badge').length) {

				$('.popup_guide_badge').click(function () {
						$(".guide_badge").fadeIn("slow");
						// スクロール禁止
						$(window).on('touchmove.noScroll', function(e) {
								e.preventDefault();
						});
				});

				$(".guide_badge .fa-times").click(function () {
						$(".guide_badge").fadeOut("slow");
						$(window).off('.noScroll');						
				});

				$(".guide_badge").click(function (event) {
								if (event.target.className == "guide_badge") {
										$(".guide_badge").fadeOut("slow");
								}
						}

				);
		}

		// 口コミをもっと見る処理
		if($(".detail_voice").length){
				$(".detail_voice .linkMore").click(function(){
						$('.detail_voice .pro_list section').show();
				}
				);
		}

		// notification処理
		var url	 = location.href;
		// GETパラメータがあれば処理開始
		if (-1 != url.indexOf("?")) {
				params		= url.split("?");
				spparams	 = params[1].split("&");
				var paramArray = [];
				// URLをパラメータで分解
				for ( i = 0; i < spparams.length; i++ ) {
						vol = spparams[i].split("=");
						paramArray.push(vol[0]);
						paramArray[vol[0]] = vol[1];
				}
				var view_flg = 0;
				if (paramArray["timeout"] == "1") {
						// マッチした場合notificationを出す
						var notif = '<p id="notification" style="display:none;position: fixed;top: 0px;left: 0px;width: 100%;background-color: #ff0000;color: #ffffff; border-bottom:1px solid #006400;font-size:18px;padding: 5px;z-index: 10000;">一定時間たったため再度、施術師を指定してください。</p>';
						view_flg = 1;
				}
				if (paramArray["password_timeout"] == "1") {
						// マッチした場合notificationを出す
						var notif = '<p id="notification" style="display:none;position: fixed;top: 0px;left: 0px;width: 100%;background-color: #ff0000;color: #ffffff; border-bottom:1px solid #006400;font-size:18px;padding: 5px;z-index: 10000;">URLの有効期限が切れました。</p>';
						view_flg = 1;
				}
				if (paramArray["no_building_id"] == "1") {
						// マッチした場合notificationを出す
						var notif = '<p id="notification" style="display:none;position: fixed;top: 0px;left: 0px;width: 100%;background-color: #ff0000;color: #ffffff; border-bottom:1px solid #006400;font-size:18px;padding: 5px;z-index: 10000;">ビルIDがありません。ログインしてください。</p>';
						view_flg = 1;
				}
				if (1 == view_flg) {
						$("body").append(notif);
						$("#notification").fadeIn(500);
						$("#notification").delay(1700).fadeOut(2000);
				}
		}

			/**
			 * ジオコーダの結果を取得したときに実行するコールバック関数。
			 * @param results ジオコーダの結果
			 * @param status ジオコーディングのステータス
			 * 
			 */
			function callbackRender(results, status) {
				if(status == google.maps.GeocoderStatus.OK) {
					var options = {
						zoom: 10,
						center: results[0].geometry.location, // 指定の住所から計算した緯度経度を指定する
						mapTypeId: google.maps.MapTypeId.ROADMAP, // 「地図」で GoogleMap を出力する
						disableDefaultUI: true,
						scrollwheel: false
					};
					// #map-canvas に GoogleMap を出力する
					var gmap = new google.maps.Map(document.getElementById('map_canvas'), options);
					// 指定の住所から計算した緯度経度の位置に Marker を立てる
					new google.maps.Marker({map: gmap, position: results[0].geometry.location});
					adjustMapSize();
				}
			}
			/**
			 * GoogleMap を表示する部分のサイズを調整する。
			 */
			function adjustMapSize() {
				var mapCanvas = $('#map_canvas');
				var marginBottom = 5;
				//mapCanvas.css("height", ($(window).height() - mapCanvas.offset().top - marginBottom) + "px");
			}
			
});
