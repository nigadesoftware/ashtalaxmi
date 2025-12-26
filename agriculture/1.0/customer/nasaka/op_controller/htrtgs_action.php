<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/htrtgs_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['htcode']=202;
    $_POST['villagecode']=0; */
    //$_POST['billperiodtransnumber']=1;
    //$_POST['bankcode']=0;
    //$_POST['bankbranchcode']=0;
    $connection = swapp_connection();
    $htrtgs1 = new htrtgs($connection,195,1,7,'HT Net Payment','HTRTGS_000','A3','L');
    $htrtgs1->seasoncode = $_SESSION['yearperiodcode'];
    $htrtgs1->billcategorycode = $_POST['billtypecode'];
    $htrtgs1->billperiodnumber = $_POST['HT_Bill_Period_Number'];
    $htrtgs1->flagcode = $_POST['flagcode'];
    if ($_POST['exportcsvfile']=='1')
    {
        $htrtgs1->export($_POST['billperiodtransnumber'],1);
    }
    elseif ($_POST['exportcsvfile']=='2')
    {
        $htrtgs1->export($_POST['billperiodtransnumber'],2);
    }
    elseif ($_POST['exportcsvfile']=='3')
    {
        $htrtgs1->export($_POST['billperiodtransnumber'],3);
    }
    elseif ($_POST['exportcsvfile']=='4')
    {
        $htrtgs1->export($_POST['billperiodtransnumber'],4);
    }
    else
    {
        $htrtgs1->startreport();
        $htrtgs1->endreport();
    }
?>