function addToastMessage(type, message, num) {
	// notificationを追加
	var position = num * 29;
	var bg_color = "#6bd909";
	if ("error" == type) {
		bg_color = "#ff0000";
	}
	var notif = '<p class="notification" style="display:none;position: fixed;top: ' + position + 'px;left: 0px;width: 100%;background-color: ' + bg_color + ';color: #ffffff; border-bottom:1px solid #006400;font-size:18px;padding: 5px;z-index: 100;">' + message + '</p>';
	$("body").append(notif);
}


function showToastMessage(type, message, num) {
	// notificationを表示
	$(".notification").fadeIn(500);
	$(".notification").delay(1700).fadeOut(2000);
}