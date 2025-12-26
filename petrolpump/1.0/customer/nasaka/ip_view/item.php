<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    include("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require("../ip_model/item_db_oracle.php");
    //Petrolpump Master Addition or Alteration
    if (isaccessible(123457506)==0 and isaccessible(123457206)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $itemcode_de = fnDecrypt($_GET['itemcode']);
    $flag = $_GET['flag'];
    // Opens a connection to a MySQL server
    $connection=petrolpump_connection();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/w3.css">
        <title>Item</title>
        <style type="text/css">
            @font-face {
            font-family: siddhanta;
            src: url("../fonts/siddhanta.ttf");
            font-weight: normal;
            }
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
                font-family: siddhanta;
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
                font-family: siddhanta;
                background-color: #fef;
            }
            label
            {
                color: #333;
                font-family: siddhanta;
                font-size: 18px;
                font-weight: normal;
            }
        </style>
        <link rel="stylesheet" href="../css/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="../js/jquery-1.10.2.js"></script>
        <script src="../js/ui/1.11.4/jquery-ui.js"></script>
        <!-- <script src="../js/1.11.0/jquery.min.js"> -->
         <script>
            $(function()
            {
                $("#serviceitemor").autocomplete({
                source: 'serviceitemor_search.php?iscultivator='+$('#iscultivator').val(),
                minLength:2,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                $('#serviceitemorid').val(i);
                this.value = v;
                return false;}
                });
            });
        </script>
         <script>
            $(document).ready(function(){
             setInterval(function(){cache_clear()},3600000);
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
                <li><a class="navbar" href="../ip_view/entitymenu.php">Entity Menu</a><br/>
                <?php
                    /*$personnamedetailtypeid_en = fnEncrypt($personnamedetailtypeid_de);
                    $personnamedetailid_en = fnEncrypt($personnamedetailid_de);
                    echo '<li><a style="color:#f48;text-align:left;" href="../ip_view/personnamedetail_find.php?personnamedetailtypeid='.$personnamedetailtypeid_en.'">personnamedetail Find</a><br/>';*/
                    echo '<li><a class="navbar" href="../ip_view/item.php?flag='.fnEncrypt('Display').'">Add/Display item</a></br>';
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/item.png" width="201" height="41px"></div>
            <?php
                $item1 = new item($connection);
                if ($item1->fetch($itemcode_de))
                {
                    echo '<section>';
                    //if ($flag_de == 'Display')
                    //{
                        echo '<form method="post" action="../ip_controller/item_action.php">';
                    //}
                        echo '<table border="0" >';

                        echo '<tr>';  
                        echo '<td></td>';  
                        echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="itemnameuni">Item Name (Unicode)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="itemnameuni">मालाचे नाव (युनिकोड)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="itemnameuni" id="itemnameuni" value="'.$item1->itemnameuni.'" style="width:300px"></td>';
                    echo '<td><label for="itemnameuni">*</label></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="itemnameeng">Item Name (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="itemnameeng">मालाचे नाव (इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="itemnameeng" id="itemnameeng" value="'.$item1->itemnameeng.'" style="width:300px"></td>';
                    echo '<td><label for="itemnameeng">*</label></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="unit">Unit</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="unit">युनिट</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="unit" id="unit" value="'.$item1->unit.'" style="width:300px"></td>';
                    echo '<td><label for="unit">*</label></td>';
                    echo '</tr>';

                    echo '<td></td>';  
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="itemcode" id="itemcode" value="'.$item1->itemcode.'" style="width:300px"></td>';
                    echo '</tr>';

                    if ($_SESSION['responsibilitycode']==123457206)
                    {
                        echo '<tr>';
                        echo '<td><input type="submit" name="btn" value="Change" style="width:100px"></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><input type="submit" name="btn" value="Delete" style="width:100px"></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="submit" name="btn" value="Reset" style="width:100px"></td>';
                    echo '</tr>';

                    echo '</table>';
                    echo '</form>';
                    echo '</section>';
                }
                else
                {
                    echo '<section>';
                    echo '<form method="post" action="../ip_controller/item_action.php">';
                    echo '<table border="0" >';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="itemnameuni">Item Name (Unicode)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="itemnameuni">मालाचे नाव (युनिकोड)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" autofocus style="font-size:12pt;height:30px" name="itemnameuni" id="itemnameuni" style="width:300px"></td>';
                    echo '<td><label for="itemnameuni">*</label></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="itemnameeng">Item Name (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="itemnameeng">मालाचे नाव (इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="itemnameeng" id="itemnameeng" style="width:300px"></td>';
                    echo '<td><label for="itemnameeng">*</label></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="unit">Unit</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="unit">युनिट</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="unit" id="unit" style="width:300px"></td>';
                    echo '<td><label for="unit">*</label></td>';
                    echo '</tr>';

                    echo '<td></td>';  
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="submit" name="btn" value="Add" style="width:100px"</button>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="submit" name="btn" value="Display" style="width:100px"</button>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="submit" name="btn" value="Reset" style="width:100px"</button>';
                    echo '</tr>';
                    echo '</table>';
                    echo '</form>';
                    echo '</section>';
                }
            ?>
        </article>
        <footer>
        </footer>
    </body>
</html>