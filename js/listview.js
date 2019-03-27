(function ($) {
	var arr = rids.split(','),
			markup = '',
			i = 1;
	$( arr ).each( function( key, value ){
		markup += '<tr>';
		markup += '<td>' + i++ + '</td>';
		markup += '<td><a href="https://systest.juniper.net/ti/webapp/dr/debug_dr/index.mhtml?mode=0:5:1&owner=ALL&result_id=' + value + '" target="_blank">' + value + '</a></td>';
		markup += '</tr>';
	});
	$('#result-list-view tbody').html( markup );

}(jQuery));
