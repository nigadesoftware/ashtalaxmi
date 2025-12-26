<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/dieselvouchertransfer_process.php');
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
    $dieselvouchertransferprocess1 = new dieselvouchertransferprocess($connection);
    $dieselvouchertransferprocess1->seasoncode = $_POST['Season'];
    if ($_POST['To_Date'] != '')
    {
        $dt = DateTime::createFromFormat('d/m/Y',$_POST['To_Date'])->format('d-M-Y');
        $dieselvouchertransferprocess1->todate = $dt;
    }
    if ($_POST['From_Date'] != '')
    {
        $dt = DateTime::createFromFormat('d/m/Y',$_POST['From_Date'])->format('d-M-Y');
        $dieselvouchertransferprocess1->fromdate = $dt;
    }
    if ($_POST['Date'] != '')
    {
        $dt = DateTime::createFromFormat('d/m/Y',$_POST['Date'])->format('d-M-Y');
        $dieselvouchertransferprocess1->claimdate = $dt;
    }
    $ret=$dieselvouchertransferprocess1->dieselvouchertransferprocess();
    if ($ret==true)
    {
        header("Location: ../mis/entitymenu.php");
    }
    else {
        echo 'Diesel Claim Transfer Not Processed';
        exit;
    }

?>