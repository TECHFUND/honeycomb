$(function() {

	DH = DH || {};

	/**
	 * empty function for callback
	 */
	var empty_func = function() {};
	//var map, map_center;

	/**
	 * initialize search box
	 */
	var $search_box = $('#search');
	$('.search-btn').click(function() {
		$search_box.removeClass('hide');
	});
	$('.close-search-box-btn').click(function() {
		$search_box.addClass('hide');
	});

	/**
	 * activate tab
	 */
	var $tabs = $('#tabs');
	if ($tabs.length) {
		$tabs.tabs({
			activate: function(event, UI) {
				/*if (map) {
					google.maps.event.trigger(map, 'resize');
					if (mapCenter) {
						map.panTo(mapCenter);
					}
				}*/
			}
		});
	}

	/**
	 * activate image slider
	 */
	var swiper = new Swiper('.swiper-container', {
		pagination: '.swiper-pagination',
		slidesPerView: 2,
		paginationClickable: true,
		spaceBetween: 10
	});

	var $sliders = $('#carousel');
	if ($sliders.length) {
		$.Elastislide.defaults = {
			orientation : 'horizontal',
			speed : 500,
			easing : 'ease-in-out',
			minItems : 1,
			start : 0,
			onClick : function( el, position, evt ) { return false; },
			onReady : function() { return false; },
			onBeforeSlide : function() { return false; },
			onAfterSlide : function() { return false; }
		};
		$sliders.elastislide();
	}

	/**
	 * activate calendar
	 */
	var $calendar = $('.datepicker');
	if ($calendar.length) {
		DH.calendar = DH.calendar || {start: '', end: '', reserved: [], move_reserved: []};

		$.datepicker.regional["jp"];

		$calendar.datepicker({
			onChangeMonthYear: function(year, month, inst) {
				setTimeout(redraw_calendar, 0);
			},
			onSelect: function(date, inst) {

				if ($(this).hasClass('readOnly')) {
					return false;
				}
				var $calendar =  $(this);
				var period = $calendar.attr('name'); // start_dt or end_dt
				if (period === 'start_dt') {
					DH.calendar.start = new Date(date);
				} else if (period === 'end_dt') {
					DH.calendar.end = new Date(date);
				} else {
					if (DH.calendar.start == "") {
						DH.calendar.start = DH.calendar.end = new Date(date);
						$("#start_dt").val($.datepicker.formatDate('yy/mm/dd', new Date(date)));
						$("#end_dt").val($.datepicker.formatDate('yy/mm/dd', new Date(date)));
					} else if (DH.calendar.start == DH.calendar.end) {
						DH.calendar.end = new Date(date);
						$("#end_dt").val($.datepicker.formatDate('yy/mm/dd', new Date(date)));
					} else {
						DH.calendar.start = DH.calendar.end = new Date(date);
						$("#start_dt").val($.datepicker.formatDate('yy/mm/dd', new Date(date)));
						$("#end_dt").val($.datepicker.formatDate('yy/mm/dd', new Date(date)));
					}
				}
				setTimeout(redraw_calendar, 0);
			},
			onClose: function(date, inst) {
				// console.log(date, inst);
			},
		});
		setTimeout(redraw_calendar, 0);

		$calendar.click(redraw_calendar);


		function redraw_calendar() {
			if (!DH || !DH.calendar) {
				return;
			}

			var $view = $('.ui-datepicker');

			var $dates = $view.find('td');

			var year = $($dates[7]).attr('data-year');
			var month = $($dates[7]).attr('data-month');

			var reserved = DH.calendar.reserved;
			var move_reserved = DH.calendar.move_reserved;

			$dates.each(function(it) {
				var $day = $($dates[it]);
				if (!$day.hasClass('ui-state-other-month')) {
					var day = $day.find('a').text();
					var _date = new Date(year, month, day);

					// color before today
					if (_date <= DH.calendar.today) {
						$day.addClass('ui-datepicker-unselectable');
					}
					// color start
					if (isSameDate(_date, DH.calendar.start)) {
						$day.addClass('start_dt');
					}

					// color end
					if (isSameDate(_date, DH.calendar.end)) {
						$day.addClass('end_dt');
					}

					// color between start and end
					if (DH.calendar.start < _date && _date < DH.calendar.end) {
						$day.addClass('term');
					}
				}
			});

			// color reserved days
			var _month = new Date(year, month);
			for (var i = 0, l = reserved.length ; i < l ; i++) {
				var from = reserved[i].from;
				var to = reserved[i].to;
				if (isSameMonth(from, _month) || isSameMonth(to, _month)) {
					$dates.each(function(it) {
						var $day = $($dates[it]);
						var day = $day.find('a').text();
						var _date = new Date(year, month, day);
						if (!$day.hasClass('ui-state-other-month')) {
							if (from <= _date && _date <= to) {
								$day.addClass('ui-datepicker-unselectable');
							}
						}
					});
				}
			}

			// color move_reserved days
			var _month = new Date(year, month);
			for (var i = 0, l = move_reserved.length ; i < l ; i++) {
				var from = move_reserved[i].from;
				var to = move_reserved[i].to;
				if (isSameMonth(from, _month) || isSameMonth(to, _month)) {
					$dates.each(function(it) {
						var $day = $($dates[it]);
						var day = $day.find('a').text();
						var _date = new Date(year, month, day);
						if (!$day.hasClass('ui-state-other-month')) {
							if (from <= _date && _date <= to) {
								$day.addClass('ui-datepicker-unselectable-move');
							}
						}
					});
				}
			}
		}

		function isSameMonth(a, b) {
			if (!a || !b) {return false;}
			return a.getYear() === b.getYear() && a.getMonth() === b.getMonth();
		}

		function isSameDate(a, b) {
			if (!a || !b) {return false;}
			return isSameMonth(a, b) && a.getDate() === b.getDate();
		}
	}

	/**
	 * activate stars
	 */
	var $stars = $('.stars');
	if ($stars.length) {
		$.fn.raty.defaults.path = "/img";
		$stars.raty({
			score: function() {
				return $(this).attr('data-score');
			},
			readOnly: $stars.hasClass('readOnly'),
			space: false,
			click: function(val) {
				// complement form value
				var _input = $(this).find('input');
				if (_input.length) {
					_input.val(val);
				}
			}
		});
	}

	/**
	 * complement images to local files
	 */
	var angle = 0;
	$('input[type=file]')
	.change(function() {
		var __input = $(this);
		var path = __input.val();
		var name = __input.attr('name');
		var reader = new FileReader();
		reader.onload = function (e) {
			// exif情報があれば回転
			$('#img_flame').css('rotate', angle);
			
			// サムネイル表示
			$('#' + name).attr('src', e.target.result);
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

	/**
	 * reservation confirm
	 */
	var submit_flg = false;
	$('#reservation-confirm-btn').click(function(e) {
		e.preventDefault();

		// complement input data
		var $error = $('.error');
		var start_dt = $('#reservation input[name=start_dt]').val();
		var end_dt = $('#reservation input[name=end_dt]').val();
		var dog_num = $('#reservation input[name=dog_num]').val();
		var message = $('#reservation textarea[name=message]').val();

		// validation
		if (!start_dt) { $error.text('預け日を入力してください'); return; }
		if (!end_dt)   { $error.text('迎え日を入力してください'); return; }
		if (new Date(start_dt) > new Date(end_dt)) { $error.text('期間が不正です'); return;}
		if (!dog_num)  { $error.text('頭数を入力してください'); return; }
		if (!message)  { $error.text('メッセージを入力してください'); return; }

		$('#reservation-confirm .start_dt .value').text(start_dt);
		$('#reservation-confirm input[name=start_dt]').val(start_dt);

		$('#reservation-confirm .end_dt .value').text(end_dt);
		$('#reservation-confirm input[name=end_dt]').val(end_dt);

		$('#reservation-confirm .dog_num .value').text(dog_num);
		$('#reservation-confirm input[name=dog_num]').val(dog_num);

		$('#reservation-confirm .message .value').text(message);
		$('#reservation-confirm input[name=message]').val(message);

		// show confirm
		$error.text('');
		$('#reservation-confirm').removeClass('hide');
		$('#reservation').addClass('hide');
	});
	$('#reservation-confirm .btn-success').click(function(e) {
		var $error = $('.error');
		if (submit_flg) {
			// noop
		} else {
			e.preventDefault();
			var check = $('#reservation-form input[type=checkbox]').prop('checked');
			if (check) {
				submit_flg = true;
				$('#reservation-form').submit();
			} else {
				$error.text('利用規約に同意してください');
			}
		}
	});
	$('#reservation-confirm .btn-danger').click(function(e) {
		e.preventDefault();
		$('#reservation-confirm').addClass('hide');
		$('#reservation').removeClass('hide');
	});
	
	/**
	 * popup layer
	 */
  $(".layer").click(function(){
    $(".modal").fadeOut();
    $(".layer").fadeOut();
  });

	/**
	 * footer scroll
	 */
  $("footer_start").on("click", function(){
	  $('html,body').animate({scrollTop: $('html').offset().top},'fast');
  });

  /** 
   * notification view
   */
  if ("" != $(".normal_notification").html) {
    $(".normal_notification").fadeIn(500);
    $(".normal_notification").delay(1700).fadeOut(2000);
  }
  if ("" != $(".err_notification").html) {
    $(".err_notification").fadeIn(500);
    $(".err_notification").delay(1700).fadeOut(2000);
  }
});