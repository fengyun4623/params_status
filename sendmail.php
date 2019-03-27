<?php
$to = "nidanaz@juniper.net";
$subject = "PR-$number has been reviewed";

if (!$reviewer_approval){
	$reviewer_approval="Reviewer comments are not valid. kindly review again";
}

$message = "
<html>
<head>
<title>PR Review and Analysis</title>
</head>
<body>
<p>$number PR is reviewed by $reviewer at $reviewer_date.</br>
Comments for PR are below:</br>
$reviewer_approval
 </p>
</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <nidanaz@juniper.net>' . "\r\n";
$headers .= 'Cc: nkodati@juniper.net' . "\r\n";

mail($to,$subject,$message,$headers);?>
