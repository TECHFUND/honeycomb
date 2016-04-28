$(function () {

    var drawer = $('#drawer'),
        drawerBtn = $('.menu-trigger'),
        drawerCloseBtn = $('.close_drawer'),
        drawerWidth = drawer.outerWidth();

	$(window).resize(function() {
        drawerWidth = drawer.outerWidth();
	});

    drawerBtn.on('click', function(){
	    drawer.show();
        drawer.animate({'left' : 0 }, 300);
    });

    drawerCloseBtn.on('click', function(){
        drawer.animate({'left' : -drawerWidth },{complete:function(){ drawer.hide();}});
    });

});
