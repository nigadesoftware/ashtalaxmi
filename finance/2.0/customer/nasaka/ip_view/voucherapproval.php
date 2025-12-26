<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetloginview.php");
    include("../info/ncryptdcrypt.php");
    include_once ("../ip_model/voucheractiondetail_db_oracle.php");
    require("../info/routine.php");
    //
    if (isaccessible(683297845124527)==0 and isaccessible(123461861)==0 and isaccessible(123462178)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $voucherdate_de = fnDecrypt($_GET['voucherdate']);
    $voucherdate_en = fnEncrypt($voucherdate_de);
    $vouchersubtypecode_de = fnDecrypt($_GET['vouchersubtypecode']);
    $vouchersubtypecode_en = fnEncrypt($vouchersubtypecode_de);
    $connection = swapp_connection();
    $vouchersubtype = vouchersubtype($connection,$vouchersubtypecode_de);
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
        <title>Voucher Approval</title>
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
            <ul class="navbar">
                <li><a href="../mis/entitymenu.php">Entity Menu</a>
                <?php
                    echo '<li><a href="../ip_view/voucherapproval_daily.php">Approval Daily</a>';
                    echo '<li><a href="../ip_view/voucherapproval_vouchersubtype.php?voucherdate='.$voucherdate_en.'">Approval SubType</a>';
                    echo '<li><a  href="../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article "w3-container">
            <?php
                echo '<section>';
                echo '<form method="post" action="../op_controller/daybook_action.php">';
                echo '<table border="0" >';
            ?>
                <table class="table table-bordered table-striped" style="width:600px;float:left;">  
                <!--<CAPTION style="font-family:siddhanta;font-size:18px;text-align:center">वाहनवार पाळीवार गाळप</CAPTION>-->
                <thead>  
                <tr style="font-family:siddhanta;font-size:18px">  
                <?php
                 if ($_SESSION['lng'] == "English")
                    {
                        echo '<th colspan="6" style="text-align:center">Approval</th>';
                    }
                 else
                    {
                        echo '<th colspan="6" style="text-align:center">मान्यता</th>';
                    }
                ?>
                </tr>  
                </thead>
                <?php
                    echo '<tr>';
                    if ($voucherdate_de!='')
                    {
                        $voucherdate = DateTime::createFromFormat('d-M-y',$voucherdate_de)->format('d/m/Y');
                    }
                    else
                    {
                        $voucherdate = '';
                    }
                    echo '<td style="text-align:left">व्हाउचर दिनांक</td>';
                    echo '<td style="text-align:left">'.$voucherdate.'</td>';
                    echo '<td style="text-align:right"></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td style="text-align:left">व्हाउचर प्रकार</td>';
                    echo '<td style="text-align:left">'.$vouchersubtype.'</td>';
                    echo '<td style="text-align:right"></td>';
                    echo '</tr>';
                    

                if ($_SESSION['lng'] == "English")
                {
                    echo '<thead> ';  
                    echo '<tr style="font-family:siddhanta;font-size:16px"> '; 
                    echo '<th style="text-align:left">Voucher Number</th>'; 
                    echo '<th style="text-align:left">Description</th>'; 
                    echo '<th colspan="1" style="text-align:right">Amount</th>'; 
                    echo '</tr>';   
                    echo '</thead>';  
                }
                else
                {
                    echo '<thead> ';  
                    echo '<tr style="font-family:siddhanta;font-size:16px"> '; 
                    echo '<th style="text-align:left">व्हाउचर नंबर</th>'; 
                    echo '<th style="text-align:left">तपशिल</th>'; 
                    echo '<th colspan="1" style="text-align:right">रक्कम</th>'; 
                    echo '</tr>';   
                    echo '</thead>';  
                } 
                    
                    $voucheractiondetail1=new voucheractiondetail($connection);
                    $result=$voucheractiondetail1->fetchpendingvoucher($voucherdate_de,$vouchersubtypecode_de);
                    $flag_en = fnEncrypt('Approval');
                    while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                    {
                        echo '<tr>';
                        $transactionnumber_en = fnEncrypt($row['TRANSACTIONNUMBER']);
                        if ($row['VOUCHERNUMBERPREFIXSUFIX']=='')
                        {
                            $transactionnumber = $row['TRANSACTIONNUMBER'];
                        }
                        else
                        {
                            $transactionnumber = $row['VOUCHERNUMBERPREFIXSUFIX'];
                        }
                        
                        echo '<td style="font-family:siddhanta;font-size:16px"><a href="../ip_view/voucherheader.php?transactionnumber='.$transactionnumber_en.'&vouchersubtypecode='.$vouchersubtypecode_en.'&voucherdate='.$voucherdate_en.'&flag='.$flag_en.'">'.$transactionnumber.'</a></td>';
                        echo '<td style="text-align:left">'.$row['DESCRIPTION'].'</td>';
                        echo '<td style="text-align:right">'.number_format((float) $row['TOTALDEBIT'],2,'.','').'</td>';
                        echo '</tr>';
                    }
                echo '</table>';
                echo '</form>';
            ?>
        </article>
        <footer>
        </footer>
    </body>
</html>