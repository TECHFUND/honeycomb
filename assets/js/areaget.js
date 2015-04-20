
function getAddressName(location_id, html_id) {

    var address_name = "";
    // apiの設定情報
    // http://developer.yahoo.co.jp/webapi/map/openlocalplatform/v1/addressdirectory.html
    var api = {
        url: "http://search.olp.yahooapis.jp/OpenLocalPlatform/V1/addressDirectory",
        appid: "dj0zaiZpPVJLbUJmOXppWXJrciZzPWNvbnN1bWVyc2VjcmV0Jng9ZDc-"
    };

    $.ajax({
        url: api.url,
        data: {
            appid: api.appid,
            ac: location_id,
            mode: "2", // 1:甲良町（犬上郡）, 2: 犬上郡甲良町
            output: "json"
        },
        dataType: "jsonp",
        success: function(data) {
            address_name = data.Feature[0].Name;
            $("#"+html_id).html(address_name);
        }
    }).fail(function(){

        error && error();

    });

}
