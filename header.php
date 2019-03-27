<?php
/*if( $_SERVER['SERVER_NAME'] != 'localhost' ){
  require_once('/var/www/html/simplesamlphp-1.14.9/lib/_autoload.php');
  $as = new SimpleSAML_Auth_Simple('default-sp');
  if (!$as->isAuthenticated()) {
    $as->requireAuth();
  }
  $attributes = $as->getAttributes();
  $uid = '';
  if (isset($attributes['uid'][0])) {
   $uid = $attributes['uid'][0];
 }
 if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$_SESSION['user'] = $uid;
$userid = $uid;


$dbh = pg_connect("host=eabu-systest-db.juniper.net dbname=dashboard user=postgres password=postgres");
$result=pg_query($dbh,"select ismanager from heirarchy where report='$userid'");
while($row=pg_fetch_array($result))
	{
		$ismanager = $row['ismanager'];

	}
}*/
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang=""> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>PARAMS STATUS FUSION</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.container {
    width: 1395px !important;
}

.container {
    padding-right: 1px !important;
    padding-left: 1px !important;
    margin-right: auto !important;
    margin-left: auto !important;
}

.ag-theme-balham .ag-header {
    color: rgba(10, 10, 10, 0.95) !important;
    background-color: #ffffff !important;
    border-bottom: 1px solid #000 !important;
}

.ag-theme-balham .ag-header-cell-label .ag-header-cell-text {
    overflow: visible !important;
    text-overflow: clip !important;
    white-space: normal !important;
    word-break: break-word !important; 
}
.ag-theme-balham .ag-header-cell, .ag-theme-balham .ag-header-group-cell {
    line-height: 15px !important;
    padding-left: 3px !important;
    padding-right: 3px !important;

}


  <style>
.label-grey{
  background: #d3d3d3;
  color:#000 !important;
}
.label-orange{
  background: #ffa500;
}
.label-red{
  background: #fe6665;
}
.label-green{
  background: #006400;
}
.label-peach{
background: #f08080;
}
.label-peachpuff{
background: #ffdab9;

body {
  text-align: center;
  background-color: #222;
  color: #fff;
  margin-top: 50px;
  .dropdown-menu {
    border-radius: 0;
  }
  .multiselect-native-select {
    position: relative;
    select {
      border: 0 !important;
      clip: rect(0 0 0 0) !important;
      height: 1px !important;
      margin: -1px -1px -1px -3px !important;
      overflow: hidden !important;
      padding: 0 !important;
      position: absolute !important;
      width: 1px !important;
      left: 50%;
      top: 30px;
    }
  }
  .multiselect-container {
    position: absolute;
    list-style-type: none;
    margin: 0;
    padding: 0;
    .input-group {
      margin: 5px;
    }
    li {
      padding: 0;
      .multiselect-all {
        label {
          font-weight: 700;
        }
      }
      a {
        padding: 0;
        label {
          margin: 0;
          height: 100%;
          cursor: pointer;
          font-weight: 400;
          padding: 3px 20px 3px 40px;
          input[type=checkbox] {
            margin-bottom: 5px;
          }
        }
        label.radio {
          margin: 0;
        }
        label.checkbox {
          margin: 0;
        }
      }
    }
    li.multiselect-group {
      label {
        margin: 0;
        padding: 3px 20px 3px 20px;
        height: 100%;
        font-weight: 700;
      }
    }
    li.multiselect-group-clickable {
      label {
        cursor: pointer;
      }
    }
  }
  .btn-group {
    .btn-group {
        .multiselect.btn {
          border-top-left-radius: 4px;
          border-bottom-left-radius: 4px;
        }
    }
  }
  .form-inline {
    .multiselect-container {
      label.checkbox {
        padding: 3px 20px 3px 40px;
      }
      label.radio {
        padding: 3px 20px 3px 40px;
      }
      li {
        a {
          label.checkbox {
            input[type=checkbox] {
              margin-left: -20px;
              margin-right: 0;
            }
          }
          label.radio {
            input[type=radio] {
              margin-left: -20px;
              margin-right: 0;
            }
          }
        }
      }
    }
  }
  .btn {
    border-radius: 0;
    padding: 10px 0;
  }
  .btn-primary {
    background-color: #ff0000;
    border: none;
    border-radius: 0;
    padding: 11px 15px;
    text-transform: uppercase;
  }
}



</style>


</style>

  <link href='//fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css' />
  <link rel="shortcut icon" href="new-presentation.png" type="image/x-icon" />
  <link rel="stylesheet" href="css/common.css" type="text/css" />
  <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="css/main.css?<?= filemtime('css/main.css') ?>" type="text/css" />
  <link rel="stylesheet" href="css/ag-grid.css" type="text/css" />
  <!link rel="stylesheet" href="css/datepicker/bootstrap-datetimepicker.css" type="text/css" />
  <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="css/datepicker/daterangepicker.css" type="text/css" />
	<link rel="stylesheet" href="css/styles.css?<?= filemtime('css/styles.css') ?>" type="text/css" />
 <?php echo '<script> var gUid = "'.$userid.'";</script>'; ?>

</head>
<body ng-app="app" class="hp100">
 <script type="text/javascript">
   uid = '<?php echo $uid; ?>';
   isManager = '<?php echo $ismanager; ?>';   
 </script>
 <div id="wrapper" class="hp100">
  <!-- Page Content -->
  <div id="page-content-wrapper" class="hp100">
    <nav class="navbar navbar-default" id="header">
      <div class="container-fluid" style="text-align: center;margin-top: 5px;">
        <div class="pull-left headertitle">Params-Status</div>
        <span class="pull-right title-right">
          <span class="user-image-wrap">
           <!-- <img src="https://orgchart.juniper.net/OPE/EmployeePhotos/<?php #echo ($userid); ?>.JPG" class="use
            r-image" alt="User Image" style="width: 100%";>
          </span>
          <span class="uname"><?php #echo ($userid); ?></span>-->
        </span>
      </div>
    </nav>

