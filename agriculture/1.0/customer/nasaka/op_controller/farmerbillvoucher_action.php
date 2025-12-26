<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmerbillvoucher_process.php');
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
    $farmerbillvoucher1 = new farmerbillvoucher($connection);
    $farmerbillvoucher1->billperiodtransnumber = $_POST['billperiodtransnumber'];
    if ($_POST['Date'] != '')
    {
        $dt = DateTime::createFromFormat('d/m/Y',$_POST['Date'])->format('d-M-Y');
        $farmerbillvoucher1->voucherdate = $dt;
        $ret=$farmerbillvoucher1->farmerbillvoucherprocess();
    }
    else
    {
        $ret=false;
    }
    
    if ($ret==true)
    {
        header("Location: ../mis/entitymenu.php");
    }
    else {
        echo 'Farmer Bill Voucher Not Processed';
        exit;
    }

?>