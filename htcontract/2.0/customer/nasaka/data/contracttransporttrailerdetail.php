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
    $contracttransportdetailid_de = fnDecrypt($_GET['contracttransportdetailid']);
    if (isset($_GET['contracttransporttrailerdetid']))
    {
        $contracttransporttrailerdetid_de = fnDecrypt($_GET['contracttransporttrailerdetid']);    
    }
    $flag = $_GET['flag'];

    // Opens a connection to a MySQL server
    $connection=rawmaterial_connection();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/swapp123.css">
        <title>Contract Transport Trailer Detail</title>
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
        <script src="http://ajax.googleapi_mysqls.com/ajax/libs/jquery/1.11.0/jquery.min.js">
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
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    </head>
    <body>
        <nav "w3-container">
            <ul class="navbar">
                <li><a class="navbar" href="../data/entitymenu.php">Entity Menu</a><br/>
                <?php
                    /*$personnamedetailtypeid_en = fnEncrypt($personnamedetailtypeid_de);
                    $personnamedetailid_en = fnEncrypt($personnamedetailid_de);
                    echo '<li><a style="color:#f48;text-align:left;" href="../data/personnamedetail_find.php?personnamedetailtypeid='.$personnamedetailtypeid_en.'">personnamedetail Find</a><br/>';*/
                    /*echo '<li><a class="navbar" href="../data/contract.php?contractid='.fnEncrypt($contractid_de).'&flag='.fnEncrypt('Display').'">Add/Display Field Plot</a></br>';*/
                    echo '<li><a class="navbar" href="../data/contract.php?contractid='.fnEncrypt($contractid_de).'&flag='.fnEncrypt('Display').'">Add/Display Contract</a></br>';
                    echo '<li><a class="navbar" href="../data/contracttransportdetail_list.php?contractid='.fnEncrypt($contractid_de).'&flag='.fnEncrypt('Display').'">Add/Display Contract Transport Detail List</a></br>';
                    echo '<li><a class="navbar" href="../data/contracttransportdetail.php?contractid='.fnEncrypt($contractid_de).'&contracttransportdetailid='.fnEncrypt($contracttransportdetailid_de).'&flag='.fnEncrypt('Display').'">Add/Display Contract Transport Detail</a></br>';
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/contract.png" width="201" height="41px"></div>
            <?php
                $query = "select f.* from contracttransporttrailerdetail f where f.active=1 and f.contractid = ".$contractid_de." and f.contracttransportdetailid=".$contracttransportdetailid_de." and f.contracttransporttrailerdetid=".$contracttransporttrailerdetid_de;
                //echo $query;
                $result = oci_parse($connection, $query); $r = oci_execute($result);
                if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    echo '<section>';
                    //if ($flag_de == 'Display')
                    //{
                        echo '<form method="post" action="../api_action/contracttransporttrailerdetail_action.php">';
                    //}
                        echo '<table border="0" >';

                        echo '<tr>';  
                        echo '<td></td>';  
                        echo '</tr>';

                    if ($_SESSION['lng'] == "English")
                    {
                        echo '<tr>';
                        echo '<td><label for="trailermfgid">Trailer Manufacturer</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>';
                        echo '<select name="trailermfgid" style="height:35px;font-size:14px;">';
                        echo '<option value="0" Selected>[No]</option>';  
                        $query = "select * from namedetail n where n.active=1 and namecategoryid=854126547 order by name_eng"; 
                        $result1 = oci_parse($connection, $query);
                        $r = oci_execute($result1);
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            if ($row1['NAMEDETAILID'] == $row['TRAILERMFGID'])
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'" Selected>'.$row1['NAME_ENG'].'</option>';   
                            }
                            else
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_ENG'].'</option>';   
                            }
                            
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="trailermfgid">ट्रेलर निर्माता</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>';
                        echo '<select name="trailermfgid" style="height:35px;font-size:14px;">';
                        echo '<option value="0" Selected>[नाही]</option>';   
                        $result1 = oci_parse($connection, "select * from namedetail n where n.active=1 and namecategoryid=854126547 order by name_unicode");
                        $r = oci_execute($result1);
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            if ($row1['NAMEDETAILID'] == $row['TRAILERMFGID'])
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'" Selected>'.$row1['NAME_UNICODE'].'</option>';   
                            }
                            else
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_UNICODE'].'</option>';                                   
                            }
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';
                    }
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="trailernumber">Trailer Number</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="trailernumber">ट्रेलर नंबर</label></td>';
                            echo '</tr>';
                        }

                        echo '<tr>';
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="trailernumber" id="trailernumber" style="width:300px" value="'.$row['TRAILERNUMBER'].'"></td>';
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="rtopassingdatetime">RTO Passing Date</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="rtopassingdatetime">आरटीओ पासिंग दिनांक</label></td>';
                            echo '</tr>';
                        }
                        if ($row['RTOPASSINGDATETIME']!='')
                        {
                            $rtopassingdatetime = date('d/m/Y',strtotime($row['RTOPASSINGDATETIME']));
                        }
                        else
                        {
                            $rtopassingdatetime = '';
                        }
                        echo '<tr>';
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="rtopassingdatetime" id="rtopassingdatetime" style="width:300px" value="'.$rtopassingdatetime.'"></td>';
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="insurancepaiddatetime">Insurance Paid Date</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="insurancepaiddatetime">विमा देय दिनांक</label></td>';
                            echo '</tr>';
                        }
                        if ($row['INSURANCEPAIDDATETIME']!='')
                        {
                            $insurancepaiddatetime = date('d/m/Y',strtotime($row['INSURANCEPAIDDATETIME']));
                        }
                        else
                        {
                            $insurancepaiddatetime = '';
                        }
                        echo '<tr>';
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="insurancepaiddatetime" id="insurancepaiddatetime" style="width:300px" value="'.$insurancepaiddatetime.'"></td>';
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="isrcattached">RC Xerox Attached?</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="isrcattached">आरसी झेरॉक्स जोडली का?</label></td>';
                            echo '</tr>';
                        }

                        echo '<tr>';
                        echo '<td>';
                        echo '<select name="isrcattached" style="height:35px;font-size:14px;">';
        
                        if ($_SESSION['lng']=="English")
                        {
                            if ($row['ISRCATTACHED']==0)
                            {
                                echo '<option value="0" Selected>No</option>';   
                                echo '<option value="1">Yes</option>';       
                            }
                            else
                            {
                                echo '<option value="0">No</option>';   
                                echo '<option value="1" Selected>Yes</option>';       
                            }
                        }
                        else
                        {
                            if ($row['ISRCATTACHED']==0)
                            {
                                echo '<option value="0" Selected>नाही</option>';
                                echo '<option value="1">होय</option>';
                            }
                            else
                            {
                                echo '<option value="0">नाही</option>';
                                echo '<option value="1" Selected>होय</option>';
                            }
                        }    
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="istcattached">TC Xerox Attached?</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="istcattached">टीसी झेरॉक्स जोडली का?</label></td>';
                            echo '</tr>';
                        }

                        echo '<tr>';
                        echo '<td>';
                        echo '<select name="istcattached" style="height:35px;font-size:14px;">';
        
                        if ($_SESSION['lng']=="English")
                        {
                            if ($row['ISRCATTACHED']==0)
                            {
                                echo '<option value="0" Selected>No</option>';   
                                echo '<option value="1">Yes</option>';   
                            }
                            else
                            {
                                echo '<option value="0">No</option>';   
                                echo '<option value="1" Selected>Yes</option>';   
                            }
                        }
                        else
                        {
                            if ($row['ISRCATTACHED']==0)
                            {
                                echo '<option value="0" Selected>नाही</option>';   
                                echo '<option value="1">होय</option>';   
                            }
                            else
                            {
                                echo '<option value="0">नाही</option>';   
                                echo '<option value="1" Selected>होय</option>';   
                            }
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';

                        echo '<tr>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractid" id="contractid" style="width:300px" value ="'.$row['CONTRACTID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contracttransportdetailid" id="contracttransportdetailid" style="width:300px" value ="'.$row['CONTRACTTRANSPORTDETAILID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contracttransporttrailerdetid" id="contracttransporttrailerdetid" style="width:300px" value ="'.$row['CONTRACTTRANSPORTTRAILERDETID'].'"></td>';
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
                        
                    echo '</table>';
                    echo '</form>';
                    echo '</section>';
                }
                else
                {
                    echo '<section>';
                    echo '<form method="post" action="../api_action/contracttransporttrailerdetail_action.php">';
                    echo '<table border="0" >';

                    if ($_SESSION['lng'] == "English")
                    {
                        echo '<tr>';
                        echo '<td><label for="trailermfgid">Trailer Manufacturer</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>';
                        echo '<select name="trailermfgid" style="height:35px;font-size:14px;">';
                        echo '<option value="0" Selected>[No]</option>';  
                        $query = "select * from namedetail n where n.active=1 and namecategoryid=854126547 order by name_eng"; 
                        $result1 = oci_parse($connection, $query);
                        $r = oci_execute($result1);
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            if ($row1['NAMEDETAILID'] == $row['TRAILERMFGID'])
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'" Selected>'.$row1['NAME_ENG'].'</option>';   
                            }
                            else
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_ENG'].'</option>';   
                            }
                            
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="trailermfgid">ट्रेलर निर्माता</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>';
                        echo '<select name="trailermfgid" style="height:35px;font-size:14px;">';
                        echo '<option value="0" Selected>[नाही]</option>';   
                        $result1 = oci_parse($connection, "select * from namedetail n where n.active=1 and namecategoryid=854126547 order by name_unicode");
                        $r = oci_execute($result1);
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            if ($row1['NAMEDETAILID'] == $row['TRAILERMFGID'])
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'" Selected>'.$row1['NAME_UNICODE'].'</option>';   
                            }
                            else
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_UNICODE'].'</option>';                                   
                            }
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';
                    }

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="trailernumber">Trailer Number</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="trailernumber">ट्रेलर नंबर</label></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="trailernumber" id="trailernumber" style="width:300px"></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="rtopassingdatetime">RTO Passing Date</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="rtopassingdatetime">आरटीओ पासिंग दिनांक</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="rtopassingdatetime" id="rtopassingdatetime" style="width:300px"></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="insurancepaiddatetime">Insurance Paid Date</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="insurancepaiddatetime">विमा देय दिनांक</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="insurancepaiddatetime" id="insurancepaiddatetime" style="width:300px"></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="isrcattached">RC Xerox Attached?</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="isrcattached">आरसी झेरॉक्स जोडली का?</label></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td>';
                    echo '<select name="isrcattached" style="height:35px;font-size:14px;">';
    
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<option value="0" Selected>No</option>';   
                        echo '<option value="1">Yes</option>';   
                    }
                    else
                    {
                        echo '<option value="0" Selected>नाही</option>';   
                        echo '<option value="1">होय</option>';   
                    }    
                    echo '</select>';
                    echo '</td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="istcattached">TC Xerox Attached?</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="istcattached">टीसी झेरॉक्स जोडली का?</label></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td>';
                    echo '<select name="istcattached" style="height:35px;font-size:14px;">';
    
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<option value="0" Selected>No</option>';   
                        echo '<option value="1">Yes</option>';   
                    }
                    else
                    {
                        echo '<option value="0" Selected>नाही</option>';   
                        echo '<option value="1">होय</option>';   
                    }    
                    echo '</select>';
                    echo '</td>';
                    echo '</tr>';


                    echo '<tr>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractid" id="contractid" style="width:300px" value ="'.$contractid_de.'"></td>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contracttransportdetailid" id="contracttransportdetailid" style="width:300px" value ="'.$contracttransportdetailid_de.'"></td>';
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