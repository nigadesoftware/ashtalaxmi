<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/nonmovingitem_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $nonmoveing1 = new nonmoveing($connection,290);
    $nonmoveing1->fromdate =$_POST['From_Date'];
    $nonmoveing1->todate = $_POST['To_Date'];
    if ($nonmoveing1->fromdate!='' and $nonmoveing1->todate!='')
    {
        $nonmoveing1->fromdate = DateTime::createFromFormat('Y-m-d',$nonmoveing1->fromdate)->format('d-M-Y');
        $nonmoveing1->todate = DateTime::createFromFormat('Y-m-d',$nonmoveing1->todate)->format('d-M-Y');

    }
    $nonmoveing1->newpage(true);
    $nonmoveing1->startreport();
    $nonmoveing1->endreport();
?>