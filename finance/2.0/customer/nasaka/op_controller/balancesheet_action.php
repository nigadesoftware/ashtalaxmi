<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/balancesheet_report.php');
    set_time_limit(0);
    //$fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
    $connection = swapp_connection();
	$balancesheet1 = new balancesheet($connection,260);
    $balancesheet1->design=0;
    $balancesheet1->yearcode = $_SESSION['yearperiodcode'];
    $balancesheet1->yearcodepre = $_SESSION['yearperiodcode']-10001;
    $balancesheet1->fromdate='01-Apr-'.substr($balancesheet1->yearcode,0,4);
    $balancesheet1->todate = $todate;
    $balancesheet1->todatepre='31-Mar-'.substr($balancesheet1->yearcode,4,4);
    
    
    $sql = 'BEGIN profitloss(:p_yearcode, :p_fromdate, :p_todate); END;';
    $result = oci_parse($connection,$sql);
    oci_bind_by_name($result,':p_yearcode',$yrcode,8);
    oci_bind_by_name($result,':p_fromdate',$frdt,11);
    oci_bind_by_name($result,':p_todate',$todt,11);
    $yrcode=$balancesheet1->yearcode;
    $frdt=$balancesheet1->fromdate;
    $todt=$balancesheet1->todate;
    $r=oci_execute($result);
    $q1 = "select *
    from profitandloss t 
    where trunc(t.fromdate)='".$frdt."' 
    and trunc(t.todate)='".$todt."'
    and (t.clprofitcur>0 
    or t.clprofitpre>0)";
    $result = oci_parse($connection, $q1);
    $r = oci_execute($result);
    $srno=1;
    if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
    {
        if ($row['CLPROFITCUR']>0 and $row['CLPROFITPRE']>0)
        {
            $balancesheet1->opprofitcur = $row['OPPROFITCUR'];
            $balancesheet1->oplosscur = $row['OPLOSSCUR'];
            $balancesheet1->profitcur = $row['PROFITCUR'];
            $balancesheet1->losscur = $row['LOSSCUR'];
            $balancesheet1->clprofitcur = $row['CLPROFITCUR'];
            $balancesheet1->cllosscur = $row['CLLOSSCUR'];
            $balancesheet1->opprofitpre = $row['OPPROFITPRE'];
            $balancesheet1->oplosspre = $row['OPLOSSPRE'];
            $balancesheet1->profitpre = $row['PROFITPRE'];
            $balancesheet1->losspre = $row['LOSSPRE'];
            $balancesheet1->clprofitpre = $row['CLPROFITPRE'];
            $balancesheet1->cllosspre = $row['CLLOSSPRE'];
        }
        elseif ($row['CLPROFITCUR']>0)
        {
            $balancesheet1->opprofitcur = $row['OPPROFITCUR'];
            $balancesheet1->oplosscur = $row['OPLOSSCUR'];
            $balancesheet1->profitcur = $row['PROFITCUR'];
            $balancesheet1->losscur = $row['LOSSCUR'];
            $balancesheet1->clprofitcur = $row['CLPROFITCUR'];
            $balancesheet1->cllosscur = $row['CLLOSSCUR'];
            $balancesheet1->clprofitpre = 0;
        }
        elseif ($row['CLPROFITPRE']>0)
        {
            $balancesheet1->opprofitpre = $row['OPPROFITPRE'];
            $balancesheet1->oplosspre = $row['OPLOSSPRE'];
            $balancesheet1->profitpre = $row['PROFITPRE'];
            $balancesheet1->losspre = $row['LOSSPRE'];
            $balancesheet1->clprofitpre = $row['CLPROFITPRE'];
            $balancesheet1->cllosspre = $row['CLLOSSPRE'];
            $balancesheet1->clprofitcur = 0;
        }
    }
    $balancesheet1->newpage(true);
    $balancesheet1->liabilities();
    $balancesheet1->pdf->setpage(1);
    $q1 = "select *
    from profitandloss t 
    where trunc(t.fromdate)='".$frdt."' 
    and trunc(t.todate)='".$todt."'
    and (t.cllosscur>0 
    or t.cllosspre>0)";
    $result = oci_parse($connection, $q1);
    $r = oci_execute($result);
    $srno=1;
    if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
    {
        if ($row['CLLOSSCUR']>0 and $row['CLLOSSPRE']>0)
        {
            $balancesheet1->opprofitcur = $row['OPPROFITCUR'];
            $balancesheet1->oplosscur = $row['OPLOSSCUR'];
            $balancesheet1->profitcur = $row['PROFITCUR'];
            $balancesheet1->losscur = $row['LOSSCUR'];
            $balancesheet1->clprofitcur = $row['CLPROFITCUR'];
            $balancesheet1->cllosscur = $row['CLLOSSCUR'];
            $balancesheet1->opprofitpre = $row['OPPROFITPRE'];
            $balancesheet1->oplosspre = $row['OPLOSSPRE'];
            $balancesheet1->profitpre = $row['PROFITPRE'];
            $balancesheet1->losspre = $row['LOSSPRE'];
            $balancesheet1->clprofitpre = $row['CLPROFITPRE'];
            $balancesheet1->cllosspre = $row['CLLOSSPRE'];
        }
        elseif ($row['CLLOSSCUR']>0)
        {
            $balancesheet1->opprofitcur = $row['OPPROFITCUR'];
            $balancesheet1->oplosscur = $row['OPLOSSCUR'];
            $balancesheet1->profitcur = $row['PROFITCUR'];
            $balancesheet1->losscur = $row['LOSSCUR'];
            $balancesheet1->clprofitcur = $row['CLPROFITCUR'];
            $balancesheet1->cllosscur = $row['CLLOSSCUR'];
            $balancesheet1->cllosspre = 0;
        }
        elseif ($row['CLLOSSPRE']>0)
        {
            $balancesheet1->opprofitpre = $row['OPPROFITPRE'];
            $balancesheet1->oplosspre = $row['OPLOSSPRE'];
            $balancesheet1->profitpre = $row['PROFITPRE'];
            $balancesheet1->losspre = $row['LOSSPRE'];
            $balancesheet1->clprofitpre = $row['CLPROFITPRE'];
            $balancesheet1->cllosspre = $row['CLLOSSPRE'];
            $balancesheet1->cllosscur = 0;
        }
    }
    $balancesheet1->liney=41;
    $balancesheet1->asset();
    $balancesheet1->summary();
    $balancesheet1->pagefooter();
    $balancesheet1->endreport();
?>