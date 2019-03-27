var isLoaderVisible = false,
    loaderTimeout;

function setCookie(cname, cvalue, days, cpath) {
    var expires = '',
        path = '';
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = '; expires=' + date.toUTCString();
    }
    if (cpath) {
        path = '; path=' + cpath;
    }
    document.cookie = cname + "=" + cvalue + expires + path;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}

function showPageLoader(){
    if( !isLoaderVisible ){
        isLoaderVisible = true;
        $('body').css({
            // 'position': 'fixed',
            'width': '100%'
        });
        $('.page-loader').fadeIn('fast');
        loaderTimeout = setTimeout(function(){
            $('.page-loader').animate({'opacity': '0.95'}, 'slow').find('.close').fadeIn();
            $('.still-working').animate({'top':'15px', 'opacity': '0.9'},'slow');
        }, 2500);
    }
}

function hidePageLoader(){
    if( isLoaderVisible ){
        clearTimeout(loaderTimeout);
        $('body').css({
            // 'position': 'static',
            'width': 'auto'
        });
        $('.page-loader').find('.close').fadeOut();
        $('.page-loader').animate({'opacity': '0.8'}).fadeOut('fast');
        $('.still-working').css({'top':'99%', 'opacity': '0'});
        isLoaderVisible = false;
    }
}

var showElementLoader = function(obj){
    $(obj).css({
        'background': 'url("img/loader.gif") no-repeat 99% 1px transparent'
    });
}

var hideElementLoader = function(obj){
    $(obj).css({
        'background': 'none'
    });
}

function ucfirst(str) {
    var firstLetter = str.substr(0, 1);
    return firstLetter.toUpperCase() + str.substr(1);
}

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
}

Object.objectsCount = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (typeof(obj[key]) == 'object') size++;
    }
    return size;
}

function preload(arrayOfImages) {
    $(arrayOfImages).each(function(){
        $('<img/>')[0].src = this;
    });
}

$(function(){
    $('.page-loader .close').click(function(){
        hidePageLoader();
    });
});
/*
window.onerror = function(msg, url, linenumber) {
	hidePageLoader();
	alert('Server Error: ' + msg + '\nURL: ' + url + '\nLine Number: ' + linenumber + '. Please try again later.');
	return true;
}
*/
function htmlEncode(value){
  //create a in-memory div, set it's inner text(which jQuery automatically encodes)
  //then grab the encoded contents back out.  The div never exists on the page.
  return $('<div/>').text(value).html();
}

function htmlDecode(value){
  return $('<div/>').html(value).text();
}

function bgGlow( elem ){
    var $obj = $( elem );
    $obj.toggleClass('bg-glow');
    setTimeout( function(){
        $obj.toggleClass('bg-glow');
    }, 1500);
}

$.fn.scrollGuard2 = function() {
    return this
    .on( 'wheel', function ( e ) {
        var $this = $(this);
        if (e.originalEvent.deltaY < 0) {
            /* scrolling up */
            return ($this.scrollTop() > 0);
        } else {
            /* scrolling down */
            return ($this.scrollTop() + $this.innerHeight() < $this[0].scrollHeight);
        }
    });
};


    // Set / update URL query String
    var updateQueryStringParam = function (key, value) {
        var baseUrl = [location.protocol, '//', location.host, location.pathname].join(''),
            urlQueryString = document.location.search,
            newParam = key + '=' + value,
            params = '?' + newParam;

        // If the "search" string exists, then build params from it
        if (urlQueryString) {
            keyRegex = new RegExp('([\?&])' + key + '[^&]*');

            // If param exists already, update it
            if (urlQueryString.match(keyRegex) !== null) {
                params = urlQueryString.replace(keyRegex, "$1" + newParam);
            } else { // Otherwise, add it to end of query string
                params = urlQueryString + '&' + newParam;
            }
        }
        window.history.replaceState({}, "", baseUrl + params);
    };
    
    // Get URL query String
    var getQueryStringParam = function (name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }