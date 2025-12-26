<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/voucher_report.php');
    require_once('../op_model/cheque_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    set_time_limit(0);
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $transactionnumber= $_POST["transactionnumber"];
    $connection = swapp_connection();
    switch ($_POST['btn'])
    {
        case 'Voucher View/Print':
            $voucher1 = new voucher($connection,120,0);
            $voucher1->transactionnumber = $transactionnumber;
            $voucher1->newpage(true);
            $voucher1->detail();
            $voucher1->endreport();
            break;
        case 'Voucher A4 View/Print':
            $voucher1 = new voucher($connection,270,1);
            $voucher1->transactionnumber = $transactionnumber;
            $voucher1->newpage(true);
            $voucher1->detail();
            $voucher1->endreport();
            break;
        case 'Cheque View/Print':
            $cheque1 = new cheque($connection,200,1);
            $cheque1->transactionnumber = $transactionnumber;
            $cheque1->newpage(true);
            $cheque1->detail();
            $cheque1->endreport();
            break;
        case 'Cane Payment Cheque View/Print':
            $cheque1 = new cheque($connection,200,1);
            $cheque1->transactionnumber = $transactionnumber;
            $refbillperiodtrnno=$cheque1->billperiodtransnumber();
            if ($refbillperiodtrnno!=0)
            {
                $query = "select transactionnumber
                from voucherheader v 
                where refbillperiodtrnno=".$refbillperiodtrnno.
                " and vouchersubtypecode=5 order by transactionnumber";
                $result = oci_parse($connection, $query);
                $r = oci_execute($result);
                while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    $cheque1->transactionnumber = $row['TRANSACTIONNUMBER'];
                    $cheque1->newpage(true);
                    $cheque1->detail();
                }
                $cheque1->endreport();
            }
            break;
            case 'Cane Payment Cheque Voucher View/Print':
                $cheque1 = new cheque($connection,200,1);
                $cheque1->transactionnumber = $transactionnumber;
                $refbillperiodtrnno=$cheque1->billperiodtransnumber();
                if ($refbillperiodtrnno!=0)
                {
                    $query = "select transactionnumber
                    from voucherheader v 
                    where refbillperiodtrnno=".$refbillperiodtrnno.
                    " and vouchersubtypecode=5 order by transactionnumber";
                    $result = oci_parse($connection, $query);
                    $r = oci_execute($result);
                    $voucher1 = new voucher($connection,120,0);
                    while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                    {
                        $voucher1->transactionnumber = $row['TRANSACTIONNUMBER'];
                        $voucher1->newpage(true);
                        $voucher1->detail();
                    }
                    $voucher1->endreport();
                }
            break;   
    }
    
?>