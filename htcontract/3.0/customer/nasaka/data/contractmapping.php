<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    include("../info/ncryptdcrypt.php");
    require("../info/rawmaterialroutine.php");
    //Raw Material HT Master Addition
    if (isaccessible(451230287895415)==0 and isaccessible(475124562358965)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $contractmappingid_de = fnDecrypt($_GET['contractmappingid']);
    $flag_de = fnDecrypt($_GET['flag']);

    function servicecontractorname(&$connection,$servicecontractorid,$lng)
    {
       $query = "select n.name_eng,n.name_unicode from servicecontractor s, personnamedetail p,namedetail n where s.active=1 and p.active=1 and n.active=1 and  s.personnamedetailid=p.personnamedetailid and p.namedetailid=n.namedetailid and s.servicecontractorid=".$servicecontractorid;
       //echo $query;
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
        <title>Contract</title>
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
            select
            {
                font-family: siddhanta;
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
        <script>
            $(function()
            {
                $("#servicecontractor").autocomplete({
                source: 'servicecontractor_search.php',
                minLength:3,
                delay:200,
                select:function(event,ui)
                {var v = ui.item.value;
                 var i = ui.item.id;
                $('#servicecontractorid').val(i);
                this.value = v;
                return false;}
                });
            });
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
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
        <div><img src="../img/contract.png" width="201" height="41px"></div>
            <?php
                // Opens a connection to a MySQL server
                $connection=rawmaterial_connection();
                $query = "select f.* from contractmapping f where f.active=1  and f.contractmappingid=".$contractmappingid_de;
                //echo $query;
                $result = oci_parse($connection, $query); $r = oci_execute($result);
                if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    echo '<section>';
                    if ($flag_de == 'Display')
                    {
                        echo '<form method="post" action="../api_action/contractmapping_action.php">';
                    }
                    echo '<table border="0" >';

                        echo '<tr>';  
                        echo '<td></td>';
                        echo '</tr>';
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="seasonid">Season</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="seasonid">हंगाम</label></td>';
                            echo '</tr>';
                        }
                        $query1 = "select seasonid,name_eng,name_unicode from season s where s.active=1 order by transactionid desc";
                        $result1 = mysqli_query($connection, $query1);
                        echo '<tr>';
                        echo '<td><select name="seasonid" style="height:35px;font-size:14px;">';
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
                                if ($row1['SEASONID']==$row['SEASONID'])
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
                                if ($row1['SEASONID']==$row['SEASONID'])
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
                        echo '<td><label for="seasonid">*</label></td>';
                        echo '</td>';
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="sugarfactoryid">
                            Sugar Factory</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="sugarfactoryid">साखर कारखाना</label></td>';
                            echo '</tr>';
                        }
                        $query = "select namedetailid,name_eng,name_unicode from namedetail n where n.active=1 and n.namecategoryid=654123874 order by name_eng";
                        $result1 = oci_parse($connection, $query); $r = oci_execute($result);
                        echo '<tr>';
                        echo '<td><select name="sugarfactoryid" style="height:35px;font-size:14px;">';
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
                                if ($row1['NAMEDETAILID'] == $row['SUGARFACTORYID'])
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
                                if ($row1['NAMEDETAILID'] == $row['SUGARFACTORYID'])
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
                        echo '<td><label for="sugarfactoryid">*</label></td>';
                        echo '</td>';
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
                        if ($row['SERVICECONTRACTORID']!='')
                        {
                            if ($_SESSION['lng']=="English")
                            {
                                echo '<td><input type="text" style="font-size:12pt;height:30px" name="servicecontractor" id="servicecontractor" style="width:300px" value="'.servicecontractorname($connection,$row['SERVICECONTRACTORID'],0).'"></td>';
                            }
                            else
                            {
                                echo '<td><input type="text" style="font-size:12pt;height:30px" name="servicecontractor" id="servicecontractor" style="width:300px" value="'.servicecontractorname($connection,$row['SERVICECONTRACTORID'],1).'"></td>';
                            }
                        }
                        else
                        {
                            echo '<td><input type="text" style="font-size:12pt;height:30px" name="servicecontractor" id="servicecontractor" style="width:300px"></td>';
                        }
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="servicecontractorid" id="servicecontractorid"></td>';
                        echo '</tr>';

                        echo '<tr>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractmappingid" id="contractmappingid" value="'.$contractmappingid_de.'"></td>';
                        echo '</tr>';
                        if ($_SESSION["responsibilitycode"] == 475124562358965)
                        {
                            echo '<tr>';
                            echo '<td><input type="submit" name="btn" value="Change" style="width:100px"</button>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td><input type="submit" name="btn" value="Delete" style="width:100px"</button>';
                            echo '</tr>';
                        }    
                        echo '<tr>';
                        echo '<td><input type="submit" name="btn" value="Reset" style="width:100px"</button>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><a href="../data/contractmappingdetail_list.php?contractmappingid='.fnEncrypt($contractmappingid_de).'">Contract Mapping Detail</a></td>';
                        echo '</tr>';
                    echo '</table>';
                    echo '</form>';
                    echo '</section>';
                }
                else
                {
                    echo '<section>';
                    echo '<form method="post" action="../api_action/contractmapping_action.php">';
                    echo '<table border="0" >';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="seasonid">Season</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="seasonid">हंगाम</label></td>';
                        echo '</tr>';
                    }
                    $query = "select seasonid,name_eng,name_unicode from season s where s.active=1 order by transactionid desc";
                    $result1 = oci_parse($connection, $query); $r = oci_execute($result);
                    echo '<tr>';
                    echo '<td><select name="seasonid" style="height:35px;font-size:14px;">';
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
                    echo '<td><label for="seasonid">*</label></td>';
                    echo '</td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="sugarfactoryid">
                        Sugar Factory</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="sugarfactoryid">साखर कारखाना</label></td>';
                        echo '</tr>';
                    }
                    $query = "select namedetailid,name_eng,name_unicode from namedetail n where n.active=1 and n.namecategoryid=654123874 order by name_eng";
                    $result1 = oci_parse($connection, $query); $r = oci_execute($result);
                    echo '<tr>';
                    echo '<td><select name="sugarfactoryid" style="height:35px;font-size:14px;">';
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
                            echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_ENG'].'</OPTION>';   
                        }
                        else
                        {
                            echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_UNICODE'].'</OPTION>';
                        }
                    }
                    echo '</select>';
                    echo '<td><label for="sugarfactoryid">*</label></td>';
                    echo '</td>';
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
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="servicecontractor" id="servicecontractor" style="width:300px"></td>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="servicecontractorid" id="servicecontractorid"></td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td></td>';  
                    echo '</tr>';
                    if ($_SESSION["responsibilitycode"] == 451230287895415)
                    {
                        echo '<tr>';
                        echo '<td><input type="submit" name="btn" value="Add" style="width:100px"</button>';
                        echo '</tr>';
                    }
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
        </div>
        </article>
        <footer>
        </footer>
    </body>
</html>