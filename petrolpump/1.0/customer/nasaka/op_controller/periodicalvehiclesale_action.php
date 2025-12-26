<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/periodicalvehiclesale_report.php');
    if (isaccessible(451278369852145)==0 and isaccessible(357451254865478)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-y');
    $customercode = $_POST["customercode"];
    require("../info/phpsqlajax_dbinfo.php");
    $connection = petrolpump_connection();
	$periodicalvehiclesale1 = new periodicalvehiclesale($connection,280);
    $periodicalvehiclesale1->customercode=$customercode;
    $periodicalvehiclesale1->fromdate=$fromdate;
    $periodicalvehiclesale1->todate=$todate;
    
    $periodicalvehiclesale1->newpage(true);
    $periodicalvehiclesale1->detail();
    $periodicalvehiclesale1->endreport();
?>