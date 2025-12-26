<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/ledger_report.php');
    set_time_limit(0);
    // on the beginning of your script save original memory limit
    //$original_mem = ini_get('memory_limit');
    //$original_exec = ini_get('max_execution_time');
    // then set it to the value you think you need (experiment)
    //ini_set('memory_limit','640M');
    // at the end of the script set it to it's original value 
    // (if you forget this PHP will do it for you when performing garbage collection)
    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
    $accountcode = $_POST["accountcode"];
    $connection = swapp_connection();
    try
    {
        $ledger1 = new ledger($connection,290);
        $ledger1->fromdate = $fromdate;
        $ledger1->todate = $todate;
        $ledger1->accountcode = $accountcode;
        $ledger1->yearcode = $_SESSION['yearperiodcode'];
        $ledger1->design=0;
        if ($_POST['exportcsvfile']==1)
        {
            $ledger1->export();
        }
        else
        {
            $ledger1->newpage(true);
            $ledger1->detail();
            $ledger1->endreport();
        }
        
        exit;
        //ini_set('memory_limit',$original_mem);
    }
    catch(Exception $e) 
    {
        echo 'Message: ' .$e->getMessage();
    }

?>