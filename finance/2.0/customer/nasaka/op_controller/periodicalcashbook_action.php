<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/periodicalcashbook_report.php');
    set_time_limit(0);
    //require_once("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');

    $cdt =  DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('Y-m-d');   
    $tdt =  DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('Y-m-d');   
    $connection = swapp_connection();
	$periodicalcashbook1 = new periodicalcashbook($connection,250);
    $dt = $fromdate;
    $cashs = $todate-$dt;
    $ccash=0;
    $db=0;
	while ($cdt<=$tdt)
	{
        $periodicalcashbook1->cashbookdate = date('d-M-Y',strtotime($dt));
        if ($periodicalcashbook1->iscashbankexists()!=0)
        {
            $pages = $periodicalcashbook1->pdf->getNumPages();
            $periodicalcashbook1->firstpage = ++$pages;
            if ($pages==1)
            {
                $periodicalcashbook1->currentpage = 0;
            }
            else
            {
                $periodicalcashbook1->currentpage = $periodicalcashbook1->firstpage-1;
            }
            
            $periodicalcashbook1->newpage(true);
            $periodicalcashbook1->creditdetail();
            $periodicalcashbook1->debitdetail();
            $db++;
        }
		$dt = date('d-M-Y',strtotime($dt.' + 1 days'));
        $cdt = date('Y-m-d',strtotime($cdt.' + 1 days'));
        $ccash++;
    }
    if ($db!=0)
    {
        $periodicalcashbook1->endreport();
    }
    else
    {
        echo 'No Transaction for the date';
    }
?>