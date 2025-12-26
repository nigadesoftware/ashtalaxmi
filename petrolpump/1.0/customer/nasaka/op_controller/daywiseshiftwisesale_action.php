<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/daywiseshiftwisesale_report.php');
    if (isaccessible(451278369852145)==0 and isaccessible(357451254865478)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $petrolpumpcode = $_POST["petrolpumpcode"];
    $pumpcode = $_POST["pumpcode"];
    $shiftcode = $_POST["shiftcode"];
    $cashcreditcode = $_POST["cashcreditcode"];
    $customertypecode = $_POST["customertypecode"];
    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-y');
    //$todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-y');
    
    require("../info/phpsqlajax_dbinfo.php");
    $connection = petrolpump_connection();
	$daywisehsiftwise1 = new daywiseshiftwise($connection,275);
    $daywisehsiftwise1->petrolpumpcode = $petrolpumpcode;
    $daywisehsiftwise1->pumpcode = $pumpcode;
    $daywisehsiftwise1->shiftcode = $shiftcode;
    $daywisehsiftwise1->cashcreditcode = $cashcreditcode;
    $daywisehsiftwise1->customertypecode = $customertypecode;
    $daywisehsiftwise1->fromdate=$fromdate;
    $daywisehsiftwise1->todate=$fromdate;
    
    $daywisehsiftwise1->newpage(true);
    $daywisehsiftwise1->detail();
    $daywisehsiftwise1->endreport();
?>