<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/periodicaldaybook_report.php');
    set_time_limit(0);
    //require_once("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');

        
    $connection = swapp_connection();
	$periodicaldaybook1 = new periodicaldaybook($connection,260);
    $dt = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('Y-m-d');
    $tdt = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('Y-m-d');
    $days = $todate-$dt;
    $cday=0;
    $db=0;
	while ($dt<=$tdt)
	{
        $periodicaldaybook1->daybookdate = date('d-M-Y',strtotime($dt));
        if ($periodicaldaybook1->iscashbankexists()!=0)
        {
            $pages = $periodicaldaybook1->pdf->getNumPages();
            $periodicaldaybook1->firstpage = ++$pages;
            if ($pages==1)
            {
                $periodicaldaybook1->currentpage = 0;
            }
            else
            {
                $periodicaldaybook1->currentpage = $periodicaldaybook1->firstpage-1;
            }
            
            $periodicaldaybook1->newpage(true);
            $periodicaldaybook1->creditdetail();
            $periodicaldaybook1->debitdetail();
            $db++;
        }
		$dt = date('Y-m-d',strtotime($dt.' + 1 days'));
        $cday++;
    }
    if ($db!=0)
    {
        $periodicaldaybook1->endreport();
    }
    else
    {
        echo 'No Transaction for the date';
    }
?>