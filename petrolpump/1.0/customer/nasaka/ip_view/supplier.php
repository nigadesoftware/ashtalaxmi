<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    include("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require("../ip_model/supplier_db_oracle.php");
    //Petrolpump Master Addition or Alteration
    if (isaccessible(123457506)==0 and isaccessible(123457206)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $suppliercode_de = fnDecrypt($_GET['suppliercode']);
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
        <title>supplier</title>
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
                $("#servicesupplieror").autocomplete({
                source: 'servicesupplieror_search.php?iscultivator='+$('#iscultivator').val(),
                minLength:2,
                delay:200,
                select:function(event,ui)
                {var v = ui.supplier.value;
                 var i = ui.supplier.id;
                $('#servicesupplierorid').val(i);
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
                    echo '<li><a class="navbar" href="../ip_view/supplier.php?flag='.fnEncrypt('Display').'">Add/Display supplier</a></br>';
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/supplier.png" width="201" height="41px"></div>
            <?php
                $supplier1 = new supplier($connection);
                if ($supplier1->fetch($suppliercode_de))
                {
                    echo '<section>';
                    //if ($flag_de == 'Display')
                    //{
                        echo '<form method="post" action="../ip_controller/supplier_action.php">';
                    //}
                        echo '<table border="0" >';

                        echo '<tr>';  
                        echo '<td></td>';  
                        echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="suppliernameuni">supplier Name (Unicode)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="suppliernameuni">पुरवठादाराचे नाव (युनिकोड)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="suppliernameuni" id="suppliernameuni" value="'.$supplier1->suppliernameuni.'" style="width:300px"></td>';
                    echo '<td><label for="suppliernameuni">*</label></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="suppliernameeng">supplier Name (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="suppliernameeng">पुरवठादाराचे नाव (इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="suppliernameeng" id="suppliernameeng" value="'.$supplier1->suppliernameeng.'" style="width:300px"></td>';
                    echo '<td><label for="suppliernameeng">*</label></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="address">Address</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="address">पत्ता</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="address" id="address" value="'.$supplier1->address.'" style="width:300px"></td>';
                   /* echo '<td><label for="address">*</label></td>';
                    echo '</tr>';*/


                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="contactnumber">Contact Number (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="contactnumber">संपर्क क्रमांक (इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="contactnumber" id="contactnumber" value="'.$supplier1->contactnumber.'" style="width:300px"></td>';
                    /*echo '<td><label for="contactnumber">*</label></td>';
                    echo '</tr>';*/

                  if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="emailid">Emailid (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="emailid">इमेल (इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="emailid" id="emailid" value="'.$supplier1->emailid.'" style="width:300px"></td>';
                   /* echo '<td><label for="emailid">*</label></td>';
                    echo '</tr>';*/


                if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="gstnumber">GST Number (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="gstnumber">जी एस टी(इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="gstnumber" id="gstnumber" value="'.$supplier1->gstnumber.'" style="width:300px"></td>';
                   /* echo '<td><label for="gstnumber">*</label></td>';
                    echo '</tr>';*/

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="vatnumber">VAT Number (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="vatnumber">VATva(इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="vatnumber" id="vatnumber" value="'.$supplier1->vatnumber.'" style="width:300px"></td>';
                   /* echo '<td><label for="vatnumber">*</label></td>';
                    echo '</tr>'*/;



                    echo '<td></td>';  
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="suppliercode" id="suppliercode" value="'.$supplier1->suppliercode.'" style="width:300px"></td>';
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
                    echo '<form method="post" action="../ip_controller/supplier_action.php">';
                    echo '<table border="0" >';

 if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="suppliernameuni">supplier Name (Unicode)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="suppliernameuni">पुरवठादाराचे नाव (युनिकोड)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="suppliernameuni" id="suppliernameuni" value="'.$supplier1->suppliernameuni.'" style="width:300px"></td>';
                    echo '<td><label for="suppliernameuni">*</label></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="suppliernameeng">supplier Name (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="suppliernameeng">पुरवठादाराचे नाव (इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="suppliernameeng" id="suppliernameeng" value="'.$supplier1->suppliernameeng.'" style="width:300px"></td>';
                    echo '<td><label for="suppliernameeng">*</label></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="address">Address</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="address">पत्ता</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="address" id="address" value="'.$supplier1->address.'" style="width:300px"></td>';
                   /* echo '<td><label for="address">*</label></td>';
                    echo '</tr>';*/


                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="contactnumber">Contact Number (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="contactnumber">संपर्क क्रमांक (इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="contactnumber" id="contactnumber" value="'.$supplier1->contactnumber.'" style="width:300px"></td>';
                   /* echo '<td><label for="contactnumber">*</label></td>';
                    echo '</tr>';*/

                  if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="emailid">Emailid (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="emailid">इमेल (इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="emailid" id="emailid" value="'.$supplier1->emailid.'" style="width:300px"></td>';
                   /* echo '<td><label for="emailid">*</label></td>';
                    echo '</tr>';*/


                if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="gstnumber">GST Number (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="gstnumber">जी एस टी(इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="gstnumber" id="gstnumber" value="'.$supplier1->gstnumber.'" style="width:300px"></td>';
                   /* echo '<td><label for="gstnumber">*</label></td>';
                    echo '</tr>';*/

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="vatnumber">VAT Number (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="vatnumber">VATva(इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="vatnumber" id="vatnumber" value="'.$supplier1->vatnumber.'" style="width:300px"></td>';
                   /* echo '<td><label for="vatnumber">*</label></td>';
                    echo '</tr>';*/




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