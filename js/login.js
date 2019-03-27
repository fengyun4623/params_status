(function ($) {
    $('#uid').focus();
    var activePage = getCookie('prdbpage');
    if( getCookie( 'ssouid' ) !== null && getCookie( 'ssouid' ) != ''){
        showPageLoader();
        $('#login-form').before('<h4 class="text-dark-blue-i text-center">Successfully authenticated. Logging you in&hellip;</h4>' );
        setCookie( 'uid', getCookie('ssouid') );
        setCookie( 'uid', getCookie('ssouid'), '', '/');
        document.cookie = "ssouid=;expires=Thu, 01 Jan 1970 00:00:01 GMT";
        document.cookie = "ssouid=;path=/;expires=Thu, 01 Jan 1970 00:00:01 GMT";
        if( activePage != '' )
            window.location.href = './' + activePage + '.php';
        else
            window.location.href = './';
    }
    else{
        $('#login-form').fadeIn('200');
        setCookie( 'uid', '', -1 );
        setCookie( 'uid', '', -1, '/' );
        setCookie( 'PHPSESSID', '', -1, '/' );
        setCookie( 'login', '', -1, '/' );
        setCookie( 'qa_key', '', -1, '/' );
        setCookie( 'qa_noticed', '', -1, '/' );
        setCookie( 'qa_session', '', -1, '/' );
    }
    setCookie( 'isSetProfile', '' );
    setCookie( 'treeUid', '' );

    $('#btn-sso-login').click(function(e){
        showPageLoader();
        e.preventDefault();
        window.location = 'api/ssologin.php?urlredirect=' + window.location.href;
    });

    $('#login-form').submit(function(event) {
        showPageLoader();
        event.preventDefault();
        var request = $.ajax( {
            type: 'POST',
            url: 'api/getvaliduser.php?uid=' + $('#uid').val()
        });
        request.done(function(msg) {
            if( msg == 'success'){
                setCookie( 'uid', $('#uid').val() );
                setCookie( 'uid', $('#uid').val(), '', '/' );
                if( activePage != '' )
                    window.location.href = './' + activePage + '.php';
                else
                    window.location.href = './';
            }
            else if( msg == 'sso'){
                $('#uid-form-group').addClass('has-error');
                $('#uid-form-group > .control-label').html('<strong>' + $('#uid').val() + '</strong> has enabled SSO only login. Click on SSO Enabled Login');
                $('#uid-form-group > #uid').focus();
                hidePageLoader();
            }
            else{
                $('#uid-form-group').addClass('has-error');
                $('#uid-form-group > .control-label').html('Invalid Username. Please try again.');
                $('#uid-form-group > #uid').val('').focus();
                hidePageLoader();
            }
        });
        request.fail(function(jqXHR, textStatus) {
            console.log( "Login Validation request failed: " + jqXHR + ' ' + textStatus );
            $('#uid-form-group').addClass('has-error');
            $('#uid-form-group > .control-label').html('Sorry. Server returned an error. Please try again later.');
            hidePageLoader();
        });
    });
}(jQuery));
