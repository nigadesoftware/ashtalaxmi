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
    if (isset($_GET['contractmortgagedetailid']))
    {
        $contractmortgagedetailid_de = fnDecrypt($_GET['contractmortgagedetailid']);
    }

    $flag = $_GET['flag'];
    // Opens a connection to a MySQL server
    $connection=rawmaterial_connection();

    function areaname(&$connection,$areaid,$lng)
    {
        $query = "select r.areaid,concat(r.areaname_eng,', ',s.subdistrictname_eng,', ',d.districtname_eng) areaname_eng,concat(r.areaname,', ',s.subdistrictname,', ',d.districtname) areaname_unicode from swappcoi_db.area r,swappcoi_db.subdistrict s,swappcoi_db.district d,swappcoi_db.state t where r.subdistrictid=s.subdistrictid and s.districtid=d.districtid and d.stateid=t.stateid and r.areaid=".$areaid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($lng==0)
            {
                return $row['AREANAME_ENG'];
            }
            else
            {
                return $row['AREANAME_UNICODE'];
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
        <title>Contract Mortgage Detail</title>
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
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script src="http://ajax.googleapi_mysqls.com/ajax/libs/jquery/1.11.0/jquery.min.js">
         </script>
         <script>
            $(function()
            {
                $("#property").autocomplete({
                source: 'servicecontractor_search.php?iscultivator='+$('#iscultivator').val(),
                minLength:2,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                $('#propertyid').val(i);
                this.value = v;
                return false;}
                });
            });
            $(function () {
                $("#area").autocomplete({
                source: 'area_search.php',
                minLength:3,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                $('#areaid').val(i);
                this.value = v;
                return false;}
                });
                });
        </script>
         <script>
            $(property).ready(function(){
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
                <li><a class="navbar" href="../data/entitymenu.php">Entity Menu</a><br/>
                <?php
                    /*$personnamedetailtypeid_en = fnEncrypt($personnamedetailtypeid_de);
                    $personnamedetailid_en = fnEncrypt($personnamedetailid_de);
                    echo '<li><a style="color:#f48;text-align:left;" href="../data/personnamedetail_find.php?personnamedetailtypeid='.$personnamedetailtypeid_en.'">personnamedetail Find</a><br/>';*/
                    echo '<li><a class="navbar" href="../data/contract.php?contractid='.fnEncrypt($contractid_de).'&flag='.fnEncrypt('Display').'">Add/Display Contract</a></br>';
                    echo '<li><a class="navbar" href="../data/contractmortgagedetail_list.php?contractid='.fnEncrypt($contractid_de).'&flag='.fnEncrypt('Display').'">Add/Display Contract Harvester Detail List</a></br>';
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/contract.png" width="201" height="41px"></div>
            <?php
                $query = "select f.*,d.name_eng as propertyname_eng,d.name_unicode as propertyname_unicode from contractmortgagedetail f, namedetail d where f.active=1 and d.active=1 and f.propertycategoryid=d.namedetailid and f.contractid = ".$contractid_de." and f.contractmortgagedetailid=".$contractmortgagedetailid_de;
                //echo $query;
                $result = oci_parse($connection, $query); $r = oci_execute($result);
                if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    echo '<section>';
                    //if ($flag_de == 'Display')
                    //{
                        echo '<form method="post" action="../api_action/contractmortgagedetail_action.php">';
                    //}
                        echo '<table border="0" >';

                        echo '<tr>';  
                        echo '<td></td>';  
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="area">
                            Area</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="area">गाव</label></td>';
                            echo '</tr>';
                        }

                        if ($_SESSION['lng']=='English')
                        {
                            echo '<td><input type="text" style="font-size:12pt;height:30px" name="area" id="area" value="'.areaname($connection,$row['AREAID'],0).'" STYLE="WIDTH:300PX"></td>';
                        }
                        else
                        {
                            echo '<td><input type="text" style="font-size:12pt;height:30px" name="area" id="area" value="'.areaname($connection,$row['AREAID'],1).'" STYLE="WIDTH:300PX"></td>';
                        }
                        echo '<td><label for="area">*</label></td>';
                        echo '<tr>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="areaid" id="areaid"></td>';
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="propertycategoryid">
                            Property</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="propertycategoryid">मिळकत</label></td>';
                            echo '</tr>';
                        }

                        $query = "select namedetailid,name_eng,name_unicode from namedetail n where n.active=1 and n.namecategoryid=632541254 order by name_eng";
                        $result1 = oci_parse($connection, $query); $r = oci_execute($result);
                        echo '<tr>';
                        echo '<td><select name="propertycategoryid" style="height:35px;font-size:14px;">';
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
                                if ($row1['NAMEDETAILID'] == $row['PROPERTYCATEGORYID'])
                                {
                                    echo '<option value="'.$row1['NAMEDETAILID'].'" SELECTED>'.$row1['NAME_ENG'].'</OPTION>';
                                }
                                else
                                {
                                    echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_ENG'].'</OPTION>';
                                }
                            }
                            else
                            {
                                if ($row1['NAMEDETAILID'] == $row['PROPERTYCATEGORYID'])
                                {
                                    echo '<option value="'.$row1['NAMEDETAILID'].'" SELECTED>'.$row1['NAME_UNICODE'].'</OPTION>';
                                }
                                else
                                {
                                    echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_UNICODE'].'</OPTION>';
                                }
                            }
                        }
                        echo '</select>';
                        echo '<td><label for="propertycategoryid">*</label></td>';
                        echo '</td>';
                        echo '</tr>';

                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractid" id="contractid" style="width:300px" value ="'.$row['CONTRACTID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractmortgagedetailid" id="contractmortgagedetailid" style="width:300px" value ="'.$row['CONTRACTMORTGAGEDETAILID'].'"></td>';
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="propertynumber">
                            Property Number</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="propertynumber">मिळकत नंबर</label></td>';
                            echo '</tr>';
                        }
                        
                        echo '<tr>';
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="propertynumber" id="propertynumber" style="width:300px" value="'.$row['PROPERTYNUMBER'].'"></td>';
                        echo '<td><label for="propertynumber">*</label></td>';
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
                    echo '<form method="post" action="../api_action/contractmortgagedetail_action.php">';
                    echo '<table border="0" >';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="area">
                        Area</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="area">गाव</label></td>';
                        echo '</tr>';
                    }
                    
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="area" id="area" style="width:300px"></td>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="areaid" id="areaid"></td>';
                    echo '<td><label for="area">*</label></td>';
                    echo '</tr>';
                    
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="propertyid">
                        Property</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="propertycategoryid">मिळकत</label></td>';
                        echo '</tr>';
                    }
                    $query = "select namedetailid,name_eng,name_unicode from namedetail n where n.active=1 and n.namecategoryid=632541254 order by name_eng";
                    $result1 = oci_parse($connection, $query); $r = oci_execute($result);
                    echo '<tr>';
                    echo '<td><select name="propertycategoryid" style="height:35px;font-size:14px;">';
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
                            if ($row1['NAMEDETAILID'] == $row['PROPERTYID'])
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'" SELECTED>'.$row1['NAME_ENG'].'</OPTION>';
                            }
                            else
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_ENG'].'</OPTION>';
                            }
                        }
                        else
                        {
                            if ($row1['NAMEDETAILID'] == $row['PROPERTYID'])
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'" SELECTED>'.$row1['NAME_UNICODE'].'</OPTION>';
                            }
                            else
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_UNICODE'].'</OPTION>';
                            }
                        }
                    }
                    echo '</select>';
                    echo '<td><label for="propertyid">*</label></td>';
                    echo '</td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="propertynumber">
                        Property Number</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="propertynumber">मिळकत नंबर</label></td>';
                        echo '</tr>';
                    }
                    
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="propertynumber" id="propertynumber" style="width:300px"></td>';
                    echo '</tr>';
                    
                    echo '<tr>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractid" id="contractid" style="width:300px" value ="'.$contractid_de.'"></td>';
                    echo '<td><label for="propertynumber">*</label></td>';
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