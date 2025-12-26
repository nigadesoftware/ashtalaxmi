<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmerunpaid_process.php');
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
    $farmerunpaid1 = new farmerunpaid($connection);
    $farmerunpaid1->billperiodtransnumber = $_POST['billperiodtransnumber'];
    $dt = DateTime::createFromFormat('d/m/Y',$_POST['Date'])->format('d-M-Y');
    $farmerunpaid1->voucherdate = $dt;
    $farmerunpaid1->bankledgercode = $_POST['accountcode'];
    $farmerunpaid1->chequenumber = $_POST['Cheque_Number'];
    $ret=$farmerunpaid1->farmerunpaidprocess();
    if ($ret==true)
    {
        header("Location: ../mis/entitymenu.php");
    }
    else 
    {
        echo 'Farmer Unpaid Not Processed';
        exit;
    }

?>