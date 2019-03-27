<?php   


// $action = $argv[1];
// $exit_date = $argv[2];
// $type = $argv[3];

// error_reporting(E_ALL);
error_reporting(0);
// ini_set("display_errors", true);
// error_reporting(E_WARNING); 
header("Access-Control-Allow-Origin: *"); 

// echo dirname('../inc/'). PHP_EOL;


// require_once(dirname('../inc/') . '/inc/dbconn.php');
// require_once(dirname('../inc/') . '/inc/function.php');
	

// cron runs
if(isset( $argv ) && $argv[1] == 'cron'){
	chdir(dirname('../inc/'));
	include('inc/dbconn.php');
	include('inc/function.php');
}else{
	include('../inc/dbconn.php');
	include('../inc/function.php');
}

// include('../inc/dbconn.php');
// include('../inc/function.php');

 // $action = "testbed";


if (isset($_POST['action']) && ! empty($_POST['action'])) {
    $action = strtolower($_POST['action']);
} elseif (isset($_GET['action']) && ! empty($_GET['action'])) {
    $action = strtolower($_GET['action']);
}else {
	$action = $argv[2];
}




switch ($action){
    case 'testbed':
        								TestBed();
        								break;
    case 'nextestbed':
        								NextTestBed();
        								break;
    case 'testbeddata':
        								TestBedData();
        								break;		
    case 'infrafailures':
        								InfraFailures();
        								break;
    case 'getprs':
        								getPRs();
        								break;
    case 'getdebugs':
        								getDebugs();
        								break;
    case 'resourcejson':
        								getResourceJson();
        								break;    
    case 'spirent':
        								Spirent();
        								break;
    case 'dailydatacron':
        								dailydataCron();
        								break;  
    case 'functionlists':
        								functionLists();
        								break;
	case 'lastupdatedate':
        								lastupdateDate();
        								break;

    // Temp function for segregatedIssues    								
	case 'segregatedissues':
        								segregatedIssues();
        								break;
	// Nida's Code        								     
    case 'triggerscript':                                                
                                        triggerscript();
									    break;


}

// For getting last Updated date
function lastupdateDate(){
	if(isset($_GET['to_date'])){
		// $data = array();
		$to_date = $_GET['to_date'];
		$jsonstr = "";
		$jsonstr ='{ ';

		$dates = selectrec("infra,activities","cronaudit_trail","exit_date="."'".$to_date."'");
		
		if(!empty($dates)){
			foreach($dates as $date){
				$jsonstr .='
					"infra":"'.$date[0].'",
					"activities":"'.$date[1].'"
				}';
			}
		}
		else {
			$jsonstr .='
					"infra":" ",
					"activities":" "
				}';
		}

		// $jsonstr = substr($jsonstr, 0, -1);
		echo $jsonstr;
	}
}
function functionLists() {
	$data = array();
	$result = mysql_query("SELECT * FROM function where active=1 order by name asc");
	while($row = mysql_fetch_assoc($result)) {
		$data[] = $row;
	}
	echo json_encode($data);

}

function dailydataCron(){

	// $exit_date = date('Y-m-d');
	// echo "ss1";
	$exit_date = date('Y-m-d');

	$yesterday = date('Y-m-d',strtotime("-1 days"));

	$yesterday = $yesterday." 23:59:01";

	$datetime = date('Y-m-d H:i:s');

	$table = "cronaudit_trail";
	$fields = "exit_date,activities,infra";



	getPRs($exit_date);
	getDebugs($exit_date);
	getetrans($exit_date);

	
	$result = singlefield("id",$table,"exit_date="."'".$exit_date."' and activities IS NOT NULL");

	if($result){
		$cond = "exit_date="."'".$exit_date."'"." ";
		$col_val = "activities='".$datetime."'";
		if(updaterecs($table,$col_val,$cond))
			$str1 ='{"status": 1, "message": "time Updated Successfully"}';
	}
	else{
		$value = '"'.$exit_date.'","'.$datetime.'","'.$yesterday.'"';
		if(insertrec($table,$fields,$value))
			$str1 ='{"status": 1, "message": "Inserted Successfully"}';
	}

	echo $str1;


}

// code taking from exit code page 
function InfraFailures() {

	// $exit_date = "2016-12-05";

    $jsonStr ="";
    // $jso ="";

    $catDatas = selectrec("DISTINCT(c.name),c.id,f.id as func_id" , 
    "category_data cd 
    inner join function f on cd.function_id = f.id 
    inner join category c on cd.category_id = c.id where c.active=1 and c.is_param= 0 order by cd.category_id asc, cd.function_id asc , cd.week_id asc" );
    // exit();
    $jsonStr .='{ ';
    $functionIDs = singlefield("group_concat(id)","function", "active=1");
    // $catweeks = selectrec("DISTINCT(DATE_FORMAT(exit_date, '%b'))","category_data ORDER BY (DATE_FORMAT(exit_date, '%b')) DESC  limit 4");


    // var_dump($catweeks);
    // $paramasdata = singlefield("GROUP_CONCAT(id)","category"," name='PARAMS_FAIL' or name='PARAMS_FAILURE' or name='PARAMS_TIMEOUT' or name='PARAMS_SYN_ERR' ");

    // echo $paramasdata;

     // $catDates = selectrec("CONCAT(DATE_FORMAT(STR_TO_DATE(CONCAT(YEARWEEK( CURDATE() - INTERVAL 4 WEEK, 1 ),' Monday'), '%X%V %W'), '%b%d') , '-' , DATE_FORMAT (DATE_ADD(CURDATE() - INTERVAL 4 WEEK , INTERVAL 6 DAY ), '%b%d') ) as date1, CONCAT(DATE_FORMAT(STR_TO_DATE(CONCAT(YEARWEEK( CURDATE() - INTERVAL 3 WEEK, 1 ),' Monday'), '%X%V %W'), '%b%d') , '-' , DATE_FORMAT (DATE_ADD(CURDATE() - INTERVAL 3 WEEK , INTERVAL 6 DAY ), '%b%d') ) as date2 ,   CONCAT(DATE_FORMAT(STR_TO_DATE(CONCAT(YEARWEEK( CURDATE() - INTERVAL 2 WEEK, 1 ),' Monday'), '%X%V %W'), '%b%d') , '-' , DATE_FORMAT (DATE_ADD(CURDATE() - INTERVAL 2 WEEK , INTERVAL 6 DAY ), '%b%d') ) as date3, CONCAT(DATE_FORMAT(STR_TO_DATE(CONCAT(YEARWEEK( CURDATE() - INTERVAL 1 WEEK, 1 ),' Monday'), '%X%V %W'), '%b%d') , '-' , DATE_FORMAT (DATE_ADD(CURDATE() - INTERVAL 1 WEEK , INTERVAL 6 DAY ), '%b%d') ) as date4  " ,"category_data");

  	 // foreach($catDates as $catDate){
  	 	// echo $catDates[0];
  	  // }


     $catDates = selectrec(" CONCAT(DATE_FORMAT(STR_TO_DATE(CONCAT(YEARWEEK( CURDATE() - INTERVAL 4 WEEK, 1 ),'Monday'), '%X%V %W'), '%b%d') , '-', DATE_FORMAT(STR_TO_DATE(CONCAT(YEARWEEK( CURDATE() - INTERVAL 3 WEEK, 1 ),'Sunday'), '%X%V %W'), '%b%d') ) as date1, CONCAT(DATE_FORMAT(STR_TO_DATE(CONCAT(YEARWEEK( CURDATE() - INTERVAL 3 WEEK, 1 ),' Monday'), '%X%V %W'), '%b%d') , '-', DATE_FORMAT(STR_TO_DATE(CONCAT(YEARWEEK( CURDATE() - INTERVAL 2 WEEK, 1 ),'Sunday'), '%X%V %W'), '%b%d') ) as date2 ,   CONCAT(DATE_FORMAT(STR_TO_DATE(CONCAT(YEARWEEK( CURDATE() - INTERVAL 2 WEEK, 1 ),' Monday'), '%X%V %W'), '%b%d') , '-', DATE_FORMAT(STR_TO_DATE(CONCAT(YEARWEEK( CURDATE() - INTERVAL 1 WEEK, 1 ),'Sunday'), '%X%V %W'), '%b%d') ) as date3, CONCAT(DATE_FORMAT(STR_TO_DATE(CONCAT(YEARWEEK( CURDATE() - INTERVAL 1 WEEK, 1 ),' Monday'), '%X%V %W'), '%b%d') , '-', DATE_FORMAT(STR_TO_DATE(CONCAT(YEARWEEK( CURDATE() - INTERVAL 0 WEEK, 1 ),'Sunday'), '%X%V %W'), '%b%d') ) as date4 ","category_data limit 1");
  	 	



    foreach ($catDatas as $key => $catData) {

		if($catData[0] == "LINK_FAIL" )
			$name = "LINK FAILURES";
		else if($catData[0] == "ABORT" )
			$name = "ABORTS";
		else if($catData[0] == "IXIA" )
			$name = "IXIA FAILURES";
		else if($catData[0] == "AGILENT" )
			$name = "AGILENT FAILURES";
		else if($catData[0] == "JPG" )
			$name = "JPG FAILURES";
		else if($catData[0] == "ISSU" )
			$name = "ISSU FAILURES";
		else if($catData[0] == "UNKNOWN_ERROR" )
			$name = "UNKNOWN ERRORS";
		else if($catData[0] == "SPIRENT" )
			$name = "SPIRENT FAILURES";
			


		$jsonStr .='"'.$catData[0].'":{
		"name":"'.$name.'",';	

		// $jsonStr .='"category":["'.$catweeks[3][0].'","'.$catweeks[2][0].'","'.$catweeks[1][0].'","'.$catweeks[0][0].'"],';
		// foreach($catDates as $catDate){ 
			$jsonStr .='"category":["'.$catDates[0][0].'","'.$catDates[0][1].'", "'.$catDates[0][2].'" , "'.$catDates[0][3].'"],';
			// $jsonStr .='"category":["Dec05-Dec11","Dec12-Dec18", "Dec19-Dec25" , "Dec26-Jan01"],';
		// }	 

		$jsonStr .='"data":[';

		// $catdataTotals = selectrec("f.name as functionname, SUM(case when week( cd.exit_date)=(week(curdate()) - 3) then cd.data_total END ) AS week1, SUM(case when week( cd.exit_date)=(week(curdate()) - 2) then cd.data_total END ) AS week2, SUM(case when week( cd.exit_date)=(week(curdate()) - 1) then cd.data_total END ) AS week3, SUM(case when week( cd.exit_date)=(week(curdate()) - 0) then cd.data_total END ) AS week4", " category_data cd join function f on f.id=cd.function_id join category c on c.id=cd.category_id"," cd.category_id =".$catData[1]." and function_id in (".$functionIDs.") group by f.name, functionname");

		// select f.name as functionname, SUM(case when week( cd.exit_date)=(week(curdate()) - 0) then cd.data_total END ) AS week1, SUM(case when week( cd.exit_date)=(week(curdate()) - 1) then cd.data_total END ) AS week2, SUM(case when week( cd.exit_date)=(week(curdate()) - 2) then cd.data_total END ) AS week3, SUM(case when week( cd.exit_date)=(week(curdate()) - 3) then cd.data_total END ) AS week4 from category_data cd join function f on f.id=cd.function_id join category c on c.id=cd.category_id where cd.category_id =1 and function_id in (1,2,3,4,5,6,7) group by f.name, functionname

		// if($catData[1] == 10 || $catData[1] == 11 || $catData[1] == 12  || $catData[1] == 26 ){

		// 		$catdataTotals = selectrec("f.name as functionname, SUM(case when yearweek( cd.exit_date,1)=YEARWEEK( CURDATE() - INTERVAL 4 WEEK, 1) then cd.data_total END ) AS yearweek1, SUM(case when yearweek( cd.exit_date,1)=YEARWEEK( CURDATE() - INTERVAL 3 WEEK, 1) then cd.data_total END ) AS yearweek2, SUM(case when yearweek( cd.exit_date,1)=YEARWEEK( CURDATE() - INTERVAL 2 WEEK, 1) then cd.data_total END ) AS yearweek3, SUM(case when yearweek( cd.exit_date,1)=YEARWEEK( CURDATE() - INTERVAL 1 WEEK, 1) then cd.data_total END ) AS yearweek4", " category_data cd join function f on f.id=cd.function_id join category c on c.id=cd.category_id"," cd.category_id in (10,11,12,26) and function_id in (".$functionIDs.") group by f.name, functionname");

		// 	$catname = "Params Status";	


		// }
		// else{
				$catdataTotals = selectrec("f.name as functionname, SUM(case when yearweek( cd.exit_date,1)=YEARWEEK( CURDATE() - INTERVAL 4 WEEK, 1) then cd.data_total END ) AS yearweek1, SUM(case when yearweek( cd.exit_date,1)=YEARWEEK( CURDATE() - INTERVAL 3 WEEK, 1) then cd.data_total END ) AS yearweek2, SUM(case when yearweek( cd.exit_date,1)=YEARWEEK( CURDATE() - INTERVAL 2 WEEK, 1) then cd.data_total END ) AS yearweek3, SUM(case when yearweek( cd.exit_date,1)=YEARWEEK( CURDATE() - INTERVAL 1 WEEK, 1) then cd.data_total END ) AS yearweek4", " category_data cd join function f on f.id=cd.function_id join category c on c.id=cd.category_id"," cd.category_id =".$catData[1]." and function_id in (".$functionIDs.") group by f.name, functionname");	
		// }
		


		
		// exit();

			// $catdataTotals = selectrec("c.name,f.name as functionname,sum(data_total)", "category_data cd join function f on f.id=cd.function_id join category c on c.id=cd.category_id " , " week(exit_date)=".($weekID-$weeks)." and cd.category_id=".$catData[1]." and function_id=".$function[0]);

			foreach ($catdataTotals as $catdataTotal) {
				// echo $catdataTotal[3];
				$catname = $catdataTotal[0];
				$jsonStr.='{
					"name":"'.$catname.'",
					"data":['.intval($catdataTotal[1]).','.intval($catdataTotal[2]).','.intval($catdataTotal[3]).','.intval($catdataTotal[4]).']
				},';
			}


		// }	
		// }

		if($catData[1] == 9 ){
			// For Red Domains
			$catDatano = 9;	
			$jsonStr = substr($jsonStr, 0, -1);
			$jsonStr .='],';  
			$jsonStr.='"reddomain":{
			"title":"Red Domain : '.$catDates[0][3].'" ,
			"data":[ ';

			$reddomains = selectrec("f.name as functionname, SUM(case when yearweek( cd.exit_date,1)=YEARWEEK( CURDATE() - INTERVAL 1 WEEK, 1) then cd.data_total END ) AS yearweek4 ", " category_data cd join function f on f.id=cd.function_id join category c on c.id=cd.category_id"," cd.category_id =".$catDatano." and function_id in (".$functionIDs.") group by f.name, functionname");

	


			foreach ($reddomains as $reddomain) {
				$jsonStr .='{
				"'.$reddomain[0].'" : "'.$reddomain[1].'"
				},';
			}			

			$jsonStr = substr($jsonStr, 0, -1);			
			$jsonStr .=']}';
		}
		else{
			$jsonStr = substr($jsonStr, 0, -1);
			$jsonStr .=']'; 
		}

		
		$jsonStr .='},';  

    }                       


    $jsonStr = substr($jsonStr, 0, -1)."}";
    // echo $jsonStr;

    // Json for All 4 Paramas Bucket
    $paramsIds = singlefield("GROUP_CONCAT(id)", "category" ," active=1 and is_param=1" );

    $paramsdataTotals = selectrec("f.name as functionname, SUM(case when yearweek( cd.exit_date,1)=YEARWEEK( CURDATE() - INTERVAL 4 WEEK, 1) then cd.data_total END ) AS yearweek1, SUM(case when yearweek( cd.exit_date,1)=YEARWEEK( CURDATE() - INTERVAL 3 WEEK, 1) then cd.data_total END ) AS yearweek2, SUM(case when yearweek( cd.exit_date,1)=YEARWEEK( CURDATE() - INTERVAL 2 WEEK, 1) then cd.data_total END ) AS yearweek3, SUM(case when yearweek( cd.exit_date,1)=YEARWEEK( CURDATE() - INTERVAL 1 WEEK, 1) then cd.data_total END ) AS yearweek4", " category_data cd join function f on f.id=cd.function_id join category c on c.id=cd.category_id"," cd.category_id in(".$paramsIds.") and function_id in (".$functionIDs.") group by f.name, functionname");



	$jso ='{
		"Paramas_Status":{
		   "name": "PARAMS FAILURES",
		   "category":["'.$catDates[0][0].'","'.$catDates[0][1].'", "'.$catDates[0][2].'" , "'.$catDates[0][3].'"],
		   "data" : [ ';	

	foreach ($paramsdataTotals as $paramsdataTotal) {
			// echo $paramsdataTotal[0];
				$jso.='{
					"name":"'.$paramsdataTotal[0].'",
					"data":['.intval($paramsdataTotal[1]).','.intval($paramsdataTotal[2]).','.intval($paramsdataTotal[3]).','.intval($paramsdataTotal[4]).'] 
				},';				
			}
	
	
	$jso = substr($jso, 0, -1);
	$jso .='],';

	$jso.='"reddomain":{
	"title":"Red Domain : '.$catDates[0][3].'" ,
	"data":[ ';

	$reddomains = selectrec("f.name as functionname, SUM(case when yearweek( cd.exit_date,1)=YEARWEEK( CURDATE() - INTERVAL 1 WEEK, 1) then cd.data_total END ) AS yearweek4 ", " category_data cd join function f on f.id=cd.function_id join category c on c.id=cd.category_id"," cd.category_id in(".$paramsIds.") and function_id in (".$functionIDs.") group by f.name, functionname");	


	foreach ($reddomains as $reddomain) {
		$jso .='{
		"'.$reddomain[0].'" : "'.$reddomain[1].'"
		},';
	}			

	$jso = substr($jso, 0, -1);			
	$jso .=']}';


	$jso .='}}'; 
			

	// echo $jso;
	// $jso = substr($jso, 0, -1)."}"; 
	
	// echo json_decode($jso,true);
	// echo $jsonStr;
    
    $merger=json_encode(array_merge(json_decode($jso, true),json_decode($jsonStr, true)));

    // echo $merger;
    // exit();

    $oldArray = json_decode($merger,true);

    // print_r($oldArray);
    // exit();

    $newArray = array("LINK_FAIL","Paramas_Status","ABORT","IXIA","SPIRENT","AGILENT","JPG","ISSU","UNKNOWN_ERROR");

    // $newArray = array_replace($newArray,$oldArray);
    $finalarray = array();

    foreach ($newArray as $key => $value) {
    	$finalarray[$value] = $oldArray[$value];
    }

    $finalarray = json_encode($finalarray);

    echo $finalarray;



}

function TestBedData() {

    $from_date = $_GET['from_date'];
    $to_date = $_GET['to_date'];

    $functioname = $_GET['function'];
    if($functioname != 'ALL')
    	$functions  = selectrec("id,name","function","active=1 and name="."'".$functioname."'");
    else
    	$functions  = selectrec("id,name","function","active=1");
    // $functioname = "CommonEdge";

	// $exit_date = singlerec("cd.exit_date" ," category_data cd LEFT JOIN function as f ON cd.function_id=f.id LEFT JOIN category as c ON c.id=cd.category_id limit 1");
	 // echo "ss".$exit_date[0];

	$mainjson = "{";
    $mainjson .= '"from_date" : "'.$from_date.'",
                  "to_date" : "'.$to_date.'" ,
                  "testbeddata": [';

    
    // $categories  = selectrec("id,name","category","active=1");
    // $cat = array();
    // foreach ($categories as $category) {
    // 	array_push($cat, $category[0]);
    // }
                
	foreach ($functions as $key => $function) {
		// foreach ($categories as $category) {
		$mainjson .='{
                        "group": "'.$function[1].'",
                        '.getAllTotalData($function[0],$from_date,$to_date).',
                        "testbeds":'.getTestbedData($function[0],$function[1],$from_date,$to_date).'
                    },';
                     
                }
		
	$mainjson = substr($mainjson, 0, -1)."]}";
	echo $mainjson;

}

function getAllTotalData($functionID,$from_date,$to_date){

// SELECT sum(data_total) FROM category_data where function_id=1 and (category_id=10 OR category_id=11 OR category_id=12)
	$aborts = singlefield("sum(data_total)","category_data"," function_id=".$functionID." and category_id=1 and exit_date >="."'".$from_date."'" ." and exit_date <="."'".$to_date."'");

	$params = singlefield("sum(data_total)","category_data"," function_id=".$functionID." and exit_date >="."'".$from_date."'" ." and exit_date <="."'".$to_date."'". " and (category_id=10 OR category_id=11 OR category_id=12 OR category_id=26 ) " );

	$linksdown = singlefield("sum(data_total)","category_data"," function_id=".$functionID." and exit_date >="."'".$from_date."'" ." and exit_date <="."'".$to_date."'". " and category_id=9" );

	$ixia = singlefield("sum(data_total)","category_data"," function_id=".$functionID." and exit_date >="."'".$from_date."'" ." and exit_date <="."'".$to_date."'". " and category_id=20" );

	$jpg = singlefield("sum(data_total)","category_data"," function_id=".$functionID." and exit_date >="."'".$from_date."'" ." and exit_date <="."'".$to_date."'". "  and category_id=22" );

	$issu = singlefield("sum(data_total)","category_data"," function_id=".$functionID." and exit_date >="."'".$from_date."'" ." and exit_date <="."'".$to_date."'". "  and category_id=23" );

	$aglient = singlefield("sum(data_total)","category_data"," function_id=".$functionID." and exit_date >="."'".$from_date."'" ." and exit_date <="."'".$to_date."'". "  and category_id=38" );

	$bsds = singlefield("sum(data_total)","category_data"," function_id=".$functionID."  and exit_date >="."'".$from_date."'" ." and exit_date <="."'".$to_date."'". "   and category_id=21" );

	$unkns = singlefield("sum(data_total)","category_data"," function_id=".$functionID."  and exit_date >="."'".$from_date."'" ." and exit_date <="."'".$to_date."'". "  and category_id=18" );

	// $debugs = singlefield("sum(data_total)","category_data"," function_id=".$functionID."  and exit_date ="."'".$from_date."'" ." and exit_date ="."'".$to_date."'". "  and category_id=39" );

	// $debugs = singlefield("sum(data_total)","category_data"," function_id=".$functionID."  and exit_date ="."'".$to_date."'" ." and category_id=39" );


	//	$prv = singlefield("sum(data_total)","category_data"," function_id=".$functionID."  and exit_date >="."'".$from_date."'" ." and exit_date <="."'".$to_date."'". "  and category_id=34" );
	//	$prinfo = singlefield("sum(data_total)","category_data"," function_id=".$functionID."  and exit_date >="."'".$from_date."'" ." and exit_date <="."'".$to_date."'". "  and category_id=35" );
	//	$prsetup = singlefield("sum(data_total)","category_data"," function_id=".$functionID."  and exit_date >="."'".$from_date."'" ." and exit_date <="."'".$to_date."'". "  and category_id=36" );
	//	$prtryfix = singlefield("sum(data_total)","category_data"," function_id=".$functionID."  and exit_date >="."'".$from_date."'" ." and exit_date <="."'".$to_date."'". "  and category_id=37" );

	// "prv": "'.intval($prv).'",
	// "prinfo": "'.intval($prinfo).'",
	// "prsetup": "'.intval($prsetup).'",
	// "prtryfix": "'.intval($prtryfix).'",

	$json .='
		"testbed": "ALL",
		"owner": "ALL",
		"aborts": "'.intval($aborts).'",
		"params": "'.intval($params).'",
		"linkdown": "'.intval($linksdown).'",
		"ixia": "'.intval($ixia).'",
		"jpg": "'.intval($jpg).'",
		"bsds": "'.intval($bsds).'",
		"issu": "'.intval($issu).'",
		"aglient": "'.intval($aglient).'",
		"unknownerrors": "'.intval($unkns).'",
		"debugs": "ALL",
		"prv": "ALL",
        "prinfo": "ALL",
        "prsetup": "ALL",
        "prtryfix": "ALL",
        "etransrp": "ALL",
        "etransprp": "ALL",
        "etranssf": "ALL"
	' ;

	return $json;

}
		
function getTestbedData($functionID,$functionname,$from_date,$to_date) {

	// echo $functionID;
	// echo $catID;
	// action=testbed&from_date=2016-12-14&to_date=2016-12-21
	// exit_date >= '2016-12-08' and exit_date <= '2016-12-09'

	$sql=selectrec("c.name,f.name,cd.domain,cd.data_total" ," category_data cd LEFT JOIN function as f ON cd.function_id=f.id LEFT JOIN category as c ON c.id=cd.category_id" ," cd.function_id=".$functionID." group by cd.domain" );

	// exit();


	$json = "[";

	foreach ($sql as $res) {

		$domainownername = getdomainownername($res[2]);


	//	$paramas = singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and category_id =10 and exit_date >="."'".$from_date."'". " and exit_date <="."'".$to_date."'") + singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and category_id =11 and exit_date >="."'".$from_date."'". " and exit_date <="."'".$to_date."'") + singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and category_id =12 and exit_date >="."'".$from_date."'". " and exit_date <="."'".$to_date."'" ) + singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and category_id =26 and exit_date >="."'".$from_date."'". " and exit_date <="."'".$to_date."'" );

         $paramas = singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and function_id=".$functionID." and category_id in (10,11,12,26) and exit_date >="."'".$from_date."'". " and exit_date <="."'".$to_date."'");

		$aborts = singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and function_id=".$functionID." and category_id =1 and exit_date >="."'".$from_date."'". "and exit_date <="."'".$to_date."'");

		$linkdown= singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and function_id=".$functionID." and category_id =9 and exit_date >="."'".$from_date."'". "and exit_date <="."'".$to_date."'");

		$ixia = singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and function_id=".$functionID." and category_id =20 and exit_date >="."'".$from_date."'". "and exit_date <="."'".$to_date."'");

		$jpg = singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and function_id=".$functionID." and category_id =22 and exit_date >="."'".$from_date."'". "and exit_date <="."'".$to_date."'");

		$bsds = singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and function_id=".$functionID." and category_id =21 and exit_date >="."'".$from_date."'". "and exit_date <="."'".$to_date."'");

		$issu = singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and function_id=".$functionID." and category_id =23 and exit_date >="."'".$from_date."'". "and exit_date <="."'".$to_date."'");

		$aglient = singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and function_id=".$functionID." and category_id =38 and exit_date >="."'".$from_date."'". "and exit_date <="."'".$to_date."'");

		$unk = singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and function_id=".$functionID." and category_id =18 and exit_date >="."'".$from_date."'". "and exit_date <="."'".$to_date."'");

		// $debugs = singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and function_id=".$functionID." and category_id =39 and exit_date ="."'".$from_date."'". "and exit_date ="."'".$to_date."'");

		$debugs = singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and function_id=".$functionID." and category_id =39 and exit_date ="."'".$to_date."'");

		$debugsresult = singlefield("result_id","category_data"," domain=". "'".$res[2]."'"." and function_id=".$functionID." and category_id =39 and exit_date ="."'".$to_date."'");

		$paramas_fail = singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and function_id=".$functionID." and category_id = 10 and exit_date >="."'".$from_date."'". " and exit_date <="."'".$to_date."'");

		$paramas_failure = singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and function_id=".$functionID." and category_id = 11 and exit_date >="."'".$from_date."'". " and exit_date <="."'".$to_date."'");

		$paramas_timeout = singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and function_id=".$functionID." and category_id = 12 and exit_date >="."'".$from_date."'". " and exit_date <="."'".$to_date."'");

		$paramas_syncerr = singlefield("sum(data_total)","category_data"," domain=". "'".$res[2]."'"." and function_id=".$functionID." and category_id = 26 and exit_date >="."'".$from_date."'". " and exit_date <="."'".$to_date."'");
		
		$params_list = "PARAMS_FAIL:".intval($paramas_fail).",PARAMS_FAILURE:".intval($paramas_failure).",PARAMS_TIMEOUT:".intval($paramas_timeout).",PARAMS_SYN_ERR:".intval($paramas_syncerr)."";

		$json .= '{
			"function_name":"'.$functionname.'",
			"testbed": "'.$res[2].'",
			"owner": "'.$domainownername.'",
			"aborts":"'.intval($aborts).'",
			"params":"'.intval($paramas).'",
			"params_list":"'.$params_list.'",
			"linkdown":"'.intval($linkdown).'",
			"ixia":"'.intval($ixia).'",
			"jpg":"'.intval($jpg).'",
			"bsds":"'.intval($bsds).'",
			"issu": "'.intval($issu).'",
			"aglient": "'.intval($aglient).'",
			"unknownerrors":"'.intval($unk).'",
			"debugs": "'.intval($debugs).'",
			"result_id":"'.$debugsresult.'",
		    '.getprsData($domainownername,$from_date,$to_date).'
		    '.getetransData($domainownername,$from_date,$to_date).'
		},';
	}

    $json = substr($json, 0, -1)."]";
   return $json;

}

function getprsData($owner,$from,$to){

	// $prv = singlefield("sum(prs_data)","prs_data"," owner=". "'".$owner."'"." and prscat_id =34 and exit_date >="."'".$from."'". "and exit_date <="."'".$to."'");
	$prv = singlefield("sum(prs_data)","prs_data"," owner=". "'".$owner."'"." and prscat_id =34 and exit_date ="."'".$to."'");

	$prinfo = singlefield("sum(prs_data)","prs_data"," owner=". "'".$owner."'"." and prscat_id =35 and exit_date ="."'".$to."'");

	$prsetup = singlefield("sum(prs_data)","prs_data"," owner=". "'".$owner."'"." and prscat_id =36 and exit_date ="."'".$to."'");

	$prtryfix = singlefield("sum(prs_data)","prs_data"," owner=". "'".$owner."'"." and prscat_id =37 and exit_date ="."'".$to."'");

	$json .='
		"prv": "'.intval($prv).'",
		"prinfo": "'.intval($prinfo).'",
		"prsetup": "'.intval($prsetup).'",
		"prtryfix": "'.intval($prtryfix).'",
	';

	return $json;


}


function getetransData($owner,$from,$to){

    $etrprp = singlefield("sum(etrans_data)","etrans_data"," owner=". "'".$owner."'"." and etrans_id =1 and exit_date ="."'".$to."'");

    $etranssf = singlefield("sum(etrans_data)","etrans_data"," owner=". "'".$owner."'"." and etrans_id =5 and exit_date ="."'".$to."'");

    $etransrp = singlefield("sum(etrans_data)","etrans_data"," owner=". "'".$owner."'"." and etrans_id in(2,3,4) and exit_date ="."'".$to."'");

    $json .='
        "etransrp": "'.$etransrp.'",
        "etransprp": "'.$etrprp.'",
        "etranssf": "'.$etranssf.'"
    ';

    return $json;
}




function TestBed(){

	$exit_date = date('Y-m-d');
	// $exit_date = "2017-01-31";

	$datetime = date('Y-m-d H:i:s');

	


	// $exit_date = "2017-01-16";
	$type = "cron";


	$from_date = $to_date = $exit_date;

	$ch = curl_init('https://rbu-dashboard.juniper.net/RBU/Regression_reports/exitcode_report.mhtml?date='.$from_date.'&to_date='.$to_date);
	// $ch = curl_init('out2.html');
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


	// Execute
	$result = curl_exec($ch);
	if (!curl_errno($ch)) {

		// if( $type == "cron") {

			$info = curl_getinfo($ch);

			$dom = new DOMDocument();
			$res=$dom->loadHTML($result);
			$xpath = new DomXPath($dom);

			$tables = $dom->getElementsByTagName('table');

			$xpath->query('.//thead/tr/th', $tables->item(1));

			// For Getting table datas
			$header_nodes  =  $xpath->query('//table[not(@id)]/tbody/tr//td[1]', $tables->item(0));
			$header_count = $header_nodes->length;

			// echo $header_count;
			// For Getting table headers
			$category_nodes  =  $xpath->query('//table[not(@id)]/thead/tr/th', $tables->item(0));
			$category_count = $category_nodes->length;


			//Push all Categories to Array Category
			$categories1 = array();
			for ($catIDss=1; $catIDss<$category_count-1;$catIDss++) {
			array_push($categories1,$category_nodes->item($catIDss)->nodeValue);
			}


			//Push all functions to Array functions
			$functions = array();
			for ($i=0; $i<$header_count;$i++) {
			array_push($functions, $header_nodes->item($i)->nodeValue);
			}

			if($header_count) {

				$table = 'function';
				$fields = 'name,active';

				foreach($functions as $function){

					$fun_name = $function;

					$function_name = singlefield("name",$table,"name="."'".$fun_name."'");

					if(!$function_name) {
						$value = '"'.$fun_name.'","1"';
						if(insertrec($table,$fields,$value)){
							$str ='{"status": 1, "message": "Inserted Successfully"}';
						}
						else{
							$str ='{"status": 0, "message": "Insertion Failure. Please try again"}';
						}
					}
					else {
						$str ='{"status": 0, "message": "Data Present"}';   
					}

				}
			}

			if($category_count){

				$table = 'category';
				$fields = 'name,active';

				$categories = array();

				//Push all categories to Array functions
				for ($catIDs=1; $catIDs<$category_count-1;$catIDs++) {
					array_push($categories, $category_nodes->item($catIDs)->nodeValue);
				}

				foreach ($categories as $category) {
					$cat_name = $category;
					$category_name = singlefield("name",$table,"name="."'".$cat_name."'");

					if(!$category_name) {
						$value = '"'.$cat_name.'","1"';
						if(insertrec($table,$fields,$value)){
							$str ='{"status": 1, "message": "Inserted Successfully"}';
						}
						else{
							$str ='{"status": 0, "message": "Insertion Failure. Please try again"}';
						}
					}
					else {
						$str ='{"status": 0, "message": "Data Present"}';   
					}
				}

	        }


			$category_Array = ["ABORT","PARAMS_FAIL","PARAMS_FAILURE","PARAMS_TIMEOUT","UNKNOWN_ERROR","LINK_FAIL","PARAMS_SYN_ERR"];

	        //Get All Functions and IDs from function Table and store in $funArray variable.
	        $funArray = selectrec("id,name","function","active=1");
	        $catArray = selectrec("id,name","category","active=1");


	      	$rows = array();
	        foreach ($functions as $key => $function) {

				//Inserting domians data in DB
			    getdomaindata($xpath,$function);

	        	$fun_key = getID($function,$funArray);

        		$testbedID = getHeaderID($xpath,"Testbed/Domain");
        
				$trNodes = $xpath->query('//table[@id="myTable"]/tbody/tr[td//text()[contains(.,"'.$function.'")]]');
				
			    $trObjs = $trNodes->length;
			    $domains = array();
			    for ($j=0; $j<$trObjs;$j++) {
			        $tds = $trNodes->item($j)->getElementsByTagName('td');
			        $domains[]      = "'".$tds->item($testbedID)->nodeValue."'";
			    }

				foreach($domains as $domvalue){
					$trNodes = $xpath->query('//table[@id="myTable"]/tbody/tr[td//text()[.="'.$function.'"] and td//text()[.='.$domvalue.']]');

	    			$trObjs = $trNodes->length;
					foreach ($category_Array as $catvalue) {
						$aborts =array();
						$res = getID($catvalue,$catArray);
			        	$abortID = getHeaderID($xpath,$catvalue);
		    			for ($j=0; $j<$trObjs;$j++) {
							$tds = $trNodes->item($j)->getElementsByTagName('td');
							array_push($aborts,$tds->item($abortID)->nodeValue);
						}	
						foreach($aborts as $abort){
							$value = '('. "'".$from_date."'".",".$fun_key.",".$domvalue.",".$res.",".intval($abort).')';
							$rows[] = $value;
						}

					}
				}
	    
			}


		

			$table = "category_data";
			$fields ="exit_date,function_id,domain,category_id,data_total";
			$value = implode(',', $rows);


			// if(allinsertrec($table,$fields,$value,"data_total=values(data_total)"))
			if(allinsertrec($table,$fields,$value))
                $str ='{"status": 1, "message": "Inserted Successfully"}';
			
			// Data fetched from http://rbu.juniper.net/kvsr/data/segregated_issues/	
			// NextTestBed($exit_date);
			// Temp Function for Segerated issues data to fetch
			segregatedIssues();
			// getPRs($exit_date);
			// getDebugs($exit_date);
			// getetrans($exit_date);
			
			echo $str;	
		// } // cron end


	} // Curl End
	curl_close($ch);

	// For last Updated date
	$table1 = "cronaudit_trail";
	$fields = "exit_date,type,infra";

	$result = singlefield("id",$table1,"exit_date="."'".$exit_date."' and infra IS NOT NULL");
	if($result){
		$cond = "exit_date="."'".$exit_date."'"." ";
		$col_val = "infra='".$datetime."'";
		if(updaterecs($table1,$col_val,$cond))
			$str1 ='{"status": 1, "message": "time Updated Successfully"}';
	}
	else{
		$value = '"'.$exit_date.'","'.$datetime.'"';
		if(insertrec($table1,$fields,$value))
			$str1 ='{"status": 1, "message": "Inserted Successfully"}';
	}

	echo $str1;

} // testbed end

// Temp Function for segregatesIssues 
function segregatedIssues() { 

	// Starting date as on  Sastry Tool URL :http://rbu.juniper.net/kvsr/segregate_scripts_into_issues.php
	$exit_date = '2016-12-26';

	$to_date = date('Y-m-d');

	$begin = new DateTime($exit_date);
	$end = new DateTime($to_date);

	$datearrays = array();
	$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
	foreach($daterange as $date){
		array_push($datearrays,$date->format("Y-m-d"));
	}

	array_push($datearrays,$to_date) ;



	$exitArrays = ["UNKNOWN_ERROR","TEST_TIMEOUT","SIG_ABORT","LINK_FAIL","IGP_FAIL","GCOV_ABORT","EXPECT_ERRORS","CONNECT_LOST","CONNECT_FAIL","ABORT","FAIL","UCODE_FAIL","PASS","NOTINUSE","UNSUPPORTED","UNTESTED","UNINITIATED","QUIT","MANY_FAILURES","FALSE_PASS","CORE_PASS","TC_RERUN_PASS","GRES_ERR_PASS","CRASH","PARAMS_FAIL","SYNTAX_ERROR","VJ_FAIL","UNSUP_VERSION","UNSUP_TESTBED","UNSUP_HW","FPC_PIC_ERROR","BAD_PROMPT","INFRA_FAIL"];


	$catArrays = ["JPG","IXIA","BSD","ISSU","AGILENT","SPIRENT"];

	// $exit_date = $exitdate;

	$dtable = "category_data";


	foreach($datearrays as $datearray) {

		$result = singlefield("exit_date","category_data"," category_id in (20,21,22,23,38,40) and exit_date="."'".$datearray."'" );

		if($result != ""){
			$cond = "category_id in (20,21,22,23,38,40) and exit_date="."'".$datearray."'";
			$type = "delete";

			if(deleterec($dtable,$cond,$type)){
				$str ='{"status": 1, "message": "Deleted Successfully"}';
				echo $str;
			}
		}


		// foreach($datearrays as $datearray){
			foreach($exitArrays as $exitArray) {
				foreach ($catArrays as $catArray) {
					$ch = curl_init('http://rbu.juniper.net/kvsr/data/segregated_issues/'.$datearray.'_'.$exitArray."_Total_".$catArray);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


					// Execute
					$result = curl_exec($ch);
					if (!curl_errno($ch)) {
						$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
						// echo $code;

						if($code == 200){
							$results = explode("\n", $result);
							$exitCodeArray = array();
							$functionArray = array();
							$domainArray = array();

							foreach ($results as $result) {
								$res = explode(",", $result);

								$exitCodeArray[] = $res[0];
								$functionArray[] = $res[1];
								$domainArray[]   = $res[2];
							}


							$domainCount = array_count_values($domainArray);

							$functions = selectrec("id","functions"," active=1");

							$uniquedomains= array_unique($domainArray);
							$rows = array();
							foreach ($uniquedomains as $key => $exitCode) {

								if($catArray  == 'JPG')
									$exitCodeArray[$key] = 'JPG';

								if($catArray  == 'IXIA')
									$exitCodeArray[$key] = 'IXIA';

								if($catArray  == 'BSD')
									$exitCodeArray[$key] = 'BSD/LINUX';

								if($catArray  == 'ISSU')
									$exitCodeArray[$key] = 'ISSU';

								if($catArray  == 'AGILENT')
									$exitCodeArray[$key] = 'AGILENT';

								if($catArray  == 'SPIRENT')
									$exitCodeArray[$key] = 'SPIRENT';


								$func_id = singlerec("id","function","name="."'".$functionArray[$key]."'");
								$exit_id = singlerec("id","category","name="."'".$exitCodeArray[$key]."'");


								$value = '('. "'".$datearray."'".",".$func_id[0].","."'".$domainArray[$key]."'".",".$exit_id[0].",".$domainCount[$domainArray[$key]].')';
								$rows[] = $value;

							}


							$table = "category_data";
							$fields ="exit_date,function_id,domain,category_id,data_total";
							$value = implode(',', $rows);

							// echo $value."<br>";	

							// if(allinsertrecd($table,$fields,$value,"data_total=values(data_total),exit_date=values(exit_date)"))
							if(allinsertrec($table,$fields,$value))
								$str ='{"status": 1, "message": "Inserted Successfully"}';

							
							// var_dump($rows);

						} // success code=200	
					} // curl close
					curl_close($ch);


				} // cat arrays
	        
			}// exit arrays;	

			echo "<br/>".$datearray ."--". $str ."<br/>";

	} // Date Arrays


}// EOF of Segreates Issues

// backup Function (Dont Delete)
// function NextTestBed($exitdate) { 
// 	$exitArrays = ["UNKNOWN_ERROR","TEST_TIMEOUT","SIG_ABORT","LINK_FAIL","IGP_FAIL","GCOV_ABORT","EXPECT_ERRORS","CONNECT_LOST","CONNECT_FAIL","ABORT","FAIL"];

// 	$catArrays = ["JPG","IXIA","BSD","ISSU","AGILENT","SPIRENT"];

// 	$exit_date = $exitdate;

// 	$type = "cron";

// 	$jpg = 0;

// 	foreach($exitArrays as $exitArray) {
// 		foreach ($catArrays as $catArray) {
// 			$ch = curl_init('http://rbu.juniper.net/kvsr/data/segregated_issues/'.$exit_date.'_'.$exitArray."_Total_".$catArray);
// 			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
// 			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// 			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


// 			// Execute
// 			$result = curl_exec($ch);
// 			if (!curl_errno($ch)) {
// 				$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// 				// echo $code;

// 				if($code == 200){
// 					$results = explode("\n", $result);
// 					$exitCodeArray = array();
// 					$functionArray = array();
// 					$domainArray = array();

// 					foreach ($results as $result) {
// 						$res = explode(",", $result);

// 						$exitCodeArray[] = $res[0];
// 						$functionArray[] = $res[1];
// 						$domainArray[]   = $res[2];
// 					}


// 					$domainCount = array_count_values($domainArray);

// 					$functions = selectrec("id","functions"," active=1");

// 					$uniquedomains= array_unique($domainArray);
// 					$rows = array();
// 					foreach ($uniquedomains as $key => $exitCode) {

// 						if($catArray  == 'JPG')
// 							$exitCodeArray[$key] = 'JPG';

// 						if($catArray  == 'IXIA')
// 							$exitCodeArray[$key] = 'IXIA';

// 						if($catArray  == 'BSD')
// 							$exitCodeArray[$key] = ' BSD/LINUX';

// 						if($catArray  == 'ISSU')
// 							$exitCodeArray[$key] = 'ISSU';

// 						if($catArray  == 'AGILENT')
// 							$exitCodeArray[$key] = 'AGILENT';

// 						if($catArray  == 'SPIRENT')
// 							$exitCodeArray[$key] = 'SPIRENT';


// 						$func_id = singlerec("id","function","name="."'".$functionArray[$key]."'");
// 						$exit_id = singlerec("id","category","name="."'".$exitCodeArray[$key]."'");


// 						$value = '('. "'".$exit_date."'".",".$func_id[0].","."'".$domainArray[$key]."'".",".$exit_id[0].",".$domainCount[$domainArray[$key]].')';
// 						$rows[] = $value;

// 					}


// 					$table = "category_data";
// 					$fields ="exit_date,function_id,domain,category_id,data_total";
// 					$value = implode(',', $rows);

// 					// echo $value."<br>";

// 					if(allinsertrec($table,$fields,$value))
// 					$str ='{"status": 1, "message": "Inserted Successfully"}';

// 					// echo $str;
// 					// var_dump($rows);

// 				} // success code=200	
// 			} // curl close
// 			curl_close($ch);


// 		} // cat arrays
        
// 	}// exit arrays;

// }// EOF of NextTestBed


function getPRs($exitdate) {


	$domains =array();

	// echo $exitdate;
	// $exitdate = "2017-01-24";

	// $ownernames = selectrec("owner_name","domain_owner");
	$ownernames = getownernames();


	// $startdate = date("Y-m-d");
	$startdate = $exitdate;
	$end =strtotime($startdate .'-2 years');
	$final = date("Y-m-d",$end);

	$respOwner = "";

	foreach ($ownernames as $ownername) {
		$respOwner .= '(Responsible =='.'"'.$ownername.'"' .') | ';
	}

	$respOwner = trim($respOwner," | ");

	$str = '( (arrival-date > "'.$final.'" ) & (arrival-date < "'.$startdate.'")) & ('.$respOwner.' )  & ((state == "verify-resolution") | (state == "need-setup") | (state == "try-fix") | (state == "need-info") )';

	// echo $str;
	// exit();


	$allprs = ` /usr/local/bin/query-pr --expr '$str' --format '"%s:&:&:%s:&:&:%s:&:&:" Number State Responsible'`;

	$prs = explode("\n", $allprs);

	// if(preg_match('/verify-resolution/', $pr[1]))
	// $verifyres++;
	// if(preg_match('/try-fix/', $pr[1]))
	// $tryfix++;
	// if(preg_match('/need-info/', $pr[1]))
	// $needinfo++;
	// if(preg_match('/need-setup/', $pr[1]))
	// $needsetup++;



	$verifyres = $tryfix = $needinfo = $needsetup =0;

	$final = array();
	$ownerarray = array();
	for($i = 0;$i<count($prs);$i++) {

		$pr = explode(":&:&:", $prs[$i]);
		$p = explode("-",$pr[0]);

		if(empty($pr[0]))  {continue;}

		$uniqpr = $pr[0];

		$resp = $pr[2];
		$state = $pr[1];


		
		$testbedname = gettestbedname($resp);

		array_push($domains, $testbedname);
		array_push($ownerarray,$resp);


		// code for according to the doamin wise
		// if(isset($final[$resp][$testbedname][$state])) 
		// 	$final[$resp][$testbedname][$state]++;
		// else 
		// 	$final[$resp][$testbedname][$state]=1;


		//Code according to the owner wise
		if(isset($final[$resp][$state])) 
			$final[$resp][$state]++;
		else 
			$final[$resp][$state]=1;

		// if(isset($final[$resp][$state."_prs"])) 
		// 	$final[$resp][$state."_prs"] .= $uniqpr." ";
		// else 
		// 	$final[$resp][$state."_prs"] = $uniqpr." ";


		//echo $pr[0]."--".$pr[1] . "--" . $pr[2] . "<br/>";
		// echo $pr[2] . "<br/>";

	}	

		// echo json_encode($final);
		// exit();

		$ownerarray = array_unique($ownerarray);
		$domains = array_unique($domains);

		// echo json_encode($final);


		$category_Array = ["verify-resolution","need-info","need-setup","try-fix"];
		$funArray = selectrec("id,name","function","active=1");
		$catArray = selectrec("id,name","category");
		$rows1 = array();
		// foreach ($funArray as $key => $function) {
		// $fun_key = $key+1;

		// domain wise data to the category_data table
		// foreach ($ownerarray as $key => $ownarr) {

		// 		foreach($domains as $domain){

		// 			$fun_key = singlefield("func_id","domains"," domain_name="."'".$domain."'");

		// 			// echo $fun_key ."<br/>";

		// 				foreach ($category_Array as $catvalue) {
		// 					$res = getID($catvalue,$catArray);
		// 					if($catvalue == 'verify-resolution'){
		// 						$total =  intval($final[$ownarr][$domain]['verify-resolution']);
		// 					}
		// 					else if($catvalue == 'need-info'){
		// 						 $total =  intval($final[$ownarr][$domain]['need-info']);
		// 					}
		// 					else if($catvalue == 'need-setup'){
		// 						$total =  intval($final[$ownarr][$domain]['need-setup']);
		// 					}
		// 					else {
		// 						$total =  intval($final[$ownarr][$domain]['try-fix']);
		// 					}

		// 					if(!$fun_key == "")	{
		// 						$value1 = '('. "'".$exitdate."'".",".$fun_key.","."'".$domain."'".",".$res.",".$total.')';
		// 						$rows1[] = $value1;
		// 					}
							
		// 				}	
		//         }
		// }

		// foreach ($funArray as $key => $function) {
			// $fun_key = $key+1;
	 		foreach($final as $key =>$value) {
	 			foreach ($value as $keyw => $valuew) {
	 				if($keyw == 'verify-resolution')
	 					$cat_id = 34;
	 				else if($keyw == 'need-info')
	 					$cat_id = 35;
	 				else if($keyw == 'need-setup')
	 					$cat_id = 36;
	 				else
	 					$cat_id = 37;

	 				$value1 = '('. "'".$exitdate."'".",".$cat_id.","."'".$key."'".",".intval($valuew).')';
            		$rows1[] = $value1;

	 			}
	 		}
	 	// }	



		

	
	$table = "prs_data";
    $fields ="exit_date,prscat_id,owner,prs_data";

	$value1 = implode(',',$rows1);

	// echo "<br/>".$value1."<br/>";
	// // allinsertrec($table,$fields,$value1);

	$result = singlefield("exit_date","prs_data"," prscat_id in (34,35,36,37) and exit_date="."'".$exitdate."'" );

	if($result != ""){
		$cond = "prscat_id in(34,35,36,37) and exit_date="."'".$exitdate."'";
		$type = "delete";

		if(deleterec($table,$cond,$type)){
			$str1 ='{"status": 1, "message": "Deleted Successfully"}';
			echo $str1;
		}

		if(allinsertrec($table,$fields,$value1)){
			$str1 ='{"status": 1, "message": "Seound Inserted Successfully"}';
			// echo $str1;
		}
	}
	else {

		if(allinsertrec($table,$fields,$value1))
		$str1 ='{"status": 1, "message": " First Inserted Successfully"}';
		
	}




	echo $str1;

// echo json_encode($final);

//echo $verifyres . "--". $tryfix . "--". $needinfo . "--". $needsetup ;    
//echo count($uniqueprs{$uniqpr});

}// EOF getprs



// DEBUGS data from query systestliveDB
function getDebugs($exitdate) {


	$from_date = $exitdate;	
	// $from_date = '2017-01-18';	
	// $from_date = '2017-01-15';	

	$dbh = pg_connect("host=ttbg-pgb-01.juniper.net port=6553 dbname=systest_live_readonly user=table_family_ro password=table_family_ro ");

	if (!$dbh) {
		die("Error in connection ".pg_last_error());
	}

	$rows = array();
	$functionIds = selectrec("id,name","function","active=1");

	foreach ($functionIds as $key => $functionId) {
		if ($functionId[1] == 'CommonEdge'){
			$functionname = 'RPT_Regress_BBE';
			$fun_key = 1;	
		}
		else if ($functionId[1] == 'TPTX'){
			$functionname = 'RPT_Regress_TPTX';
			$fun_key = 2;
		}
		else if ($functionId[1] == 'Services'){
			$functionname = 'RPT_Regress_SERVICES';
			$fun_key = 3;
		}
		else if ($functionId[1] == 'ACX'){
			$functionname = 'RPT_Regress_ACX';
			$fun_key = 4;
		}
		else if ($functionId[1] == 'MMX'){
			$functionname = 'RPT_Regress_MMX';
			$fun_key = 5;
		}
		else if ($functionId[1] == 'KERNL-MNGBLT'){
			$functionname = 'RPT_Regress_KM';
			$fun_key = 6;
		}
		else {
			$functionname = 'RPT_Regress_RPD';
			$fun_key = 7;
		}

	

		// without Date
		// $sql = "SELECT COUNT(debug_exitcode) as total,reg_testbed  as domain,array_to_string(array_agg(distinct result_id),',') as result_id FROM er_debug_exec WHERE display_flag =1 and debug_exitcode = '-' and result_id in (select b.result_id FROM er_regression_result a, er_regression_result b WHERE (a.result_id= b. parent_result_id or a.result_id= b.merge_result_id ) and a.name ~ '".$functionname."'  UNION select c.result_id FROM er_regression_result c WHERE c.name ~ '".$functionname."') group by reg_testbed";

		// with Date 
		$sql = "SELECT COUNT(debug_exitcode) as total,reg_testbed  as domain,array_to_string(array_agg(distinct result_id),',') as result_id FROM er_debug_exec WHERE display_flag =1 and debug_exitcode = '-' and result_id in (select b.result_id FROM er_regression_result a, er_regression_result b WHERE (a.result_id= b. parent_result_id or a.result_id= b.merge_result_id ) and a.name ~ '".$functionname."' and a.added_on >='2017-01-01' UNION select c.result_id FROM er_regression_result c WHERE c.name ~ '".$functionname."' and c.added_on >='2017-01-01' ) group by reg_testbed";
		// $sql = "SELECT COUNT(debug_exitcode) as total,reg_testbed  as domain FROM er_debug_exec WHERE display_flag =1 and debug_exitcode = '-' and result_id in (select b.result_id FROM er_regression_result a, er_regression_result b WHERE (a.result_id= b. parent_result_id or a.result_id= b.merge_result_id ) and a.name ~ 'RPT_Regress_K&M' UNION select c.result_id FROM er_regression_result c WHERE c.name ~ 'RPT_Regress_K&M') group by reg_testbed";

		// echo $sql."<br/>";
		// exit();


		$result = pg_query($dbh,$sql);

		
		// if($result){
			
			while($r = pg_fetch_assoc($result)){        
				$cat_id =  39;
				$total = $r['total'];
				$result_id = $r['result_id'];
				$string = preg_replace('/\([^)]*\)|[()]/', '', $r['domain']);
				$domvalue = preg_replace('/^-/', '', $string);

				$value = '('. "'".$from_date."'".",".$fun_key.","."'".$domvalue."'".",".$cat_id.",".intval($total).","."'".$result_id."'".')';
				$rows[] = $value;
			}


	}

	$value = implode(',', $rows);
			// echo $value ."<br/>";
			// exit();

	$table = "category_data";
	$fields ="exit_date,function_id,domain,category_id,data_total,result_id";

	$result = singlefield("exit_date","category_data"," category_id=39 and function_id in (1,2,3,4,5,6,7) and exit_date="."'".$from_date."'" );

	if($result == ""){
		if(allinsertrec($table,$fields,$value))
		$str ='{"status": 1, "message": "deFirst Inserted Successfully"}';
	}
	else {

		$cond = "category_id =".$cat_id ." and exit_date="."'".$from_date."'";
		$type = "delete";

		if(deleterec($table,$cond,$type)){
			$str ='{"status": 1, "message": "DeDeleted Successfully"}';
			echo $str;
		}

		if(allinsertrec($table,$fields,$value)){
			$str ='{"status": 1, "message": "Desecound Inserted Successfully"}';
		// echo $str;
		}
	}	

    echo $str;

}


function getetrans($exit_date) {

	// $date =  '2016-12-11';
    $bumap = array(35 => "RBU", 5 => "PSD JUNOS SW"); 
    $business_units = array(35,5);
    
    $finalarray = array();

    foreach($business_units as $bu)    {
        $url1="http://inception.juniper.net/etrans/apis/scripts/search.json?query=scripts(business_unit_id=$bu,disabled=false),statuses(name~INTEGRATION_PENDING|REVIEW_COMPLETED|REVIEW_PENDING|PR_PENDING|SCRIPT_FAILURE)&results=scripts,regression_owners,systest_responsibles,regression_responsibles,systest_managers,regression_managers,systest_owners&group=statuses(name)";

        $data1=file_get_contents($url1);
        $arr1 = json_decode($data1);
        $end = $arr1->no_limit_result_count;
        $start = 0;
        $limit = 1000;


        do{
            $url = "http://inception.juniper.net/etrans/apis/scripts/search.json?query=scripts(business_unit_id=$bu,disabled=false),statuses(name~INTEGRATION_PENDING|REVIEW_COMPLETED|REVIEW_PENDING|PR_PENDING|SCRIPT_FAILURE)&results=scripts,regression_owners,systest_responsibles,regression_responsibles,systest_managers,regression_managers,systest_owners&group=statuses(name)&start=$start&limit=$limit";
            $start = $start+1000;
            $data=file_get_contents($url);
            $arr = json_decode($data);

            $grouplen = count($arr->groups);

            
            $final = array();
            $reg_ownrs = $sys_resp = $sysown = $regresp = $sysman = $regman = $sysown = "";
            $reo = "";
            // print $url;
            
            for($group=0;$group<$grouplen;$group++){

                $len=count($arr->groups[$group]->results);

                for($temp=0;$temp<$len;$temp++) {
                // echo $reo;
                $etrans_name = $arr->groups[$group]->group_by_value;
                $result = $arr->groups[$group]->results[$temp];
                $rownrs = $result->regression_owners;
                $sysresp = $result->systest_responsibles;
                $regresp = $result->regression_responsibles;
                $sysman = $result->systest_managers;
                $regman = $result->regression_managers;
                $sysown= $result->systest_owners;



                $id = $result->id;
                $snames = $result->name;
                // count($rownrs);

                for($i=0;$i<count($rownrs);$i++) {
                    $reg_ownrs = $rownrs[$i]->username ." ,";
                }

                for($j=0;$j<count($sysresp);$j++) {
                    $sys_resp = $sysresp[$j]->username ." ,";
                }

				for($k=0;$k<count($regresp);$k++) {
                    $reg_resp = $regresp[$k]->username ." ,";
                }

                for($m=0;$m<count($sysman);$m++) {
                    $sys_man = $sysman[$m]->username ." ,";
                }

                for($n=0;$n<count($regman);$n++) {
                    $reg_man = $regman[$n]->username ." ,";
                }

                for($o=0;$o<count($sysown);$o++) {
                    $sys_own = $sysown[$o]->username ." ,";
                }                



                $ids = $id;
                $regowns = $reg_ownrs;

                $reg_ownrs = rtrim($reg_ownrs, ',');
                $sys_resp = rtrim($sys_resp, ',');
                $reg_resp = rtrim($reg_resp, ',');
                $sys_man = rtrim($sys_man, ',');
                $reg_man = rtrim($reg_man, ',');
                $sys_own = rtrim($sys_own, ',');
                // $sys_resp = rtrim($sys_resp, ',');
                // echo $temp . "--//--" .$etrans_name . "--" .$id ."--" . $reg_ownrs."<br/>";



                if(isset($final[$etrans_name][$reg_ownrs]["count"])){
                	$final[$etrans_name][$reg_ownrs]["count"]++;
                } 
                else{
                    $final[$etrans_name][$reg_ownrs]["count"]=1;
                }

				if(isset($final[$etrans_name][$reg_ownrs]["script_ids"])) 
					$final[$etrans_name][$reg_ownrs]["script_ids"] .= $ids.",";
				else 
					$final[$etrans_name][$reg_ownrs]["script_ids"] = $ids.",";

				if(isset($final[$etrans_name][$reg_ownrs]["scripts"])) 
					$final[$etrans_name][$reg_ownrs]["scripts"] .= $snames.",";
				else 
					$final[$etrans_name][$reg_ownrs]["scripts"] = $snames.",";

				if(isset($final[$etrans_name][$reg_ownrs]["sysresp"])) 
					$final[$etrans_name][$reg_ownrs]["sysresp"] .= $sys_resp.",";
				else 
					$final[$etrans_name][$reg_ownrs]["sysresp"] = $sys_resp.",";

				if(isset($final[$etrans_name][$reg_ownrs]["regresp"])) 
					$final[$etrans_name][$reg_ownrs]["regresp"] .= $reg_resp.",";
				else 
					$final[$etrans_name][$reg_ownrs]["regresp"] = $reg_resp.",";

				if(isset($final[$etrans_name][$reg_ownrs]["sysman"])) 
					$final[$etrans_name][$reg_ownrs]["sysman"] .= $sys_man.",";
				else 
					$final[$etrans_name][$reg_ownrs]["sysman"] = $sys_man.",";

				if(isset($final[$etrans_name][$reg_ownrs]["regman"])) 
					$final[$etrans_name][$reg_ownrs]["regman"] .= $reg_man.",";
				else 
					$final[$etrans_name][$reg_ownrs]["regman"] = $reg_man.",";

				if(isset($final[$etrans_name][$reg_ownrs]["sysown"])) 
					$final[$etrans_name][$reg_ownrs]["sysown"] .= $sys_own.",";
				else 
					$final[$etrans_name][$reg_ownrs]["sysown"] = $sys_own.",";



                }

            }


            

        }while($end > $start);
        array_push($finalarray,$final);
    }

    $json = json_encode($finalarray);
    $rows = array();
    $str = "";
    // echo $json;
    // exit(); 

    // $exit_date = '2017-01-05';

    $decoded = json_decode($json);
    // echo count($decoded);
    $table = "etrans_data";
    $fields ="exit_date,etrans_id,owner,etrans_data,scriptids,scripts,sysresp,regresp,sysman,regman,sysown";
    // $value = implode(',', $rows);


    // INTEGRATION_PENDING
    if(array_key_exists('INTEGRATION_PENDING',$decoded[0])) {
        foreach ($decoded[0]->INTEGRATION_PENDING as $key => $values) {
            $etrans_id = 3;

        	$key = trim($key);
        	$count = $values->count;
        	$scrptIds = rtrim($values->script_ids,',');
        	$scripts = rtrim($values->scripts,',');
        	$sysresp = rtrim($values->sysresp,',');
        	$regresp = rtrim($values->regresp,',');
        	$sysman = rtrim($values->sysman,',');
        	$regman = rtrim($values->regman,',');
        	$sysown = rtrim($values->sysown,',');

            $value = '('. "'".$exit_date."'".",".$etrans_id.","."'".$key."'".",".intval($count).","."'".$scrptIds."'".","."'".$scripts."'".","."'".$sysresp."'".","."'".$regresp."'".","."'".$sysman."'".","."'".$regman."'".","."'".$sysown."'".')';
            $rows[] = $value;
        }
    }
    if(array_key_exists('INTEGRATION_PENDING',$decoded[1])){
         foreach ($decoded[1]->INTEGRATION_PENDING as $key => $values) {
            $etrans_id = 3;
           $key = trim($key);
        	$count = $values->count;
        	$scrptIds = rtrim($values->script_ids,',');
        	$scripts = rtrim($values->scripts,',');
        	$sysresp = rtrim($values->sysresp,',');
        	$regresp = rtrim($values->regresp,',');
        	$sysman = rtrim($values->sysman,',');
        	$regman = rtrim($values->regman,',');
        	$sysown = rtrim($values->sysown,',');

            $value = '('. "'".$exit_date."'".",".$etrans_id.","."'".$key."'".",".intval($count).","."'".$scrptIds."'".","."'".$scripts."'".","."'".$sysresp."'".","."'".$regresp."'".","."'".$sysman."'".","."'".$regman."'".","."'".$sysown."'".')';
            $rows[] = $value;
         }
    }

    // PR_PENDING
    if(array_key_exists('PR_PENDING',$decoded[0])) {
        foreach ($decoded[0]->PR_PENDING as $key => $values) {
            $etrans_id = 1;
           	$key = trim($key);
        	$count = $values->count;
        	$scrptIds = rtrim($values->script_ids,',');
        	$scripts = rtrim($values->scripts,',');
        	$sysresp = rtrim($values->sysresp,',');
        	$regresp = rtrim($values->regresp,',');
        	$sysman = rtrim($values->sysman,',');
        	$regman = rtrim($values->regman,',');
        	$sysown = rtrim($values->sysown,',');

            $value = '('. "'".$exit_date."'".",".$etrans_id.","."'".$key."'".",".intval($count).","."'".$scrptIds."'".","."'".$scripts."'".","."'".$sysresp."'".","."'".$regresp."'".","."'".$sysman."'".","."'".$regman."'".","."'".$sysown."'".')';
            $rows[] = $value;
        }
    }
    if(array_key_exists('PR_PENDING',$decoded[1])){
         foreach ($decoded[1]->PR_PENDING as $key => $values) {
            $etrans_id = 1;
           	$key = trim($key);
        	$count = $values->count;
        	$scrptIds = rtrim($values->script_ids,',');
        	$scripts = rtrim($values->scripts,',');
        	$sysresp = rtrim($values->sysresp,',');
        	$regresp = rtrim($values->regresp,',');
        	$sysman = rtrim($values->sysman,',');
        	$regman = rtrim($values->regman,',');
        	$sysown = rtrim($values->sysown,',');

            $value = '('. "'".$exit_date."'".",".$etrans_id.","."'".$key."'".",".intval($count).","."'".$scrptIds."'".","."'".$scripts."'".","."'".$sysresp."'".","."'".$regresp."'".","."'".$sysman."'".","."'".$regman."'".","."'".$sysown."'".')';
            $rows[] = $value;
         }
    }

    // REVIEW_COMPLETED
    if(array_key_exists('REVIEW_COMPLETED',$decoded[0])) {
        foreach ($decoded[0]->REVIEW_COMPLETED as $key => $values) {
            $etrans_id = 4;
           	$key = trim($key);
        	$count = $values->count;
        	$scrptIds = rtrim($values->script_ids,',');
        	$scripts = rtrim($values->scripts,',');
        	$sysresp = rtrim($values->sysresp,',');
        	$regresp = rtrim($values->regresp,',');
        	$sysman = rtrim($values->sysman,',');
        	$regman = rtrim($values->regman,',');
        	$sysown = rtrim($values->sysown,',');

            $value = '('. "'".$exit_date."'".",".$etrans_id.","."'".$key."'".",".intval($count).","."'".$scrptIds."'".","."'".$scripts."'".","."'".$sysresp."'".","."'".$regresp."'".","."'".$sysman."'".","."'".$regman."'".","."'".$sysown."'".')';
            $rows[] = $value;
        }
    }


    if(array_key_exists('REVIEW_COMPLETED',$decoded[1])){
         foreach ($decoded[1]->REVIEW_COMPLETED as $key => $values) {
            $etrans_id = 4;
           	$key = trim($key);
        	$count = $values->count;
        	$scrptIds = rtrim($values->script_ids,',');
        	$scripts = rtrim($values->scripts,',');
        	$sysresp = rtrim($values->sysresp,',');
        	$regresp = rtrim($values->regresp,',');
        	$sysman = rtrim($values->sysman,',');
        	$regman = rtrim($values->regman,',');
        	$sysown = rtrim($values->sysown,',');

            $value = '('. "'".$exit_date."'".",".$etrans_id.","."'".$key."'".",".intval($count).","."'".$scrptIds."'".","."'".$scripts."'".","."'".$sysresp."'".","."'".$regresp."'".","."'".$sysman."'".","."'".$regman."'".","."'".$sysown."'".')';
            $rows[] = $value;
         }
    }

    
   

    //REVIEW_PENDING
    if(array_key_exists('REVIEW_PENDING',$decoded[0])) {
        foreach ($decoded[0]->REVIEW_PENDING as $key => $values) {
            $etrans_id = 2;
            $key = trim($key);
        	$count = $values->count;
        	$scrptIds = rtrim($values->script_ids,',');
        	$scripts = rtrim($values->scripts,',');
        	$sysresp = rtrim($values->sysresp,',');
        	$regresp = rtrim($values->regresp,',');
        	$sysman = rtrim($values->sysman,',');
        	$regman = rtrim($values->regman,',');
        	$sysown = rtrim($values->sysown,',');

            $value = '('. "'".$exit_date."'".",".$etrans_id.","."'".$key."'".",".intval($count).","."'".$scrptIds."'".","."'".$scripts."'".","."'".$sysresp."'".","."'".$regresp."'".","."'".$sysman."'".","."'".$regman."'".","."'".$sysown."'".')';
            $rows[] = $value;
        }
    }
    if(array_key_exists('REVIEW_PENDING',$decoded[1])){
         foreach ($decoded[1]->REVIEW_PENDING as $key => $values) {
            $etrans_id = 2;
            $key = trim($key);
        	$count = $values->count;
        	$scrptIds = rtrim($values->script_ids,',');
        	$scripts = rtrim($values->scripts,',');
        	$sysresp = rtrim($values->sysresp,',');
        	$regresp = rtrim($values->regresp,',');
        	$sysman = rtrim($values->sysman,',');
        	$regman = rtrim($values->regman,',');
        	$sysown = rtrim($values->sysown,',');

            $value = '('. "'".$exit_date."'".",".$etrans_id.","."'".$key."'".",".intval($count).","."'".$scrptIds."'".","."'".$scripts."'".","."'".$sysresp."'".","."'".$regresp."'".","."'".$sysman."'".","."'".$regman."'".","."'".$sysown."'".')';
            $rows[] = $value;
         }
    }



    //SCRIPT_FAILURE
    if(array_key_exists('SCRIPT_FAILURE',$decoded[0])) {
        foreach ($decoded[0]->SCRIPT_FAILURE as $key => $values) {
            $etrans_id = 5;
            $key = trim($key);
        	$count = $values->count;
        	$scrptIds = rtrim($values->script_ids,',');
        	$scripts = rtrim($values->scripts,',');
        	$sysresp = rtrim($values->sysresp,',');
        	$regresp = rtrim($values->regresp,',');
        	$sysman = rtrim($values->sysman,',');
        	$regman = rtrim($values->regman,',');
        	$sysown = rtrim($values->sysown,',');

            $value = '('. "'".$exit_date."'".",".$etrans_id.","."'".$key."'".",".intval($count).","."'".$scrptIds."'".","."'".$scripts."'".","."'".$sysresp."'".","."'".$regresp."'".","."'".$sysman."'".","."'".$regman."'".","."'".$sysown."'".')';
            $rows[] = $value;
        }
    }
    if(array_key_exists('SCRIPT_FAILURE',$decoded[1])){
         foreach ($decoded[1]->SCRIPT_FAILURE as $key => $values) {
            $etrans_id = 5;
            $key = trim($key);
        	$count = $values->count;
        	$scrptIds = rtrim($values->script_ids,',');
        	$scripts = rtrim($values->scripts,',');
        	$sysresp = rtrim($values->sysresp,',');
        	$regresp = rtrim($values->regresp,',');
        	$sysman = rtrim($values->sysman,',');
        	$regman = rtrim($values->regman,',');
        	$sysown = rtrim($values->sysown,',');

            $value = '('. "'".$exit_date."'".",".$etrans_id.","."'".$key."'".",".intval($count).","."'".$scrptIds."'".","."'".$scripts."'".","."'".$sysresp."'".","."'".$regresp."'".","."'".$sysman."'".","."'".$regman."'".","."'".$sysown."'".')';
            $rows[] = $value;
         }
    }

    // exit();
    
    $value = implode(',', $rows);

    // echo $value;
    // exit();
    // $exit_date = '2017-01-18';
    $result = singlefield("exit_date","etrans_data"," etrans_id in (1,2,3,4,5) and exit_date="."'".$exit_date."'" );

	if($result != ""){
		$cond = "etrans_id in (1,2,3,4,5) and exit_date="."'".$exit_date."'";
		$type = "delete";

		if(deleterec($table,$cond,$type)){
			$str ='{"status": 1, "message": "Deleted Successfully"}';
			echo $str;
		}

		if(allinsertrec($table,$fields,$value)){
			$str ='{"status": 1, "message": "Secound Inserted Successfully"}';
			echo $str;
		}

	}
	else {

		if(allinsertrec($table,$fields,$value))
        	$str ='{"status": 1, "message": " First Inserted Successfully"}';
	}

    echo $str;
}// EOF getetrans

function getID($function_name,$funArray){

   foreach ($funArray as $key => $val) {
       if ($val[1] === $function_name) {
           return $val[0];
       }
   }
   return null;
}

function getdomaindata($xpath,$function){

	$testbedID = getHeaderID($xpath,"Testbed/Domain");
	$trNodes = $xpath->query('//table[@id="myTable"]/tbody/tr[td//text()[contains(.,"'.$function.'")]]');
    $trObjs = $trNodes->length;

    $function_id = singlefield("id","function"," name="."'".$function."'");
    // echo $function_id;

    for ($j=0; $j<$trObjs;$j++) {

        $tds = $trNodes->item($j)->getElementsByTagName('td');
        $table      = "domains";
        $fields     = "domain_name,func_id";
        $value      = "'".$tds->item($testbedID)->nodeValue."'".",".$function_id;

        $col_val    = '`domain_name`="'.$tds->item($testbedID)->nodeValue.'" ';
        $cond   = '`domain_name`="'.$tds->item($testbedID)->nodeValue.'" ';

        $resul = singlefield("id",$table,"domain_name="."'".$tds->item($testbedID)->nodeValue."'" ); 

        if($resul == "")
            insertrec($table,$fields,$value);
        else
            updaterecs($table,$col_val,$cond);

         $domainownername = getdomainownername($tds->item($testbedID)->nodeValue); 

    }
}

function getHeaderID($xpath, $value){
    $header_nodes  = $xpath->query('//table[@id="myTable"]/thead/tr/th');
    $header_count = $header_nodes->length;
        for ($i=0; $i<$header_count;$i++) {
            if($header_nodes->item($i)->nodeValue == $value)
                return $i;
        }
}

function getdomainownername($domainame){

	// chdir(dirname('../inc/'));
	// echo dirname(__FILE__);
    if (($handle = fopen(dirname(__FILE__).'/domain_owner_map.csv', 'r')) === false) {
        die('Error opening file');
    }

    $headers = fgetcsv($handle, 1024, ',');
    $complete = array();

    while ($row = fgetcsv($handle, 1024, ',')) {
        $complete[] = array_combine($headers, $row);
    }

    fclose($handle);

    // $filteredArray = array_filter($complete,'filterArray');
    $filteredArray = array_filter($complete,function($value) use($domainame){
        return ($value["Domain"] == $domainame);
    });

    // print_r($filteredArray);

    foreach($filteredArray as $k=>$v){
        

        // $table      = "domain_owner";
        // $fields     = "owner_name";
        // $value      = "'".$v["Owner"]."'";
        // $col_val    = '`owner_name`="'.$v["Owner"].'" ';
        // $cond   = '`owner_name`="'.$v["Owner"].'" ';

        // $resul = singlefield("id",$table,"owner_name="."'".$v["Owner"]."'" ); 

        // if($resul == "")
        //     insertrec($table,$fields,$value);
        // else
        //     updaterecs($table,$col_val,$cond);

        return $v["Owner"];
    }
}



function getownernames(){

    if (($handle = fopen(dirname(__FILE__).'/domain_owner_map.csv', 'r')) === false) {
        die('Error opening file');
    }

    $headers = fgetcsv($handle, 1024, ',');
    $complete = array();

    while ($row = fgetcsv($handle, 1024, ',')) {
        $complete[] = $row[1];
    }

    return array_unique($complete);

    fclose($handle);

}

function gettestbedname($testbedname){

    if (($handle = fopen(dirname(__FILE__).'/domain_owner_map.csv', 'r')) === false) {
        die('Error opening file');
    }

    $headers = fgetcsv($handle, 1024, ',');
    $complete = array();

    while ($row = fgetcsv($handle, 1024, ',')) {
        $complete[] = array_combine($headers, $row);
    }

    fclose($handle);

    // $filteredArray = array_filter($complete,'filterArray');
    $filteredArray = array_filter($complete,function($value) use($testbedname){
        return ($value["Owner"] == $testbedname);
    });

    // print_r($filteredArray);

    foreach($filteredArray as $k=>$v){
        return $v["Domain"];
    }
}


function getResourceJson(){
	echo '
		{
	    "Ixia":{
	      "title": "IXIA",
	      "link":"//jdiregression.juniper.net/sanitycheck/ixiajson.php"
	    },
	    "Agilent":{
	        "title": "Agilent",
	        "link": "//jdiregression.juniper.net/sanitycheck/agilentjson.php"
	    },
	    "Spirent":{
	        "title": "Spirent",
	        "link": "//jdiregression.juniper.net/sanitycheck/spirentjson.php"
	    },
	    "BSD":{
	        "title": "BSD",
	        "link":"//jdiregression.juniper.net/sanitycheck/bsdjson.php"
	    },
	    "JPG":{
	        "title": "JPG",
	        "link": "//jdiregression.juniper.net/sanitycheck/jpgjson.php"
	    },
	    "Switch":{
	        "title": "Switch",
	        "link": "//jdiregression.juniper.net/sanitycheck/switchesjson.php"
	    },
	    "Routers":{
	        "title": "Routers",
	        "link": "//jdiregression.juniper.net/cms/api/index.php?action=routers"
	    },
	    "Params-status":{
	        "title": "Params-status",
	        "link":"//jdiregression.juniper.net/kvsr/params_status.php"
	    }         
		}
	';
}

// function getResourceJson(){
// 	echo '
// 		{
// 	    "Link-status":{
// 	      "title": "Link-Status",
// 	      "link":"http://rbu-lnx02.englab.juniper.net/tools/finder/genrateReport.php"
// 	    },
// 	    "Params-status":{
// 	        "title": "Params-status",
// 	        "link":"http://rbu.juniper.net/kvsr/params_status.php"
// 	    },
// 	    "Ixia":{
// 	      "title": "IXIA",
// 	      "link":"json/ixiajson.json"
// 	    },
// 	    "Agilent":{
// 	        "title": "Agilent",
// 	        "link": "json/agilent.json"
// 	    },
// 	    "Spirent":{
// 	        "title": "Spirent",
// 	        "link": "json/spirent.json"
// 	    },
// 	    "BSD":{
// 	        "title": "BSD",
// 	        "link":"json/bsd.json"
// 	    },
// 	    "JPG":{
// 	        "title": "JPG",
// 	        "link": "json/jpg.json"
// 	    },
// 	    "Switch":{
// 	        "title": "Switch",
// 	        "link": "json/switch.json"
// 	    }         
// 		}
// 	';
// }

// Temp function created for sprient data to fetch as it was added newly
function Spirent() { 
	$exitArrays = ["UNKNOWN_ERROR","TEST_TIMEOUT","SIG_ABORT","LINK_FAIL","IGP_FAIL","GCOV_ABORT","EXPECT_ERRORS","CONNECT_LOST","CONNECT_FAIL","ABORT","FAIL"];

	$catArrays = ["SPIRENT"];

	// $exit_date = '2017-01-01';

	$jpg = 0;

	foreach($exitArrays as $exitArray) {
		foreach ($catArrays as $catArray) {
			$ch = curl_init('http://rbu.juniper.net/kvsr/data/segregated_issues/'.$exit_date.'_'.$exitArray."_Total_".$catArray);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


			// Execute
			$result = curl_exec($ch);
			if (!curl_errno($ch)) {
				$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				// echo $code;

				if($code == 200){
					$results = explode("\n", $result);
					$exitCodeArray = array();
					$functionArray = array();
					$domainArray = array();

					foreach ($results as $result) {
						$res = explode(",", $result);

						$exitCodeArray[] = $res[0];
						$functionArray[] = $res[1];
						$domainArray[]   = $res[2];
					}


					$domainCount = array_count_values($domainArray);

					$functions = selectrec("id","functions"," active=1");

					$uniquedomains= array_unique($domainArray);
					$rows = array();
					foreach ($uniquedomains as $key => $exitCode) {
						// echo 'http://rbu.juniper.net/kvsr/data/segregated_issues/'.$exit_date.'_'.$exitArray."_Total_".$catArray ."<br/>";
						// echo $exit_date."-".$exitCodeArray[$key]."-".$functionArray[$key]."-".$domainArray[$key]."-".$domainCount[$domainArray[$key]]."<br/>";

						if($exitCodeArray[$key] == 'BSD#SPIRENT' || $exitCodeArray[$key] == 'SPIRENT#UNDEF_SUB')
							$exitCodeArray[$key] = 'SPIRENT';

						$func_id = singlerec("id","function","name="."'".$functionArray[$key]."'");
						$exit_id = singlerec("id","category","name="."'".$exitCodeArray[$key]."'");


						$value = '('. "'".$exit_date."'".",".$func_id[0].","."'".$domainArray[$key]."'".",".$exit_id[0].",".$domainCount[$domainArray[$key]].')';
						$rows[] = $value;

					}


					$table = "category_data";
					$fields ="exit_date,function_id,domain,category_id,data_total";
					$value = implode(',', $rows);

					// echo $value."<br>";

					if(allinsertrec($table,$fields,$value))
					$str ='{"status": 1, "message": "Inserted Successfully"}';

					// echo $str;
					// var_dump($rows);

				} // success code=200	
			} // curl close
			curl_close($ch);


		} // cat arrays
        
	}// exit arrays;

	echo $str;

}// EOF of NextTestBed


// Nida's Code 
// Gives all Functions names from DB
function triggerscript() {

	$device=$_GET['device'];
	$hostname=$_GET['hostname'];

	// if(isset($_GET["user"])) {
	// 	$device="NONE";
	// 	if($_GET["device"]) {
	// 		$device = $_GET["device"];
	// 	}
	// 	$hostname="NONE";
	// 	if($_GET["hostname"]){
	// 		$hostname = $_GET["hostname"];
	// 	}
	// }
	$filename =$device."solo.pl ";
	#$chassis ="$hostname";
	#echo "filename=$filename\n";   
	#echo "check the page for refrehed data\n\n";


		$output = shell_exec("/volume/perl/bin/perl " . $filename.$hostname);
		print $output;
		error_reporting(E_ALL & ~E_NOTICE);
		#echo "check this page http://jdiregression.juniper.net/regression-dashboard-stage/index.php \r\n";
		#echo "out :$filename $hostname\n";
		$data =array();
		$data['result']="Success";
		#echo json_encode($data);

}





