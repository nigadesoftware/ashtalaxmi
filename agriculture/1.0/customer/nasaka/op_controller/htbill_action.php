<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/htbill_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['htcode']=202;
    $_POST['billperiodtransnumber']=1;
    $_POST['circlecode']=0;
    $_POST['villagecode']=0; */
    $connection = swapp_connection();
    
    $detail_query_1 = "select billcategorycode from billperiodheader b 
    where billperiodtransnumber=".$_POST['billperiodtransnumber'];
    $detail_result_1 = oci_parse($connection, $detail_query_1);
    $r = oci_execute($detail_result_1);
    if ($detail_row_1 = oci_fetch_array($detail_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
    {
        $billcategorycode = $detail_row_1['BILLCATEGORYCODE'];
    }




    if ($billcategorycode==1)
    {
    $htbill1 = new htbill($connection,265,1,7,'HT Bill','HTBILL_000','A4','P');
    $htbill1->billperiodtransnumber = $_POST['billperiodtransnumber'];
    $htbill1->startreport();
    $htbill1->endreport();
    }
    else
    {
    $htbill1 = new htbill($connection,265,1,7,'HT Bill','HTBILL_000','A5','L');
    $htbill1->billperiodtransnumber = $_POST['billperiodtransnumber'];
    $htbill1->startreport();
    $htbill1->endreport();
    }    
?>