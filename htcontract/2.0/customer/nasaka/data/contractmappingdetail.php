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
    $contractmappingid_de = fnDecrypt($_GET['contractmappingid']);
    if (isset($_GET['contractmappingdetailid']))
    {
        $contractmappingdetailid_de = fnDecrypt($_GET['contractmappingdetailid']);    
    }
    $flag = $_GET['flag'];
    function servicecontractorid(&$connection,$contractmappingid)
    {
        $query = "select c.servicecontractorid from contractmapping c where c.active=1 and c.contractmappingid=".$contractmappingid;
        //echo $query;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($row['SERVICECONTRACTORID'] == '')
            {
                return '';
            }
            else
            {
                return $row['SERVICECONTRACTORID'];
            }
        }
    }
    // Opens a connection to a MySQL server
    $connection=rawmaterial_connection();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/swapp123.css">
        <title>Contract Mapping Detail</title>
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
                    echo '<li><a class="navbar" href="../data/contractmapping.php?contractmappingid='.fnEncrypt($contractmappingid_de).'&flag='.fnEncrypt('Display').'">Add/Display Contract Mapping</a></br>';
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/contract.png" width="201" height="41px"></div>
            <?php
                $query = "select f.* from contractmappingdetail f where f.active=1 and f.contractmappingid = ".$contractmappingid_de." and f.contractmappingdetailid=".$contractmappingdetailid_de;
                //echo $query;
                $result = oci_parse($connection, $query); $r = oci_execute($result);
                if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    echo '<section>';
                    //if ($flag_de == 'Display')
                    //{
                        echo '<form method="post" action="../api_action/contractmappingdetail_action.php">';
                    //}
                        echo '<table border="0" >';

                        echo '<tr>';  
                        echo '<td></td>';  
                        echo '</tr>';

                        if ($_SESSION['lng'] == "English")
                        {
                            echo '<tr>';
                            echo '<td><label for="contracttransportdetailid">Transportation Vehicle</label></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>';
                            $sercontid = servicecontractorid($connection,$contractmappingid_de);
                            if ($sercontid == '')
                            {
                                $query = "select c.contracttransportdetailid,c.vehiclenumber from contracttransportdetail c,contract t,contractmapping m where c.active=1 and t.active=1 and m.active=1 and t.seasonid=m.seasonid and c.contractid=t.contractid and m.contractmappingid=".$contractmappingid_de." order by vehiclenumber";
                            }
                            else
                            {
                                $query = "select c.contracttransportdetailid,c.vehiclenumber from contracttransportdetail c,contract t,contractmapping m where c.active=1 and t.active=1 and m.active=1 and c.contractid=t.contractid and t.servicecontractorid=m.servicecontractorid and t.seasonid=m.seasonid  and m.servicecontractorid=".$sercontid." and m.contractmappingid=".$contractmappingid_de." order by vehiclenumber";
                            }
                            echo '<select name="contracttransportdetailid" style="height:35px;font-size:14px;">';
                            echo '<option value="0" Selected>[No]</option>';   
                            $result1 = oci_parse($connection, $query); $r = oci_execute($result);
                            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                            {
                                if ($row1['CONTRACTTRANSPORTDETAILID']==$row['CONTRACTTRANSPORTDETAILID'])
                                {
                                    echo '<option value="'.$row1['CONTRACTTRANSPORTDETAILID'].'" SELECTED>'.$row1['VEHICLENUMBER'].'</OPTION>';
                                }
                                else
                                {
                                    echo '<option value="'.$row1['CONTRACTTRANSPORTDETAILID'].'">'.$row1['VEHICLENUMBER'].'</OPTION>';
                                }
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="contracttransportdetailid">वाहतुकीचे वाहन</label></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>';
                            $sercontid = servicecontractorid($connection,$contractmappingid_de);
                            if ($sercontid == '')
                            {
                                $query = "select c.contracttransportdetailid,c.vehiclenumber from contracttransportdetail c,contract t,contractmapping m where c.active=1 and t.active=1 and m.active=1 and t.seasonid=m.seasonid and c.contractid=t.contractid and m.contractmappingid=".$contractmappingid_de." order by vehiclenumber";
                            }
                            else
                            {
                                $query = "select c.contracttransportdetailid,c.vehiclenumber from contracttransportdetail c,contract t,contractmapping m where c.active=1 and t.active=1 and m.active=1 and c.contractid=t.contractid and t.servicecontractorid=m.servicecontractorid and t.seasonid=m.seasonid  and m.servicecontractorid=".$sercontid." and m.contractmappingid=".$contractmappingid_de." order by vehiclenumber";
                            }
                            echo '<select name="contracttransportdetailid" style="height:35px;font-size:14px;">';
                            echo '<option value="0" Selected>[नाही]</option>';   
                            $result1 = oci_parse($connection, $query); $r = oci_execute($result);
                            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                            {
                                if ($row1['CONTRACTTRANSPORTDETAILID']==$row['CONTRACTTRANSPORTDETAILID'])
                                {
                                    echo '<option value="'.$row1['CONTRACTTRANSPORTDETAILID'].'" SELECTED>'.$row1['VEHICLENUMBER'].'</OPTION>';   
                                }
                                else
                                {
                                    echo '<option value="'.$row1['CONTRACTTRANSPORTDETAILID'].'">'.$row1['VEHICLENUMBER'].'</OPTION>';      
                                }
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        
                        if ($_SESSION['lng'] == "English")
                        {
                            echo '<tr>';
                            echo '<td><label for="contractharvestdetailid">Harvester</label></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>';
                            $sercontid = servicecontractorid($connection,$contractmappingid_de);
                            if ($sercontid == '')
                            {
                                $query = "select c.contractharvestdetailid,n.name_eng,n.name_unicode from contractharvestdetail c,namedetail n,contract t,contractmapping m where c.active=1 and n.active=1 and c.namedetailid=n.namedetailid and t.active=1 and m.active=1 and t.seasonid=m.seasonid and c.contractid=t.contractid and m.contractmappingid=".$contractmappingid_de." order by name_eng";
                            }
                            else
                            {
                                $query = "select c.contractharvestdetailid,n.name_eng,n.name_unicode from contractharvestdetail c,namedetail n,contract t,contractmapping m where c.active=1 and n.active=1 and c.contractid=t.contractid and t.servicecontractorid=m.servicecontractorid and c.namedetailid=n.namedetailid and t.active=1 and m.active=1 and t.seasonid=m.seasonid and m.servicecontractorid=".$sercontid." and m.contractmappingid=".$contractmappingid_de." order by name_eng";
                            }
                            echo '<select name="contractharvestdetailid" style="height:35px;font-size:14px;">';
                            echo '<option value="0" Selected>[No]</option>';   
                            //echo $query;
                            $result1 = oci_parse($connection, $query); $r = oci_execute($result);
                            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                            {
                                if ($row1['CONTRACTHARVESTDETAILID']==$row['CONTRACTHARVESTDETAILID'])
                                {
                                    echo '<option value="'.$row1['CONTRACTHARVESTDETAILID'].'" SELECTED>'.$row1['NAME_ENG'].'</OPTION>';   
                                }
                                else
                                {
                                    echo '<option value="'.$row1['CONTRACTHARVESTDETAILID'].'">'.$row1['NAME_ENG'].'</OPTION>';      
                                }
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="contractharvestdetailid">वाहतुकीचे वाहन</label></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>';
                            $sercontid = servicecontractorid($connection,$contractmappingid_de);
                            if ($sercontid == '')
                            {
                                $query = "select c.contractharvestdetailid,n.name_eng,n.name_unicode from contractharvestdetail c,namedetail n,contract t,contractmapping m where c.active=1 and n.active=1 and c.namedetailid=n.namedetailid and t.active=1 and m.active=1 and t.seasonid=m.seasonid and c.contractid=t.contractid and m.contractmappingid=".$contractmappingid_de." order by name_eng";
                            }
                            else
                            {
                                $query = "select c.contractharvestdetailid,n.name_eng,n.name_unicode from contractharvestdetail c,namedetail n,contract t,contractmapping m where c.active=1 and n.active=1 and c.contractid=t.contractid and t.servicecontractorid=m.servicecontractorid and c.namedetailid=n.namedetailid and t.active=1 and m.active=1 and t.seasonid=m.seasonid and m.servicecontractorid=".$sercontid." and m.contractmappingid=".$contractmappingid_de." order by name_eng";
                            }
                            echo '<select name="contractharvestdetailid" style="height:35px;font-size:14px;">';
                            echo '<option value="0" Selected>[नाही]</option>';   
                            $result1 = oci_parse($connection, $query); $r = oci_execute($result);
                            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                            {
                                if ($row1['CONTRACTHARVESTDETAILID']==$row['CONTRACTHARVESTDETAILID'])
                                {
                                    echo '<option value="'.$row1['CONTRACTHARVESTDETAILID'].'" SELECTED>'.$row1['NAME_UNICODE'].'</OPTION>';   
                                }
                                else
                                {
                                    echo '<option value="'.$row1['CONTRACTHARVESTDETAILID'].'">'.$row1['NAME_UNICODE'].'</OPTION>';      
                                }
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '</tr>';
                        }

                        echo '<tr>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractmappingid" id="contractmappingid" style="width:300px" value ="'.$row['CONTRACTMAPPINGID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractmappingdetailid" id="contractmappingdetailid" style="width:300px" value ="'.$row['CONTRACTMAPPINGDETAILID'].'"></td>';
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
                    echo '<form method="post" action="../api_action/contractmappingdetail_action.php">';
                    echo '<table border="0" >';

                    if ($_SESSION['lng'] == "English")
                    {
                        echo '<tr>';
                        echo '<td><label for="contracttransportdetailid">Transportation Vehicle</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>';
                        $sercontid = servicecontractorid($connection,$contractmappingid_de);
                        if ($sercontid == '')
                        {
                            $query = "select c.contracttransportdetailid,c.vehiclenumber from contracttransportdetail c,contract t,contractmapping m where c.active=1 and t.active=1 and m.active=1 and t.seasonid=m.seasonid and c.contractid=t.contractid and m.contractmappingid=".$contractmappingid_de." order by vehiclenumber";
                        }
                        else
                        {
                            $query = "select c.contracttransportdetailid,c.vehiclenumber from contracttransportdetail c,contract t,contractmapping m where c.active=1 and t.active=1 and m.active=1 and c.contractid=t.contractid and t.servicecontractorid=m.servicecontractorid and t.seasonid=m.seasonid  and m.servicecontractorid=".$sercontid." and m.contractmappingid=".$contractmappingid_de." order by vehiclenumber";
                        }
                        //echo $query;
                        echo '<select name="contracttransportdetailid" style="height:35px;font-size:14px;">';
                        echo '<option value="0" Selected>[No]</option>';   
                        $result1 = oci_parse($connection, $query); $r = oci_execute($result);
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            echo '<option value="'.$row1['CONTRACTTRANSPORTDETAILID'].'">'.$row1['VEHICLENUMBER'].'</OPTION>';   
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="contracttransportdetailid">वाहतुकीचे वाहन</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>';
                        $sercontid = servicecontractorid($connection,$contractmappingid_de);
                        if ($sercontid == '')
                        {
                            $query = "select c.contracttransportdetailid,c.vehiclenumber from contracttransportdetail c,contract t,contractmapping m where c.active=1 and t.active=1 and m.active=1 and t.seasonid=m.seasonid and c.contractid=t.contractid and m.contractmappingid=".$contractmappingid_de." order by vehiclenumber";
                        }
                        else
                        {
                            $query = "select c.contracttransportdetailid,c.vehiclenumber from contracttransportdetail c,contract t,contractmapping m where c.active=1 and t.active=1 and m.active=1 and c.contractid=t.contractid and t.servicecontractorid=m.servicecontractorid and t.seasonid=m.seasonid  and m.servicecontractorid=".$sercontid." and m.contractmappingid=".$contractmappingid_de." order by vehiclenumber";
                        }
                        echo '<select name="contracttransportdetailid" style="height:35px;font-size:14px;">';
                        echo '<option value="0" Selected>[नाही]</option>';   
                        $result1 = oci_parse($connection, $query); $r = oci_execute($result);
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            echo '<option value="'.$row1['CONTRACTTRANSPORTDETAILID'].'">'.$row1['VEHICLENUMBER'].'</OPTION>';   
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    
                    if ($_SESSION['lng'] == "English")
                    {
                        echo '<tr>';
                        echo '<td><label for="contractharvestdetailid">Harvester</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>';
                        $sercontid = servicecontractorid($connection,$contractmappingid_de);
                        if ($sercontid == '')
                        {
                            $query = "select c.contractharvestdetailid,n.name_eng,n.name_unicode from contractharvestdetail c,namedetail n,contract t,contractmapping m where c.active=1 and n.active=1 and c.namedetailid=n.namedetailid and t.active=1 and m.active=1 and t.seasonid=m.seasonid and c.contractid=t.contractid and m.contractmappingid=".$contractmappingid_de." order by name_eng";
                        }
                        else
                        {
                            $query = "select c.contractharvestdetailid,n.name_eng,n.name_unicode from contractharvestdetail c,namedetail n,contract t,contractmapping m where c.active=1 and n.active=1 and c.contractid=t.contractid and t.servicecontractorid=m.servicecontractorid and c.namedetailid=n.namedetailid and t.active=1 and m.active=1 and t.seasonid=m.seasonid and m.servicecontractorid=".$sercontid." and m.contractmappingid=".$contractmappingid_de." order by name_eng";
                        }
                        echo '<select name="contractharvestdetailid" style="height:35px;font-size:14px;">';
                        echo '<option value="0" Selected>[No]</option>';   
                        $result1 = oci_parse($connection, $query); $r = oci_execute($result);
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            echo '<option value="'.$row1['CONTRACTHARVESTDETAILID'].'">'.$row1['NAME_ENG'].'</OPTION>';   
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="contractharvestdetailid">वाहतुकीचे वाहन</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>';
                        $sercontid = servicecontractorid($connection,$contractmappingid_de);
                        if ($sercontid == '')
                        {
                            $query = "select c.contractharvestdetailid,n.name_eng,n.name_unicode from contractharvestdetail c,namedetail n,contract t,contractmapping m where c.active=1 and n.active=1 and c.namedetailid=n.namedetailid and t.active=1 and m.active=1 and t.seasonid=m.seasonid and c.contractid=t.contractid and m.contractmappingid=".$contractmappingid_de." order by name_eng";
                        }
                        else
                        {
                            $query = "select c.contractharvestdetailid,n.name_eng,n.name_unicode from contractharvestdetail c,namedetail n,contract t,contractmapping m where c.active=1 and n.active=1 and c.contractid=t.contractid and t.servicecontractorid=m.servicecontractorid and c.namedetailid=n.namedetailid and t.active=1 and m.active=1 and t.seasonid=m.seasonid and m.servicecontractorid=".$sercontid." and m.contractmappingid=".$contractmappingid_de." order by name_eng";
                        }
                        echo '<select name="contractharvestdetailid" style="height:35px;font-size:14px;">';
                        echo '<option value="0" Selected>[नाही]</option>';   
                        $result1 = mysqli_query($connection, "select c.contractharvestdetailid,n.name_eng,n.name_unicode from contractharvestdetail c,namedetail n,contract t,contractmapping m where c.active=1 and n.active=1 and c.contractid=t.contractid and c.namedetailid=n.namedetailid and t.active=1 and m.active=1 and t.seasonid=m.seasonid and m.contractmappingid=".$contractmappingid_de." order by name_unicode");
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            echo '<option value="'.$row1['CONTRACTHARVESTDETAILID'].'">'.$row1['NAME_UNICODE'].'</OPTION>';   
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractmappingid" id="contractmappingid" style="width:300px" value ="'.$contractmappingid_de.'"></td>';
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