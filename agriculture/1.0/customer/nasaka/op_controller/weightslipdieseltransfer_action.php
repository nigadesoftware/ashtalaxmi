<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/weightslipdieseltransfer_process.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['farmercode']=202;
    $_POST['paysheetperiodtransnumber']=1;
    $_POST['circlecode']=0;
    $_POST['villagecode']=0; */
    $connection = swapp_connection();
    $weightslipdieseltransferprocess1 = new weightslipdieseltransferprocess($connection);
    $weightslipdieseltransferprocess1->pumpcode = 1;
    if ($_POST['From_Date'] != '')
    {
        $dt = DateTime::createFromFormat('d/m/Y',$_POST['From_Date'])->format('d-M-Y');
        $weightslipdieseltransferprocess1->fromdate = $dt;
    }
    if ($_POST['To_Date'] != '')
    {
        $dt = DateTime::createFromFormat('d/m/Y',$_POST['To_Date'])->format('d-M-Y');
        $weightslipdieseltransferprocess1->todate = $dt;
    }
    $ret=$weightslipdieseltransferprocess1->weightslipdieseltransferprocess();
    if ($ret==true)
    {
        header("Location: ../mis/entitymenu.php");
    }
    else {
        echo 'Diesel Claim Transfer Not Processed';
        exit;
    }

?>