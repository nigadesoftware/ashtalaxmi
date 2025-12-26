<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmerrtgs_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['farmercode']=202;
    $_POST['villagecode']=0; */
    //$_POST['billperiodtransnumber']=1;
    //$_POST['bankcode']=0;
    //$_POST['bankbranchcode']=0;
    $connection = swapp_connection();
    $farmerrtgs1 = new farmerrtgs($connection,195,1,7,'Farmer Net Payment','FRRTGS_000','A3','L');
    $circlecode=$_POST['circlecode'];
    if ($_POST['exportcsvfile']=='1')
    {
        $farmerrtgs1->export($_POST['billperiodtransnumber'],1,$circlecode);
    }
    elseif ($_POST['exportcsvfile']=='2')
    {
        $farmerrtgs1->export($_POST['billperiodtransnumber'],3,$circlecode);
    }
    elseif ($_POST['exportcsvfile']=='3')
    {
        $farmerrtgs1->export($_POST['billperiodtransnumber'],2,$circlecode);
    }
    elseif ($_POST['exportcsvfile']=='4')
    {
        $farmerrtgs1->export($_POST['billperiodtransnumber'],4,$circlecode);
    }
    else
    {
        $farmerrtgs1->billperiodtransnumber = $_POST['billperiodtransnumber'];
        $farmerrtgs1->startreport();
        $farmerrtgs1->endreport();
    
    }
?>