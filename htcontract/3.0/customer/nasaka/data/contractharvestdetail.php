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
    if (isset($_GET['contractharvestdetailid']))
    {
        $contractharvestdetailid_de = fnDecrypt($_GET['contractharvestdetailid']);    
    }
    $contractcategoryid_de = fnDecrypt($_GET['contractcategoryid']);    
    $flag = $_GET['flag'];

    // Opens a connection to a MySQL server
    $connection=rawmaterial_connection();
    function bankbranch(&$connection,$bankbranchid,$lng)
    {
        $query = "select r.bankbranchid,r.name_unicode,r.name_eng
        from bankbranch r where r.bankbranchid=".$bankbranchid;
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
    function servicecontractorid(&$connection,$contractid)
    {
        $query = "select r.servicecontractorid
        from contract r where r.contractid=".$contractid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['SERVICECONTRACTORID'];
        }
        else
        {
            return '';
        }
    }
    function servicecontractorname(&$connection,$contractid,$lng)
    {
        $query = "select s.name_unicode,s.name_eng
        from contract r,servicecontractor s 
        where r.servicecontractorid=s.servicecontractorid 
        and r.contractid=".$contractid;
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
        else
        {
            return '';
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/swapp123.css">
        <title>Contract Harvest Detail</title>
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
        <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script src="http://ajax.googleapi_mysqls.com/ajax/libs/jquery/1.11.0/jquery.min.js">
         </script> -->
         <link rel="stylesheet" href="../css/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="../js/jquery-1.10.2.js"></script>
        <script src="../js/ui/1.11.4/jquery-ui.js"></script>
      
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
                    echo '<li><a class="navbar" href="../data/contractharvestdetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$_GET['contractcategoryid'].'&flag='.fnEncrypt('Display').'">Add/Display Contract Harvester Detail List</a></br>';
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/contract.png" width="201" height="41px"></div>
            <?php
                $query = "select f.*,s.name_eng as contractorname_eng,s.name_unicode as contractorname_unicode from contractharvestdetail f, servicecontractor s where f.active=1 and s.active=1 and f.servicecontractorid=s.servicecontractorid and f.contractid = ".$contractid_de." and f.contractharvestdetailid=".$contractharvestdetailid_de;
                //echo $query;
                $result = oci_parse($connection, $query); $r = oci_execute($result);
                if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    echo '<section>';
                    //if ($flag_de == 'Display')
                    //{
                        echo '<form method="post" action="../api_action/contractharvestdetail_action.php">';
                    //}
                        echo '<table border="0" >';

                        echo '<tr>';  
                        echo '<td></td>';  
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="servicecontractorid">Service Contractor</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="servicecontractorid">सेवा कंत्राटदार</label></td>';
                            echo '</tr>';
                        }
                        echo '<tr>';
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<td><input type="text" style="font-size:12pt;height:30px" name="servicecontractor" id="servicecontractor" style="width:300px" value="'.$row['CONTRACTORNAME_ENG'].'"></td>';
                        }
                        else
                        {
                            echo '<td><input type="text" style="font-size:12pt;height:30px" name="servicecontractor" id="servicecontractor" style="width:300px" value="'.$row['CONTRACTORNAME_UNICODE'].'"></td>';
                        }
                        echo '<td><label for="servicecontractorid">*</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><input readonly="readonly" type="text" style="font-size:12pt;height:30px" name="servicecontractorid" id="servicecontractorid" value="'.$row['SERVICECONTRACTORID'].'"></td>';
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="noofvehicles">No. of Bulluckcart</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="noofvehicles">बैलगाड्यांची संख्या</label></td>';
                            echo '</tr>';
                        }

                        echo '<tr>';
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="noofvehicles" id="noofvehicles" style="width:300px" value="'.$row['NOOFVEHICLES'].'"></td>';
                        echo '<td><label for="noofvehicles">*</label></td>';
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="noofharvesterlabour">No. of Harvester Labour</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="noofharvesterlabour">कोयत्यांची संख्या</label></td>';
                            echo '</tr>';
                        }

                        echo '<tr>';
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="noofharvesterlabour" id="noofharvesterlabour" style="width:300px" value="'.$row['NOOFHARVESTERLABOUR'].'"></td>';
                        echo '<td><label for="noofharvesterlabour">*</label></td>';
                        echo '</tr>';
                        
                        if ($_SESSION['lng'] == "English")
                        {
                            echo '<tr>';
                            echo '<td><label for="transportationuptovehicleid">Transportation Upto Vehicle</label></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>';
                            echo '<select name="transportationuptovehicleid" style="height:35px;font-size:14px;">';
                            echo '<option value="0">[No]</option>';   
                            if ($contractcategoryid_de ==785415263)
                            {
                                $query = "select * from namedetail n where n.active=1 and namecategoryid=671529934 and namedetailid = 125478451 order by name_unicode";
                                $result1 = oci_parse($connection, $query);
                            }
                            else
                            {
                                $query = "select * from namedetail n where n.active=1 and namecategoryid=671529934 and namedetailid <> 125478451 order by name_unicode";
                                $result1 = oci_parse($connection, $query);
                            }
                            $r = oci_execute($result1);
                            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                            {
                                if ($row1['NAMEDETAILID']==$row['TRANSPORTATIONUPTOVEHICLEID'])
                                {
                                    echo '<option value="'.$row1['NAMEDETAILID'].'" SELECTED>'.$row1['NAME_ENG'].'</OPTION>';
                                }
                                else
                                {
                                    echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_ENG'].'</OPTION>';   
                                }
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '<td><label for="transportationuptovehicleid">*</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="transportationuptovehicleid">वाहना पर्यंतची वहातुक</label></td>';
                            echo '</tr>';

                            echo '<tr>';
                            echo '<td>';
                            echo '<select name="transportationuptovehicleid" style="height:35px;font-size:14px;">';
                            echo '<option value="0" Selected>[नाही]</option>';   
                            $result1 = mysqli_query($connection, "select * from namedetail n where n.active=1 and namecategoryid=671529934 order by name_unicode");
                            //if ($contractcategoryid_de ==785415263)
                            //{
                                //and namedetailid = 125478451
                                $query = "select * from namedetail n where n.active=1 and namecategoryid=671529934  order by name_unicode";
                                $result1 = oci_parse($connection, $query);
                            //}
                            /* else
                            {
                                $query = "select * from namedetail n where n.active=1 and namecategoryid=671529934 and namedetailid <> 125478451 order by name_unicode";
                                $result1 = oci_parse($connection, $query);
                            } */
                            $r = oci_execute($result1);
                            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                            {
                                if ($row1['NAMEDETAILID']==$row['TRANSPORTATIONUPTOVEHICLEID'])
                                {
                                    echo '<option value="'.$row1['NAMEDETAILID'].'" SELECTED>'.$row1['NAME_UNICODE'].'</OPTION>';   
                                }
                                else
                                {
                                    echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_UNICODE'].'</OPTION>';   
                                }
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '<td><label for="transportationuptovehicleid">*</label></td>';
                            echo '</tr>';
                        }
                        echo '<tr>';
                        echo '<td><label for="bankbranch">Bank Branch</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        if ($_SESSION['lng']=='English')
                        {
                            echo '<td><input type="text" style="font-size:12pt;height:30px" name="bankbranch" id="bankbranch" value="'.bankbranch($connection,$row['BANKBRANCHID'],0).'" STYLE="WIDTH:300PX"></td>';
                        }
                        else
                        {
                            echo '<td><input type="text" style="font-size:12pt;height:30px" name="bankbranch" id="bankbranch" value="'.bankbranch($connection,$row['BANKBRANCHID'],1).'" STYLE="WIDTH:300PX"></td>';
                        }
                        
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="bankbranchid" id="bankbranchid" value="'.$row['BANKBRANCHID'].'"></td>';
                        echo '</tr>';
                        
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="chequenumber">Cheque Number</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="chequenumber">चेक क्रमांक</label></td>';
                            echo '</tr>';
                        }

                        echo '<tr>';
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="chequenumber" id="chequenumber" style="width:300px" value="'.$row['CHEQUENUMBER'].'"></td>';
                        echo '</tr>';

                        echo '<tr>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractid" id="contractid" style="width:300px" value ="'.$row['CONTRACTID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractharvestdetailid" id="contractharvestdetailid" style="width:300px" value ="'.$row['CONTRACTHARVESTDETAILID'].'"></td>';
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
                        
                        $contractreferencecategoryid_en = fnEncrypt('254156358');
                        $contractreferencedetailid_en = fnEncrypt($row['CONTRACTHARVESTDETAILID']);
                        /* echo '<tr>';
                        echo '<td><a href="../data/contractaadhardetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractreferencecategoryid='.$contractreferencecategoryid_en.'&contractreferencedetailid='.$contractreferencedetailid_en.'">Aadhar Detail</a></td>';
                        echo '</tr>'; */

                        echo '<tr>';
                        echo '<td><a href="../data/contractfingerprintdetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractreferencecategoryid='.$contractreferencecategoryid_en.'&contractreferencedetailid='.$contractreferencedetailid_en.'">Finger Print Detail</a></td>';
                        echo '</tr>';

                        echo '<tr>';
                        echo '<td><a href="../data/contractphotodetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractreferencecategoryid='.$contractreferencecategoryid_en.'&contractreferencedetailid='.$contractreferencedetailid_en.'">Photo Detail</a></td>';
                        echo '</tr>';

                    echo '</table>';
                    echo '</form>';
                    echo '</section>';
                }
                else
                {
                    echo '<section>';
                    echo '<form method="post" action="../api_action/contractharvestdetail_action.php">';
                    echo '<table border="0" >';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="servicecontractorid">Service Contractor</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="servicecontractorid">सेवा कंत्राटदार</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="servicecontractor" id="servicecontractor" style="width:300px" value="'.servicecontractorname($connection,$contractid_de,1).'"></td>';
                    echo '<td><label for="servicecontractorid">*</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input readonly="readonly" tabindex="-1" type="text" style="font-size:12pt;height:30px" name="servicecontractorid" id="servicecontractorid" value="'.servicecontractorid($connection,$contractid_de).'"></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="noofvehicles">No. of Bulluckcart</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="noofvehicles">बैलगाड्यांची संख्या</label></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="noofvehicles" id="noofvehicles" style="width:300px"></td>';
                    echo '<td><label for="noofvehicles">*</label></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="noofharvesterlabour">No. of Harvester Labour</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="noofharvesterlabour">कोयत्यांची संख्या</label></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="noofharvesterlabour" id="noofharvesterlabour" style="width:300px"></td>';
                    echo '<td><label for="noofharvesterlabour">*</label></td>';
                    echo '</tr>';
                    
                    if ($_SESSION['lng'] == "English")
                        {
                            echo '<tr>';
                            echo '<td><label for="transportationuptovehicleid">Transportation Upto Vehicle</label></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>';
                            echo '<select name="transportationuptovehicleid" style="height:35px;font-size:14px;">';
                            echo '<option value="0">[No]</option>';   
                            if ($contractcategoryid_de ==785415263)
                            {
                                $query = "select * from namedetail n where n.active=1 and namecategoryid=671529934 and namedetailid = 125478451 order by name_unicode";
                                $result1 = oci_parse($connection, $query);
                            }
                            else
                            {
                                $query = "select * from namedetail n where n.active=1 and namecategoryid=671529934 and namedetailid <> 125478451 order by name_unicode";
                                $result1 = oci_parse($connection, $query);
                            }
                            $r = oci_execute($result1);
                            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                            {
                                if ($row1['NAMEDETAILID']==$row['TRANSPORTATIONUPTOVEHICLEID'])
                                {
                                    echo '<option value="'.$row1['NAMEDETAILID'].'" SELECTED>'.$row1['NAME_ENG'].'</OPTION>';
                                }
                                else
                                {
                                    echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_ENG'].'</OPTION>';   
                                }
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '<td><label for="transportationuptovehicleid">*</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="transportationuptovehicleid">वाहना पर्यंतची वहातुक</label></td>';
                            echo '</tr>';

                            echo '<tr>';
                            echo '<td>';
                            echo '<select name="transportationuptovehicleid" style="height:35px;font-size:14px;">';
                            echo '<option value="0" Selected>[नाही]</option>';   
                            $result1 = mysqli_query($connection, "select * from namedetail n where n.active=1 and namecategoryid=671529934 order by name_unicode");
                            //if ($contractcategoryid_de ==785415263)
                            //{
                                //and namedetailid = 125478451
                                $query = "select * from namedetail n where n.active=1 and namecategoryid=671529934  order by name_unicode";
                                $result1 = oci_parse($connection, $query);
                            //}
                            /* else
                            {
                                $query = "select * from namedetail n where n.active=1 and namecategoryid=671529934 and namedetailid <> 125478451 order by name_unicode";
                                $result1 = oci_parse($connection, $query);
                            } */
                            $r = oci_execute($result1);
                            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                            {
                                if ($row1['NAMEDETAILID']==$row['TRANSPORTATIONUPTOVEHICLEID'])
                                {
                                    echo '<option value="'.$row1['NAMEDETAILID'].'" SELECTED>'.$row1['NAME_UNICODE'].'</OPTION>';   
                                }
                                else
                                {
                                    echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_UNICODE'].'</OPTION>';   
                                }
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '<td><label for="transportationuptovehicleid">*</label></td>';
                            echo '</tr>';
                        }

                    echo '<tr>';
                    echo '<td><label for="bankbranch">Bank Branch</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="bankbranch" id="bankbranch" style="width:300px"></td>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="bankbranchid" id="bankbranchid"></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="chequenumber">Cheque Number</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="chequenumber">चेक क्रमांक</label></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="chequenumber" id="chequenumber" style="width:300px"></td>';
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