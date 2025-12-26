<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/caneseedvoucher_process.php');
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
    $caneseedvoucher1 = new caneseedvoucher($connection);
    $caneseedvoucher1->fromdate = $_POST['From_Date'];
    $caneseedvoucher1->todate = $_POST['To_Date'];
    $caneseedvoucher1->voucherdate = $_POST['Date'];
    if ($_POST['From_Date'] != '' and $_POST['To_Date'] != '' and $_POST['Date'] != '')
    {
        $fdt = DateTime::createFromFormat('d/m/Y',$_POST['From_Date'])->format('d-M-Y');
        $caneseedvoucher1->fromdate = $fdt;
        $tdt = DateTime::createFromFormat('d/m/Y',$_POST['To_Date'])->format('d-M-Y');
        $caneseedvoucher1->todate = $tdt;
        $dt = DateTime::createFromFormat('d/m/Y',$_POST['Date'])->format('d-M-Y');
        $caneseedvoucher1->voucherdate = $dt;
        $ret=$caneseedvoucher1->caneseedvoucherprocess();
        if ($ret==true)
        {
            header("Location: ../mis/entitymenu.php");
        }
        else 
        {
            echo 'Cane seed voucher Not Processed';
            exit;
        }
    }
?>