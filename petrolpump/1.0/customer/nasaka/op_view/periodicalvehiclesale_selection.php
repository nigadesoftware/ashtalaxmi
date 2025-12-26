<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    include("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    //
    if (isaccessible(451278369852145)==0 and isaccessible(357451254865478)==0)
    {
        echo 'Communication Error';
        exit;
    }    $connection = petrolpump_connection();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/w3.css">
        <title>Selection Parameter</title>
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
                background-color: #fff
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
                background-color: #fef
            }
            input[readonly],select:disabled
            {
                border-radius: 10px;
                outline: none;
                background-color:lightblue;
                color:black;
            }
            label
            {
                color: #000;
                font-weight: bold;
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
                $("#customer").autocomplete({
                source: '../ip_view/customer_search.php',
                minLength:1,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                 var n = ui.item.vehiclenumber;
                 var r = ui.item.refcode;
                $('#customercode').val(i);
                $('#vehiclenumber').val(n);
                $('#refcode').val(r);
                this.value = v;
                return false;}
                });
            });
        </script>
    </head>
    <body>
        <nav "w3-container">
            <ul class="navbar">
                <li><a class="navbar" href="../ip_view/entitymenu.php">Entity Menu</a>
                <?php
                    echo '<li><a style="color:#f48" class="navbar" href="../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <?php
                echo '<section>';
                echo '<form method="post" action="../op_controller/periodicalvehiclesale_action.php">';
                echo '<table border="0" >';
            echo '<tr>';
            if ($_SESSION['lng']=="English")
            {
                echo '<tr>';
                echo '<td><label for="fromdate">From Date</label></td>';
                echo '</tr>';
            }
            else
            {
                echo '<tr>';
                echo '<td><label for="fromdate">दिनांक पर्यंत</label></td>';
                echo '</tr>';
            }
            $cdt = currentdate();
            echo '<td><input type="text" style="font-size:12pt;height:30px" name="fromdate" id="fromdate" value ="'.$cdt.'"></td>';
            echo '<td><label for="fromdate">*</label></td>';
            echo '</tr>';

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
            echo '<td><input type="text" style="font-size:12pt;height:30px" name="todate" id="todate" value ="'.$cdt.'"></td>';
            echo '<td><label for="todate">*</label></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td></td>';
            echo '</tr>';

            if ($_SESSION['lng']=="English")
            {
                echo '<tr>';
                echo '<td><label for="customercode">Customer</label></td>';
                echo '</tr>';
            }
            else
            {
                echo '<tr>';
                echo '<td><label for="customercode">ग्राहक</label></td>';
                echo '</tr>';
            }
            echo '<tr>';
            if ($_SESSION['lng']=="English")
            {
                echo '<td><input type="text" style="font-size:12pt;height:30px" name="customer" id="customer" value="'.$saleheader1->customernameeng.'" style="width:300px"></td>';
            }
            else
            {
                echo '<td><input type="text" style="font-size:12pt;height:30px" name="customer" id="customer" value="'.$saleheader1->customernameuni.'" style="width:300px"></td>';
            }
             echo '<td><label for="customer">*</label></td>';
             echo '</tr>';
             echo '<tr>';
             echo '<td><input type="text" tabindex="-1" readonly="readonly" style="font-size:12pt;height:30px" name="customercode" id="customercode" value="'.$saleheader1->customercode.'" style="width:300px"></td>';
             echo '</tr>';
             echo '<tr>';
             echo '<td><input type="text" tabindex="-1" readonly="readonly" style="font-size:12pt;height:30px" name="refcode" id="refcode" value="'.$saleheader1->refcode.'" style="width:300px"></td>';
             echo '</tr>';
             echo '<tr>';
             echo '<td><input type="text" tabindex="-1" readonly="readonly" style="font-size:12pt;height:30px" name="vehiclenumber" id="vehiclenumber" value="'.$saleheader1->vehiclenumber.'" style="width:300px"></td>';
             echo '</tr>';

            echo '<tr>';
            echo '<td><input type="submit" style="width:200px;font-size:13pt;" value="रिपोर्ट (Report)"/>';
            echo '</tr>';

            echo '</table>';
            echo '</form>';
        ?>
        </article>
        <footer>
        </footer>
    </body>
</html>