<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    include("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    //
    if (isaccessible(357451254865478)==0)
    {
        echo 'Communication Error';
        exit;
    }  
    $categoryid_de = fnDecrypt($_GET['categoryid']); 
    if ($categoryid_de == 1)
    {
        $title = "Periodical Daybook";
    } 
    elseif ($categoryid_de == 2)
    {
        $title = "Trail Balance Detailed";
    }
    elseif ($categoryid_de == 3)
    {
        $title = "Trail Balance Groupwise (Opening-Tr-Closing)";
    }
    elseif ($categoryid_de == 33)
    {
        $title = "Trail Balance Groupwise (Closing Balance)";
    }
    elseif ($categoryid_de == 34)
    {
        $title = "Journal Register";
    }
    elseif ($categoryid_de == 35)
    {
        $title = "Sugarsale Journal Register";
    }
    elseif ($categoryid_de == 4)
    {
        $title = "Main Ledger";
    }
    elseif ($categoryid_de == 5)
    {
        $title = "Ledger";
    }
    elseif ($categoryid_de == 6)
    {
        $title = "Subledger";
    }
    elseif ($categoryid_de == 60)
    {
        $title = "Multi Subledger";
    }
    elseif ($categoryid_de == 7)
    {
        $title = "Schedule";
    }   
    elseif ($categoryid_de == 8)
    {
        $title = "Profit and Loss";
    }  
    elseif ($categoryid_de == 9)
    {
        $title = "Balancesheet";
    }
    elseif ($categoryid_de == 10)
    {
        $title = "Subledger Balance List";
    }
    elseif ($categoryid_de == 11)
    {
        $title = "Periodical Cashbook";
    }
    elseif ($categoryid_de == 51)
    {
        $title = "Ledger View";
    }
    elseif ($categoryid_de == 52)
    {
        $title = "Trail Balance View";
    }
    elseif ($categoryid_de == 53)
    {
        $title = "Schedule View";
    }
    elseif ($categoryid_de == 54)
    {
        $title = "Cash Position";
    }
    elseif ($categoryid_de == 55)
    {
        $title = "Bank Cheque Issue Register";
    }
    elseif ($categoryid_de == 56)
    {
        $title = "SubLedger View";
    }
    elseif ($categoryid_de == 57)
    {
        $title = "SubLedger Balance List View";
    }
    elseif ($categoryid_de == 59)
    {
        $title = "Individual Ledger Balance List View";
    }
    elseif ($categoryid_de == 52)
    {
        $title = "Trail Balance Groupwise View";
    }
    elseif ($categoryid_de == 61)
    {
        $title = "Subledger Year Balance List";
    }
    $connection = swapp_connection();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/w3.css">
        <link rel="stylesheet" href="../css/swapp123.css">
        <?php
            echo '<title>'.$title.'Selection Parameter</title>';
        ?>
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
        </script>
        <link rel="stylesheet" href="../css/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="..//js/jquery-1.10.2.js"></script>
        <script src="..//js/ui/1.11.4/jquery-ui.js"></script>
        <script>
            $(function()
            {
                <?php
                    if ($categoryid_de == 10 or $categoryid_de == 61)
                    {
                        echo '$("#accounthead").autocomplete({
                        source: "../ip_view/accountheadsub_search.php",
                        minLength:1,
                        delay:200,
                        select:function(event,ui)
                        {var v = ui.item.value;
                        var i = ui.item.id;
                        $("#accountcode").val(i);
                        this.value = v;
                        return false;}
                        });';
                    }
                    else
                    {
                        echo '$("#accounthead").autocomplete({
                        source: "../ip_view/accounthead_search.php",
                        minLength:1,
                        delay:200,
                        select:function(event,ui)
                        {var v = ui.item.value;
                        var i = ui.item.id;
                        $("#accountcode").val(i);
                        this.value = v;
                        return false;}
                        });';
                    }
                ?>
            });
        </script>
        <script>
        $(function()
            {
                $("#accountsubledger").autocomplete({
                source: function(request, response) {
                    $.getJSON("../ip_view/accountsubledger_search.php", { term: request.term,accountcode:$('#accountcode').val()}, 
                            response);
                },
                minLength:3,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                $('#subledgercode').val(i);
                this.value = v;
                return false;}
                });
            });
        </script>
        <script>
        $(function()
            {
                $("#accountsubledgername").autocomplete({
                source: function(request, response) {
                    $.getJSON("../ip_view/accountsubledgername_search.php", { term: request.term}, 
                            response);
                },
                minLength:3,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                $('#accountsubledgername').val(v);
                this.value = v;
                return false;}
                });
            });
        </script>
        <script>
            $(function () {
                $(" #entityledgeraccountname ").autocomplete({
                source: '../ip_view/entityledgeraccount_search.php',
                minLength:2,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                $('#entityledgeraccountid').val(i);
                this.value = v;
                return false;}
                });
                });
        </script>
        <script>
            $(function () {
                $(" #entitysubledgeraccountname ").autocomplete({
                source: '../ip_view/entitysubledgeraccount_search.php?entityledgeraccountid',
                minLength:2,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                $(' #entitysubledgeraccountid ').val(i);
                this.value = v;
                return false;}
                });
                });
        </script>
        <script>
        $(function()
            {
                $("#individual").autocomplete({
                source: function(request, response) {
                    $.getJSON("../ip_view/individual_search.php", { term: request.term}, 
                            response);
                },
                minLength:1,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                $('#referencecode').val(i);
                this.value = v;
                return false;}
                });
            });
        </script>
    
    </head>
    <body>
        <nav "w3-container">
            <ul class="navbar">
                <li><a class="navbar" href="../mis/entitymenu.php">Entity Menu</a>
                <?php
                    echo '<li><a style="color:#f48" class="navbar" href="../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <?php
                echo '<section>';
                if ($categoryid_de==1)
                {
                    echo '<form method="post" action="../op_controller/periodicaldaybook_action.php">';
                }
                elseif ($categoryid_de==2)
                {
                    echo '<form method="post" action="../op_controller/trialbalancedetailed_action.php">';
                }
                elseif ($categoryid_de==3)
                {
                    echo '<form method="post" action="../op_controller/trialbalancegroup_action.php">';
                }
                elseif ($categoryid_de==33)
                {
                    echo '<form method="post" action="../op_controller/trialbalancegroupclosing_action.php">';
                }
                elseif ($categoryid_de==34)
                {
                    echo '<form method="post" action="../op_controller/journalregister_action.php">';
                }
                elseif ($categoryid_de==35)
                {
                    echo '<form method="post" action="../op_controller/sugarsalejournalregister_action.php">';
                }
                elseif ($categoryid_de==4)
                {
                    echo '<form method="post" action="../op_controller/mainledger_action.php">';
                }
                elseif ($categoryid_de==5)
                {
                    echo '<form method="post" action="../op_controller/ledger_action.php">';
                }
                elseif ($categoryid_de==6)
                {
                    echo '<form method="post" action="../op_controller/subledger_action.php">';
                }
                elseif ($categoryid_de==60)
                {
                    echo '<form method="post" action="../op_controller/multisubledger_action.php">';
                }
                elseif ($categoryid_de==7)
                {
                    echo '<form method="post" action="../op_controller/schedule_action.php">';
                }
                elseif ($categoryid_de==8)
                {
                    echo '<form method="post" action="../op_controller/pandl_action.php">';
                }
                elseif ($categoryid_de==9)
                {
                    echo '<form method="post" action="../op_controller/balancesheet_action.php">';
                }
                elseif ($categoryid_de==10)
                {
                    echo '<form method="post" action="../op_controller/subledgerbalancelist_action.php">';
                }
                elseif ($categoryid_de==11)
                {
                    echo '<form method="post" action="../op_controller/periodicalcashbook_action.php">';
                }
                elseif ($categoryid_de==51)
                {
                    echo '<form method="post" action="../op_view/monthlyledger_selection.php">';
                }
                elseif ($categoryid_de==52)
                {
                    echo '<form method="post" action="../op_view/trialbalance_view.php">';
                }
                elseif ($categoryid_de==53)
                {
                    echo '<form method="post" action="../op_view/schedule_view.php">';
                }
                elseif ($categoryid_de==54)
                {
                    echo '<form method="post" action="../op_controller/cashposition_action.php">';
                }
                elseif ($categoryid_de==55)
                {
                    echo '<form method="post" action="../op_controller/bankchequeissue_action.php">';
                }
                elseif ($categoryid_de==56)
                {
                    echo '<form method="post" action="../op_view/monthlysubledger_selection.php">';
                }
                elseif ($categoryid_de==57)
                {
                    echo '<form method="post" action="../op_view/subledgerbalancelist_view.php">';
                }
                elseif ($categoryid_de==58)
                {
                    echo '<form method="post" action="../op_view/trialbalancegroup_view.php">';
                }
                elseif ($categoryid_de==59)
                {
                    echo '<form method="post" action="../op_view/individualledgerbalancelist_view.php">';
                }
                elseif ($categoryid_de==61)
                {
                    echo '<form method="post" action="../op_controller/subledgeryearbalancelist_action.php">';
                }
                echo '<table border="0" >';
                if ($categoryid_de == 7) 
                {
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="grouptypecode" class="thick">Group Type</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="grouptypecode" class="thick">ग्रुप प्रकार</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    $query1 = "select grouptypecode as code,grouptypenameeng as name_eng,grouptypenameuni as name_unicode from grouptype order by grouptypecode";
                    $result1 = oci_parse($connection, $query1);
                    $r = oci_execute($result1);
                    echo '<tr>';
                    echo '<td><select name="grouptypecode" style="height:35px;font-size:14px;">';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<option value="0">[Select]</option>';
                    }
                    else
                    {
                        echo '<option value="0">[निवडा]</option>';
                    }
                    while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                    {
                        if ($_SESSION['lng']=="English")
                        {
                            if ($row1['CODE']==$accountgroup1->grouptypecode)
                            {
                                echo '<option value="'.$row1['CODE'].'" Selected>'.$row1['NAME_ENG'].'</option>';
                            }
                            else
                            {
                                echo '<option value="'.$row1['CODE'].'">'.$row1['NAME_ENG'].'</option>';
                            }
                        }
                        else
                        {
                            if ($row1['CODE']==$accountgroup1->grouptypecode)
                            {
                                echo'<option value="'.$row1['CODE'].'" Selected>'.$row1['NAME_UNICODE'].'</option>';
                            }
                            else
                            {
                                echo '<option value="'.$row1['CODE'].'">'.$row1['NAME_UNICODE'].'</option>';
                            }
                        }
                    }
                    echo '</select>';
                    echo '<td><label for="grouptypecode">*</label></td>';
                    echo '</tr>';
                }
                if ($categoryid_de!=7 and $categoryid_de!=9 and $categoryid_de!=10 and $categoryid_de != 51 and $categoryid_de != 53 and $categoryid_de != 56)
                {
                    echo '<tr>';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="fromdate">Date</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="fromdate">दिनांक पासून</label></td>';
                        echo '</tr>';
                    }
                    if ($categoryid_de==1 or $categoryid_de==11)
                    {
                        $fromdate = currentdate();
                    }
                    else
                    {
                        $fromdate = '01/04/'.substr($_SESSION['yearperiodcode'],0,4);
                    }
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="fromdate" id="fromdate" value ="'.$fromdate.'"></td>';
                    echo '<td><label for="fromdate">*</label></td>';
                    echo '</tr>';
                }
                if ($categoryid_de != 51 and $categoryid_de != 56)
                {
                    echo '<tr>';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="todate">To Date</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="todate">दिनांक पर्यंत</label></td>';
                        echo '</tr>';
                    }
                    if ($categoryid_de==1 or $categoryid_de==11)
                    {
                        $todate = currentdate();
                    }
                    else
                    {
                        $todate ='31/03/'.substr($_SESSION['yearperiodcode'],4,4);
                    }
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="todate" id="todate" value ="'.$todate.'"></td>';
                    echo '<td><label for="todate">*</label></td>';
                    echo '</tr>';
                }
                if ($categoryid_de == 4 or $categoryid_de == 5 or $categoryid_de == 6  or $categoryid_de ==10 or $categoryid_de == 51 or $categoryid_de == 55 or $categoryid_de == 56 or $categoryid_de == 57 or $categoryid_de == 61)
                {
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="accountcode" class="thick">Account Code</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="accountcode" class="thick">अकौंट खाते</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<td><input autofocus type="text" style="font-size:12pt;height:30px" name="accounthead" id="accounthead" style="width:300px"></td>';  
                    }
                    else
                    {
                        echo '<td><input autofocus type="text" style="font-size:12pt;height:30px" name="accounthead" id="accounthead" style="width:300px"></td>';  
                    }
                    echo '<td><label for="accounthead">*</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" readonly="readonly" tabindex="-1" style="font-size:12pt;height:30px" name="accountcode" id="accountcode" style="width:300px"></td>';
                    echo '</tr>';
                }

                if ($categoryid_de == 6 or $categoryid_de == 56) 
                {
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="subledgercode" class="thick">Subaccount Code</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="subledgercode" class="thick">सब अकौंट खाते</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="accountsubledger" id="accountsubledger" style="width:300px"></td>';
                    echo '<td><label for="accountsubledger">*</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" readonly="readonly" tabindex="-1" style="font-size:12pt;height:30px" name="subledgercode" id="subledgercode"  style="width:300px"></td>';
                    echo '</tr>';
                 }
                 
                 if ($categoryid_de == 60) 
                 {
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="referencecode" class="thick">Reference Code</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="referencecode" class="thick">संदर्भ कोड</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="referencecode" id="referencecode" style="width:300px"></td>';
                    echo '<td><label for="referencecode">*</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="name" id="name" style="width:300px"></td>';
                    echo '</tr>';
                 }
                 
                if ($categoryid_de == 61) 
                {
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="seasoncode" class="thick">Season Year</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="seasoncode" class="thick">हंगाम</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="seasoncode" id="seasoncode" style="width:300px"></td>';
                    //echo '<td><label for="seasoncode">*</label></td>';
                    echo '</tr>';
                 }

                 if ($categoryid_de == 58) 
                 { 
                    echo '<tr>';
                    echo '<td><input type="checkbox" id="printerfriendly" name="printerfriendly" value="1">Printer Friendly</td>';
                    echo '</tr>';
                 }
                 if ($categoryid_de == 2 or $categoryid_de == 3 or $categoryid_de == 5 or $categoryid_de == 10 or $categoryid_de == 55 or $categoryid_de == 61) 
                 { 
                    echo '<tr>';
                    echo '<td><input type="checkbox" id="exportcsvfile" name="exportcsvfile" value="1">Export CSV File</td>';
                    echo '</tr>';
                 }
                if ($categoryid_de == 59) 
                {
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="individual" class="thick">Individual Account</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="individual" class="thick">वैयक्तिक खाते</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="individual" id="individual" style="width:300px"></td>';
                    echo '<td><label for="individual">*</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" readonly="readonly" tabindex="-1" style="font-size:12pt;height:30px" name="referencecode" id="referencecode"  style="width:300px"></td>';
                    echo '</tr>';
                }    
                echo '<tr>';
                echo '<td></td>';
                echo '</tr>';
                echo '<tr>';
                //echo '<td><input class="button123" type="submit" name="btn" value="View" style="width:100px"></td>';;
                echo '<td><input class="button123" type="submit" name="btn" value="Report" style="width:100px"></td>';;
                echo '</tr>';
                echo '</table>';
                echo '</form>';
            ?>
        </article>
        <footer>
        </footer>
    </body>
</html>