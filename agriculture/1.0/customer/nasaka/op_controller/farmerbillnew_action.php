<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmerbillnew_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['farmercode']=202;
    $_POST['billperiodtransnumber']=1;
    $_POST['circlecode']=0;
    $_POST['villagecode']=0; */
    $connection = swapp_connection();
    $farmerbill1 = new farmerbill($connection,285,1,7,'Farmer Bill','FBILL_000','A5','L');
  
    $farmerbill1->farmercode = $_POST['farmercode'];
    $farmerbill1->billperiodtransnumber = $_POST['farmercode'];
    $farmerbill1->startreport();
   

?>