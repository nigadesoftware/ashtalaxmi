<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/htbillvoucher_process.php');
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
    $htbillvoucher1 = new htbillvoucher($connection);
    $htbillvoucher1->seasoncode = $_POST['Season'];
    $htbillvoucher1->billcategorycode = $_POST['billtypecode'];
    $htbillvoucher1->periodnumber = $_POST['HT_Bill_Period_Number'];
    $htbillvoucher1->trothercode = $_POST['flagcode'];
    if ($_POST['Date'] != '')
    {
        $dt = DateTime::createFromFormat('d/m/Y',$_POST['Date'])->format('d-M-Y');
        $htbillvoucher1->voucherdate = $dt;
        $ret=$htbillvoucher1->htbillvoucherprocess();
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
        echo 'HT Bill Voucher Not Processed';
        exit;
    }

?>