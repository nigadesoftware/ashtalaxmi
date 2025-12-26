<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetloginform.php");
    include("../info/ncryptdcrypt.php");
    include_once("../ip_model/accounthead_db_oracle.php");
    include_once("../ip_model/accountsubledger_db_oracle.php");
    require("../info/routine.php");
    //
    /* if (isaccessible(357451254865478)==0)
    {
        echo 'Communication Error';
        exit;
    } */
    if (isset($_POST['accountcode']))
    {
        $accountcode = $_POST['accountcode'];
    }
    elseif (isset($_GET['accountcode']))
    {
        $accountcode = fnDecrypt($_GET['accountcode']);
    }
    if (isset($_POST['subledgercode']))
    {
        $subledgercode = $_POST['subledgercode'];
    }
    elseif (isset($_GET['subledgercode']))
    {
        $subledgercode = fnDecrypt($_GET['subledgercode']);
    }
    $connection = swapp_connection();
    $accountcode_en = fnEncrypt($accountcode);
    $yearopening=0;
    $credittotal=0;
    $debittotal=0;
    $yearclosing=0;
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
        <title>Monthly SubLedger Selection Parameter</title>
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
                    echo '<a href="../mis/entitymenu.php">Entity Menu</br></a><a href="../op_view/subledgerbalancelist_view.php?accountcode='.$accountcode_en.'">SubLedger Balance List View</a><a style="color:#f48" class="navbar" href="../sqlproc/logout.php">Log Out</a>';
                ?>
            </ul>
        </nav>
        <article "w3-container">
            <?php
                echo '<section>';
                echo '<form method="post" action="../op_controller/monthlybook_action.php">';
                //echo '<a href="../mis/entitymenu.php">Entity Menu</a><a style="color:#f48" class="navbar" href="../sqlproc/logout.php">Log Out</a>';
                echo '<table border="0" >';
            ?>
                <table class="table table-bordered table-striped" style="width:800px;float:left;">  
                <!--<CAPTION style="font-family:siddhanta;font-size:18px;text-align:center">वाहनवार पाळीवार गाळप</CAPTION>-->
                <?php
                    $accounthead1 = new accounthead($connection);
                    $accounthead1->accountcode = $accountcode;
                    $accounthead1->fetch();
                    $accountsubledger1 = new accountsubledger($connection);
                    $accountsubledger1->accountcode = $accountcode;
                    $accountsubledger1->subledgercode = $subledgercode;
                    $accountsubledger1->fetch();
                    echo '<thead>'; 
                    echo '<tr style="font-family:siddhanta;font-size:16px">';  
                    echo '<th colspan ="6" style="text-align:left">'.$accounthead1->accountnameuni.' : '.$accountsubledger1->subledgernameuni.'</th>';  
                    echo '</tr>';   
                    echo '</thead>';   
                    echo '<thead>'; 
                    echo '<tr style="font-family:siddhanta;font-size:16px">';  
                    echo '<th style="text-align:left">महिना</th>';  
                    echo '<th style="text-align:right">आरंभीची शिल्लक</th>'; 
                    echo '<th style="text-align:right">जमा</th>'; 
                    echo '<th style="text-align:right">नावे</th>'; 
                    echo '<th style="text-align:right">अखेरची शिल्लक</th>'; 
                    echo '</tr>';   
                    echo '</thead>';   
                    
                    
                    monthlydetail($accountcode,$subledgercode,$accountsubledger1,4,$yearopening,$credittotal,$debittotal,$yearclosing);
                    monthlydetail($accountcode,$subledgercode,$accountsubledger1,5,$yearopening,$credittotal,$debittotal,$yearclosing);
                    monthlydetail($accountcode,$subledgercode,$accountsubledger1,6,$yearopening,$credittotal,$debittotal,$yearclosing);
                    monthlydetail($accountcode,$subledgercode,$accountsubledger1,7,$yearopening,$credittotal,$debittotal,$yearclosing);
                    monthlydetail($accountcode,$subledgercode,$accountsubledger1,8,$yearopening,$credittotal,$debittotal,$yearclosing);
                    monthlydetail($accountcode,$subledgercode,$accountsubledger1,9,$yearopening,$credittotal,$debittotal,$yearclosing);
                    monthlydetail($accountcode,$subledgercode,$accountsubledger1,10,$yearopening,$credittotal,$debittotal,$yearclosing);
                    monthlydetail($accountcode,$subledgercode,$accountsubledger1,11,$yearopening,$credittotal,$debittotal,$yearclosing);
                    monthlydetail($accountcode,$subledgercode,$accountsubledger1,12,$yearopening,$credittotal,$debittotal,$yearclosing);
                    monthlydetail($accountcode,$subledgercode,$accountsubledger1,1,$yearopening,$credittotal,$debittotal,$yearclosing);
                    monthlydetail($accountcode,$subledgercode,$accountsubledger1,2,$yearopening,$credittotal,$debittotal,$yearclosing);
                    monthlydetail($accountcode,$subledgercode,$accountsubledger1,3,$yearopening,$credittotal,$debittotal,$yearclosing);
                    monthlydetail($accountcode,$subledgercode,$accountsubledger1,13,$yearopening,$credittotal,$debittotal,$yearclosing);
                echo '</table>';
                echo '</form>';
            ?>
        </article>
        <footer>
        </footer>
    </body>
</html>
<?php
function monthlydetail($accountcode,$subledgercode,&$accountsubledger1,$monthcode,&$yearopening,&$credittotal,&$debittotal,&$yearclosing)
{
        $accountcode_en=fnEncrypt($accountcode);
        $subledgercode_en=fnEncrypt($subledgercode);
        $monthcode_en=fnEncrypt($monthcode);
        echo '<tr>';
        if ($monthcode==1)
        {
            if ($_SESSION['lng'] == "English")
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">January</a></td>';
            }
            else
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">जानेवारी</a></td>';
            }  
        }
        else if ($monthcode==2)
        {
            if ($_SESSION['lng'] == "English")
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">February</a></td>';
            }
            else
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">फेब्रुवारी</a></td>';
            }  
        }
        else if ($monthcode==3)
        {
            if ($_SESSION['lng'] == "English")
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">March</a></td>';
            }
            else
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">मार्च</a></td>';
            }  
        }
        else if ($monthcode==4)
        {
            if ($_SESSION['lng'] == "English")
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">April</a></td>';
            }
            else
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">एप्रिल</a></td>';
            }  
        }
        else if ($monthcode==5)
        {
            if ($_SESSION['lng'] == "English")
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">May</a></td>';
            }
            else
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">मे</a></td>';
            }  
        }
        else if ($monthcode==6)
        {
            if ($_SESSION['lng'] == "English")
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">June</a></td>';
            }
            else
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">जून</a></td>';
            }  
        }
        else if ($monthcode==7)
        {
            if ($_SESSION['lng'] == "English")
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">July</a></td>';
            }
            else
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">जुलै</a></td>';
            }  
        }
        else if ($monthcode==8)
        {
            if ($_SESSION['lng'] == "English")
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">August</a></td>';
            }
            else
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">ऑगस्ट</a></td>';
            }  
        }
        else if ($monthcode==9)
        {
            if ($_SESSION['lng'] == "English")
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">September</a></td>';
            }
            else
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">सप्टेंबर</a></td>';
            }  
        }
        else if ($monthcode==10)
        {
            if ($_SESSION['lng'] == "English")
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">October</a></td>';
            }
            else
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">ऑक्टोबर</a></td>';
            }  
        }
        else if ($monthcode==11)
        {
            if ($_SESSION['lng'] == "English")
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">November</a></td>';
            }
            else
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">नोव्हेंबर</a></td>';
            }  
        }
        else if ($monthcode==12)
        {
            if ($_SESSION['lng'] == "English")
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">December</a></td>';
            }
            else
            {
                echo '<td style="font-family:siddhanta;font-size:16px"> <a style="color:green;" href="../op_view/dailysubledger_selection.php?accountcode='.$accountcode_en.'&subledgercode='.$subledgercode_en.'&monthcode='.$monthcode_en.'">डिसेंबर</a></td>';
            }  
        }
        if ($monthcode<=12)
        {
            $yearcode=$_SESSION['yearperiodcode'];
            if ($monthcode>=4)
            {
                $year=substr($yearcode,0,4);
            }
            else
            {
                $year=substr($yearcode,4,4);
            }
            $monthstartdate='1/'.$monthcode.'/'.$year;
            $monthstartdate=DateTime::createFromFormat('d/m/Y',$monthstartdate)->format('d-M-Y');
            $maxdays = monthmaxdays($monthcode);
            $monthenddate=$maxdays.'/'.$monthcode.'/'.$year;
            $monthenddate=DateTime::createFromFormat('d/m/Y',$monthenddate)->format('d-M-Y');
            $openingbalance = $accountsubledger1->subopeningbalance($monthstartdate);
            $credit = $accountsubledger1->subbdcredit($monthstartdate,$monthenddate);
            $debit = $accountsubledger1->subbddebit($monthstartdate,$monthenddate);
            $closingbalance = $accountsubledger1->subclosingbalance($monthenddate);
            $credittotal=$credittotal+$credit;
            $debittotal=$debittotal+$debit;
            if ($monthcode==4)
            {
                $yearopening=$openingbalance;
            }
            if ($monthcode==3)
            {
                $yearclosing=$closingbalance;
            }
            echo '<td style="font-family:SakalMarathiNormal922;font-size:16px;text-align:right;">'.moneyFormatIndia(abs($openingbalance)).($openingbalance>=0?' Dr':' Cr').'</td>';
            echo '<td style="font-family:SakalMarathiNormal922;font-size:16px;text-align:right;">'.moneyFormatIndia($credit).'</td>';
            echo '<td style="font-family:SakalMarathiNormal922;font-size:16px;text-align:right;">'.moneyFormatIndia($debit).'</td>';
            echo '<td style="font-family:SakalMarathiNormal922;font-size:16px;text-align:right;">'.moneyFormatIndia(abs($closingbalance)).($closingbalance>=0?' Dr':' Cr').'</td>';
        }
        else
        {
            if ($_SESSION['lng'] == "English")
            {
                echo '<td style="font-family:siddhanta;font-size:16px">Total</td>';
            }
            else
            {
                echo '<td style="font-family:siddhanta;font-size:16px">एकूण</td>';
            }
            echo '<td style="font-family:SakalMarathiNormal922;font-size:16px;text-align:right;">'.moneyFormatIndia(abs($yearopening)).($yearopening>=0?' Dr':' Cr').'</td>';
            echo '<td style="font-family:SakalMarathiNormal922;font-size:16px;text-align:right;">'.moneyFormatIndia($credittotal).'</td>';
            echo '<td style="font-family:SakalMarathiNormal922;font-size:16px;text-align:right;">'.moneyFormatIndia($debittotal).'</td>';
            echo '<td style="font-family:SakalMarathiNormal922;font-size:16px;text-align:right;">'.moneyFormatIndia(abs($yearclosing)).($yearclosing>=0?' Dr':' Cr').'</td>';
        }
        echo '</tr>';
}
?>