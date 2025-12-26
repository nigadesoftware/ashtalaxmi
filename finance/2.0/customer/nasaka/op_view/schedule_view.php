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
    if (isset($_POST['todate']))
    {
        $todatecur = DateTime::createFromFormat('d/m/Y',$_POST['todate'])->format('d-M-Y');
    }
    else
    {
        $todatecur = '31-Mar-'.substr($_SESSION['yearperiodcode'],4,4);
    }
    $todatepre = '31-Mar-'.substr($_SESSION['yearperiodcode']-10001,4,4);
    $yearcodecur = $_SESSION['yearperiodcode'];
    $yearcodepre = $_SESSION['yearperiodcode']-10001;
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
            <?php
                $query = "select schedulecode
                ,schedulenumber
                ,schedulenameuni
                ,schedulenameeng
                ,case when scheduleclosingbalancecur<0 then abs(scheduleclosingbalancecur) else 0 end scheduleclosingbalancecur_cr
                ,case when scheduleclosingbalancecur>0 then scheduleclosingbalancecur else 0 end scheduleclosingbalancecur_dr
                ,case when scheduleclosingbalancepre<0 then abs(scheduleclosingbalancepre) else 0 end scheduleclosingbalancepre_cr
                ,case when scheduleclosingbalancepre>0 then scheduleclosingbalancepre else 0 end scheduleclosingbalancepre_dr
                from (
                select schedulecode
                ,schedulenumber
                ,schedulenameuni
                ,schedulenameeng
                ,scheduleclosingbalance(".$yearcodecur.",g.schedulecode,'".$todatecur."') as scheduleclosingbalancecur 
                ,scheduleclosingbalance(".$yearcodepre.",g.schedulecode,'".$todatepre."') as scheduleclosingbalancepre 
                from accountschedule g)
                where not(scheduleclosingbalancecur=0 and scheduleclosingbalancepre=0)
                order by schedulecode";
                $result = oci_parse($connection, $query);
                $r = oci_execute($result);
                while ($row_schedule = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    echo '<thead>';
                    echo '<tr style="font-family:siddhanta;font-size:18px">';
                    echo '<thead>';  
                    if ($_SESSION['lng'] == "English")
                    {
                        echo '<tr style="font-family:siddhanta;font-size:18px">'; 
                        echo '<th colspan="2" style="text-align:center">Schedule</th>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<th colspan="3" style="text-align:center">Schedule Number :'.$row['SCHEDULENUMBER'].' '.$row['SCHEDULENAMEENG'].'</th>';
                        echo '</tr>';
                        echo '<tr style="font-family:siddhanta;font-size:18px">'; 
                        echo '<th colspan="5" style="text-align:center">Date '.$todate.'</th>';
                        echo '</tr>';
                    }
                    else
                    {         
                        echo '<tr style="font-family:siddhanta;font-size:18px">'; 
                        echo '<th colspan="2" style="text-align:center">शेड्युल</th>';
                        echo '<th colspan="3" style="text-align:center">शेड्युल नंबर :'.$row_schedule['SCHEDULENUMBER'].' '.$row_schedule['SCHEDULENAMEUNI'].'</th>';
                        echo '</tr>';
                        echo '<tr style="font-family:siddhanta;font-size:16px">'; 
                        echo '<th colspan="5" style="text-align:center">दिनांक '.$todatecur.' पर्यंत</th>';
                        echo '</tr>';
                    }
                    echo '<thead>';
                    echo '</tr>';
                    echo '</thead>';
                    if ($_SESSION['lng'] == "English")
                    {
                        echo '<thead>';   
                        echo '<tr style="font-family:siddhanta;font-size:18px">';   
                        echo '<th colspan="5" style="text-align:center"></th>'; 
                        echo '</tr>';   
                        echo '</thead>'; 
                        echo '<thead>';   
                        echo '<tr style="font-family:siddhanta;font-size:16px"> '; 
                        echo '<th colspan="2" style="text-align:center">Previous Year</th>';  
                        echo '<th style="text-align:center">Particulars</th>'; 
                        echo '<th colspan="2" style="text-align:center">Current Year</th>'; 
                        echo '</tr>'; 
                        echo '</thead>';
                        echo '<thead>';   
                        echo '<tr style="font-family:siddhanta;font-size:16px">'; 
                        echo '<th style="text-align:right">Credit</th>'; 
                        echo '<th style="text-align:right">Debit</th>'; 
                        echo '<th style="text-align:left"></th>';  
                        echo '<th style="text-align:right">Credit</th>'; 
                        echo '<th style="text-align:right">Debit</th>';
                        echo '</tr>'; 
                        echo '</thead>';
                    }
                    else
                    {
                        echo '<thead>';   
                        echo '<tr style="font-family:siddhanta;font-size:18px">';   
                        echo '<th colspan="5" style="text-align:center"></th>'; 
                        echo '</tr>';   
                        echo '</thead>'; 
                        echo '<thead>';   
                        echo '<tr style="font-family:siddhanta;font-size:16px"> '; 
                        echo '<th colspan="2" style="text-align:center">मागील वर्ष</th>';  
                        echo '<th style="text-align:center">तपशील</th>'; 
                        echo '<th colspan="2" style="text-align:center">चालू वर्ष</th>'; 
                        echo '</tr>'; 
                        echo '</thead>';
                        echo '<thead>';   
                        echo '<tr style="font-family:siddhanta;font-size:16px">'; 
                        echo '<th style="text-align:right">जमा</th>'; 
                        echo '<th style="text-align:right">नावे</th>'; 
                        echo '<th style="text-align:left"></th>';  
                        echo '<th style="text-align:right">जमा</th>'; 
                        echo '<th style="text-align:right">नावे</th>'; 
                        echo '</tr>'; 
                        echo '</thead>';    
                    }
                    $query_subschedule = "select * 
                    from accountsubschedule g 
                    where g.schedulecode=".$row_schedule['SCHEDULECODE']." 
                    and not(nvl(subscheduleclosingbalance(".$yearcodecur.",g.subschedulecode,'".$todatecur."'),0) =0
                    and nvl(subscheduleclosingbalance(".$yearcodepre.",g.subschedulecode,'".$todatepre."'),0)=0)
                    order by subschedulecode";
                    $result_subschedule = oci_parse($connection, $query_subschedule);
                    $r = oci_execute($result_subschedule);
                    $subnorec=0;
                    $subsrno=1;
                    $ret=0;
                    while ($row_subschedule = oci_fetch_array($result_subschedule,OCI_ASSOC+OCI_RETURN_NULLS))
                    {
                        $subnorec++;
                        $query_subsubschedule ="select * from accountsubsubschedule g 
                        where g.schedulecode=".$row_schedule['SCHEDULECODE']." 
                        and g.subschedulecode=".$row_subschedule['SUBSCHEDULECODE']." 
                        and not(nvl(subsubscheduleclosingbalance(".$yearcodecur.",g.subsubschedulecode,'".$todatecur."'),0) =0
                        and nvl(subsubscheduleclosingbalance(".$yearcodepre.",g.subsubschedulecode,'".$todatepre."'),0)=0)
                        order by subsubschedulecode";
                        $result_subsubschedule = oci_parse($connection, $query_subsubschedule);
                        $r = oci_execute($result_subsubschedule);
                        $subsubnorec=0;
                        $subsubsrno=1;
                        while ($row_subsubschedule = oci_fetch_array($result_subsubschedule,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            $subsubnorec++;
                            detail($connection,$yearcodecur,$yearcodepre,$todatecur,$todatepre,$row_schedule['SCHEDULECODE'],$row_subschedule['SUBSCHEDULECODE'],$row_subsubschedule['SUBSUBSCHEDULECODE']);
                        }
                        if ($subsubnorec==0)
                        {
                            detail($connection,$yearcodecur,$yearcodepre,$todatecur,$todatepre,$row_schedule['SCHEDULECODE'],$row_subschedule['SUBSCHEDULECODE'],'');
                        }
                    }
                    if ($subnorec==0)
                    {
                        $ret=detail($connection,$yearcodecur,$yearcodepre,$todatecur,$todatepre,$row_schedule['SCHEDULECODE'],'','');
                    }
                }
                echo '<tr>';
                echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($closingbalancepre_cr)).'</td>';
                echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(($closingbalancepre_dr)).'</td>';
                echo '<td style="text-align:left;font-family:siddhanta;font-size:16px">एकूण</td>';
                echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($closingbalancepre_cr)).'</td>';
                echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(($closingbalancepre_dr)).'</td>';
                echo '</tr>';
                echo '</table>';
                echo '</form>';

                function detail(&$connection,$yearcodecur,$yearcodepre,$todatecur,$todatepre,$schedulecode,$subschedulecode,$subsubschedulecode)
                {
                    $query = "select * from (select accountcode,accountnameuni,closingbalancecur,closingbalancepre,
                    case when closingbalancepre<0 then abs(closingbalancepre) else 0 end closingbalancepre_cr
                    ,case when closingbalancepre>0 then closingbalancepre else 0 end closingbalancepre_dr
                    ,case when closingbalancecur<0 then abs(closingbalancecur) else 0 end closingbalancecur_cr
                    ,case when closingbalancecur>0 then closingbalancecur else 0 end closingbalancecur_dr
                    from (
                    select t.accountcode,accountnameuni,
                    accountclosingbalance(".$yearcodecur.",t.accountcode,'".$todatecur."') as closingbalancecur,
                    accountclosingbalance(".$yearcodepre.",t.accountcode,'".$todatepre."') as closingbalancepre
                    from accounthead t where schedulecode".invl($schedulecode,true,false,'=').
                    " and subschedulecode".invl($subschedulecode,true,false,'=').
                    " and subsubschedulecode".invl($subsubschedulecode,true,false,'=').")
                    where not(closingbalancecur=0
                    and closingbalancepre=0)
                    )
                    order by accountcode";
                    $result = oci_parse($connection, $query);
                    $r = oci_execute($result);
                    $openingbalance_cr=0; 
                    $openingbalance_dr=0; 
                    $closingbalance_cr=0;
                    $closingbalance_dr=0;
                    $acsrno=0;
                    while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                    {
                        echo '<tr>';
                        echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($row['CLOSINGBALANCEPRE_CR'])).'</td>';
                        echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(($row['CLOSINGBALANCEPRE_DR'])).'</td>';
                        echo '<td style="text-align:left;font-family:siddhanta;font-size:16px">'.$row['ACCOUNTCODE'].' '.$row['ACCOUNTNAMEUNI'].'</td>';
                        echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(abs($row['CLOSINGBALANCECUR_CR'])).'</td>';
                        echo '<td style="text-align:right;font-family:siddhanta;font-size:16px">'.moneyFormatIndia(($row['CLOSINGBALANCECUR_DR'])).'</td>';
                        echo '</tr>';  
                    }
                    return $acsrno;
                }
                
                function invl($data,$isnumber=true,$makezeronull=false,$relopr)
                {
                    if (isset($relopr) and $relopr!="")
                    {
                        $opr=' '.$relopr.' ';
                    }
                    else
                    {
                        $opr='';
                    }
                    if (isset($data) and $data != "" and $makezeronull==false)
                    {
                        if ($isnumber == true)
                        {
                            return $opr.$data;
                        }
                        else
                        {
                            return $opr."'".$data."'";
                        }
                    }
                    elseif (isset($data) and $data != "" and $makezeronull==true)
                    {
                        if ($data=='0' or $data==0)
                        {
                            if (isset($relopr) and $relopr!="")
                            {
                                return ' is Null';
                            }
                            else
                            {
                                return 'Null';
                            }
                        }
                        else
                        {
                            if ($isnumber == true)
                            {
                                return $opr.$data;
                            }
                            else
                            {
                                return $opr."'".$data."'";
                            }
                        }
                    }
                    else
                    {
                        if (isset($relopr) and $relopr!="")
                        {
                            return ' is Null';
                        }
                        else
                        {
                            return 'Null';
                        }
                    }
                }
                
                function isnvl($data)
                {
                    if (isset($data) and $data != "")
                    {
                        return false;
                    }
                    else
                    {
                        return true;
                    }
                }
            ?>
        </article>
        <footer>
        </footer>
    </body>
</html>