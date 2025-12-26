<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetloginview.php");
    include("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    include("../ip_model/accounthead_db_oracle.php");
    //
    if (isaccessible(357451254865478)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $bookcategorycode_de = fnDecrypt($_GET['bookcategorycode']); 
    $monthcode_de = fnDecrypt($_GET['monthcode']);
    $yearcode = $_SESSION['yearperiodcode'];
    if ($monthcode_de>=4)
    {
        $year=substr($yearcode,0,4);
    }
    else
    {
        $year=substr($yearcode,4,4);
    }
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
                echo '<form method="post" action="../op_controller/monthlybook_action.php">';
                echo '<table border="0" >';
            ?>
                <table class="table table-bordered table-striped" style="width:800px;float:left;">  
                <!--<CAPTION style="font-family:siddhanta;font-size:18px;text-align:center">वाहनवार पाळीवार गाळप</CAPTION>-->
                <?php
                   if ($bookcategorycode_de==1)
                   {
                        echo '<thead>'; 
                        echo '<tr style="font-family:siddhanta;font-size:16px">';  
                        echo '<th style="text-align:left">दिनांक</th>';  
                        echo '<th style="text-align:left">आरंभीची शिल्लक</th>'; 
                        echo '<th style="text-align:left">जमा</th>'; 
                        echo '<th style="text-align:left">बँक जमा</th>'; 
                        echo '<th style="text-align:right">नावे</th>';
                        echo '<th style="text-align:right">बँक नावे</th>';  
                        echo '<th style="text-align:right">अखेरची शिल्लक</th>'; 
                        echo '</tr>';   
                        echo '</thead>';
                        $accounthead1 = new accounthead($connection);
                        $accounthead1->accountcode = $accounthead1->cashaccountcode();
                        $maxdays=monthmaxdays($monthcode_de);
                        $bookcategorycode_en = fnEncrypt($bookcategorycode_de);
                        $monthcode_en = fnEncrypt($monthcode_de);
                        for($i=1;$i<=$maxdays;$i++) 
                        {
                            $date=$i.'/'.$monthcode_de.'/'.$year;
                            $date_en=fnEncrypt($date);
                            $date1=DateTime::createFromFormat('d/m/Y',$date)->format('d-M-Y');
                            $openingbalance = $accounthead1->openingbalance($date1);
                            $credit = $accounthead1->bddebit($date1,$date1);
                            $debit = $accounthead1->bdcredit($date1,$date1);
                            $closingbalance = $accounthead1->closingbalance($date1);
                            $bankcredit = $accounthead1->bdbankcredit($date1,$date1);
                            $bankdebit = $accounthead1->bdbankdebit($date1,$date1);
                            echo '<tr>';
                            echo '<td style="font-family:siddhanta;font-size:16px;"> <a href="../op_view/daybook_view.php?bookcategorycode='.$bookcategorycode_en.'&monthcode='.$monthcode_en.'&date='.$date_en.'">'.$date.'</a></td>';
                            echo '<td style="font-family:siddhanta;text-align:right;">'.$openingbalance.'</td>';
                            echo '<td style="font-family:siddhanta;text-align:right;">'.$credit.'</td>';
                            echo '<td style="font-family:siddhanta;text-align:right;">'.$bankcredit.'</td>';
                            echo '<td style="font-family:siddhanta;text-align:right;">'.$debit.'</td>';
                            echo '<td style="font-family:siddhanta;text-align:right;">'.$bankdebit.'</td>';
                            echo '<td style="font-family:siddhanta;text-align:right;">'.$closingbalance.'</td>';
                            echo '</tr>';
                        }
                   }
                    
                echo '</table>';
                echo '</form>';
            ?>
        </article>
        <footer>
        </footer>
    </body>
</html>
