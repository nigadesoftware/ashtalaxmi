<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    include("../info/ncryptdcrypt.php");
    require("../info/rawmaterialroutine.php");
    include("../data/contractdetailrecordcount.php");
    //Raw Material HT Master Addition or HT Master Alteration
    if (isaccessible(451230287895415)==0 and isaccessible(475124562358965)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $contractid_de = fnDecrypt($_GET['contractid']);
    $flag_de = fnDecrypt($_GET['flag']);

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
        <script src="../js/1.11.0/jquery.min.js">
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
        <link rel="stylesheet" href="../css/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="..//js/jquery-1.10.2.js"></script>
        <script src="..//js/ui/1.11.4/jquery-ui.js"></script>
        <script>
            $(function()
            {
                $("#servicecontractor").autocomplete({
                source: 'servicecontractor_search.php?iscultivator=0',
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
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
        <div><img src="../img/contract.png" width="201" height="41px"></div>
            <?php
                // Opens a connection to a MySQL server
                $connection=rawmaterial_connection();
                if ($contractid_de == '')
                {
                    $contractid_de =0;
                }
                $query = "select f.*,name_eng,name_unicode 
                from contract f,servicecontractor s 
                where f.active=1 
                and f.servicecontractorid=s.servicecontractorid 
                and f.contractid=".$contractid_de;
                //echo $query;
                $result = oci_parse($connection, $query); $r = oci_execute($result);
                $r = oci_execute($result);
                if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS)) 
                {
                    echo '<section>';
                    if ($flag_de == 'Display')
                    {
                        echo '<form method="post" action="../api_action/contract_action.php">';
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
                        //$result1 = mysqli_query($connection, $query1);
                        $result1 = oci_parse($connection, $query1);
                        $r = oci_execute($result1);
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
                                    echo '<option value="'.$row1['SEASONID'].'" Selected>'.$row1['NAME_ENG'].'</option>';
                                }
                                else
                                {
                                    echo '<option value="'.$row1['SEASONID'].'">'.$row1['NAME_ENG'].'</option>';
                                }
                            }
                            else
                            {
                                if ($row1['SEASONID']==$row['SEASONID'])
                                {
                                    echo '<option value="'.$row1['SEASONID'].'" Selected>'.$row1['NAME_UNICODE'].'</option>';
                                }
                                else
                                {
                                    echo '<option value="'.$row1['SEASONID'].'">'.$row1['NAME_UNICODE'].'</option>';
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
                        //$result1 = oci_parse($connection, $query); $r = oci_execute($result);
                        $result1 = oci_parse($connection, $query);
                        $r = oci_execute($result1);
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
                                    echo '<option value="'.$row1['NAMEDETAILID'].'" Selected>'.$row1['NAME_ENG'].'</option>';
                                }
                                else
                                {
                                    echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_ENG'].'</option>';
                                }
                            }
                            else
                            {
                                if ($row1['NAMEDETAILID'] == $row['SUGARFACTORYID'])
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
                        echo '<td><label for="sugarfactoryid">*</label></td>';
                        echo '</td>';
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="contractcategoryid">Contract Category</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="contractcategoryid">कंत्राट वर्ग</label></td>';
                            echo '</tr>';
                        }
                        $query = "select contractcategoryid,contractcategoryname_eng,contractcategoryname_unicode from contractcategory c where c.active=1 order by transactionid desc";
                        //$result1 = oci_parse($connection, $query); $r = oci_execute($result);
                        $result1 = oci_parse($connection, $query);
                        $r = oci_execute($result1);
                        echo '<tr>';
                        echo '<td><select name="contractcategoryid" style="height:35px;font-size:14px;">';
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
                                if ($row1['CONTRACTCATEGORYID'] == $row['CONTRACTCATEGORYID'])
                                {
                                    echo '<option value="'.$row1['CONTRACTCATEGORYID'].'" Selected>'.$row1['CONTRACTCATEGORYNAME_ENG'].'</option>';   
                                }
                                else
                                {
                                    echo '<option value="'.$row1['CONTRACTCATEGORYID'].'">'.$row1['CONTRACTCATEGORYNAME_ENG'].'</option>';   
                                }
                            }
                            else
                            {
                                if ($row1['CONTRACTCATEGORYID'] == $row['CONTRACTCATEGORYID'])
                                {
                                    echo '<option value="'.$row1['CONTRACTCATEGORYID'].'" Selected>'.$row1['CONTRACTCATEGORYNAME_UNICODE'].'</option>';
                                }
                                else
                                {
                                    echo '<option value="'.$row1['CONTRACTCATEGORYID'].'">'.$row1['CONTRACTCATEGORYNAME_UNICODE'].'</option>';
                                }
                            }
                        }
                        echo '</select>';
                        echo '<td><label for="contractcategoryid">*</label></td>';
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
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<td><input type="text" style="font-size:12pt;height:30px" name="servicecontractor" id="servicecontractor" style="width:300px" value="'.$row['NAME_ENG'].'"></td>';
                        }
                        else
                        {
                            echo '<td><input type="text" style="font-size:12pt;height:30px" name="servicecontractor" id="servicecontractor" style="width:300px" value="'.$row['NAME_UNICODE'].'"></td>';
                        }
                        echo '<td><label for="servicecontractorid">*</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><input readonly="readonly" type="text" style="font-size:12pt;height:30px" name="servicecontractorid" id="servicecontractorid" value="'.$row['SERVICECONTRACTORID'].'"></td>';
                        echo '</tr>';

                        echo '<tr>';
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="applicationnumber">Application Number</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="applicationnumber">अर्ज क्रमांक</label></td>';
                            echo '</tr>';
                        }
                        
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="applicationnumber" id="applicationnumber" value="'.$row['APPLICATIONNUMBER'].'" readonly="readonly"></td>';
                        echo '<td><label for="applicationnumber">*</label></td>';
                        echo '</tr>';

                        echo '<tr>';
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="applicationdatetime">Application Date (dd/mm/yyyy)</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="applicationdatetime">अर्ज दिनांक (dd/mm/yyyy)</label></td>';
                            echo '</tr>';
                        }
                        if (isset($row['APPLICATIONDATETIME']))
                        {
                            $applicationdatetime = date('d/m/Y',strtotime($row['APPLICATIONDATETIME']));
                        }
                        
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="applicationdatetime" id="applicationdatetime" value="'.$applicationdatetime.'"></td>';
                        echo '<td><label for="applicationdatetime">*</label></td>';
                        echo '</tr>';

                        echo '<tr>';
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="contractnumber_prefixsuffix">Contract Number</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="contractnumber_prefixsuffix">अर्ज क्रमांक</label></td>';
                            echo '</tr>';
                        }
                        
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="contractnumber_prefixsuffix" id="contractnumber_prefixsuffix" value="'.$row['CONTRACTNUMBER_PREFIXSUFFIX'].'" readonly="readonly"></td>';
                        echo '</tr>';
                        
                        echo '<tr>';
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="contractdatetime">Contract Date (dd/mm/yyyy)</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="contractdatetime">कंत्राट दिनांक (dd/mm/yyyy)</label></td>';
                            echo '</tr>';
                        }
                        if (isset($row['CONTRACTDATETIME']))
                        {
                            $contractdatetime = date('d/m/Y',strtotime($row['CONTRACTDATETIME']));
                        }
                        
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="contractdatetime" id="contractdatetime" value="'.$contractdatetime.'"></td>';
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="casteid">
                            Caste</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="casteid">जात</label></td>';
                            echo '</tr>';
                        }
                        $query = "select namedetailid,name_eng,name_unicode from namedetail n where n.active=1 and n.namecategoryid=913742561 order by name_eng";
                        //$result1 = oci_parse($connection, $query); $r = oci_execute($result);
                        $result1 = oci_parse($connection, $query);
                        $r = oci_execute($result1);
                        echo '<tr>';
                        echo '<td><select name="casteid" style="height:35px;font-size:14px;">';
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
                                if ($row1['NAMEDETAILID'] == $row['CASTEID'])
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
                                if ($row1['NAMEDETAILID'] == $row['CASTEID'])
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
                        echo '<td><label for="casteid">*</label></td>';
                        echo '</td>';
                        echo '</tr>';
    
                        echo '<tr>';
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="age">Age</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="age">वय</label></td>';
                            echo '</tr>';
                        }
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="age" id="age" value="'.$row['AGE'].'"></td>';
                        echo '</tr>';
                        
                        echo '<tr>';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="fieldarea">Area</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="fieldarea">क्षेत्र</label></td>';
                        echo '</tr>';
                    }
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="fieldarea" id="fieldarea" value="'.$row['FIELDAREA'].'"></td>';
                    echo '<td><label for="fieldarea">*</label></td>';
                    echo '</tr>';
                    echo '<tr>';
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
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="address" id="address" value="'.$row['ADDRESS'].'"></td>';
                    echo '<td><label for="address">*</label></td>';
                    
                    echo '<tr>';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="isadvance">Advance Allowed?</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="isadvance">अॅॅॅडव्हान्स आहे?</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><select name="isadvance" style="height:35px;font-size:14px;">';
                    if ($_SESSION['lng']=="English")
                    {
                        if ($row['ISADVANCE']=='0')
                        {
                            echo '<option value="1" >Yes</option>';
                            echo '<option value="0" Selected>No</option>';
                        }
                        if ($row['ISADVANCE']=='1')
                        {
                            echo '<option value="1" Selected>Yes</option>';
                            echo '<option value="0">No</option>';
                        }
                    }
                    else
                    {
                        if ($row['ISADVANCE']=='0')
                        {
                            echo '<option value="1">[आहे]</option>';
                            echo '<option value="0" Selected>[नाही]</option>';
                        }
                        if ($row['ISADVANCE']=='1')
                        {
                            echo '<option value="1" Selected>आहे</option>';
                            echo '<option value="0">नाही</option>';
                        }
                    }
                    echo '</select>';
                    echo '<td><label for="isadvance">*</label></td>';
                    echo '</td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="contractorzonecode">Zone</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="contractorzonecode">झोन</label></td>';
                        echo '</tr>';
                    }
                    $query = "select contractorzonecode
                    ,contractorzonename_eng
                    ,contractorzonename_unicode 
                    from contractorzone c 
                    order by contractorzonename_eng";
                    //$result1 = oci_parse($connection, $query); $r = oci_execute($result);
                    $result1 = oci_parse($connection, $query);
                    $r = oci_execute($result1);
                    echo '<tr>';
                    echo '<td><select name="contractorzonecode" style="height:35px;font-size:14px;">';
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
                            if ($row1['CONTRACTORZONECODE'] == $row['CONTRACTORZONECODE'])
                            {
                                echo '<option value="'.$row1['CONTRACTORZONECODE'].'" Selected>'.$row1['CONTRACTORZONENAME_ENG'].'</option>';   
                            }
                            else
                            {
                                echo '<option value="'.$row1['CONTRACTORZONECODE'].'">'.$row1['CONTRACTORZONENAME_ENG'].'</option>';   
                            }
                        }
                        else
                        {
                            if ($row1['CONTRACTORZONECODE'] == $row['CONTRACTORZONECODE'])
                            {
                                echo '<option value="'.$row1['CONTRACTORZONECODE'].'" Selected>'.$row1['CONTRACTORZONENAME_UNICODE'].'</option>';
                            }
                            else
                            {
                                echo '<option value="'.$row1['CONTRACTORZONECODE'].'">'.$row1['CONTRACTORZONENAME_UNICODE'].'</option>';
                            }
                        }
                    }
                    echo '</select>';
                    echo '<td><label for="contractcategoryid">*</label></td>';
                    echo '</td>';
                    echo '</tr>';

                    echo '<tr>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="entityglobalgroupid" id="entityglobalgroupid" value="'.$row['ENTITYGLOBALGROUPID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="finalreportperiodid" id="finalreportperiodid" value="'.$row['FINALREPORTPERIODID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractid" id="contractid" value="'.$row['CONTRACTID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractnumber" id="contractid" value="'.$row['CONTRACTNUMBER'].'"></td>';
                        echo '<tr>';

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
                        $contractcategoryid_en = fnEncrypt($row['CONTRACTCATEGORYID']);
                        if ($row['CONTRACTCATEGORYID'] == 521478963)
                        {
                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Transporter Details';
                            }
                            else
                            {
                                $label = 'वहातूकदार माहिती';
                            }
                            echo '<tr>';
                            echo '<td><a href="../data/contracttransportdetail_list.php?contractid='.fnEncrypt($contractid_de).'">'.$label.tick(contracttransportdetailcount($connection,$contractid_de)).'</a></td>';
                            echo '</tr>';
                            /* if ($_SESSION['lng']=="English")
                            {
                                $label = 'Harvester Details';
                            }
                            else
                            {
                                $label = 'तोडणीदार माहिती';
                            }
                            echo '<tr>';
                            echo '<td><a href="../data/contractharvestdetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$contractcategoryid_en.'">'.$label.tick(contractharvestdetailcount($connection,$contractid_de)).'</a></td>';
                            echo '</tr>'; */
                        }
                        elseif ($row['CONTRACTCATEGORYID'] == 785415263)
                        {
                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Bulluckcart Contractor Details';
                            }
                            else
                            {
                                $label = 'बैलगाडी मुकादम माहिती';
                            }
                            echo '<tr>';
                            echo '<td><a href="../data/contractharvestdetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$contractcategoryid_en.'">'.$label.tick(contractharvestdetailcount($connection,$contractid_de)).'</a></td>';
                            echo '</tr>';
                        }
                        elseif ($row['CONTRACTCATEGORYID'] == 947845153)
                        {
                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Harvester Details';
                            }
                            else
                            {
                                $label = 'तोडणीदार माहिती';
                            }
                            echo '<tr>';
                            echo '<td><a href="../data/contractharvestdetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$contractcategoryid_en.'">'.$label.tick(contractharvestdetailcount($connection,$contractid_de)).'</a></td>';
                            echo '</tr>';
                            
                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Transporter Details';
                            }
                            else
                            {
                                $label = 'वहातूकदार माहिती';
                            }

                            echo '<tr>';
                            echo '<td><a href="../data/contracttransportdetail_list.php?contractid='.fnEncrypt($contractid_de).'">'.$label.tick(contracttransportdetailcount($connection,$contractid_de)).'</a></td>';
                            echo '</tr>';
                        }
                        if ($_SESSION['lng']=="English")
                        {
                            $label = 'Guarantor Details';
                        }
                        else
                        {
                            $label = 'जामिनदार माहिती';
                        }
                        echo '<tr>';
                        echo '<td><a href="../data/contractguarantordetail_list.php?contractid='.fnEncrypt($contractid_de).'">'.$label.tick(contractguarantordetailcount($connection,$contractid_de)).'</a></td>';
                        echo '</tr>';
                        if ($_SESSION['lng']=="English")
                        {
                            $label = 'Witness Details';
                        }
                        else
                        {
                            $label = 'साक्षीदार माहिती';
                        }
                        echo '<tr>';
                        echo '<td><a href="../data/contractwitnessdetail_list.php?contractid='.fnEncrypt($contractid_de).'">'.$label.tick(contractwitnessdetailcount($connection,$contractid_de)).'</a></td>';
                        echo '</tr>';
                        /* if ($row['CONTRACTCATEGORYID'] == 521478963 OR $row['CONTRACTCATEGORYID'] == 785415263)
                        {
                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Nominee Details';
                            }
                            else
                            {
                                $label = 'वारसदार माहिती';
                            }
                            echo '<tr>';
                            echo '<td><a href="../data/contractnomineedetail_list.php?contractid='.fnEncrypt($contractid_de).'">'.$label.tick(contractnomineedetailcount($connection,$contractid_de)).'</a></td>';
                            echo '</tr>';
                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Last Performance Details';
                            }
                            else
                            {
                                $label = 'मागील कार्यक्षमता माहिती';
                            }
                            echo '<tr>';
                            echo '<td><a href="../data/contractperformancedetail_list.php?contractid='.fnEncrypt($contractid_de).'">'.$label.tick(contractperformancedetailcount($connection,$contractid_de)).'</a></td>';
                            echo '</tr>';
                        } */
                        if ($_SESSION['lng']=="English")
                        {
                            $label = 'Document Details';
                        }
                        else
                        {
                            $label = 'दस्ताएेवज (डाॅक्युमेंट) माहिती';
                        }
                        echo '<tr>';
                        echo '<td><a href="../data/contractdocumentdetail_list.php?contractid='.fnEncrypt($contractid_de).'">'.$label.tick(contractdocumentdetailcount($connection,$contractid_de)).'</a></td>';
                        echo '</tr>';
                        
                        /* if ($row['CONTRACTCATEGORYID'] == 947845153)
                        {
                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Mortgage Details';
                            }
                            else
                            {
                                $label = 'गहाणखत माहिती';
                            }
                            echo '<tr>';
                            echo '<td><a href="../data/contractmortgagedetail_list.php?contractid='.fnEncrypt($contractid_de).'">'.$label.tick(contractmortgagedetailcount($connection,$contractid_de)).'</a></td>';
                            echo '</tr>';
                        }
                        if ($_SESSION['lng']=="English")
                        {
                            $label = 'Advance Details';
                        }
                        else
                        {
                            $label = 'अॅडव्हान्स माहिती';
                        }
                        echo '<tr>';
                        echo '<td><a href="../data/contractisadvancedetail_list.php?contractid='.fnEncrypt($contractid_de).'">'.$label.tick(contractisadvancedetailcount($connection,$contractid_de)).'</a></td>';
                        echo '</tr>';
                        if ($_SESSION['lng']=="English")
                        {
                            $label = 'Receipt Details';
                        }
                        else
                        {
                            $label = 'भरणा माहिती';
                        }
                        echo '<tr>';
                        echo '<td><a href="../data/contractreceiptdetail_list.php?contractid='.fnEncrypt($contractid_de).'">'.$label.tick(contractreceiptdetailcount($connection,$contractid_de)).'</a></td>';
                        echo '</tr>';
                        if ($_SESSION['lng']=="English")
                        {
                            $label = 'Item Loan Details';
                        }
                        else
                        {
                            $label = 'वस्तू उधार माहिती';
                        }
                        echo '<tr>';
                        echo '<td><a href="../data/contractitemloandetail_list.php?contractid='.fnEncrypt($contractid_de).'">'.$label.tick(contractitemloandetailcount($connection,$contractid_de)).'</a></td>';
                        echo '</tr>';
                        if ($_SESSION['lng']=="English")
                        {
                            $label = 'Approver Details';
                        }
                        else
                        {
                            $label = 'शिफारसदार माहिती';
                        }
                        echo '<tr>';
                        echo '<td><a href="../data/contractapproverdetail_list.php?contractid='.fnEncrypt($contractid_de).'">'.$label.tick(contractapproverdetailcount($connection,$contractid_de)).'</a></td>';
                        echo '</tr>';                         */
                        $contractcategoryid_en = fnEncrypt($row['CONTRACTCATEGORYID']);
                        if ($row['CONTRACTCATEGORYID'] == 521478963)
                        {
                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Contract Demand View/Print';
                            }
                            else
                            {
                                $label = 'करार मागणी पाहणे/छपाई';
                            }
                            $reportid_en = fnEncrypt('00000001');
                            echo '<tr>';
                            echo '<td><a href="../view/contract_view.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$contractcategoryid_en.'&reportid='.$reportid_en.'">'.$label.'</a></td>';
                            echo '</tr>';
                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Nominal Member View/Print';
                            }
                            else
                            {
                                $label = 'नाममात्र सभासद पाहणे/छपाई';
                            }
                            $reportid_en = fnEncrypt('00000002');
                            echo '<tr>';
                            echo '<td><a href="../view/contract_view.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$contractcategoryid_en.'&reportid='.$reportid_en.'">'.$label.'</a></td>';
                            echo '</tr>';
                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Contract View/Print';
                            }
                            else
                            {
                                $label = 'करारनामा पाहणे/छपाई';
                            }
                            $reportid_en = fnEncrypt('00000009');
                            echo '<tr>';
                            echo '<td><a href="../view/contract_view.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$contractcategoryid_en.'&reportid='.$reportid_en.'">'.$label.'</a></td>';
                            echo '</tr>';
                        }
                        //Bulluckcart
                        elseif ($row['CONTRACTCATEGORYID'] == 785415263)
                        {
                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Contract Demand View/Print';
                            }
                            else
                            {
                                $label = 'करार मागणी पाहणे/छपाई';
                            }
                            $reportid_en = fnEncrypt('00000001');
                            echo '<tr>';
                            echo '<td><a href="../view/contract_view.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$contractcategoryid_en.'&reportid='.$reportid_en.'">'.$label.'</a></td>';
                            echo '</tr>';

                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Nominal Member View/Print';
                            }
                            else
                            {
                                $label = 'नाममात्र सभासद पाहणे/छपाई';
                            }
                            $reportid_en = fnEncrypt('00000002');
                            echo '<tr>';
                            echo '<td><a href="../view/contract_view.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$contractcategoryid_en.'&reportid='.$reportid_en.'">'.$label.'</a></td>';
                            echo '</tr>';
                            
                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Jamin Rokha View/Print';
                            }
                            else
                            {
                                $label = 'जामीन रोखा पाहणे/छपाई';
                            }
                            $reportid_en = fnEncrypt('00000003');
                            echo '<tr>';
                            echo '<td><a href="../view/contract_view.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$contractcategoryid_en.'&reportid='.$reportid_en.'">'.$label.'</a></td>';
                            echo '</tr>';

                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Angikaran View/Print';
                            }
                            else
                            {
                                $label = 'अंगीकरण पाहणे/छपाई';
                            }
                            $reportid_en = fnEncrypt('00000004');
                            echo '<tr>';
                            echo '<td><a href="../view/contract_view.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$contractcategoryid_en.'&reportid='.$reportid_en.'">'.$label.'</a></td>';
                            echo '</tr>';

                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Contract View/Print';
                            }
                            else
                            {
                                $label = 'करारनामा पाहणे/छपाई';
                            }
                            $reportid_en = fnEncrypt('00000009');
                            echo '<tr>';
                            echo '<td><a href="../view/contract_view.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$contractcategoryid_en.'&reportid='.$reportid_en.'">'.$label.'</a></td>';
                            echo '</tr>';
                        }
                        //Harvester
                        elseif ($row['CONTRACTCATEGORYID'] == 947845153)
                        {
                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Contract Demand View/Print';
                            }
                            else
                            {
                                $label = 'करार मागणी पाहणे/छपाई';
                            }
                            $reportid_en = fnEncrypt('00000001');
                            echo '<tr>';
                            echo '<td><a href="../view/contract_view.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$contractcategoryid_en.'&reportid='.$reportid_en.'">'.$label.'</a></td>';
                            echo '</tr>';

                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Nominal Member View/Print';
                            }
                            else
                            {
                                $label = 'नाममात्र सभासद पाहणे/छपाई';
                            }
                            $reportid_en = fnEncrypt('00000002');
                            echo '<tr>';
                            echo '<td><a href="../view/contract_view.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$contractcategoryid_en.'&reportid='.$reportid_en.'">'.$label.'</a></td>';
                            echo '</tr>';
                            
                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Jamin Rokha View/Print';
                            }
                            else
                            {
                                $label = 'जामीन रोखा पाहणे/छपाई';
                            }
                            $reportid_en = fnEncrypt('00000003');
                            echo '<tr>';
                            echo '<td><a href="../view/contract_view.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$contractcategoryid_en.'&reportid='.$reportid_en.'">'.$label.'</a></td>';
                            echo '</tr>';

                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Document Submission View/Print';
                            }
                            else
                            {
                                $label = 'कागदपत्राची पुर्तता पाहणे/छपाई';
                            }
                            $reportid_en = fnEncrypt('00000005');
                            echo '<tr>';
                            echo '<td><a href="../view/contract_view.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$contractcategoryid_en.'&reportid='.$reportid_en.'">'.$label.'</a></td>';
                            echo '</tr>';

                            if ($_SESSION['lng']=="English")
                            {
                                $label = 'Contract View/Print';
                            }
                            else
                            {
                                $label = 'करारनामा पाहणे/छपाई';
                            }
                            $reportid_en = fnEncrypt('00000009');
                            echo '<tr>';
                            echo '<td><a href="../view/contract_view.php?contractid='.fnEncrypt($contractid_de).'&contractcategoryid='.$contractcategoryid_en.'&reportid='.$reportid_en.'">'.$label.'</a></td>';
                            echo '</tr>';
                        }


                    echo '</table>';
                    echo '</form>';
                    echo '</section>';
                }
                else
                {
                    echo '<section>';
                    echo '<form method="post" action="../api_action/contract_action.php">';
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
                    //$result1 = oci_parse($connection, $query); $r = oci_execute($result);
                    $result1 = oci_parse($connection, $query);
                    $r = oci_execute($result1);
                    $i=0;
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
                            if ($i==0)
                            {
                                echo '<option value="'.$row1['SEASONID'].'" Selected>'.$row1['NAME_ENG'].'</option>';   
                            }
                            else
                            {
                                echo '<option value="'.$row1['SEASONID'].'">'.$row1['NAME_ENG'].'</option>';   
                            }
                        }
                        else
                        {
                            if ($i==0)
                            {
                                echo '<option value="'.$row1['SEASONID'].'" Selected>'.$row1['NAME_UNICODE'].'</option>';
                            }
                            else
                            {
                                echo '<option value="'.$row1['SEASONID'].'">'.$row1['NAME_UNICODE'].'</option>';
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
                    //$result1 = oci_parse($connection, $query); $r = oci_execute($result);
                    $result1 = oci_parse($connection, $query);
                    $r = oci_execute($result1);
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
                    $i=0;
                    while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                    {
                        if ($_SESSION['lng']=="English")
                        {
                            if ($i==0)
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
                            if ($i==0)
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'" Selected>'.$row1['NAME_UNICODE'].'</option>';
                            }
                            else
                            {
                                echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_UNICODE'].'</option>';
                            }
                        }
                        $i++;
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
                    echo '<td><input autofocus type="text" style="font-size:12pt;height:30px" name="servicecontractor" id="servicecontractor" style="width:300px"></td>';
                    echo '<td><label for="servicecontractorid">*</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input readonly="readonly" type="text" style="font-size:12pt;height:30px" name="servicecontractorid" id="servicecontractorid"></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="contractcategoryid">Contract Category</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="contractcategoryid">कंत्राट वर्ग</label></td>';
                        echo '</tr>';
                    }
                    $query = "select contractcategoryid,contractcategoryname_eng,contractcategoryname_unicode from contractcategory c where c.active=1 order by transactionid desc";
                    //$result1 = oci_parse($connection, $query); $r = oci_execute($result);
                    $result1 = oci_parse($connection, $query);
                    $r = oci_execute($result1);
                    echo '<tr>';
                    echo '<td><select name="contractcategoryid" style="height:35px;font-size:14px;">';
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
                            echo '<option value="'.$row1['CONTRACTCATEGORYID'].'">'.$row1['CONTRACTCATEGORYNAME_ENG'].'</option>';   
                        }
                        else
                        {
                            echo '<option value="'.$row1['CONTRACTCATEGORYID'].'">'.$row1['CONTRACTCATEGORYNAME_UNICODE'].'</option>';
                        }
                    }
                    echo '</select>';
                    echo '<td><label for="contractcategoryid">*</label></td>';
                    echo '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="applicationdatetime">Application Date (dd/mm/yyyy)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="applicationdatetime">अर्ज दिनांक (dd/mm/yyyy)</label></td>';
                        echo '</tr>';
                    }

                    
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="applicationdatetime" id="applicationdatetime"></td>';
                    echo '<td><label for="applicationdatetime">*</label></td>';
                    echo '</tr>';

                    echo '<tr>';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="contractdatetime">Contract Date (dd/mm/yyyy)</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="contractdatetime">कंत्राट दिनांक (dd/mm/yyyy)</label></td>';
                        echo '</tr>';
                    }
                    
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="contractdatetime" id="contractdatetime"></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="casteid">
                        Caste</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="casteid">जात</label></td>';
                        echo '</tr>';
                    }
                    $query = "select namedetailid,name_eng,name_unicode from namedetail n where n.active=1 and n.namecategoryid=913742561 order by name_eng";
                    //$result1 = oci_parse($connection, $query); $r = oci_execute($result);
                    $result1 = oci_parse($connection, $query);
                    $r = oci_execute($result1);
                    echo '<tr>';
                    echo '<td><select name="casteid" style="height:35px;font-size:14px;">';
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
                            echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_ENG'].'</option>';   
                        }
                        else
                        {
                            echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_UNICODE'].'</option>';
                        }
                    }
                    echo '</select>';
                    //echo '<td><label for="casteid">*</label></td>';
                    echo '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="age">Age</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="age">वय</label></td>';
                        echo '</tr>';
                    }
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="age" id="age"></td>';
                    echo '<td><label for="age">*</label></td>';
                    echo '</tr>';

                    echo '<tr>';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="fieldarea">Area</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="fieldarea">क्षेत्र</label></td>';
                        echo '</tr>';
                    }
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="fieldarea" id="fieldarea"></td>';
                    echo '<td><label for="fieldarea">*</label></td>';
                    echo '</tr>';

                    echo '<tr>';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="fieldarea">Address</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="fieldarea">पत्ता</label></td>';
                        echo '</tr>';
                    }
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="address" id="address"></td>';
                    echo '<td><label for="address">*</label></td>';
                    echo '</tr>';

                    echo '<tr>';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="isadvance">Advance Allowed?</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="isadvance">अॅॅॅडव्हान्स आहे?</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><select name="isadvance" style="height:35px;font-size:14px;">';
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<option value="1" Selected>[Yes]</option>';
                        echo '<option value="0" Selected>[No]</option>';
                        echo '<option value="2" Selected>[No Advance Commision Allowed]</option>';
                    }
                    else
                    {
                        echo '<option value="1">[आहे]</option>';
                        echo '<option value="0">[नाही]</option>';
                        echo '<option value="2">[नाही अॅॅॅडव्हान्स कमिशन आहे]</option>';
                    }
                    echo '</select>';
                    echo '<td><label for="isadvance">*</label></td>';
                    echo '</td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="contractorzonecode">Zone</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="contractorzonecode">झोन</label></td>';
                        echo '</tr>';
                    }
                    $query = "select contractorzonecode
                    ,contractorzonename_eng
                    ,contractorzonename_unicode 
                    from contractorzone c 
                    order by contractorzonename_eng";
                    //$result1 = oci_parse($connection, $query); $r = oci_execute($result);
                    $result1 = oci_parse($connection, $query);
                    $r = oci_execute($result1);
                    echo '<tr>';
                    echo '<td><select name="contractorzonecode" style="height:35px;font-size:14px;">';
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
                            if ($row1['CONTRACTORZONECODE'] == $row['CONTRACTORZONECODE'])
                            {
                                echo '<option value="'.$row1['CONTRACTORZONECODE'].'" Selected>'.$row1['CONTRACTORZONENAME_ENG'].'</option>';   
                            }
                            else
                            {
                                echo '<option value="'.$row1['CONTRACTORZONECODE'].'">'.$row1['CONTRACTORZONENAME_ENG'].'</option>';   
                            }
                        }
                        else
                        {
                            if ($row1['CONTRACTCATEGORYID'] == $row['CONTRACTCATEGORYID'])
                            {
                                echo '<option value="'.$row1['CONTRACTORZONECODE'].'" Selected>'.$row1['CONTRACTORZONENAME_UNICODE'].'</option>';
                            }
                            else
                            {
                                echo '<option value="'.$row1['CONTRACTORZONECODE'].'">'.$row1['CONTRACTORZONENAME_UNICODE'].'</option>';
                            }
                        }
                    }
                    echo '</select>';
                    echo '<td><label for="contractorzonecode">*</label></td>';
                    echo '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="entityglobalgroupid" id="entityglobalgroupid" value="'.$_SESSION['entityglobalgroupid'].'"></td>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="finalreportperiodid" id="finalreportperiodid" value="'.$_SESSION['finalreportperiodid'].'"></td>';
                    echo '<tr>';
                    if ($_SESSION["responsibilitycode"] == 451230287895415 or $_SESSION["responsibilitycode"] == 475124562358965)
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