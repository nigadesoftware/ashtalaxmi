<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/periodicalcreditsalesum_report.php');
    if (isaccessible(451278369852145)==0 and isaccessible(357451254865478)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
    
    require("../info/phpsqlajax_dbinfo.php");
    
    switch ($_POST['btn'])
	{
        case 'रिपोर्ट (Report)':
            $connection = petrolpump_connection();
            $periodicalcreditsalesum1 = new periodicalcreditsalesum($connection,280);
            $periodicalcreditsalesum1->fromdate=$fromdate;
            $periodicalcreditsalesum1->todate=$todate;
            
            $periodicalcreditsalesum1->newpage(true);
            $periodicalcreditsalesum1->detail();
            $periodicalcreditsalesum1->endreport();
            break;
        case 'Data Transfer':
            $connection = sugarht_connection();
            $query = 'BEGIN transporterdiesel(:pfromdate, :ptodate); END;';
            $result = oci_parse($connection,$query);
            //  Bind the input parameter
            oci_bind_by_name($result,':pfromdate',$fromdate,-1);
            // Bind the output parameter
            oci_bind_by_name($result,':ptodate',$todate,-1);
            if (oci_execute($result))
            {
                echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Data is transfered successfully</span></br>';
            }
            else
            {
                echo '<span class="w3-container" style="background-color:#a00;color:#ff8;text-align:left;">Data is not transfered</span></br>';
            }
            echo '<li><a class="navbar" href="../ip_view/entitymenu.php">Entity Menu</a><br/>';
            break;
    }    
?>