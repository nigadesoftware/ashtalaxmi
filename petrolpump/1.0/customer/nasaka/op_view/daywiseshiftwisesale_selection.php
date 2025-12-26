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
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="../js/jquery-1.10.2.js"></script>
        <script src="../js/ui/1.11.4/jquery-ui.js"></script>
        <script>
            $(function () {
                $(" #entityledgeraccountname ").autocomplete({
                source: 'entityledgeraccount_search.php',
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
                source: 'entitysubledgeraccount_search.php?entityledgeraccountid',
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
                <li><a class="navbar" href="../ip_view/entitymenu.php">Entity Menu</a>
                <?php
                    echo '<li><a style="color:#f48" class="navbar" href="../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <?php
                echo '<section>';
                echo '<form method="post" action="../op_controller/daywiseshiftwisesale_action.php">';
                echo '<table border="0" >';
                if ($_SESSION['lng']=="English")
                {
                    echo '<tr>';
                    echo '<td><label for="petrolpumpcode">Petrolpump</label></td>';
                    echo '</tr>';
                }
                else
                {
                    echo '<tr>';
                    echo '<td><label for="petrolpumpcode">पेट्रोल पंप </label></td>';
                    echo '</tr>';
                }
                $query = "select namedetailid,name_eng,name_unicode from nst_nasaka_db.namedetail s where s.namecategoryid=621451254";
                //$result1 = oci_parse($connection, $query); $r = oci_execute($result);
                $result1 = oci_parse($connection, $query);
                $r = oci_execute($result1);
                echo '<tr>';
                echo '<td><select name="petrolpumpcode" style="height:35px;font-size:14px;">';
                while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<option value="'.$row1['NAMEDETAILID'].'" Selected>'.$row1['NAME_ENG'].'</option>';   
                    }
                    else
                    {
                        echo '<option value="'.$row1['NAMEDETAILID'].'" Selected>'.$row1['NAME_UNICODE'].'</option>';
                    }
                }
                echo '</select>';
                echo '<td><label for="petrolpumpcode">*</label></td>';
                echo '</td>';
                echo '</tr>';

                if ($_SESSION['lng']=="English")
                {
                    echo '<tr>';
                    echo '<td><label for="pumpcode">Pump</label></td>';
                    echo '</tr>';
                }
                else
                {
                    echo '<tr>';
                    echo '<td><label for="pumpcode">पंप</label></td>';
                    echo '</tr>';
                }
                $query = "select pumpcode,pumpname_eng,pumpname_uni from pump c";
                //$result1 = oci_parse($connection, $query); $r = oci_execute($result);
                $result1 = oci_parse($connection, $query);
                $r = oci_execute($result1);
                echo '<tr>';
                echo '<td><select name="pumpcode" style="height:35px;font-size:14px;">';
                if ($_SESSION['lng']=="English")
                {
                    echo '<option value="0" Selected>[All]</option>';
                }
                else
                {
                    echo '<option value="0" Selected>[सर्व]</option>';
                }
                while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<option value="'.$row1['PUMPCODE'].'">'.$row1['PUMPNAME_ENG'].'</option>';   
                    }
                    else
                    {
                        echo '<option value="'.$row1['PUMPCODE'].'">'.$row1['PUMPNAME_UNI'].'</option>';
                    }
                }
                echo '</select>';
                echo '<td><label for="pumpcode">*</label></td>';
                echo '</td>';
                echo '</tr>';
                if ($_SESSION['lng']=="English")
                {
                    echo '<tr>';
                    echo '<td><label for="shiftcode">Shift</label></td>';
                    echo '</tr>';
                }
                else
                {
                    echo '<tr>';
                    echo '<td><label for="shiftcode">शिफ्ट</label></td>';
                    echo '</tr>';
                }
                echo '<tr>';
                $query = "select namedetailid,name_eng,name_unicode from nst_nasaka_db.namedetail s where s.namecategoryid=475156235";
                //$result1 = oci_parse($connection, $query); $r = oci_execute($result);
                $result1 = oci_parse($connection, $query);
                $r = oci_execute($result1);
                echo '<tr>';
                echo '<td><select name="shiftcode" style="height:35px;font-size:14px;">';
                if ($_SESSION['lng']=="English")
                {
                    echo '<option value="0" Selected>[All]</option>';
                }
                else
                {
                    echo '<option value="0">[सर्व]</option>';
                }
                while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_ENG'].'</option>';   
                    }
                    else
                    {
                        echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_UNICODE'].'</option>';
                    }
                }
                echo '</select>';
                echo '<td><label for="shiftcode">*</label></td>';
                echo '</td>';
                echo '</tr>';

                echo '<td><label for="shiftcode">*</label></td>';
                echo '</td>';
                echo '</tr>';

                if ($_SESSION['lng']=="English")
                {
                    echo '<tr>';
                    echo '<td><label for="cashcreditcode">Cash Credit</label></td>';
                    echo '</tr>';
                }
                else
                {
                    echo '<tr>';
                    echo '<td><label for="cashcreditcode">रोख उधार</label></td>';
                    echo '</tr>';
                }
                echo '<tr>';
                echo '<td><select name="cashcreditcode" style="height:35px;font-size:14px;">';
                if ($_SESSION['lng']=="English")
                {
                    echo '<option value="0" Selected>[All]</option>';
                    echo '<option value="1" >Cash</option>';
                    echo '<option value="2" >Credit</option>';
                }
                else
                {
                    echo '<option value="0" Selected>[सर्व]</option>';
                    echo '<option value="0">रोख</option>';
                    echo '<option value="0">उधार</option>';
                }
                
                echo '</select>';
                echo '<td><label for="cashcreditcode">*</label></td>';
                echo '</td>';
                echo '</tr>';

 

                if ($_SESSION['lng']=="English")
                {
                    echo '<tr>';
                    echo '<td><label for="customertypecode">Customer type</label></td>';
                    echo '</tr>';
                }
                else
                {
                    echo '<tr>';
                    echo '<td><label for="customertypecode">ग्राहक प्रकार</label></td>';
                    echo '</tr>';
                }
                $query = "select namedetailid,name_eng,name_unicode from nst_nasaka_db.namedetail s where s.namecategoryid=623542547";
                //$result1 = oci_parse($connection, $query); $r = oci_execute($result);
                $result1 = oci_parse($connection, $query);
                $r = oci_execute($result1);
                echo '<tr>';
                echo '<td><select name="customertypecode" style="height:35px;font-size:14px;">';
                if ($_SESSION['lng']=="English")
                {
                    echo '<option value="0" Selected>[All]</option>';
                }
                else
                {
                    echo '<option value="0">[सर्व]</option>';
                }
                while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_ENG'].'</option>';   
                    }
                    else
                    {
                        echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_UNICODE'].'</option>';
                    }
                }
                echo '</select>';
                echo '<td><label for="customertypecode">*</label></td>';
                echo '</td>';
                echo '</tr>';



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
                    echo '<td><label for="fromdate">दिनांक</label></td>';
                    echo '</tr>';
                }
                $cdt = currentdate();
                echo '<td><input type="text" style="font-size:12pt;height:30px" name="fromdate" id="fromdate" value ="'.$cdt.'"></td>';
                echo '<td><label for="fromdate">*</label></td>';
                echo '</tr>';

/*                echo '<tr>';
                if ($_SESSION['lng']=="English")
                {
                    echo '<tr>';
                    echo '<td><label for="todate">To Date</label></td>';
                    echo '</tr>';
                }
                else
                {
                    echo '<tr>';
                    echo '<td><label for="todate">To Date</label></td>';
                    echo '</tr>';
                }
                echo '<td><input type="text" style="font-size:12pt;height:30px" name="todate" id="todate" value ="'.$cdt.'"></td>';
                echo '<td><label for="todate">*</label></td>';
                echo '</tr>';
*/
                echo '<tr>';
                echo '<td></td>';
                echo '</tr>';

                echo '<tr>';
                echo '<td><input type="submit" style="width:100px;font-size:13pt;" value="रिपोर्ट (Report)"/>';
                echo '</tr>';

                echo '</table>';
                echo '</form>';
            ?>
        </article>
        <footer>
        </footer>
    </body>
</html>