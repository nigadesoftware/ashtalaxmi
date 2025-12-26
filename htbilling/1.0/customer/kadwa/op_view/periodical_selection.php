<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    include("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    //
    if (isaccessible(123468567)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $goodscategorycode_de = fnDecrypt($_GET['goodscategorycode']);   
    $categorycode_de = fnDecrypt($_GET['categorycode']); 
    if ($categorycode_de == 1)
    {
        $title = "Periodical Saleregister";
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
                $("#accounthead").autocomplete({
                source: '../ip_view/accounthead_search.php',
                minLength:1,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                $('#accountcode').val(i);
                this.value = v;
                return false;}
                });
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
                minLength:1,
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
                $("#broker").autocomplete({
                source: '../ip_view/broker_search.php',
                minLength:0,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                $('#brokercode').val(i);
                this.value = v;
                return false;}
                });
            });
        </script>
        <script>
            $(function()
            {
                $("#saletender").autocomplete({
                source: function(request, response) {
                    $.getJSON("../ip_view/saletender_search.php", { term: request.term}, 
                            response);
                },
                minLength:1,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                $('#tendertransactionnumber').val(i);
                this.value = v;
                return false;}
                });
            });
        </script>
        <script>
            $(function()
            {
                $("#goodssalepermission").autocomplete({
                source: '../ip_view/goodssalepermission_search.php',
                minLength:1,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                $('#permissiontransactionnumber').val(i);
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
                if ($categorycode_de==1)
                {
                    echo '<form method="post" action="../op_controller/saleregister_action.php">';
                }
                elseif ($categorycode_de==2)
                {
                    echo '<form method="post" action="../op_controller/saleregisterdetail_action.php">';
                }
                elseif ($categorycode_de==3)
                {
                    echo '<form method="post" action="../op_controller/memosaledetail_action.php">';
                }
                elseif ($categorycode_de==4)
                {
                    echo '<form method="post" action="../op_controller/memosalesum_action.php">';
                }
                elseif ($categorycode_de==5)
                {
                    echo '<form method="post" action="../op_controller/godownstocksum_action.php">';
                }
                elseif ($categorycode_de==6)
                {
                    echo '<form method="post" action="../op_controller/brokertenderbalance_action.php">';
                }
                elseif ($categorycode_de==7)
                {
                    echo '<form method="post" action="../op_controller/tenderwisesaledetail_action.php">';
                }
                elseif ($categorycode_de==8)
                {
                    echo '<form method="post" action="../op_controller/tenderallotment_action.php">';
                }
                elseif ($categorycode_de==9)
                {
                    echo '<form method="post" action="../op_controller/sugarb2b_action.php">';
                }
                elseif ($categorycode_de==10)
                {
                    echo '<form method="post" action="../op_controller/releaseorderbalance_action.php">';
                }
                echo '<table border="0" >';
                if ($categorycode_de==1 or $categorycode_de==2 or $categorycode_de==3 or $categorycode_de==4 or $categorycode_de==5 or $categorycode_de==7 or $categorycode_de==8 or $categorycode_de==9)
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
                    if ($categorycode_de==1 or $categorycode_de==2 or $categorycode_de==3 or $categorycode_de==4 or $categorycode_de==5 or $categorycode_de==6 or $categorycode_de==7 or $categorycode_de==8 or $categorycode_de==9 or $categorycode_de==10)
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

                if ($categorycode_de==1 or $categorycode_de==2 or $categorycode_de==3 or $categorycode_de==4 or $categorycode_de==5 or $categorycode_de==6 or $categorycode_de==7 or $categorycode_de==8 or $categorycode_de==9)
                {
                    echo '<tr>';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="todate">Date</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="todate">दिनांक पर्यंत</label></td>';
                        echo '</tr>';
                    }
                    if ($categorycode_de==1 or $categorycode_de==2 or $categorycode_de==3 or $categorycode_de==4 or $categorycode_de==5 or $categorycode_de==6 or $categorycode_de==7 or $categorycode_de==8 or $categorycode_de==9 or $categorycode_de==10)
                    {
                        $todate = currentdate();
                    }
                    else
                    {
                        $todate = '01/04/'.substr($_SESSION['yearperiodcode'],0,4);
                    }
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="todate" id="todate" value ="'.$todate.'"></td>';
                    echo '<td><label for="fromdate">*</label></td>';
                    echo '</tr>';
                }
                if ($categorycode_de==6)
                    {
                        echo '<tr>';
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="broker">Broker</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="broker">ब्रोकर</label></td>';
                            echo '</tr>';
                        }

                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="broker" id="broker" value =""></td>';
                        echo '<td><label for="broker">*</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><input type="text" readonly="readonly" style="font-size:12pt;height:30px" name="brokercode" id="brokercode"></td>';
                        echo '</tr>';
                    }
                if ($categorycode_de==8)
                    {
                        echo '<tr>';
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="tendertransactionnumber">Tender</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="tendertransactionnumber">टेंडर</label></td>';
                            echo '</tr>';
                        }

                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="saletender" id="saletender"></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><input type="text" readonly="readonly" style="font-size:12pt;height:30px" name="tendertransactionnumber" id="tendertransactionnumber"></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><input type="text" readonly="readonly" style="font-size:12pt;height:30px" name="brokercode" id="brokercode"></td>';
                        echo '</tr>';
                    }
                if ($categorycode_de==10)
                    {
                        echo '<tr>';
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="tendertransactionnumber">Release Order</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="tendertransactionnumber">रिलीज ऑर्डर</label></td>';
                            echo '</tr>';
                        }

                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="goodssalepermission" id="goodssalepermission"></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><input type="text" readonly="readonly" style="font-size:12pt;height:30px" name="permissiontransactionnumber" id="permissiontransactionnumber"></td>';
                        echo '</tr>';
                    }
                echo '<tr>';
                echo '<td></td>';
                echo '</tr>';
                echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="goodscategorycode" id="goodscategorycode" value ="'.$goodscategorycode_de.'"></td>';
                echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="categorycode" id="categorycode" value ="'.$categorycode_de.'"></td>';
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