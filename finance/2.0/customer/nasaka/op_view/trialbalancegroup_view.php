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
    if (isset($_POST['printerfriendly']))
    {
        $printerfriendly=$_POST['printerfriendly'];
    }
    else
    {
        $printerfriendly=0;
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
                <table class="table table-bordered table-striped" style="width:1000px;float:left;">  
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

                $query="select * from (
                    --main group start
                    select ".$_SESSION['yearperiodcode']." as yearperiodcode,g.groupcode,null as subgroupcode,null as subsubgroupcode,1 as sequence,null as accountcode,max(g.groupnameuni) as accountnameuni
                     ,0 as openingbalance_cr
                     ,0 as openingbalance_dr
                     ,0 as credit
                     ,0 as debit
                     ,0 as closingbalance_cr
                     ,0 as closingbalance_dr
                    from accounthead a,accountgroup g
                    where a.groupcode=g.groupcode
                    group by g.groupcode
                    union all
                    select yearperiodcode,groupcode,null as subgroupcode,null as subsubgroupcode,9 as sequence,null as accountcode,accountnameuni,
                    case when openingbalance<0 then abs(openingbalance) else 0 end openingbalance_cr
                            ,case when openingbalance>0 then openingbalance else 0 end openingbalance_dr
                            ,credit
                            ,debit
                            ,case when closingbalance<0 then abs(closingbalance) else 0 end closingbalance_cr
                            ,case when closingbalance>0 then closingbalance else 0 end closingbalance_dr
                    from (        
                    select t.yearperiodcode,g.groupcode,g.groupnameuni as accountnameuni
                            ,sum(openingbalance) as openingbalance
                            ,sum(credit) as credit
                            ,sum(debit) as debit
                            ,nvl(sum(openingbalance),0)+nvl(sum(debit),0)-nvl(sum(credit),0) as closingbalance
                            from
                            (
                            select a.yearperiodcode,a.accountcode
                            ,(nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0)) as openingbalance
                            ,0 as credit
                            ,0 as debit
                            ,0 as closingbalance
                            from (
                            select a.yearperiodcode,a.accountcode,nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance
                            from accountopening a
                            union all
                            select t.yearperiodcode,d.accountcode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance
                            from voucherheader t,voucherdetail d
                            where t.transactionnumber=d.transactionnumber
                            and t.voucherdate<'".$fromdate."'
                            and t.approvalstatus=9
                            group by t.yearperiodcode,d.accountcode
                            )a
                            group by a.yearperiodcode,a.accountcode
                            union all
                            select t.yearperiodcode,d.accountcode,0 as openingbalance,nvl(sum(d.credit),0) as credit, 0 as debit,0 as closingbalance
                            from voucherheader t,voucherdetail d
                            where t.transactionnumber=d.transactionnumber
                            and t.voucherdate>='".$fromdate."' and t.voucherdate<='".$todate."'
                            and t.approvalstatus=9
                            group by t.yearperiodcode,d.accountcode
                            union all
                            select t.yearperiodcode,d.accountcode,0 as openingbalance,0 as credit,nvl(sum(d.debit),0) as debit,0 as closingbalance
                            from voucherheader t,voucherdetail d
                            where t.transactionnumber=d.transactionnumber
                            and t.voucherdate>='".$fromdate."' and t.voucherdate<='".$todate."'
                            and t.approvalstatus=9
                            group by t.yearperiodcode,d.accountcode
                            )t,accounthead h,accountgroup g
                            where t.accountcode=h.accountcode
                            and h.groupcode=g.groupcode
                            group by t.yearperiodcode,g.groupcode,g.groupnameuni
                            having yearperiodcode=".$_SESSION['yearperiodcode']." 
                            )
                    union all
                    --sub group start
                    select ".$_SESSION['yearperiodcode']." as yearperiodcode,s.groupcode,s.subgroupcode,null as subsubgroupcode,2 as sequence,null as accountcode,max(s.subgroupnameuni) as accountnameuni
                     ,0 as openingbalance_cr
                     ,0 as openingbalance_dr
                     ,0 as credit
                     ,0 as debit
                     ,0 as closingbalance_cr
                     ,0 as closingbalance_dr
                    from accounthead a,accountgroup g,accountsubgroup s
                    where a.groupcode=s.groupcode and a.subgroupcode=s.subgroupcode and s.groupcode=g.groupcode
                    group by s.groupcode,s.subgroupcode
                    union all
                    select yearperiodcode,groupcode,subgroupcode,null as subsubgroupcode,8 as sequence,null as accountcode,accountnameuni,
                    case when openingbalance<0 then abs(openingbalance) else 0 end openingbalance_cr
                            ,case when openingbalance>0 then openingbalance else 0 end openingbalance_dr
                            ,credit
                            ,debit
                            ,case when closingbalance<0 then abs(closingbalance) else 0 end closingbalance_cr
                            ,case when closingbalance>0 then closingbalance else 0 end closingbalance_dr
                    from (        
                    select t.yearperiodcode,s.groupcode,s.subgroupcode,s.subgroupnameuni as accountnameuni
                            ,sum(openingbalance) as openingbalance
                            ,sum(credit) as credit
                            ,sum(debit) as debit
                            ,nvl(sum(openingbalance),0)+nvl(sum(debit),0)-nvl(sum(credit),0) as closingbalance
                            from
                            (
                            select a.yearperiodcode,a.accountcode
                            ,(nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0)) as openingbalance
                            ,0 as credit
                            ,0 as debit
                            ,0 as closingbalance
                            from (
                            select a.yearperiodcode,a.accountcode,nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance
                            from accountopening a
                            union all
                            select t.yearperiodcode,d.accountcode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance
                            from voucherheader t,voucherdetail d
                            where t.transactionnumber=d.transactionnumber
                            and t.voucherdate<'".$fromdate."'
                            and t.approvalstatus=9
                            group by t.yearperiodcode,d.accountcode
                            )a
                            group by a.yearperiodcode,a.accountcode
                            union all
                            select t.yearperiodcode,d.accountcode,0 as openingbalance,nvl(sum(d.credit),0) as credit, 0 as debit,0 as closingbalance
                            from voucherheader t,voucherdetail d
                            where t.transactionnumber=d.transactionnumber
                            and t.voucherdate>='".$fromdate."' and t.voucherdate<='".$todate."'
                            and t.approvalstatus=9
                            group by t.yearperiodcode,d.accountcode
                            union all
                            select t.yearperiodcode,d.accountcode,0 as openingbalance,0 as credit,nvl(sum(d.debit),0) as debit,0 as closingbalance
                            from voucherheader t,voucherdetail d
                            where t.transactionnumber=d.transactionnumber
                            and t.voucherdate>='".$fromdate."' and t.voucherdate<='".$todate."'
                            and t.approvalstatus=9
                            group by t.yearperiodcode,d.accountcode
                            )t,accounthead h,accountgroup g,accountsubgroup s
                            where t.accountcode=h.accountcode
                            and h.groupcode=s.groupcode and h.subgroupcode=s.subgroupcode and g.groupcode=s.groupcode
                            group by t.yearperiodcode,s.groupcode,s.subgroupcode,s.subgroupnameuni
                            having yearperiodcode=".$_SESSION['yearperiodcode']." 
                            )
                    union all
                    --sub sub group start
                    select ".$_SESSION['yearperiodcode']." as yearperiodcode,r.groupcode,r.subgroupcode,r.subsubgroupcode,3 as sequence,null as accountcode,max(r.subsubgroupnameuni) as accountnameuni
                     ,0 as openingbalance_cr
                     ,0 as openingbalance_dr
                     ,0 as credit
                     ,0 as debit
                     ,0 as closingbalance_cr
                     ,0 as closingbalance_dr
                    from accounthead a,accountsubsubgroup r
                    where a.groupcode=r.groupcode and a.subgroupcode=r.subgroupcode and a.subsubgroupcode=r.subsubgroupcode
                    group by r.groupcode,r.subgroupcode,r.subsubgroupcode
                    union all
                    select yearperiodcode,groupcode,subgroupcode,null as subsubgroupcode,7 as sequence,null as accountcode,accountnameuni,
                    case when openingbalance<0 then abs(openingbalance) else 0 end openingbalance_cr
                            ,case when openingbalance>0 then openingbalance else 0 end openingbalance_dr
                            ,credit
                            ,debit
                            ,case when closingbalance<0 then abs(closingbalance) else 0 end closingbalance_cr
                            ,case when closingbalance>0 then closingbalance else 0 end closingbalance_dr
                    from (        
                    select t.yearperiodcode,r.groupcode,r.subgroupcode,r.subsubgroupnameuni as accountnameuni
                            ,sum(openingbalance) as openingbalance
                            ,sum(credit) as credit
                            ,sum(debit) as debit
                            ,nvl(sum(openingbalance),0)+nvl(sum(debit),0)-nvl(sum(credit),0) as closingbalance
                            from
                            (
                            select a.yearperiodcode,a.accountcode
                            ,(nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0)) as openingbalance
                            ,0 as credit
                            ,0 as debit
                            ,0 as closingbalance
                            from (
                            select a.yearperiodcode,a.accountcode,nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance
                            from accountopening a
                            union all
                            select t.yearperiodcode,d.accountcode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance
                            from voucherheader t,voucherdetail d
                            where t.transactionnumber=d.transactionnumber
                            and t.voucherdate<'".$fromdate."'
                            and t.approvalstatus=9
                            group by t.yearperiodcode,d.accountcode
                            )a
                            group by a.yearperiodcode,a.accountcode
                            union all
                            select t.yearperiodcode,d.accountcode,0 as openingbalance,nvl(sum(d.credit),0) as credit, 0 as debit,0 as closingbalance
                            from voucherheader t,voucherdetail d
                            where t.transactionnumber=d.transactionnumber
                            and t.voucherdate>='".$fromdate."' and t.voucherdate<='".$todate."'
                            and t.approvalstatus=9
                            group by t.yearperiodcode,d.accountcode
                            union all
                            select t.yearperiodcode,d.accountcode,0 as openingbalance,0 as credit,nvl(sum(d.debit),0) as debit,0 as closingbalance
                            from voucherheader t,voucherdetail d
                            where t.transactionnumber=d.transactionnumber
                            and t.voucherdate>='".$fromdate."' and t.voucherdate<='".$todate."'
                            and t.approvalstatus=9
                            group by t.yearperiodcode,d.accountcode
                            )t,accounthead h,accountsubsubgroup r
                            where t.accountcode=h.accountcode
                            and h.groupcode=r.groupcode and h.subgroupcode=r.subgroupcode 
                            and h.subsubgroupcode=r.subsubgroupcode
                            group by t.yearperiodcode,r.groupcode,r.subgroupcode,r.subsubgroupnameuni
                            having yearperiodcode=".$_SESSION['yearperiodcode']." 
                            )
                    --end sub sub group
                    union all
                    --account start
                    select yearperiodcode,groupcode,subgroupcode,subsubgroupcode,4 as sequence,accountcode,accountnameuni,
                    case when openingbalance<0 then abs(openingbalance) else 0 end openingbalance_cr
                            ,case when openingbalance>0 then openingbalance else 0 end openingbalance_dr
                            ,credit
                            ,debit
                            ,case when closingbalance<0 then abs(closingbalance) else 0 end closingbalance_cr
                            ,case when closingbalance>0 then closingbalance else 0 end closingbalance_dr
                    from (        
                    select t.yearperiodcode,h.groupcode,h.subgroupcode,h.subsubgroupcode,t.accountcode,h.accountnameuni
                            ,sum(openingbalance) as openingbalance
                            ,sum(credit) as credit
                            ,sum(debit) as debit
                            ,nvl(sum(openingbalance),0)+nvl(sum(debit),0)-nvl(sum(credit),0) as closingbalance
                            from
                            (
                            select a.yearperiodcode,a.accountcode
                            ,(nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0)) as openingbalance
                            ,0 as credit
                            ,0 as debit
                            ,0 as closingbalance
                            from (
                            select a.yearperiodcode,a.accountcode,nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance
                            from accountopening a
                            union all
                            select t.yearperiodcode,d.accountcode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance
                            from voucherheader t,voucherdetail d
                            where t.transactionnumber=d.transactionnumber
                            and t.voucherdate<'".$fromdate."'
                            and t.approvalstatus=9
                            group by t.yearperiodcode,d.accountcode
                            )a
                            group by a.yearperiodcode,a.accountcode
                            union all
                            select t.yearperiodcode,d.accountcode,0 as openingbalance,nvl(sum(d.credit),0) as credit, 0 as debit,0 as closingbalance
                            from voucherheader t,voucherdetail d
                            where t.transactionnumber=d.transactionnumber
                            and t.voucherdate>='".$fromdate."' and t.voucherdate<='".$todate."'
                            and t.approvalstatus=9
                            group by t.yearperiodcode,d.accountcode
                            union all
                            select t.yearperiodcode,d.accountcode,0 as openingbalance,0 as credit,nvl(sum(d.debit),0) as debit,0 as closingbalance
                            from voucherheader t,voucherdetail d
                            where t.transactionnumber=d.transactionnumber
                            and t.voucherdate>='".$fromdate."' and t.voucherdate<='".$todate."'
                            and t.approvalstatus=9
                            group by t.yearperiodcode,d.accountcode
                            )t,accounthead h
                            where t.accountcode=h.accountcode
                            group by t.yearperiodcode,h.groupcode,h.subgroupcode,h.subsubgroupcode,t.accountcode,h.accountnameuni
                            having yearperiodcode=".$_SESSION['yearperiodcode'].")
                            )
                            order by groupcode,nvl(subgroupcode,sequence)
                            ,nvl(subsubgroupcode,sequence),sequence,accountcode
                            ";
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
                            if ($row['SEQUENCE']==1 or $row['SEQUENCE']==9) 
                            {
                                $arrow='->';
                                $color="Brown";
                            }
                            elseif ($row['SEQUENCE']==2 or $row['SEQUENCE']==8) 
                            {
                                $arrow='-->';
                                $color="Olive";
                            }
                            elseif ($row['SEQUENCE']==3 or $row['SEQUENCE']==7) 
                            {
                                $arrow='--->';
                                $color="Purple";
                            }
                            elseif ($row['SEQUENCE']==4) 
                            {
                                $arrow='';
                                $color="Black";
                            }
                            if ($row['SEQUENCE']==7 or $row['SEQUENCE']==8 or $row['SEQUENCE']==9 or $row['SEQUENCE']==10) 
                            $istotal=1;
                            else
                            $istotal=0;
                            echo '<tr>';
                            if ($printerfriendly==0)
                            {
                                echo '<td style="text-align:right;font-family:siddhanta;font-size:14px;"><a style="color:green;" href="../op_view/monthlyledger_selection.php?accountcode='.$accountcode_en.'">'.$arrow.$row['ACCOUNTCODE'].'</a></td>';
                            }
                            else
                            {
                                echo '<td style="text-align:right;font-family:siddhanta;font-size:14px;">'.$arrow.$row['ACCOUNTCODE'].'</td>';
                            }
                            if ($_SESSION['lng'] == "English")
                            {
                                if ($istotal==1)
                                    echo '<td style="font-family:siddhanta;font-size:15px;color:'.$color.'">'.'Total '.$row['ACCOUNTNAMEENG'].'</td>';
                                else
                                    echo '<td style="font-family:siddhanta;font-size:15px;color:'.$color.'">'.$row['ACCOUNTNAMEENG'].'</td>';
                            }
                            else
                            {
                                if ($istotal==1)
                                    echo '<td style="font-family:siddhanta;font-size:14px;color:'.$color.'">'.'एकूण '.$row['ACCOUNTNAMEUNI'].'</td>';
                                else
                                echo '<td style="font-family:siddhanta;font-size:15px;color:'.$color.'">'.$row['ACCOUNTNAMEUNI'].'</td>';
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
                            if ($row['SEQUENCE']==9)
                            {
                                echo '<tr>';
                                echo '<td></td>';
                                echo '</tr>';
                            }
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