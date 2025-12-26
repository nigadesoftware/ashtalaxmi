<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/circlevillageletterregister_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $circlevillagesugarregister1 = new circlevillagesugarregister($connection,290);
    $circlevillagesugarregister1->circlecode = $_POST['circlecode'];
    $circlevillagesugarregister1->letterdate = $_POST['Date'];
    if ($_POST['exportcsvfile']==1)
    {
        $circlevillagesugarregister1->shares=0;
    }
    else
    {
        $circlevillagesugarregister1->shares=1;
    }
    //$circlevillagesugarregister1->newpage(true);
    $circlevillagesugarregister1->detail();
    $circlevillagesugarregister1->endreport();
?>