<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/htbillcontractor_report.php');
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
        $htbillcontractor1 = new htbillcontractor($connection,260,1,7,'HT Bill Contractor','HTBILLCON_000','A4','P');
        $htbillcontractor1->billperiodtransnumber = $_POST['billperiodtransnumber'];
        $htbillcontractor1->startreport();
        $htbillcontractor1->endreport();
    }
    else
    {
        $htbillcontractor1 = new htbillcontractor($connection,260,1,7,'HT Bill Contractor','HTBILLCON_000','A5','L');
        $htbillcontractor1->billperiodtransnumber = $_POST['billperiodtransnumber'];
        $htbillcontractor1->startreport();
        $htbillcontractor1->endreport();
    }
?>