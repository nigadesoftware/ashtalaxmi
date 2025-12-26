<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetloginform.php");
    include("../info/ncryptdcrypt.php");
    include_once("../ip_model/accounthead_db_oracle.php");
    include_once("../ip_model/voucherheader_db_oracle.php");
    include_once("../ip_model/accountsubledger_db_oracle.php");
    require("../info/routine.php");
    //
    /* if (isaccessible(357451254865478)==0)
    {
        echo 'Communication Error';
        exit;
    } */
    $accountcode_de = fnDecrypt($_GET['accountcode']); 
    $subledgercode_de = fnDecrypt($_GET['subledgercode']); 
    $monthcode_de = fnDecrypt($_GET['monthcode']);
    $date = fnDecrypt($_GET['date']);
    $date_de= DateTime::createFromFormat('d/m/Y',$date)->format('d-M-Y');
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
                    $subledgercode_en = fnEncrypt($subledgercode_de);
                    $monthcode_en = fnEncrypt($monthcode_de);
                    $date_en = fnEncrypt($date);
                    echo '<a href="../mis/entitymenu.php">Entity Menu</br></a><a href="../op_view/monthlysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'">Monthly SubLedger View</br></a><a href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'&date='.$date_en.'">Daily SubLedger View</a><a style="color:#f48" class="navbar" href="../sqlproc/logout.php">Log Out</a>';
                ?>
            </ul>
        </nav>
        <article "w3-container">
            <?php
                echo '<section>';
                echo '<form method="post" action="../op_controller/daybook_action.php">';
                echo '<table border="0" >';
            ?>
                <table class="table table-bordered table-striped" style="width:800px;float:left;">  
                <!--<CAPTION style="font-family:siddhanta;font-size:18px;text-align:center">वाहनवार पाळीवार गाळप</CAPTION>-->
                <thead>  
                <tr style="font-family:siddhanta;font-size:18px">  
                <?php
                    $accounthead1=new accounthead($connection);
                    $voucherheader1=new voucherheader($connection);
                    $accounthead1->accountcode=$accountcode_de;
                    $accounthead1->fetch();
                    $accountsubledger1 = new accountsubledger($connection);
                    $accountsubledger1->accountcode = $accountcode_de;
                    $accountsubledger1->subledgercode = $subledgercode_de;
                    $accountsubledger1->fetch();
                    $openingbalance=$accountsubledger1->subopeningbalance($date_de);
                    $closingbalance=$accountsubledger1->subclosingbalance($date_de);
                    echo '<thead>';  
                    if ($_SESSION['lng'] == "English")
                        {
                            echo '<tr style="font-family:siddhanta;font-size:18px">'; 
                            echo '<th colspan="6" style="text-align:center">SubAccount '.$accounthead1->accountnameeng.' : '.$accountsubledger1->subledgernameeng.' </th>';
                            echo '</tr>';
                             echo '<tr style="font-family:siddhanta;font-size:18px">'; 
                            echo '<th colspan="6" style="text-align:center">SubLedger Date '.$date.' </th>';        
                            echo '</tr>';
                        }
                    else
                        {         
                            echo '<tr style="font-family:siddhanta;font-size:18px">'; 
                            echo '<th colspan="6" style="text-align:center">सबलेजर '.$accounthead1->accountnameuni.' : '.$accountsubledger1->subledgernameuni.' </th>';
                            echo '</tr>';
                            echo '<tr style="font-family:siddhanta;font-size:16px">'; 
                            echo '<th colspan="6" style="text-align:center">सबलेजर खतावणी दिनांक '.$date.' </th>';
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
                    echo '<th colspan="6" style="text-align:center"></th>'; 
                    echo '</tr>';   
                    echo '</thead>'; 
                    echo '<thead> ';  
                    echo '<tr style="font-family:siddhanta;font-size:16px"> '; 
                    echo '<th style="text-align:left">Voucher Number</th>';  
                    echo '<th style="text-align:left">Description</th>'; 
                    echo '<th colspan="1" style="text-align:right">Credit</th>'; 
                    echo '<th colspan="1" style="text-align:right">Debit</th>'; 
                    echo '<th colspan="1" style="text-align:right">Balance</th>'; 
                    echo '</tr>';   
                    echo '</thead>';  
                }
                else
                {
                    echo '<thead>';   
                    echo '<tr style="font-family:siddhanta;font-size:18px">';   
                    echo '<th colspan="6" style="text-align:center"></th>'; 
                    echo '</tr>';   
                    echo '</thead>'; 
                    echo '<thead>';   
                    echo '<tr style="font-family:siddhanta;font-size:16px"> '; 
                    echo '<th style="text-align:left">व्हा.नंबर</th>';  
                    echo '<th style="text-align:left">तपशिल</th>'; 
                    echo '<th colspan="1" style="text-align:right">जमा</th>'; 
                    echo '<th colspan="1" style="text-align:right">नावे</th>'; 
                    echo '<th colspan="1" style="text-align:right">शिल्लक</th>'; 
                    echo '</tr>'; 
                    echo '</thead>';  
                } 

                    $query ="select h.transactionnumber
                    ,h.vouchernumberprefixsufix
                    ,h.voucherdate
                    ,h.narration
                    ,nvl(d.credit,0) as credit
                    ,nvl(d.debit,0) as debit
                    ,oppaccounthead(h.transactionnumber,d.accountcode) as oppaccountheadnameuni
                    from voucherdetail d,voucherheader h
                    where d.transactionnumber=h.transactionnumber
                    and h.voucherdate='".$date_de."'
                    and h.yearperiodcode=".$yearcode."
                    and h.approvalstatus=9
                    and d.accountcode = ".$accountcode_de." 
                    and d.subledgercode = ".$subledgercode_de;
                    $result = oci_parse($connection, $query);
                    $r = oci_execute($result);
                    $cashtotal = 0;
                    $banktotal = 0;
                    $total = 0;
        
                    echo '<tr>';
                    echo '<td style="font-family:siddhanta;font-size:16px"></td>';
                    if ($_SESSION['lng'] == "English")
                    {
                        echo '<td style="font-family:siddhanta;font-size:16px">Opening Balance</td>';
                    }
                    else
                    {
                        echo '<td style="font-family:siddhanta;font-size:16px">आरंभीची शिल्लक</td>';
                    }
                    echo '<td style="text-align:right"></td>';
                    echo '<td style="text-align:right"></td>';
                    echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($openingbalance)).($openingbalance>=0?'Dr':'Cr').'</td>';
                    echo '</tr>';
                    $closingbalance=$openingbalance;
                    $credittotal=0;
                    $debittotal=0;
                    while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                    {
                        $credittotal = $credittotal+$row['CREDIT'];
                        $debittotal = $debittotal+$row['DEBIT'];
                        $closingbalance=$closingbalance+$row['DEBIT']-$row['CREDIT'];
                        $transactionnumber_en = fnEncrypt( $row['TRANSACTIONNUMBER']);
                        $vouchersubtypecode_en = fnEncrypt($row['VOUCHERSUBTYPECODE']);
                        echo '<tr>';
                        $flag_en = fnEncrypt('Display');
                        if ($row['VOUCHERNUMBERPREFIXSUFIX']=='')
                        {
                            echo '<td style="font-family:siddhanta;font-size:14px;"><a style="color:green;" href="../ip_view/voucherheader.php?transactionnumber='.$transactionnumber_en.'&vouchersubtypecode='.$vouchersubtypecode_en.'&flag='.$flag_en.'">'.$row['TRANSACTIONNUMBER'].'</a></td>';
                        }
                        else
                        {
                            echo '<td style="font-family:siddhanta;font-size:14px;"><a style="color:green;" href="../ip_view/voucherheader.php?transactionnumber='.$transactionnumber_en.'&vouchersubtypecode='.$vouchersubtypecode_en.'&flag='.$flag_en.'">'.$row['VOUCHERNUMBERPREFIXSUFIX'].'</a></td>';
                        }
                        echo '<td style="font-family:siddhanta;font-size:16px">'.$row['OPPACCOUNTHEADNAMEUNI'].'</br>'.$row['NARRATION'].'</td>';
                        echo '<td style="font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($row['CREDIT'])).'</td>';
                        echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($row['DEBIT'])).'</td>';
                        echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($closingbalance)).($closingbalance>=0?'Dr':'Cr').'</td>';
                        echo '</tr>';
                      
                    }
                    echo '<tr>';
                    echo '<td style="font-family:siddhanta;font-size:16px">एकूण</td>';

                    echo '<td style="font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($openingbalance)).($openingbalance>=0?'Dr':'Cr').'</td>';
                    echo '<td style="font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($credittotal)).'</td>';
                    echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($debittotal)).'</td>';
                    echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($closingbalance)).($closingbalance>=0?'Dr':'Cr').'</td>';
                    echo '</tr>';
                echo '</table>';
                echo '</form>';
            ?>
        </article>
        <footer>
        </footer>
    </body>
</html>