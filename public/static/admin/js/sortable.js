$(function () {
    var getQueryParams = function() {
        var query = location.search.substr(1);
        var result = {};
        if( query === '' ) {
            return result;
        }
        query.split("&").forEach(function(part) {
            var item = part.split("=");
            result[item[0]] = decodeURIComponent(item[1]);
        });
        return result;
    };

    var setQueryParams = function(params) {
        var baseUrl = location.href,
            pos = baseUrl.indexOf("?");
        if(pos>-1) {
            baseUrl = baseUrl.substr(0, pos);
        }
        var paramsArray = Object.keys(params).map(function(value, index) {
            return value + "=" + params[value];
        });
        return baseUrl + '?' + paramsArray.join('&');
    };

    $('.sortable').click(function () {

        var key = $(this).data('key'),
            params = getQueryParams();
        if( params['order'] === key ) {
            params['direction'] = ( params['direction'] === 'asc' ) ? 'desc' : 'asc';
        }else {
            params['order'] = key;
            params['direction'] = 'asc';
        }
        location.href = setQueryParams(params);
    });

    $('.filter').change(function () {

        var self = $(this),
            key = self.data('key'),
            value = self.val(),
            params = getQueryParams();
        if( value == '' ) {
            delete params[key];
        }else {
            params[key] = value;
        }
        location.href = setQueryParams(params);
    });

});