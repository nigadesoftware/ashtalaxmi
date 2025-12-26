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
    if (isset($_GET['contracttransportdetailid']))
    {
        $contracttransportdetailid_de = fnDecrypt($_GET['contracttransportdetailid']);    
    }
    $flag = $_GET['flag'];

    // Opens a connection to a MySQL server
    $connection=rawmaterial_connection();

    function contractcategoryid(&$connection,$contractid)
    {
        $query = "select c.contractcategoryid from contract c where c.active=1 
        and c.contractid=".$contractid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['CONTRACTCATEGORYID'];
        }
        else
        {
            return 0;
        }
    }

    function bankbranch(&$connection,$bankbranchid,$lng)
    {
        $query = "select r.bankbranchid,r.bankid,
        r.name_unicode,
        r.name_eng
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
        <title>Contract Transport Detail</title>
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
                <li><a class="navbar" href="../data/entitymenu.php">Entity Menu</a><br/>
                <?php
                    /*$personnamedetailtypeid_en = fnEncrypt($personnamedetailtypeid_de);
                    $personnamedetailid_en = fnEncrypt($personnamedetailid_de);
                    echo '<li><a style="color:#f48;text-align:left;" href="../data/personnamedetail_find.php?personnamedetailtypeid='.$personnamedetailtypeid_en.'">personnamedetail Find</a><br/>';*/
                    echo '<li><a class="navbar" href="../data/contract.php?contractid='.fnEncrypt($contractid_de).'&flag='.fnEncrypt('Display').'">Add/Display Contract</a></br>';
                    echo '<li><a class="navbar" href="../data/contracttransportdetail_list.php?contractid='.fnEncrypt($contractid_de).'&flag='.fnEncrypt('Display').'">Add/Display Contract Transport Detail List</a></br>';
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/contract.png" width="201" height="41px"></div>
            <?php
                $contractcategoryid = contractcategoryid($connection,$contractid_de);
                $query = "select f.*,s.name_eng as contractorname_eng,s.name_unicode as contractorname_unicode from contracttransportdetail f,servicecontractor s where f.active=1 and s.active=1 and f.servicecontractorid=s.servicecontractorid and f.contractid = ".$contractid_de." and f.contracttransportdetailid=".$contracttransportdetailid_de;
                //echo $query;
                $result = oci_parse($connection, $query); $r = oci_execute($result);
                if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    echo '<section>';
                    //if ($flag_de == 'Display')
                    //{
                        echo '<form method="post" action="../api_action/contracttransportdetail_action.php">';
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

                        if ($_SESSION['lng'] == "English")
                        {
                            echo '<tr>';
                            echo '<td><label for="transportationvehicleid">Transportation Vehicle</label></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>';
                            echo '<select name="transportationvehicleid" style="height:35px;font-size:14px;">';
                            echo '<option value="0" Selected>[No]</option>';   
                            $query = "select * from namedetail n where n.active=1 and namecategoryid=398541725 and n.namedetailid not in (248767942) order by name_eng";
                            $result1 = oci_parse($connection, $query); $r = oci_execute($result1);
                            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                            {
                                if ($row['TRANSPORTATIONVEHICLEID'] == $row1['NAMEDETAILID'])
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
                            echo '<td><label for="transportationvehicleid">वाहतुकीचे वाहन</label></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>';
                            echo '<select name="transportationvehicleid" style="height:35px;font-size:14px;">';
                            echo '<option value="0" Selected>[नाही]</option>';   
                            $query = "select * from namedetail n where n.active=1 and namecategoryid=398541725 and n.namedetailid not in (248767942) order by name_unicode";
                            $result1 = oci_parse($connection, $query);
                            $r = oci_execute($result1);
                            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                            {
                                if ($row['TRANSPORTATIONVEHICLEID'] == $row1['NAMEDETAILID'])
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

                    if ($_SESSION['lng'] == "English")
                    {
                        echo '<tr>';
                        echo '<td><label for="vehiclemfgid">Vehicle Manufacturer</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>';
                        echo '<select name="vehiclemfgid" style="height:35px;font-size:14px;">';
                        echo '<option value="0" Selected>[No]</option>';  
                        $query = "select * from namedetail n where n.active=1 and namecategoryid=325968741 order by name_eng"; 
                        $result1 = oci_parse($connection, $query);
                        $r = oci_execute($result1);
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            if ($row1['NAMEDETAILID'] == $row['VEHICLEMFGID'])
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
                        echo '<td><label for="vehiclemfgid">वाहन निर्माता</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>';
                        echo '<select name="vehiclemfgid" style="height:35px;font-size:14px;">';
                        echo '<option value="0" Selected>[नाही]</option>';   
                        $result1 = oci_parse($connection, "select * from namedetail n where n.active=1 and namecategoryid=325968741 order by name_unicode");
                        $r = oci_execute($result1);
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            if ($row1['NAMEDETAILID'] == $row['VEHICLEMFGID'])
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
                            echo '<td><label for="vehiclenumber">Vehicle Number</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="vehiclenumber">वाहन नंबर</label></td>';
                            echo '</tr>';
                        }

                        echo '<tr>';
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="vehiclenumber" id="vehiclenumber" style="width:300px" value="'.$row['VEHICLENUMBER'].'"></td>';
                        echo '</tr>';

                        if ($contractcategoryid == 521478963)
                        {
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

                            if ($_SESSION['lng']=='English')
                            {
                                echo '<tr>';
                                echo '<td><label for="bankbranch">Bank Branch</label></td>';
                                echo '</tr>';
                            }
                            else
                            {
                                echo '<tr>';
                                echo '<td><label for="bankbranch">बँक शाखा</label></td>';
                                echo '</tr>';
                            }
                            echo '<tr>';
                            if ($_SESSION['lng']=='English')
                            {
                                echo '<td><input type="text" style="font-size:12pt;height:30px" name="bankbranch" id="bankbranch" value="'.bankbranch($connection,$row['BANKBRANCHID'],0).'" STYLE="WIDTH:300PX"></td>';
                            }
                            else
                            {
                                echo '<td><input type="text" style="font-size:12pt;height:30px" name="bankbranch" id="bankbranch" value="'.bankbranch($connection,$row['BANKBRANCHID'],1).'" STYLE="WIDTH:300PX"></td>';
                            }
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td><input readonly="readonly" type="text" style="font-size:12pt;height:30px" name="bankbranchid" id="bankbranchid" value="'.$row['BANKBRANCHID'].'"></td>';
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
                        }

                        echo '<tr>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractid" id="contractid" style="width:300px" value ="'.$row['CONTRACTID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contracttransportdetailid" id="contracttransportdetailid" style="width:300px" value ="'.$row['CONTRACTTRANSPORTDETAILID'].'"></td>';
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
                        
                        $contractreferencecategoryid_en = fnEncrypt('584251658');
                        $contractreferencedetailid_en = fnEncrypt($row['CONTRACTTRANSPORTDETAILID']);
                        /* echo '<tr>';
                        echo '<td><a href="../data/contractaadhardetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractreferencecategoryid='.$contractreferencecategoryid_en.'&contractreferencedetailid='.$contractreferencedetailid_en.'">Aadhar Detail</a></td>';
                        echo '</tr>'; */

                        echo '<tr>';
                        echo '<td><a href="../data/contractfingerprintdetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractreferencecategoryid='.$contractreferencecategoryid_en.'&contractreferencedetailid='.$contractreferencedetailid_en.'">Finger Print Detail</a></td>';
                        echo '</tr>';

                        echo '<tr>';
                        echo '<td><a href="../data/contractphotodetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractreferencecategoryid='.$contractreferencecategoryid_en.'&contractreferencedetailid='.$contractreferencedetailid_en.'">Photo Detail</a></td>';
                        echo '</tr>';
                        // Vehicle is tractor then
                        if ($row['TRANSPORTATIONVEHICLEID'] == 248768236 AND $contractcategoryid == 521478963)
                        {
                            $query = "SELECT f.* FROM contracttransporttrailerdetail f where f.active=1 and f.contractid=".$contractid_de." and f.contracttransportdetailid=".$contracttransportdetailid_de;                         
                            //echo $query;
                            //oci_parse($connection,'SET NAMES UTF8');
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
                            $contracttransportdetailid_en = fnEncrypt($contracttransportdetailid_de);
                            $contracttransporttrailerdetid_en = fnEncrypt('000000000');
                            //if ($_SESSION["responsibilitycode"] == 325434741256025)
                            //{
                                echo '<tr style="font-size:13px">';
                                echo '<td><a style="color:#4a4" class="servicebar" href="../data/contracttransporttrailerdetail.php?contractid='.$contractid_en.'&contracttransportdetailid='.$contracttransportdetailid_en.'&contracttransporttrailerdetid='.$contracttransporttrailerdetid_en.'&flag=new">नवीन ट्रेलर माहिती</br>New Trailer Detail</a>';
                                echo '</tr>';
                            //}
                            while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                            {
                                echo '<tr style="font-size:13px">';
                                $contracttransporttrailerdetid_en = fnEncrypt($row['CONTRACTTRANSPORTTRAILERDETID']);
                                echo '<td><a class="servicebar">'.$row['TRAILERNUMBER'].'</A>';
                                if ($_SESSION["responsibilitycode"] == 475124562358965)
                                {
                                    echo '<td><a style="color:#333" class="servicebar" href="../data/contracttransporttrailerdetail.php?contractid='.$contractid_en.'&contracttransportdetailid='.$contracttransportdetailid_en.'&contracttransporttrailerdetid='.$contracttransporttrailerdetid_en.'&flag=view'.'"><img border="0" alt="पहा (View)" src="../img/viewdata.png" width="18" height="10"></br></a>';
                                    if ($flag=='change')
                                    {
                                        echo '<td><a style="color:#333" class="servicebar" href="../data/contracttransporttrailerdetail.php?contractid='.$contractid_en.'&contracttransportdetailid='.$contracttransportdetailid_en.'&contracttransporttrailerdetid='.$contracttransporttrailerdetid_en.'&flag=change'.'"><img border="0" alt="बदल (Change)" src="../img/changedata.png" width="18" height="10"></br></a>';    
                                    }
                                    if ($flag=='delete')
                                    {
                                        echo '<td><a style="color:#333" class="servicebar" href="../data/contracttransporttrailerdetail.php?contractid='.$contractid_en.'&contracttransportdetailid='.$contracttransportdetailid_en.'&contracttransporttrailerdetid='.$contracttransporttrailerdetid_en.'&flag=delete'.'"><img border="0" alt="खोडणे (Delete)" src="../img/deletedata.png" width="18" height="10"></br></a>';
                                    }
                                }
                                else if ($_SESSION["responsibilitycode"] == 451230287895415)
                                {
                                    echo '<td><a style="color:#333" class="servicebar" href="../data/contracttransporttrailerdetail.php?contractid='.$contractid_en.'&contracttransportdetailid='.$contracttransportdetailid_en.'&CONTRACTTRANSPORTTRAILERDETID='.$CONTRACTTRANSPORTTRAILERDETID_en.'&flag=view'.'"><img border="0" alt="पहा (View)" src="../img/viewdata.png" width="18" height="10"></br></a>';
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
                    echo '<form method="post" action="../api_action/contracttransportdetail_action.php">';
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
                    echo '<td><input readonly="readonly" type="text" style="font-size:12pt;height:30px" name="servicecontractorid" id="servicecontractorid" value="'.servicecontractorid($connection,$contractid_de).'"></td>';
                    echo '</tr>';

                    if ($_SESSION['lng'] == "English")
                    {
                        echo '<tr>';
                        echo '<td><label for="transportationvehicleid">Transportation Vehicle</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>';
                        echo '<select name="transportationvehicleid" style="height:35px;font-size:14px;">';
                        echo '<option value="0" Selected>[No]</option>';  
                        $query = "select * from namedetail n where n.active=1 and namecategoryid=398541725 and n.namedetail not in (248767942) order by name_eng"; 
                        $result1 = oci_parse($connection, $query);
                        $r = oci_execute($result1);
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_ENG'].'</option>';   
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="transportationvehicleid">वाहतुकीचे वाहन</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>';
                        echo '<select name="transportationvehicleid" style="height:35px;font-size:14px;">';
                        echo '<option value="0" Selected>[नाही]</option>';   
                        $result1 = oci_parse($connection, "select * from namedetail n where n.active=1 and namecategoryid=398541725 and n.namedetailid not in (248767942) order by name_unicode");
                        $r = oci_execute($result1);
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_UNICODE'].'</option>';   
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';
                    }

                    if ($_SESSION['lng'] == "English")
                    {
                        echo '<tr>';
                        echo '<td><label for="vehiclemfgid">Vehicle Manufacturer</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>';
                        echo '<select name="vehiclemfgid" style="height:35px;font-size:14px;">';
                        echo '<option value="0" Selected>[No]</option>';  
                        $query = "select * from namedetail n where n.active=1 and namecategoryid=325968741 order by name_eng"; 
                        $result1 = oci_parse($connection, $query);
                        $r = oci_execute($result1);
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_ENG'].'</option>';   
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="vehiclemfgid">वाहन निर्माता</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>';
                        echo '<select name="vehiclemfgid" style="height:35px;font-size:14px;">';
                        echo '<option value="0" Selected>[नाही]</option>';   
                        $result1 = oci_parse($connection, "select * from namedetail n where n.active=1 and namecategoryid=325968741 order by name_unicode");
                        $r = oci_execute($result1);
                        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            echo '<option value="'.$row1['NAMEDETAILID'].'">'.$row1['NAME_UNICODE'].'</option>';   
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    
                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="vehiclenumber">Vehicle Number</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="vehiclenumber">वाहन नंबर</label></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="vehiclenumber" id="vehiclenumber" style="width:300px"></td>';
                    echo '</tr>';

                    if ($contractcategoryid == 521478963)
                    {
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
                            echo '<td><label for="bankbranch">Bank Branch</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="bankbranch">बँक शाखा</label></td>';
                            echo '</tr>';
                        }
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
                    }

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