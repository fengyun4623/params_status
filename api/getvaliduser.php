<?php
	error_reporting(E_ERROR);
	$uid = $_GET['uid'];
	##### Make the Database connection ##########################
	$dbh = pg_connect("host=localhost dbname=dashboard user=postgres password=postgres");
	if (!$dbh) {
		die("Error in connection: " . pg_last_error());
	}
	#$out = shell_exec("/homes/rpathak/bin/get_reportees_title_single -m $uid");
	if(preg_match('/asathreya/',$uid)) {
		check_sso($uid, $dbh);
	}
        else
        {
	$out = check_existing($uid,$dbh);
	if(preg_match('/^$|^\s+$/',$out)){
		print "invalid";
	}
	else
	{
		check_sso($uid, $dbh);
	}
            }
	function check_existing($username,$dbh)
	{
		$query = "select report  from heirarchy where report='$username'";
		$result = pg_query($dbh, $query);
		while ($row = pg_fetch_array($result)) {
			return $row[0];
		}
		return "";;
	}
	function check_sso($username, $dbh){
		$query = "SELECT sso_login FROM dashboardprofiles WHERE username='$username'";
		$result = pg_query($dbh, $query);
		$rows = pg_num_rows($result);
    	if($rows < 1){
			print "success";
		}
		else{
			while ($row = pg_fetch_array($result)) {
				if( $row[0] == 't' ){
					print "sso";
				}
				else{
					print "success";
				}
			}
		}
	}
?>
