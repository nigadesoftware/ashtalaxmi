<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetloginview.php");
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
    $bookcategorycode_de = fnDecrypt($_GET['bookcategorycode']); 
    $monthcode_de = fnDecrypt($_GET['monthcode']);
    $date_de = fnDecrypt($_GET['date']);    
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
        <!--<nav "w3-container">
            <ul class="navbar">
                <li><a class="navbar" href="../mis/entitymenu.php">Entity Menu</a>
                <?php
                    echo '<li><a style="color:#f48" class="navbar" href="../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>-->
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
                 if ($_SESSION['lng'] == "English")
                    {
                        echo '<th colspan="6" style="text-align:center">Daybook Date '.$date_de.' </th>';
                    }
                 else
                    {
                        echo '<th colspan="6" style="text-align:center">रोजकिर्द दिनांक '.$date_de.' </th>';
                    }
                ?>
                </tr>  
                </thead>
                 <?php
                if ($_SESSION['lng'] == "English")
                {
                    echo '<thead>';  
                    echo '<tr style="font-family:siddhanta;font-size:18px">';   
                    echo '<th colspan="6" style="text-align:center">Credit</th>'; 
                    echo '</tr>';   
                    echo '</thead>'; 
                    echo '<thead> ';  
                    echo '<tr style="font-family:siddhanta;font-size:16px"> '; 
                    echo '<th style="text-align:left">Voucher Number</th>';  
                    echo '<th style="text-align:left">Code</th>'; 
                    echo '<th style="text-align:left">Description</th>'; 
                    echo '<th colspan="1" style="text-align:right">Cash</th>'; 
                    echo '<th colspan="1" style="text-align:right">Bank</th>'; 
                    echo '<th colspan="1" style="text-align:right">Total</th>'; 
                    echo '</tr>';   
                    echo '</thead>';  
                }
                else
                {
                    echo '<thead>';   
                    echo '<tr style="font-family:siddhanta;font-size:18px">';   
                    echo '<th colspan="6" style="text-align:center">जमा</th>'; 
                    echo '</tr>';   
                    echo '</thead>'; 
                    echo '<thead>';   
                    echo '<tr style="font-family:siddhanta;font-size:16px"> '; 
                    echo '<th style="text-align:left">व्हा.नंबर</th>';  
                    echo '<th style="text-align:left">कोड</th>'; 
                    echo '<th style="text-align:left">तपशिल</th>'; 
                    echo '<th colspan="1" style="text-align:right">रोख</th>'; 
                    echo '<th colspan="1" style="text-align:right">बँक</th>'; 
                    echo '<th colspan="1" style="text-align:right">एकूण</th>'; 
                    echo '</tr>'; 
                    echo '</thead>';  
                } 
                    $date = DateTime::createFromFormat('d/m/Y',$date_de)->format('d-M-Y');
                    $query = "select *
                    from vw_daybook_credit_account_sum where 
                    voucherdate = '".$date."'";
                    $result = oci_parse($connection, $query);
                    $r = oci_execute($result);
                    $cashtotal = 0;
                    $banktotal = 0;
                    $total = 0;
                    $accounthead1=new accounthead($connection);
                    $voucherheader1=new voucherheader($connection);
                    $accounthead1->accountcode=$accounthead1->cashaccountcode();
                    $accounthead1->fetch();
                    $cashopening=$accounthead1->openingbalance($date);
                    $cashclosing=$accounthead1->closingbalance($date);
                    echo '<tr>';
                    echo '<td style="font-family:siddhanta;font-size:16px"></td>';
                    echo '<td style="font-family:siddhanta;font-size:16px"></td>';
                    if ($_SESSION['lng'] == "English")
                    {
                        echo '<td style="font-family:siddhanta;font-size:16px">Opening Cash Balance</td>';
                    }
                    else
                    {
                        echo '<td style="font-family:siddhanta;font-size:16px">आरंभीची रोख शिल्लक</td>';
                    }
                    echo '<td style="text-align:right">'.number_format((float) $cashopening,2,'.','').'</td>';
                    echo '<td style="text-align:right">'.number_format((float) 0,2,'.','').'</td>';
                    echo '<td style="text-align:right">'.number_format((float) $cashopening,2,'.','').'</td>';
                    echo '</tr>';

                    while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                    {
                        echo '<tr>';
                        echo '<td style="font-family:siddhanta;font-size:16px"></td>';
                        echo '<td style="font-family:siddhanta;font-size:16px">'.$row['ACCOUNTCODE'].'</td>';
                        echo '<td style="font-family:siddhanta;font-size:16px">'.$row['ACCOUNTNAMEUNI'].'</td>';
                        echo '<td style="text-align:right">'.number_format((float) $row['CASH'],2,'.','').'</td>';
                        echo '<td style="text-align:right">'.number_format((float) $row['BANK'],2,'.','').'</td>';
                        echo '<td style="text-align:right">'.number_format((float) $row['TOTAL'],2,'.','').'</td>';
                        echo '</tr>';
                        $cashtotal = $cashtotal+$row['CASH'];
                        $banktotal = $banktotal+$row['BANK'];
                        $total = $total+$row['TOTAL'];
                        $query1 = "select *
                        from vw_daybook_credit_detail where 
                        voucherdate = '".$date."' and accountcode=".$row['ACCOUNTCODE'];
                        $result1 = oci_parse($connection, $query1);
                        $r1 = oci_execute($result1);
                        $ptno=0;
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            if ($ptno==0)
                            {
                                $i=oci_num_rows($result1);
                            }
                            $i--;
                            if (($ptno==$row1['TRANSACTIONNUMBER'] or $ptno==0) and $i>=1)
                            {
                                $narration='';
                                $ptno=$row1['TRANSACTIONNUMBER'];
                            }
                            elseif ($ptno==0 and $i>=1)
                            {
                                $narration='';
                                $ptno=$row1['TRANSACTIONNUMBER'];
                            }
                            else
                            {
                                if ($ptno==0)
                                {
                                    $voucherheader1->transactionnumber = $row1['TRANSACTIONNUMBER'];
                                }
                                else
                                {
                                    $voucherheader1->transactionnumber = $ptno;
                                }
                                $voucherheader1->fetch();
                                $narration=$voucherheader1->narration;
                                $ptno=$row1['TRANSACTIONNUMBER'];
                            }
                            echo '<tr>';
                            $transactionnumber_en = fnEncrypt( $row1['TRANSACTIONNUMBER']);
                            $vouchersubtypecode_en = fnEncrypt($row1['VOUCHERSUBTYPECODE']);
                            $flag_en = fnEncrypt('Display');
                            if ($row1['VOUCHERNUMBERPREFIXSUFIX']=='')
                            {
                                echo '<td style="font-family:siddhanta;font-size:14px"><a href="../ip_view/voucherheader.php?transactionnumber='.$transactionnumber_en.'&vouchersubtypecode='.$vouchersubtypecode_en.'&flag='.$flag_en.'">'.$row1['TRANSACTIONNUMBER'].'</a></td>';
                            }
                            else
                            {
                                echo '<td style="font-family:siddhanta;font-size:14px"><a href="../ip_view/voucherheader.php?transactionnumber='.$transactionnumber_en.'&vouchersubtypecode='.$vouchersubtypecode_en.'&flag='.$flag_en.'">'.$row1['VOUCHERNUMBERPREFIXSUFIX'].'</a></td>';
                            }
                            echo '<td style="font-family:siddhanta;font-size:14px">'.$row1['SUBLEDGERCODE'].'</td>';
                            echo '<td style="font-family:siddhanta;font-size:14px">'.$row1['SUBLEDGERNAMEUNI'].' हस्ते-'.$row1['DESCRIPTION'].'</td>';
                            echo '<td style="font-family:siddhanta;font-size:14px;text-align:right">'.number_format((float) $row1['CASH'],2,'.','').'</td>';
                            echo '<td style="font-family:siddhanta;font-size:14px;text-align:right">'.number_format((float) $row1['BANK'],2,'.','').'</td>';
                            echo '<td style="font-family:siddhanta;font-size:14px;text-align:right">'.number_format((float) $row1['TOTAL'],2,'.','').'</td>';
                            echo '</tr>';
                            if ($narration != '')
                            {
                                echo '<tr>';
                                echo '<td style="font-family:siddhanta;font-size:14px"></td>';
                                echo '<td style="font-family:siddhanta;font-size:14px"></td>';
                                echo '<td style="font-family:siddhanta;font-size:14px">'.$narration.'</td>';
                                echo '<td style="font-family:siddhanta;font-size:14px;text-align:right"></td>';
                                echo '<td style="font-family:siddhanta;font-size:14px;text-align:right"></td>';
                                echo '<td style="font-family:siddhanta;font-size:14px;text-align:right"></td>';
                                echo '</tr>';
                            }
                        }
                    }
                    echo '<tr>';
                    echo '<td style="font-family:siddhanta;font-size:16px"></td>';
                    echo '<td style="font-family:siddhanta;font-size:16px"></td>';
                    echo '<td style="font-family:siddhanta;font-size:16px">एकूण जमा</td>';
                    echo '<td style="text-align:right">'.number_format((float) $cashtotal,2,'.','').'</td>';
                    echo '<td style="text-align:right">'.number_format((float) $banktotal,2,'.','').'</td>';
                    echo '<td style="text-align:right">'.number_format((float) $total,2,'.','').'</td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td style="font-family:siddhanta;font-size:16px"></td>';
                    echo '<td style="font-family:siddhanta;font-size:16px"></td>';
                    if ($_SESSION['lng'] == "English")
                    {
                        echo '<td style="font-family:siddhanta;font-size:16px">Opening Cash Balance</td>';
                    }
                    else
                    {
                        echo '<td style="font-family:siddhanta;font-size:16px">आरंभीची रोख शिल्लक</td>';
                    }
                    echo '<td style="text-align:right">'.number_format((float) $cashopening,2,'.','').'</td>';
                    echo '<td style="text-align:right">'.number_format((float) 0,2,'.','').'</td>';
                    echo '<td style="text-align:right">'.number_format((float) $cashopening+$total,2,'.','').'</td>';
                    echo '</tr>';
                echo '</table>';
                ?>
                <table class="table table-bordered table-striped" style="width:800px;float:left;">  
                <?php
                if ($_SESSION['lng'] == "English")
                {
                    echo '<thead>';  
                    echo '<tr style="font-family:siddhanta;font-size:18px">';   
                    echo '<th colspan="6" style="text-align:center">Debit</th>'; 
                    echo '</tr>';   
                    echo '</thead>'; 
                    echo '<thead> ';  
                    echo '<tr style="font-family:siddhanta;font-size:16px"> '; 
                    echo '<th style="text-align:left">Voucher Number</th>';  
                    echo '<th style="text-align:left">Code</th>'; 
                    echo '<th style="text-align:left">Description</th>'; 
                    echo '<th colspan="1" style="text-align:right">Cash</th>'; 
                    echo '<th colspan="1" style="text-align:right">Bank</th>'; 
                    echo '<th colspan="1" style="text-align:right">Total</th>'; 
                    echo '</tr>';   
                    echo '</thead>';  
                }
                else
                {
                    echo '<thead>';   
                    echo '<tr style="font-family:siddhanta;font-size:18px">';   
                    echo '<th colspan="6" style="text-align:center">नावे</th>'; 
                    echo '</tr>';   
                    echo '</thead>'; 
                    echo '<thead>';   
                    echo '<tr style="font-family:siddhanta;font-size:16px"> '; 
                    echo '<th style="text-align:left">व्हा.नंबर</th>';  
                    echo '<th style="text-align:left">कोड</th>'; 
                    echo '<th style="text-align:left">तपशिल</th>'; 
                    echo '<th colspan="1" style="text-align:right">रोख</th>'; 
                    echo '<th colspan="1" style="text-align:right">बँक</th>'; 
                    echo '<th colspan="1" style="text-align:right">एकूण</th>'; 
                    echo '</tr>'; 
                    echo '</thead>';  
                }  
                    $date = DateTime::createFromFormat('d/m/Y',$date_de)->format('d-M-y');
                    $query = "select *
                    from vw_daybook_debit_account_sum where 
                    voucherdate = '".$date."'";
                    $result = oci_parse($connection, $query);
                    $r = oci_execute($result);
                    $cashtotal = 0;
                    $banktotal = 0;
                    $total = 0;
                    while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                    {
                        echo '<tr>';
                        echo '<td style="font-family:siddhanta;font-size:16px"></td>';
                        echo '<td style="font-family:siddhanta;font-size:16px">'.$row['ACCOUNTCODE'].'</td>';
                        echo '<td style="font-family:siddhanta;font-size:16px">'.$row['ACCOUNTNAMEUNI'].'</td>';
                        echo '<td style="text-align:right">'.number_format((float) $row['CASH'],2,'.','').'</td>';
                        echo '<td style="text-align:right">'.number_format((float) $row['BANK'],2,'.','').'</td>';
                        echo '<td style="text-align:right">'.number_format((float) $row['TOTAL'],2,'.','').'</td>';
                        echo '</tr>';
                        $cashtotal = $cashtotal+$row['CASH'];
                        $banktotal = $banktotal+$row['BANK'];
                        $total = $total+$row['TOTAL'];
                        $query1 = "select *
                        from vw_daybook_debit_detail where 
                        voucherdate = '".$date."' and accountcode=".$row['ACCOUNTCODE'];
                        $result1 = oci_parse($connection, $query1);
                        $r1 = oci_execute($result1);
                        $ptno=0;
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            if ($ptno==0)
                            {
                                $i=oci_num_rows($result1);
                            }
                            $i--;
                            if (($ptno==$row1['TRANSACTIONNUMBER'] or $ptno==0) and $i>=1)
                            {
                                $narration='';
                                $ptno=$row1['TRANSACTIONNUMBER'];
                            }
                            elseif ($ptno==0 and $i>=1)
                            {
                                $narration='';
                                $ptno=$row1['TRANSACTIONNUMBER'];
                            }
                            else
                            {
                                if ($ptno==0)
                                {
                                    $voucherheader1->transactionnumber = $row1['TRANSACTIONNUMBER'];
                                }
                                else
                                {
                                    $voucherheader1->transactionnumber = $ptno;
                                }
                                $voucherheader1->fetch();
                                $narration=$voucherheader1->narration;
                                $ptno=$row1['TRANSACTIONNUMBER'];
                            }
                            echo '<tr>';
                            $transactionnumber_en = fnEncrypt( $row1['TRANSACTIONNUMBER']);
                            $vouchersubtypecode_en = fnEncrypt($row1['VOUCHERSUBTYPECODE']);
                            $flag_en = fnEncrypt('Display');
                            if ($row1['VOUCHERNUMBERPREFIXSUFIX']=='')
                            {
                                echo '<td style="font-family:siddhanta;font-size:14px"><a href="../ip_view/voucherheader.php?transactionnumber='.$transactionnumber_en.'&vouchersubtypecode='.$vouchersubtypecode_en.'&flag='.$flag_en.'">'.$row1['TRANSACTIONNUMBER'].'</a></td>';
                            }
                            else
                            {
                                echo '<td style="font-family:siddhanta;font-size:14px"><a href="../ip_view/voucherheader.php?transactionnumber='.$transactionnumber_en.'&vouchersubtypecode='.$vouchersubtypecode_en.'&flag='.$flag_en.'">'.$row1['VOUCHERNUMBERPREFIXSUFIX'].'</a></td>';
                            }
                            echo '<td style="font-family:siddhanta;font-size:14px">'.$row1['SUBLEDGERCODE'].'</td>';
                            echo '<td style="font-family:siddhanta;font-size:14px">'.$row1['SUBLEDGERNAMEUNI'].' हस्ते-'.$row1['DESCRIPTION'].'</td>';
                            echo '<td style="font-family:siddhanta;font-size:14px;text-align:right">'.number_format((float) $row1['CASH'],2,'.','').'</td>';
                            echo '<td style="font-family:siddhanta;font-size:14px;text-align:right">'.number_format((float) $row1['BANK'],2,'.','').'</td>';
                            echo '<td style="font-family:siddhanta;font-size:14px;text-align:right">'.number_format((float) $row1['TOTAL'],2,'.','').'</td>';
                            echo '</tr>';
                            if ($narration != '')
                            {
                                echo '<tr>';
                                echo '<td style="font-family:siddhanta;font-size:14px"></td>';
                                echo '<td style="font-family:siddhanta;font-size:14px"></td>';
                                echo '<td style="font-family:siddhanta;font-size:14px">'.$narration.'</td>';
                                echo '<td style="font-family:siddhanta;font-size:14px;text-align:right"></td>';
                                echo '<td style="font-family:siddhanta;font-size:14px;text-align:right"></td>';
                                echo '<td style="font-family:siddhanta;font-size:14px;text-align:right"></td>';
                                echo '</tr>';
                            }
                        }
                    
                    }
                    echo '<tr>';
                    echo '<td style="font-family:siddhanta;font-size:16px"></td>';
                    echo '<td style="font-family:siddhanta;font-size:16px"></td>';
                    echo '<td style="font-family:siddhanta;font-size:16px">एकूण नावे</td>';
                    echo '<td style="text-align:right">'.number_format((float) $cashtotal,2,'.','').'</td>';
                    echo '<td style="text-align:right">'.number_format((float) $banktotal,2,'.','').'</td>';
                    echo '<td style="text-align:right">'.number_format((float) $total,2,'.','').'</td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td style="font-family:siddhanta;font-size:16px"></td>';
                    echo '<td style="font-family:siddhanta;font-size:16px"></td>';
                    if ($_SESSION['lng'] == "English")
                    {
                        echo '<td style="font-family:siddhanta;font-size:16px">Closing Cash Balance</td>';
                    }
                    else
                    {
                        echo '<td style="font-family:siddhanta;font-size:16px">अखेरची रोख शिल्लक</td>';
                    }
                    echo '<td style="text-align:right">'.number_format((float) $cashclosing,2,'.','').'</td>';
                    echo '<td style="text-align:right">'.number_format((float) 0,2,'.','').'</td>';
                    echo '<td style="text-align:right">'.number_format((float) $cashclosing+$total,2,'.','').'</td>';
                    echo '</tr>';
                echo '</table>';
                echo '</form>';
            ?>
        </article>
        <footer>
        </footer>
    </body>
</html>