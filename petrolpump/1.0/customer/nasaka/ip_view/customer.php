<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    include("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require("../ip_model/customer_db_oracle.php");
    //Petrolpump Master Addition or Alteration
    if (isaccessible(123457506)==0 and isaccessible(123457206)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $customercode_de = fnDecrypt($_GET['customercode']);
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
        <title>customer</title>
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
                $("#servicecustomeror").autocomplete({
                source: 'servicecustomeror_search.php?iscultivator='+$('#iscultivator').val(),
                minLength:2,
                delay:200,
                select:function(event,ui)
                {var v = ui.customer.value;
                 var i = ui.customer.id;
                $('#servicecustomerorid').val(i);
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
                    echo '<li><a class="navbar" href="../ip_view/customer.php?flag='.fnEncrypt('Display').'">Add/Display customer</a></br>';
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/customer.png" width="201" height="41px"></div>
            <?php
                $customer1 = new customer($connection);
                if ($customer1->fetch($customercode_de))
                {
                    echo '<section>';
                    //if ($flag_de == 'Display')
                    //{
                        echo '<form method="post" action="../ip_controller/customer_action.php">';
                    //}
                        echo '<table border="0" >';

                        echo '<tr>';  
                        echo '<td></td>';  
                        echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="customernameuni">Customer Name (Unicode)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="customernameuni">ग्राहकाचे नाव (युनिकोड)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="customernameuni" id="customernameuni" value="'.$customer1->customernameuni.'" style="width:300px"></td>';
                    echo '<td><label for="customernameuni">*</label></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="customernameeng">Customer Name (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="customernameeng">ग्राहकाचे नाव (इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="customernameeng" id="customernameeng" value="'.$customer1->customernameeng.'" style="width:300px"></td>';
                    echo '<td><label for="customernameeng">*</label></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="vehiclenumber">Vehicle Number</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="vehiclenumber">वाहन नं</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="vehiclenumber" id="vehiclenumber" value="'.$customer1->vehiclenumber.'" style="width:300px"></td>';
                    echo '<td><label for="vehiclenumber">*</label></td>';
                    echo '</tr>';

                    //Combo start
                    echo '<tr>';  
                    echo '<td></td>';  
                    echo '</tr>';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="customertypecode">Customer type code (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="customertypecode">कस्टमर प्रकार कोड (इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    $query1 = "select namedetailid,name_eng,name_unicode from nst_nasaka_db.namedetail s where namecategoryid=623542547 order by name_eng";
                    //$result1 = mysqli_query($connection, $query1);
                    $result1 = oci_parse($connection, $query1);
                    $r = oci_execute($result1);
                    echo '<tr>';
                    echo '<td><select name="customertypecode" style="height:35px;font-size:14px;">';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<option value="0">[No]</option>';
                    }
                    else
                    {
                        echo '<option value="0">[नाही]</option>';
                    }
                    while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                    {
                        if ($_SESSION['lng']=="English")
                        {
                            if ($row1['NAMEDETAILID']==$customer1->customertypecode)
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'" Selected>'.$row1['NAME_ENG'].'</option>';
                            }
                            else
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_ENG'].'</option>';
                            }
                        }
                        else
                        {
                            if ($row1['NAMEDETAILID']==$customer1->customertypecode)
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'" Selected>'.$row1['NAME_UNICODE'].'</option>';
                            }
                            else
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_UNICODE'].'</option>';
                            }
                        }
                    }
                    echo '</select>';
                    echo '<td><label for="namedetailid">*</label></td>';
                    echo '</td>';
                    echo '</tr>';
                    //Combo end


                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="refcode">Referance code (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="refcode">संदर्भ कोड (इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="refcode" id="refcode" value="'.$customer1->refcode.'" style="width:300px"></td>';
                    echo '<td><label for="refcode">*</label></td>';
                    echo '</tr>';



                    echo '<td></td>';  
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="customercode" id="customercode" value="'.$customer1->customercode.'" style="width:300px"></td>';
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
                    echo '<form method="post" action="../ip_controller/customer_action.php">';
                    echo '<table border="0" >';

                if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="customernameuni">Customer Name (Unicode)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="customernameuni">ग्राहकाचे नाव (युनिकोड)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="customernameuni" id="customernameuni" value="'.$customer1->customernameuni.'" style="width:300px"></td>';
                    echo '<td><label for="customernameuni">*</label></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="customernameeng">Customer Name (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="customernameeng">ग्राहकाचे नाव (इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="customernameeng" id="customernameeng" value="'.$customer1->customernameeng.'" style="width:300px"></td>';
                    echo '<td><label for="customernameeng">*</label></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="vehiclenumber">Vehicle Number</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="vehiclenumber">वाहन नं</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="vehiclenumber" id="vehiclenumber" value="'.$customer1->vehiclenumber.'" style="width:300px"></td>';
                    echo '<td><label for="vehiclenumber">*</label></td>';
                    echo '</tr>';
                    
                    //Combo start
                    echo '<tr>';  
                    echo '<td></td>';  
                    echo '</tr>';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="customertypecode">Customer Type Code (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="customertypecode">कस्टमर प्रकार कोड (इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    $query1 = "select namedetailid,name_eng,name_unicode from nst_nasaka_db.namedetail s where namecategoryid=623542547 order by name_eng";
                    //$result1 = mysqli_query($connection, $query1);
                    $result1 = oci_parse($connection, $query1);
                    $r = oci_execute($result1);
                    echo '<tr>';
                    echo '<td><select name="customertypecode" style="height:35px;font-size:14px;">';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<option value="0">[No]</option>';
                    }
                    else
                    {
                        echo '<option value="0">[नाही]</option>';
                    }
                    while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                    {
                        if ($_SESSION['lng']=="English")
                        {
                            if ($row1['NAMEDETAILID']==$customer1->customertypecode)
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'" Selected>'.$row1['NAME_ENG'].'</option>';
                            }
                            else
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_ENG'].'</option>';
                            }
                        }
                        else
                        {
                            if ($row1['NAMEDETAILID']==$customer1->customertypecode)
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'" Selected>'.$row1['NAME_UNICODE'].'</option>';
                            }
                            else
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_UNICODE'].'</option>';
                            }
                        }
                    }
                    echo '</select>';
                    echo '<td><label for="namedetailid">*</label></td>';
                    echo '</td>';
                    echo '</tr>';
                    //Combo end
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="refcode">Referance code (English)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="refcode">संदर्भ कोड (इंग्रजी)</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="refcode" id="refcode" value="'.$customer1->refcode.'" style="width:300px"></td>';
                    echo '<td><label for="refcode">*</label></td>';
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