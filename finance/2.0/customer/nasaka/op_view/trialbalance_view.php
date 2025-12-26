<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetloginform.php");
    include("../info/ncryptdcrypt.php");
    include_once("../ip_model/accounthead_db_oracle.php");
    include_once("../ip_model/voucherheader_db_oracle.php");
    require("../info/routine.php");
    //
    if (isaccessible(357451254865478)==0)
    {
        echo 'Communication Error';
        exit;
    }
    if (isset($_POST['fromdate']))
    {
        $fromdate = DateTime::createFromFormat('d/m/Y',$_POST['fromdate'])->format('d-M-Y');
        $fromdate_en = fnEncrypt($_POST['fromdate']);
    }
    else
    {
        $fromdate ='01-Apr-'.substr($_SESSION['yearperiodcode'],0,4);
        $fromdate_en = fnEncrypt($_POST['fromdate']);
    }
    if (isset($_POST['todate']))
    {
        $todate = DateTime::createFromFormat('d/m/Y',$_POST['todate'])->format('d-M-Y');
        $todate_en = fnEncrypt($_POST['todate']);
    }
    else
    {
        $todate = '31-Mar-'.substr($_SESSION['yearperiodcode'],4,4);
        $todate_en = fnEncrypt($_POST['todate']);
    }
    $yearcode = $_SESSION['yearperiodcode'];
    $connection = swapp_connection();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/bootstrap/3.3.0/css/bootstrap.min.css">
        <script type="text/javascript" charset="utf8" src="../js/ajax/jQuery/jquery-2.0.3.js"></script>
        <script type="text/javascript" src="../js/bootstrap/3.3.5/js/bootstrap.min.js"></script>
       <style type="text/css">
            th
            {
                background-color: #fff;
                color: #000;
            }
            tr:nth-child(even) {background: #FFF}
            tr:nth-child(odd) {background: #FFF}
            table
            {   
                float :left;
                margin-left:10px;
            }
            caption
            {
                background-color:#844;
                color:#fdd;
            }    
        </style>


        <link rel="stylesheet" href="../css/w3.css">
        <title>Daybook Selection Parameter</title>
        <style type="text/css">
            body
            {
                background-color: #fff;
            }
            header
            {
                background-color: #fff;
                min-height: 38px;
                color: #070;
                font-family: Arial;
                font-size: 19px;
            }
            nav
            {
                width: 300px;
                float: left;
                list-style-type: none;
                font-family: verdana;
                font-size: 15px;
                color: #f48;
                line-height: 30px;
            }
            a
            {
                color: #f48;
            }
            article
            {
                background-color: #fff;
                display: table;
                margin-left: 0px;
                padding-left: 10px;
                font-family: Verdana;
                font-size: 15px;
            }
            section
            {
                margin-left: 0px;
                margin-right: 15px;
                float: left;
                text-align: justify;
                color: #000;
                line-height: 23px;
            }
            footer
            {
                float: bottom;
                color: #000;
                font-family: verdana;
                font-size: 12px;
            }
            div
            {
                float:left;
            }
            input, textarea
            {
                outline: none;
            }
            button
            {
                width:200px;
                height:35px;
                color:#000;
                border-radius: 5px;
            }
            input:focus, textarea:focus
            {
                border-radius: 5px;
                outline: none;
            }
            label
            {
                color: #000;
            }
        </style>

        <script src="../css/ui/1.11.4/themes/smoothness/jquery-ui.css">
         </script>
         <script>
            $(document).ready(function(){
             setInterval(function(){cache_clear()},360000);
             });
             function cache_clear()
            {
             window.location.reload(true);
            }
        </script>
    </head>
    <body>
        <nav "w3-container">
            <ul >
                <?php
                    $accountcode_en = fnEncrypt($accountcode_de);
                    $monthcode_en = fnEncrypt($monthcode_de);
                    $date_en = fnEncrypt($date);
                    echo '<a href="../mis/entitymenu.php">Entity Menu</br></a><a style="color:#f48" class="navbar" href="../sqlproc/logout.php">Log Out</a>';
                ?>
            </ul>
        </nav>
        <article "w3-container">
            <?php
                echo '<section>';
                echo '<form method="post" action="">';
                echo '<table border="0" >';
            ?>
                <table class="table table-bordered table-striped" style="width:800px;float:left;">  
                <!--<CAPTION style="font-family:siddhanta;font-size:18px;text-align:center">वाहनवार पाळीवार गाळप</CAPTION>-->
                <thead>  
                <tr style="font-family:siddhanta;font-size:18px">  
                <?php
                    echo '<thead>';  
                    if ($_SESSION['lng'] == "English")
                    {
                        echo '<tr style="font-family:siddhanta;font-size:18px">'; 
                        echo '<th colspan="8" style="text-align:center">Trial Balance</th>';
                        echo '</tr>';
                        echo '<tr style="font-family:siddhanta;font-size:18px">'; 
                        echo '<th colspan="8" style="text-align:center">From Date '.$fromdate.' To '.$todate.'</th>';
                        echo '</tr>';
                    }
                    else
                    {         
                        echo '<tr style="font-family:siddhanta;font-size:18px">'; 
                        echo '<th colspan="8" style="text-align:center">तेरीज पत्रक</th>';
                        echo '</tr>';
                        echo '<tr style="font-family:siddhanta;font-size:16px">'; 
                        echo '<th colspan="8" style="text-align:center">दिनांक '.$fromdate.' पासून '.$todate.' पर्यंत</th>';
                        echo '</tr>';
                    }
                    echo '<thead>';
                ?>
                </tr>  
                </thead>
                 <?php
                if ($_SESSION['lng'] == "English")
                {
                    echo '<thead>';   
                    echo '<tr style="font-family:siddhanta;font-size:18px">';   
                    echo '<th colspan="8" style="text-align:center"></th>'; 
                    echo '</tr>';   
                    echo '</thead>'; 
                    echo '<thead>';   
                    echo '<tr style="font-family:siddhanta;font-size:16px"> '; 
                    echo '<th colspan="2" style="text-align:center">Account Particulars</th>';  
                    echo '<th colspan="2" style="text-align:center">Opening Balance</th>'; 
                    echo '<th colspan="2" style="text-align:center">Periodical Transaction</th>'; 
                    echo '<th colspan="2" style="text-align:center">Closing Balance</th>'; 
                    echo '</tr>'; 
                    echo '</thead>';
                    echo '<thead>';   
                    echo '<tr style="font-family:siddhanta;font-size:16px">';
                    echo '<th style="text-align:left">Code</th>';  
                    echo '<th style="text-align:left">Account Head</th>';  
                    echo '<th style="text-align:right">Credit</th>'; 
                    echo '<th style="text-align:right">Debit</th>'; 
                    echo '<th style="text-align:right">Credit</th>'; 
                    echo '<th style="text-align:right">Debit</th>'; 
                    echo '<th style="text-align:right">Credit</th>'; 
                    echo '<th style="text-align:right">Debit</th>';
                    echo '</tr>'; 
                    echo '</thead>';    
                }
                else
                {
                    echo '<thead>';   
                    echo '<tr style="font-family:siddhanta;font-size:18px">';   
                    echo '<th colspan="8" style="text-align:center"></th>'; 
                    echo '</tr>';   
                    echo '</thead>'; 
                    echo '<thead>';   
                    echo '<tr style="font-family:siddhanta;font-size:16px"> '; 
                    echo '<th colspan="2" style="text-align:center">खाते तपशील</th>';  
                    echo '<th colspan="2" style="text-align:center">आरंभीची शिल्लक</th>'; 
                    echo '<th colspan="2" style="text-align:center">कालावधीतील</th>'; 
                    echo '<th colspan="2" style="text-align:center">अखेरची शिल्लक</th>'; 
                    echo '</tr>'; 
                    echo '</thead>';
                    echo '<thead>';   
                    echo '<tr style="font-family:siddhanta;font-size:16px"> '; 
                    echo '<th style="text-align:left">कोड</th>';  
                    echo '<th style="text-align:left">खाते</th>';  
                    echo '<th style="text-align:right">जमा</th>'; 
                    echo '<th style="text-align:right">नावे</th>'; 
                    echo '<th style="text-align:right">जमा</th>'; 
                    echo '<th style="text-align:right">नावे</th>'; 
                    echo '<th style="text-align:right">जमा</th>'; 
                    echo '<th style="text-align:right">नावे</th>'; 
                    echo '</tr>'; 
                    echo '</thead>';    
                } 

                $query = "
                select accountcode,accountnameuni,accountnameeng,
                case when openingbalance<0 then abs(openingbalance) else 0 end openingbalance_cr
                ,case when openingbalance>0 then openingbalance else 0 end openingbalance_dr
                ,credit
                ,debit
                ,case when closingbalance<0 then abs(closingbalance) else 0 end closingbalance_cr
                ,case when closingbalance>0 then closingbalance else 0 end closingbalance_dr 
                from(
                select t.accountcode,h.accountnameuni,h.accountnameeng,sum(openingbalance) as openingbalance,sum(credit) as credit,sum(debit) as debit,sum(closingbalance) as closingbalance
                from (
                select accountcode,nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) as openingbalance,0 as credit,0 as debit,0 as closingbalance
                from (
                select a.accountcode,nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
                from accountopening a
                where a.yearperiodcode=".$yearcode." 
                union all
                select d.accountcode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
                from voucherheader t,voucherdetail d
                where t.transactionnumber=d.transactionnumber 
                and t.yearperiodcode=".$yearcode." 
                and t.voucherdate<'".$fromdate."'
                and t.approvalstatus=9
                group by d.accountcode)
                group by accountcode
                union all
                select d.accountcode,0 as openingbalance,nvl(sum(d.credit),0) as credit,0 as debit,0 as closingbalance
                from voucherheader t,voucherdetail d
                where t.transactionnumber=d.transactionnumber 
                and t.yearperiodcode=".$yearcode." and t.voucherdate>='".$fromdate."' and t.voucherdate<='".$todate."'
                and t.approvalstatus=9 and nvl(d.credit,0)>0
                group by accountcode
                union all
                select d.accountcode,0 as openingbalance,0 as credit,nvl(sum(d.debit),0) as debit,0 as closingbalance
                from voucherheader t,voucherdetail d
                where t.transactionnumber=d.transactionnumber 
                and t.yearperiodcode=".$yearcode." and t.voucherdate>='".$fromdate."' and t.voucherdate<='".$todate."'
                and t.approvalstatus=9 and nvl(d.debit,0)>0
                group by accountcode
                union all      
                select accountcode,0 as openingbalance,0 as credit,0 as debit,nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) as closingbalance
                from 
                (
                select a.accountcode,nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
                from accountopening a
                where a.yearperiodcode=".$yearcode." 
                union all
                select d.accountcode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
                from voucherheader t,voucherdetail d
                where t.transactionnumber=d.transactionnumber 
                and t.yearperiodcode=".$yearcode." 
                and t.voucherdate<='".$todate."'
                and t.approvalstatus=9
                group by d.accountcode
                )
                group by accountcode)t,accounthead h
                where t.accountcode=h.accountcode
                group by t.accountcode,h.accountnameuni,h.accountnameeng)
                order by accountcode";
                $result = oci_parse($connection, $query);
                $r = oci_execute($result);
                $openingbalance_cr=0; 
                $openingbalance_dr=0;
                $credit=0;
                $debit=0;
                $closingbalance_cr=0;
                $closingbalance_dr=0;
                while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    if ($row['OPENINGBALANCE_CR']==0 and $row['OPENINGBALANCE_DR']==0 and $row['DEBIT']==0 and $row['CREDIT']==0 and $row['CLOSINGBALANCE_CR']==0 and $row['CLOSINGBALANCE_DR']==0)
                    {
                        $a=0;
                    }
                    else
                    {
                        $accountcode_en = fnEncrypt($row['ACCOUNTCODE']);
                        echo '<tr>';
                        echo '<td style="font-family:siddhanta;font-size:14px;"><a style="color:green;" href="../op_view/monthlyledger_selection.php?accountcode='.$accountcode_en.'">'.$row['ACCOUNTCODE'].'</a></td>';
                        if ($_SESSION['lng'] == "English")
                        {
                            echo '<td style="font-family:siddhanta;font-size:14px;">'.$row['ACCOUNTNAMEENG'].'</td>';
                        }
                        else
                        {
                            echo '<td style="font-family:siddhanta;font-size:14px;">'.$row['ACCOUNTNAMEUNI'].'</td>';
                        }
                        echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($row['OPENINGBALANCE_CR'])).'</td>';
                        echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(($row['OPENINGBALANCE_DR'])).'</td>';
                        echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($row['CREDIT'])).'</td>';
                        echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(($row['DEBIT'])).'</td>';
                        echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($row['CLOSINGBALANCE_CR'])).'</td>';
                        echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(($row['CLOSINGBALANCE_DR'])).'</td>';
                        echo '</tr>';
                        $openingbalance_cr=$openingbalance_cr+$row['OPENINGBALANCE_CR'];
                        $openingbalance_dr=$openingbalance_dr+$row['OPENINGBALANCE_DR'];
                        $credit=$credit+$row['CREDIT'];
                        $debit=$debit+$row['DEBIT'];
                        $closingbalance_cr=$closingbalance_cr+$row['CLOSINGBALANCE_CR'];
                        $closingbalance_dr=$closingbalance_dr+$row['CLOSINGBALANCE_DR'];
                    }
                }
                echo '<tr>';
                echo '<td colspan="2" style="font-family:siddhanta;font-size:16px">एकूण</td>';
                echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($openingbalance_cr)).'</td>';
                echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(($openingbalance_dr)).'</td>';
                echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($credit)).'</td>';
                echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(($debit)).'</td>';
                echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($closingbalance_cr)).'</td>';
                echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(($closingbalance_dr)).'</td>';
                echo '</tr>';
                echo '</table>';
                echo '</form>';
            ?>
        </article>
        <footer>
        </footer>
    </body>
</html>