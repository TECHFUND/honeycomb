
<script src="/assets/js/jquery-1.11.2.min.js"></script>
<script src="/assets/js/common.js"></script>
<script src="/assets/js/drawer.js"></script>
<script src="/assets/js/jquery.cookie.min.js"></script>
<script src="//maps.google.com/maps/api/js" type="text/javascript"></script>
<!-- slider -->
<script type="text/javascript" src="/assets/js/slider/jshashtable-2.1_src.js"></script>
<script type="text/javascript" src="/assets/js/slider/jquery.numberformatter-1.2.3.js"></script>
<script type="text/javascript" src="/assets/js/slider/tmpl.js"></script>
<script type="text/javascript" src="/assets/js/slider/jquery.dependClass-0.1.js"></script>
<script type="text/javascript" src="/assets/js/slider/draggable-0.1.js"></script>
<script type="text/javascript" src="/assets/js/slider/jquery.slider.js"></script>

<script>
/**
 * ジオコーダの結果を取得したときに実行するコールバック関数。
 * @param results ジオコーダの結果
 * @param status ジオコーディングのステータス
 * 
 */
function callbackRender(results, status) {
  if(status == google.maps.GeocoderStatus.OK) {
    var options = {
			zoom: 15,
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
</script>
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php if (isset($js_script) and "" != $js_script) {echo($js_script);} ?>

<!-- google analytics -->
<script>
 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
 (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
 m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
 })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

 ga('create', 'UA-74329615-2', 'auto');
 ga('send', 'pageview');

</script>

<!-- end -->
</body>
</html>