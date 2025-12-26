<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/pandl_report.php');
    set_time_limit(0);
    //$fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
    $connection = swapp_connection();
	$pandl1 = new pandl($connection,260);
    $pandl1->design=0;
    $pandl1->fromdatecur = $fromdate;
    $pandl1->todatecur = $todate;
    $pandl1->fromdatepre = date('d-M-Y', strtotime("-1 years", strtotime($fromdate)));
    $pandl1->todatepre = date('d-M-Y', strtotime("-1 years", strtotime($todate)));
    $pandl1->yearcodecur = $_SESSION['yearperiodcode'];
    $pandl1->yearcodepre = $_SESSION['yearperiodcode']-10001;
    
    $sql = 'BEGIN profitloss(:p_yearcode, :p_fromdate, :p_todate); END;';
    $result = oci_parse($connection,$sql);
    oci_bind_by_name($result,':p_yearcode',$yrcode,8);
    oci_bind_by_name($result,':p_fromdate',$frdt,11);
    oci_bind_by_name($result,':p_todate',$todt,11);
    $yrcode=$pandl1->yearcodecur;
    $frdt=$pandl1->fromdatecur;
    $todt=$pandl1->todatecur;
    $r=oci_execute($result);
    $q1 = "select nvl(t.profitcur,0) as profitcur
    ,abs(nvl(t.losscur ,0)) as losscur
    ,nvl(t.profitpre,0) as profitpre
    ,abs(nvl(t.losspre ,0)) as losspre
    from profitandloss t 
    where trunc(t.fromdate)='".$frdt."' and trunc(t.todate)='".$todt."'";
    $result = oci_parse($connection, $q1);
    $r = oci_execute($result);
    $srno=1;
    if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
    {
        $pandl1->profitcur = $row['PROFITCUR'];
        $pandl1->losscur = $row['LOSSCUR'];
        $pandl1->profitpre = $row['PROFITPRE'];
        $pandl1->losspre = $row['LOSSPRE'];
    }
    else
    {
        $pandl1->profitcur = 0;
        $pandl1->losscur =0;
        $pandl1->profitpre = 0;
        $pandl1->losspre =0;
    }

    $pandl1->newpage(true);
    $pandl1->expenses();
    //$pandl1->pdf->setpage(1);
    //$pandl1->liney=48;
    $pandl1->income();
    $pandl1->summary();
    $pandl1->pagefooter();
    $pandl1->endreport();

?>