<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmerbill_process.php');
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
    $farmerbillprocess1 = new billprocess($connection);
    $farmerbillprocess1->billperiodtransnumber = $_POST['billperiodtransnumber'];
    if ($_POST["isunpostdata"]==1)
        $ret=$farmerbillprocess1->unpostdata();
    else
        $ret=$farmerbillprocess1->farmerbillprocess();
    if ($ret==true)
    {
        header("Location: ../mis/entitymenu.php");
    }
    else {
        echo 'Farmer Bill Not Processed';
        exit;
    }

?>