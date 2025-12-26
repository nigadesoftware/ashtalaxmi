<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    include("../info/ncryptdcrypt.php");
    require("../info/rawmaterialroutine.php");
    //Raw Material Transaction Addition or Alteration
    if (isaccessible(451230287895415)==0 and isaccessible(475124562358965)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $contractid_de = fnDecrypt($_GET['contractid']);
    if (isset($_GET['contractperformancedetailid']))
    {
        $contractperformancedetailid_de = fnDecrypt($_GET['contractperformancedetailid']);    
    }
    $flag = $_GET['flag'];

    // Opens a connection to a MySQL server
    $connection=rawmaterial_connection();

    function bankbranch(&$connection,$bankbranchid,$lng)
    {
        $query = "select r.bankbranchid,b.bankid,
        concat(d.name_unicode,' शाखा, ',n.name_unicode) as name_unicode,
        concat(d.name_eng,' Branch, ',n.name_eng) as name_eng
        from bank b,namedetail n,bankbranch r,namedetail d where b.active=1 and n.active=1 and r.active=1 and d.active=1 and r.bankid=b.bankid and r.namedetailid=d.namedetailid and b.namedetailid=n.namedetailid and r.bankbranchid=".$bankbranchid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($lng==0)
            {
                return $row['NAME_ENG'];
            }
            else
            {
                return $row['NAME_UNICODE'];
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/swapp123.css">
        <title>Contract Performance Detail</title>
        <style type="text/css">
            @font-face
            {
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
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script src="http://ajax.googleapi_mysqls.com/ajax/libs/jquery/1.11.0/jquery.min.js">
         </script>
         <script>
            $(function()
            {
                $("#servicecontractor").autocomplete({
                source: 'servicecontractor_search.php',
                minLength:2,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                $('#servicecontractorid').val(i);
                this.value = v;
                return false;}
                });
            });
            $(function () {
                $("#bankbranch").autocomplete({
                source: 'bankbranch_search.php',
                minLength:2,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                $('#bankbranchid').val(i);
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
                <li><a class="navbar" href="../mis/entitymenu.php">Entity Menu</a><br/>
                <?php
                    /*$personnamedetailtypeid_en = fnEncrypt($personnamedetailtypeid_de);
                    $personnamedetailid_en = fnEncrypt($personnamedetailid_de);
                    echo '<li><a style="color:#f48;text-align:left;" href="../data/personnamedetail_find.php?personnamedetailtypeid='.$personnamedetailtypeid_en.'">personnamedetail Find</a><br/>';*/
                    echo '<li><a class="navbar" href="../data/contract.php?contractid='.fnEncrypt($contractid_de).'&flag='.fnEncrypt('Display').'">Add/Display Contract</a></br>';
                    echo '<li><a class="navbar" href="../data/contractperformancedetail_list.php?contractid='.fnEncrypt($contractid_de).'&flag='.fnEncrypt('Display').'">Add/Display Contract Transport Detail List</a></br>';
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/contract.png" width="201" height="41px"></div>
            <?php
                $query = "select f.*,n.name_eng,n.name_unicode from contractperformancedetail f,season n where f.active=1 and n.active=1 and f.lastseasonid=n.seasonid and f.contractid = ".$contractid_de." and f.contractperformancedetailid=".$contractperformancedetailid_de;
                //echo $query;
                $result = oci_parse($connection, $query); $r = oci_execute($result);
                if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    echo '<section>';
                    //if ($flag_de == 'Display')
                    //{
                        echo '<form method="post" action="../api_action/contractperformancedetail_action.php">';
                    //}
                        echo '<table border="0" >';

                        echo '<tr>';  
                        echo '<td></td>';  
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="lastseasonid">Season</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="lastseasonid">हंगाम</label></td>';
                            echo '</tr>';
                        }
                        $query = "select seasonid,name_eng,name_unicode from season s where s.active=1 order by transactionid desc";
                        $result1 = oci_parse($connection, $query); $r = oci_execute($result1);
                        echo '<tr>';
                        echo '<td><select name="lastseasonid" style="height:35px;font-size:14px;">';
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<option value="0" Selected>[No]</option>';
                        }
                        else
                        {
                            echo '<option value="0">[नाही]</option>';
                        }
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            if ($_SESSION['lng']=="English")
                            {
                                if ($row1['SEASONID'] == $row['LASTSEASONID'])
                                {
                                    echo '<option value="'.$row1['SEASONID'].'" SELECTED>'.$row1['NAME_ENG'].'</OPTION>';
                                }
                                else
                                {
                                    echo '<option value="'.$row1['SEASONID'].'">'.$row1['NAME_ENG'].'</OPTION>';
                                }
                            }
                            else
                            {
                                if ($row1['SEASONID'] == $row['LASTSEASONID'])
                                {
                                    echo '<option value="'.$row1['SEASONID'].'" SELECTED>'.$row1['NAME_UNICODE'].'</OPTION>';
                                }
                                else
                                {
                                    echo '<option value="'.$row1['SEASONID'].'">'.$row1['NAME_UNICODE'].'</OPTION>';
                                }
                            }
                        }
                        echo '</select>';
                        echo '<td><label for="lastseasonid">*</label></td>';
                        echo '</td>';
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="lastseasonhttonnage">Last Season HT Tonnage</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="lastseasonhttonnage">मागील हंगाम तोडणी/वाहतूक टनेज</label></td>';
                            echo '</tr>';
                        }
                        echo '<tr>';
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="lastseasonhttonnage" id="lastseasonhttonnage" style="width:300px" value="'.$row['LASTSEASONHTTONNAGE'].'"></td>';
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="balance">Balance</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="balance">बाकी</label></td>';
                            echo '</tr>';
                        }
                        echo '<tr>';
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="balance" id="balance" style="width:300px" value="'.$row['BALANCE'].'"></td>';
                        echo '</tr>';

                        if ($_SESSION['lng'] == "English")
                        {
                            echo '<tr>';
                            echo '<td><label for="debitcredit">Debit Credit</br></label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="debitcredit">जमा नावे</br></label></td>';
                            echo '</tr>';
                        }
                        echo '<tr>';
                        echo '<td>';
                        echo '<select name="debitcredit" style="height:35px;font-size:14px;">';
                        if ($_SESSION['lng'] == "English")
                        {
                            if ($row['DEBITCREDIT'] == 157489650)
                            {
                                echo '<option value="157489650" Selected>Debit</option>';
                                echo '<option value="357481241" >Credit</option>';
                            }
                            elseif ($row['DEBITCREDIT'] == 357481241)
                            {
                                echo '<option value="157489650" Selected>Debit</option>';
                                echo '<option value="357481241" >Credit</option>';
                            }
                        }
                        else
                        {
                            if ($row['DEBITCREDIT'] == 157489650)
                            {
                                echo '<option value="157489650" Selected>जमा</option>';
                                echo '<option value="357481241" >नावे</option>';
                            }
                            elseif ($row['DEBITCREDIT'] == 357481241)
                            {
                                echo '<option value="157489650" Selected>जमा</option>';
                                echo '<option value="357481241" >नावे</option>';
                            }
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';

                        echo '<tr>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractid" id="contractid" style="width:300px" value ="'.$row['CONTRACTID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractperformancedetailid" id="contractperformancedetailid" style="width:300px" value ="'.$row['CONTRACTPERFORMANCEDETAILID'].'"></td>';
                        echo '</tr>';
                        
                        if ($flag=='change')
                        {
                            echo '<tr>';
                            echo '<td><input type="submit" name="btn" value="Change" style="width:100px"></td>';
                            echo '</tr>';
                        }
                        if ($flag=='delete')
                        {
                            echo '<tr>';
                            echo '<td><input type="submit" name="btn" value="Delete" style="width:100px"></td>';
                            echo '</tr>';
                        }
                        echo '<tr>';
                        echo '<td><input type="submit" name="btn" value="Reset" style="width:100px"></td>';
                        echo '</tr>';
                        
                        // Vehicle is tractor then
                        if ($row['TRANSPORTATIONVEHICLEID'] == 248768236)
                        {
                            $query = "SELECT f.* FROM contractperformancetrailerdetail f where f.active=1 and f.contractid=".$contractid_de." and f.contractperformancedetailid=".$contractperformancedetailid_de;                         
                            //echo $query;
                            //mysqli_query($connection,'SET NAMES UTF8');
                            $result = oci_parse($connection, $query); $r = oci_execute($result);
                            if (!$result)
                            {
                              die('Communication Error1');
                            }
                            // Iterate through the rows, adding XML nodes for each
                            echo '<table style="border=1px solid black; border-radius:10px; padding:0px; padding-top:6px;margin: 0px; font-family:verdana;" align="left" width=500px>';
                            if ($_SESSION["responsibilitycode"] == 475124562358965)
                                {
                                    echo '<col style="width:40%">';
                                    echo '<col style="width:20%">';
                                    echo '<col style="width:20%">';
                                    echo '<col style="width:20%">';
                                }
                            else if ($_SESSION["responsibilitycode"] == 451230287895415)
                                {
                                    echo '<col style="width:40%">';
                                    echo '<col style="width:60%">';
                                }
                            echo '<tr style="font-size:14px">';
                            echo '<td><label></br>ट्रेलर माहिती यादी</br>Trailer Detail List</label></td>';
                            echo '</tr>';
                            $contractid_en = fnEncrypt($contractid_de);
                            $contractperformancedetailid_en = fnEncrypt($contractperformancedetailid_de);
                            $contractperformancetrailerdetailid_en = fnEncrypt('000000000');
                            //if ($_SESSION["responsibilitycode"] == 325434741256025)
                            //{
                                echo '<tr style="font-size:13px">';
                                echo '<td><a style="color:#4a4" class="servicebar" href="../data/contractperformancetrailerdetail.php?contractid='.$contractid_en.'&contractperformancedetailid='.$contractperformancedetailid_en.'&contractperformancetrailordetailid='.$contractperformancetrailordetailid_en.'&flag=new">नवीन ट्रेलर माहिती</br>New Trailer Detail</a>';
                                echo '</tr>';
                            //}
                            while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                            {
                                echo '<tr style="font-size:13px">';
                                $contractperformancetrailerdetailid_en = fnEncrypt($row['CONTRACTPERFORMANCETRAILERDETAILID']);
                                echo '<td><a class="servicebar">'.$row['TRAILERNUMBER'].'</A>';
                                if ($_SESSION["responsibilitycode"] == 475124562358965)
                                {
                                    echo '<td><a style="color:#333" class="servicebar" href="../data/contractperformancetrailerdetail.php?contractid='.$contractid_en.'&contractperformancedetailid='.$contractperformancedetailid_en.'&contractperformancetrailerdetailid='.$contractperformancetrailerdetailid_en.'&flag=view'.'"><img border="0" alt="पहा (View)" src="../img/viewdata.png" width="18" height="10"></br></a>';
                                    if ($flag=='change')
                                    {
                                        echo '<td><a style="color:#333" class="servicebar" href="../data/contractperformancetrailerdetail.php?contractid='.$contractid_en.'&contractperformancedetailid='.$contractperformancedetailid_en.'&contractperformancetrailerdetailid='.$contractperformancetrailerdetailid_en.'&flag=change'.'"><img border="0" alt="बदल (Change)" src="../img/changedata.png" width="18" height="10"></br></a>';    
                                    }
                                    if ($flag=='delete')
                                    {
                                        echo '<td><a style="color:#333" class="servicebar" href="../data/contractperformancetrailerdetail.php?contractid='.$contractid_en.'&contractperformancedetailid='.$contractperformancedetailid_en.'&contractperformancetrailerdetailid='.$contractperformancetrailerdetailid_en.'&flag=delete'.'"><img border="0" alt="खोडणे (Delete)" src="../img/deletedata.png" width="18" height="10"></br></a>';
                                    }
                                }
                                else if ($_SESSION["responsibilitycode"] == 451230287895415)
                                {
                                    echo '<td><a style="color:#333" class="servicebar" href="../data/contractperformancetrailerdetail.php?contractid='.$contractid_en.'&contractperformancedetailid='.$contractperformancedetailid_en.'&contractperformancetrailerdetailid='.$contractperformancetrailerdetailid_en.'&flag=view'.'"><img border="0" alt="पहा (View)" src="../img/viewdata.png" width="18" height="10"></br></a>';
                                }
                                echo '</tr>';
                            }
                        }

                    echo '</table>';
                    echo '</form>';
                    echo '</section>';
                }
                else
                {
                    echo '<section>';
                    echo '<form method="post" action="../api_action/contractperformancedetail_action.php">';
                    echo '<table border="0" >';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="lastseasonid">Season</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="lastseasonid">हंगाम</label></td>';
                        echo '</tr>';
                    }
                    $query = "select seasonid,name_eng,name_unicode from season s where s.active=1 order by transactionid desc";
                    $result1 = oci_parse($connection, $query); $r = oci_execute($result1);
                    echo '<tr>';
                    echo '<td><select name="lastseasonid" style="height:35px;font-size:14px;">';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<option value="0" Selected>[No]</option>';
                    }
                    else
                    {
                        echo '<option value="0">[नाही]</option>';
                    }
                    while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                    {
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<option value="'.$row1['SEASONID'].'">'.$row1['NAME_ENG'].'</OPTION>';   
                        }
                        else
                        {
                            echo '<option value="'.$row1['SEASONID'].'">'.$row1['NAME_UNICODE'].'</OPTION>';
                        }
                    }
                    echo '</select>';
                    echo '<td><label for="lastseasonid">*</label></td>';
                    echo '</td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="lastseasonhttonnage">Last Season HT Tonnage</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="lastseasonhttonnage">मागील हंगाम तोडणी/वाहतूक टनेज</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="lastseasonhttonnage" id="lastseasonhttonnage" style="width:300px"></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="balance">Balance</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="balance">बाकी</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="balance" id="balance" style="width:300px"></td>';
                    echo '</tr>';

                    if ($_SESSION['lng'] == "English")
                    {
                        echo '<tr>';
                        echo '<td><label for="debitcredit">Debit Credit</br></label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="debitcredit">जमा नावे</br></label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td>';
                    echo '<select name="debitcredit" style="height:35px;font-size:14px;">';
                    if ($_SESSION['lng'] == "English")
                    {
                        echo '<option value="157489650" Selected>Debit</option>';
                        echo '<option value="357481241" >Credit</option>';
                    }
                    else
                    {
                        echo '<option value="157489650" Selected>जमा</option>';
                        echo '<option value="357481241" >नावे</option>';
                    }
                    echo '</select>';
                    echo '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractid" id="contractid" style="width:300px" value ="'.$contractid_de.'"></td>';
                    echo '</tr>';

                    echo '<tr>';
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