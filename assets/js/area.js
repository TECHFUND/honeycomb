// 引数のエリアコードの子地域情報を取ってくる
function fetchChildrenArea(ac, success, error){

    // apiの設定情報
    // http://developer.yahoo.co.jp/webapi/map/openlocalplatform/v1/addressdirectory.html
    var api = {
        url: "http://search.olp.yahooapis.jp/OpenLocalPlatform/V1/addressDirectory",
        appid: "dj0zaiZpPVJLbUJmOXppWXJrciZzPWNvbnN1bWVyc2VjcmV0Jng9ZDc-"
    };

    var that = this;

    // キャッシュあればキャッシュを使用
    that.cache = that.cache || {};
    if( that.cache[ac] ){
        success && success(that.cache[ac]);
        return;
    }

    $.ajax({
        url: api.url,
        data: {
            appid: api.appid,
            ac: ac,
            mode: "2", // 1:甲良町（犬上郡）, 2: 犬上郡甲良町
            output: "json"
        },
        dataType: "jsonp"
    }).done(function(json){

        var data, props, i, ii;

        if(json.error){
            error && error();
            return;
        }

        // jsonデータから子地域データを取得
        data = json;
        props = ["Feature", "0", "Property", "AddressDirectory"];
        while(data && props.length){
            data = data[props.shift()];
        }

        if(!data || !data.length){
            error && error();
            return;
        }

        i = 0, ii = data.length;
        while(i < ii){
            data[i] = {
                ac: data[i]["AreaCode"],
                name: data[i]["Name"]
            };
            i++;
        }

        that.cache[ac] = data;
        success && success(data);

    }).fail(function(){

        error && error();

    });
}


var $selects = $("select");
if (typeof(ac1_init_value) == "undefined") {
    ac1_init_value = 0;
}

var setSelectOptions = function($select, data){
    var i, ii, options = [];
    options.push("<option value=''>" + $select.attr("data-label") + "</option>");
    if(data){
        for(i = 0, ii = data.length; i < ii; i++){
            init_select = "";
            if (data[i].ac == ac1_init_value) {
                init_select = " selected";
            }
            options.push("<option data-ac='" + data[i].ac + "' value='" + data[i].ac + "' id='select" + data[i].ac + "'" + init_select + ">" + data[i].name + "</option>");
        }
    }

    options.length === 1? $select.hide() : $select.show();
    $select.html(options.join(""));
};

$selects.eq(0).change(function(){
    var ac = $(this).find(":selected").attr("data-ac");
    if(ac){
        fetchChildrenArea(ac, function(data){
            setSelectOptions($selects.eq(1), data);
        });
    }else{
        setSelectOptions($selects.eq(1));
    }
});

$selects.eq(1).change(function(){
    var ac = $(this).find(":selected").attr("data-ac");
    if(ac){
        fetchChildrenArea(ac, function(data){
        });
    }
});

fetchChildrenArea("JP", function(data){
    setSelectOptions($selects.eq(0), data);
    setSelectOptions($selects.eq(1));

    // 初期値セット
    if (typeof(ac0_init_value) != "undefined") {
        $selects.eq(0).val(ac0_init_value).trigger('change');
    }
    
});

