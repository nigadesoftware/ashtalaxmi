<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    include("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    //
   
    $goodscategorycode_de = fnDecrypt($_GET['goodscategorycode']);   
    $categorycode_de = fnDecrypt($_GET['categorycode']); 
    if ($goodscategorycode_de==2)
    {
      $reportresponsibility=123470469;
    }
    elseif ($goodscategorycode_de==4)
    {
      $reportresponsibility=123473005;
    }
    elseif ($goodscategorycode_de==1)
    {
      $reportresponsibility=123480613;
    }
    elseif ($goodscategorycode_de==3)
    {
      $reportresponsibility=123480633;
    }
    elseif ($goodscategorycode_de==5)
    {
      $reportresponsibility=123480623;
    }
    elseif ($goodscategorycode_de==6)
    {
      $reportresponsibility=123480643;
    }
    elseif ($goodscategorycode_de==7)
    {
      $reportresponsibility=123980613;
    }
    elseif ($goodscategorycode_de==8)
    {
      $reportresponsibility=123490613;
    }
    
    
     if (isaccessible($reportresponsibility)==0)
        {
            echo 'Communication Error';
            exit;
        }
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
               $("#purchaser").autocomplete({
                source: function(request, response) {
                    $.getJSON("../ip_view/purchaser_search.php", { term: request.term,goodscategorycode:$('#goodscategorycode').val()}, 
                    response);
                },
                minLength:1,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                $('#purchasercode').val(i);
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
                echo '<table border="0" >';
                if ($categorycode_de==1 or $categorycode_de==2 or $categorycode_de==3 or $categorycode_de==4 or $categorycode_de==5 or $categorycode_de==6)
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
                    if ($categorycode_de==1 or $categorycode_de==2 or $categorycode_de==3 or $categorycode_de==4 or $categorycode_de==5 or $categorycode_de==6)
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

                if ($categorycode_de==1 or $categorycode_de==2 or $categorycode_de==3 or $categorycode_de==4 or $categorycode_de==5 or $categorycode_de==6)
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
                    if ($categorycode_de==1 or $categorycode_de==2 or $categorycode_de==3 or $categorycode_de==4 or $categorycode_de==5 or $categorycode_de==6)
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
            if ($_SESSION['lng']=="English")
            {
                echo '<tr>';
                echo '<td><label for="purchasercode" class="thick">Purchaser Name</label></td>';
                echo '</tr>';
            }
            else
            {
                echo '<tr>';
                echo '<td><label for="purchasercode" class="thick">खरेदीदाराचे नाव </label></td>';
                echo '</tr>';
            }
            echo '<tr>';
            echo '<td><input type="text" style="font-size:12pt;height:30px" name="purchaser" id="purchaser" value="'.$saleinvoiceheader1->purchaser.'" style="width:300px"></td>';
            echo '<td><label for="goodspurchaser">*</label></td>';
             echo '</tr>';
             echo '<tr>';
            echo '<td><input type="text" readonly="readonly" tabindex="-1" style="font-size:12pt;height:30px" name="purchasercode" id="purchasercode" value="'.$saleinvoiceheader1->purchasercode.'" style="width:300px"></td>';
             echo '</tr>';

             if ($_SESSION['lng']=="English")
            {
                echo '<tr>';
                echo '<td><label for="finishedgoodscode" class="thick">Goods Name</label></td>';
                echo '</tr>';
            }
            else
            {
                echo '<tr>';
                echo '<td><label for="finishedgoodscode" class="thick">उत्पादीत मालाचे नाव </label></td>';
                echo '</tr>';
            }
            echo '<tr>';
            $query1 = "select finishedgoodscode as code
            ,FINISHEDGOODSNAMEENG as name_eng
            ,FINISHEDGOODSNAMEUNI as name_unicode 
            from FINISHEDGOODS 
            where goodscategorycode=".$goodscategorycode_de;
            $result1 = oci_parse($connection, $query1);
            $r = oci_execute($result1);
            echo '<tr>';
            echo '<td><select name="finishedgoods" id="finishedgoods" style="height:35px;font-size:14px;">';
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
                        echo '<option value="'.$row1['CODE'].'">'.$row1['NAME_ENG'].'</option>';
                 }
                 else
                 {
                         echo '<option value="'.$row1['CODE'].'">'.$row1['NAME_UNICODE'].'</option>';
                 }
             }
             echo '</select>';
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