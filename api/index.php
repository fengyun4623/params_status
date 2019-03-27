<?php
//nidanaz
ini_set('memory_limit', '-1');
error_reporting(E_ALL ^ E_DEPRECATED);
$action = "getjsonmanager";
if (isset($_POST['action']) && ! empty($_POST['action'])) {
	$action = strtolower($_POST['action']);
} 
elseif (isset($_GET['action']) && ! empty($_GET['action'])) {
	$action = strtolower($_GET['action']);
}


function  get_value($url){
$ch = curl_init();
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);

$result = file_get_contents($url);
$array = array();
$array = json_decode($result, true);
return $array;
}


switch ($action){
    case 'getjsonmanager':
    getJson();
    break;
    case 'getjsonfull':
    getJson();
    break;
} 


function getJson(){

	$func_arr = array("CI","SERVICES","BBE","MMX","KM","TPTX","RPD","ACX","EX","QFX","LEGACY-EX","LEGACY-QFX");
	foreach ($func_arr as $key => $teams) {
	$dbh = pg_connect("host=rbu.juniper.net dbname=regression_pr_db user=postgres password=postgres");
		$result=pg_query($dbh,"select * from params_status_fusion where team ='$teams' and excluded =false and tag_fusion ='active' order by status;");
	
		$arr = array();
		$j = 0;

		while($row=pg_fetch_array($result))
		{ //team,domain,domain_owner,tot_script,tot_pp,tot_np,fail_pp,fail_np,pass_pp,pass_np,syntax
			$arr{$j}{'team'} = $row['team'];
			$arr{$j}{'domain_owner'}=$row['domain_owner'];
			$arr{$j}{'domain'}=$row['domain'];
			$arr{$j}{'tot_script'} = (int)$row['tot_script'];
			$arr{$j}{'tot_pp'} = (int)$row['tot_pp'];
			$arr{$j}{'tot_np'} = (int)$row['tot_np'];
			$arr{$j}{'fail_pp'} = (int)$row['fail_pp'];
			$arr{$j}{'fail_np'} = (int)$row['fail_np'];
			$arr{$j}{'pass_pp'} = (int)$row['pass_pp'];
			$arr{$j}{'pass_np'} = (int)$row['pass_np'];
			$arr{$j}{'tot_jpg'} = (int)$row['tot_jpg'];
			$arr{$j}{'jpg_fp'} = (int)$row['jpg_fp'];
			$arr{$j}{'jpg_pp'} = (int)$row['jpg_pp'];
			$arr{$j}{'no_dbp'} = (int)$row['no_dbp'];
			$arr{$j}{'status'} = $row['status'];
			$arr{$j}{'time'} = $row['time'];
			$arr{$j}{'hw_pending'} = $row['hw_pending'];


			$j++;
		}
		$teams = strtolower($teams);
		$teams = preg_replace('/\-/', '', $teams);
		$data[$teams]=$arr;
	}

	$dbh = pg_connect("host=rbu.juniper.net dbname=regression_pr_db user=postgres password=postgres");
	$result=pg_query($dbh,"select * from params_status_fusion where excluded ='t' and tag_fusion ='active' order by team");
	
		$arr3 = array();
		$j = 0;

		while($row=pg_fetch_array($result))
		{ //team,domain,domain_owner,tot_script,tot_pp,tot_np,fail_pp,fail_np,pass_pp,pass_np,syntax
			$arr3{$j}{'team'} = $row['team'];
			$arr3{$j}{'domain_owner'}=$row['domain_owner'];
			$arr3{$j}{'domain'}=$row['domain'];
			$arr3{$j}{'tot_script'} = (int)$row['tot_script'];
			$arr3{$j}{'tot_pp'} = (int)$row['tot_pp'];
			$arr3{$j}{'tot_np'} = (int)$row['tot_np'];
			$arr3{$j}{'fail_pp'} = (int)$row['fail_pp'];
			$arr3{$j}{'fail_np'} = (int)$row['fail_np'];
			$arr3{$j}{'pass_pp'} = (int)$row['pass_pp'];
			$arr3{$j}{'pass_np'} = (int)$row['pass_np'];
			$arr3{$j}{'tot_jpg'} = (int)$row['tot_jpg'];
			$arr3{$j}{'jpg_fp'} = (int)$row['jpg_fp'];
			$arr3{$j}{'jpg_pp'} = (int)$row['jpg_pp'];
			$arr3{$j}{'no_dbp'} = (int)$row['no_dbp'];
			$arr3{$j}{'status'} = $row['status'];
			$arr3{$j}{'time'} = $row['time'];
			$arr3{$j}{'hw_pending'} = $row['hw_pending'];
			$arr3{$j}{'exld_cmnt'} = $row['exld_cmnt'];
			$j++;
		}
		$teams = strtolower($teams);
		$teams = preg_replace('/\-/', '', $teams);
		$data['excluded']=$arr3;

	$result=pg_query($dbh,"select distinct team as team, count(domain) as domain,sum(case when status ='FAIL' then 1 else 0 end) as fail_dom,sum(case when status ='PASS' then 1 else 0 end) as pass_dom, sum(tot_script::int) as tot_script, sum(tot_script::int) as tot_script ,sum(tot_pp::int) as tot_pp ,sum(tot_np::int) as tot_np ,sum(fail_pp::int) as fail_pp ,sum(tot_jpg::int) as tot_jpg ,sum(jpg_fp::int) as jpg_fp ,sum(jpg_pp::int) as jpg_pp ,sum(fail_np::int) as fail_np ,sum(pass_pp::int) as pass_pp ,sum(pass_np::int) as pass_np, sum(no_dbp::int) as no_dbp ,sum(syntax::int) as syntax,sum(hw_pending::int) as hw_pending from params_status_fusion where excluded =false and tag_fusion ='active' group by team order by team;");
	
	$arr1 = array();
	$i = 0;
	while($row=pg_fetch_array($result))
	{ //team,domain,domain_owner,tot_script,tot_pp,tot_np,fail_pp,fail_np,pass_pp,pass_np,syntax
		
		$arr1{$i}{'team'} = $row['team'];
		$team = $row['team'];
		$arr1{$i}{'domain'} = (int)$row['domain'];
		$dom_tot +=  $row['domain'];
		$arr1{$i}{'pass_dom'} = (int)$row['pass_dom'];
		$pass_dom +=  (int)$row['pass_dom'];
		$arr1{$i}{'fail_dom'} = (int)$row['fail_dom'];
		$fail_dom +=  (int)$row['fail_dom'];
		$arr1{$i}{'tot_script'} = (int)$row['tot_script'];
		$scr_tot += (int)$row['tot_script'];
		
		$arr1{$i}{'no_dbp'} = (int)$row['no_dbp'];
		$no_dbp += (int)$row['no_dbp'];


		$arr1{$i}{'tot_jpg'} = (int)$row['tot_jpg'];
		$tot_jpg +=  (int)$row['tot_jpg'];
		$arr1{$i}{'jpg_fp'} = (int)$row['jpg_fp'];
		$jpg_fp +=  (int)$row['jpg_fp'];
		$arr1{$i}{'jpg_pp'} = (int)$row['jpg_pp'];
		$jpg_pp += (int)$row['jpg_pp'];

		$tot_pp += (int)$row['tot_pp'];
		$arr1{$i}{'tot_pp'} = (int)$row['tot_pp'];
		$tot_np += (int)$row['tot_np'];
		$arr1{$i}{'tot_np'} = (int)$row['tot_np'];
		$fail_pp += (int)$row['fail_pp'];
		$arr1{$i}{'fail_pp'} = (int)$row['fail_pp'];
		$fail_np += (int)$row['fail_np'];
		$arr1{$i}{'fail_np'} = (int)$row['fail_np'];
		$pass_pp += (int)$row['pass_pp'];
		$arr1{$i}{'pass_pp'} = (int)$row['pass_pp'];
		$pass_np += (int)$row['pass_np'];
		$arr1{$i}{'pass_np'} = (int)$row['pass_np'];
		$hw_pending += (int)$row['hw_pending'];
		$arr1{$i}{'hw_pending'} = (int)$row['hw_pending'];

		$i++;
	}
	$arr1{$i}{'team'}="Total";
	$arr1{$i}{'domain'} =(int)$dom_tot;
	$arr1{$i}{'pass_dom'} =(int)$pass_dom;
	$arr1{$i}{'fail_dom'} =(int)$fail_dom;
	$arr1{$i}{'tot_script'} =(int)$scr_tot;
	$arr1{$i}{'tot_pp'} =(int)$tot_pp;
	$arr1{$i}{'tot_np'} =(int)$tot_np;
	$arr1{$i}{'fail_pp'} =(int)$fail_pp;
	$arr1{$i}{'fail_np'} =(int)$fail_np;
	$arr1{$i}{'pass_pp'} =(int)$pass_pp;
	$arr1{$i}{'pass_np'} =(int)$pass_np;
	$arr1{$i}{'tot_jpg'} =(int)$tot_jpg;
	$arr1{$i}{'jpg_fp'} =(int)$jpg_fp;
	$arr1{$i}{'jpg_pp'} =(int)$jpg_pp;
	$arr1{$i}{'no_dbp'} =(int)$no_dbp;
	$arr1{$i}{'hw_pending'} =(int)$hw_pending;

	$data['summary']=$arr1;
echo json_encode($data);	
}


function returnFunction($msg){
	echo json_encode($msg);
	exit;
}
?>
