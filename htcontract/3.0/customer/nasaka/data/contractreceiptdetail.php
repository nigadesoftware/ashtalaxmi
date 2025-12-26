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
    if (isset($_GET['contractreceiptdetailid']))
    {
        $contractreceiptdetailid_de = fnDecrypt($_GET['contractreceiptdetailid']);    
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
        <title>Contract Receipt Detail</title>
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
                    echo '<li><a class="navbar" href="../data/contractreceiptdetail_list.php?contractid='.fnEncrypt($contractid_de).'&flag='.fnEncrypt('Display').'">Add/Display Contract Transport Detail List</a></br>';
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/contract.png" width="201" height="41px"></div>
            <?php
                $query = "select f.* from contractreceiptdetail f where f.active=1 and f.contractid = ".$contractid_de." and f.contractreceiptdetailid=".$contractreceiptdetailid_de;
                //echo $query;
                $result = oci_parse($connection, $query); $r = oci_execute($result);
                if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    echo '<section>';
                    //if ($flag_de == 'Display')
                    //{
                        echo '<form method="post" action="../api_action/contractreceiptdetail_action.php">';
                    //}
                        echo '<table border="0" >';

                        echo '<tr>';  
                        echo '<td></td>';  
                        echo '</tr>';
                        
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="receiptcategoryid">Receipt Category</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="receiptcategoryid">जमा वर्ग</label></td>';
                            echo '</tr>';
                        }
                        $query1 = "select receiptcategoryid,receiptcategoryname_eng,receiptcategoryname_unicode from receiptcategory s where s.active=1 order by transactionid desc";
                        $result1 = oci_parse($connection, $query1);
                        $r = oci_execute($result1);
                        echo '<tr>';
                        echo '<td><select name="receiptcategoryid" style="height:35px;font-size:14px;">';
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
                                if ($row1['RECEIPTCATEGORYID']==$row['RECEIPTCATEGORYID'])
                                {
                                    echo '<option value="'.$row1['RECEIPTCATEGORYID'].'" SELECTED>'.$row1['RECEIPTCATEGORYNAME_ENG'].'</OPTION>';
                                }
                                else
                                {
                                    echo '<option value="'.$row1['RECEIPTCATEGORYID'].'">'.$row1['RECEIPTCATEGORYNAME_ENG'].'</OPTION>';
                                }
                            }
                            else
                            {
                                if ($row1['RECEIPTCATEGORYID']==$row['RECEIPTCATEGORYID'])
                                {
                                    echo '<option value="'.$row1['RECEIPTCATEGORYID'].'" SELECTED>'.$row1['RECEIPTCATEGORYNAME_UNICODE'].'</OPTION>';
                                }
                                else
                                {
                                    echo '<option value="'.$row1['RECEIPTCATEGORYID'].'">'.$row1['RECEIPTCATEGORYNAME_UNICODE'].'</OPTION>';
                                }
                            }
                        }
                        echo '</select>';
                        echo '<td><label for="receiptcategoryid">*</label></td>';
                        echo '</td>';
                        echo '</tr>';

                        if (isset($row['RECEIPTDATETIME']))
                        {
                            $receiptdatetime = date('d/m/Y',strtotime($row['RECEIPTDATETIME']));
                        }
                        
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="receiptdatetime">Receipt Date</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="receiptdatetime">जमा पावती दिनांक</label></td>';
                            echo '</tr>';
                        }
                        echo '<tr>';
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="receiptdatetime" id="receiptdatetime" style="width:300px" value="'.$receiptdatetime.'"></td>';
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="bankbranchid">Bank Branch</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="bankbranchid">बँक शाखा</label></td>';
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
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="bankbranchid" id="bankbranchid" style="width:300px" value="'.$row['BANKBRANCHID'].'"></td>';
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
                            echo '<td><label for="chequenumber">चेक नंबर</label></td>';
                            echo '</tr>';
                        }
                        echo '<tr>';
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="chequenumber" id="chequenumber" style="width:300px" value="'.$row['CHEQUENUMBER'].'"></td>';
                        echo '</tr>';

                        if (isset($row['CHEQUEDATETIME']))
                        {
                            $chequedatetime = date('d/m/Y',strtotime($row['CHEQUEDATETIME']));
                        }
                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="chequedatetime">Cheque Date</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="chequedatetime">चेक दिनांक</label></td>';
                            echo '</tr>';
                        }
                        echo '<tr>';
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="chequedatetime" id="chequedatetime" style="width:300px" value="'.$chequedatetime.'"></td>';
                        echo '</tr>';

                        if ($_SESSION['lng']=="English")
                        {
                            echo '<tr>';
                            echo '<td><label for="chequeamount">Cheque Amount</label></td>';
                            echo '</tr>';
                        }
                        else
                        {
                            echo '<tr>';
                            echo '<td><label for="chequeamount">चेक रक्कम</label></td>';
                            echo '</tr>';
                        }
                        echo '<tr>';
                        echo '<td><input type="text" style="font-size:12pt;height:30px" name="chequeamount" id="chequeamount" style="width:300px" value="'.$row['CHEQUEAMOUNT'].'"></td>';
                        echo '</tr>';

                        echo '<tr>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractid" id="contractid" style="width:300px" value ="'.$row['CONTRACTID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractreceiptdetailid" id="contractreceiptdetailid" style="width:300px" value ="'.$row['CONTRACTRECEIPTDETAILID'].'"></td>';
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
                    echo '<form method="post" action="../api_action/contractreceiptdetail_action.php">';
                    echo '<table border="0" >';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="receiptcategoryid">Receipt Category</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="receiptcategoryid">जमा वर्ग</label></td>';
                        echo '</tr>';
                    }
                    $query1 = "select receiptcategoryid,receiptcategoryname_eng,receiptcategoryname_unicode from receiptcategory s where s.active=1 order by transactionid desc";
                    $result1 = oci_parse($connection, $query1);
                    $r = oci_execute($result1);
                    echo '<tr>';
                    echo '<td><select name="receiptcategoryid" style="height:35px;font-size:14px;">';
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
                            echo '<option value="'.$row1['RECEIPTCATEGORYID'].'">'.$row1['RECEIPTCATEGORYNAME_ENG'].'</OPTION>';
                        }
                        else
                        {
                            echo '<option value="'.$row1['RECEIPTCATEGORYID'].'">'.$row1['RECEIPTCATEGORYNAME_UNICODE'].'</OPTION>';
                        }
                    }
                    echo '</select>';
                    echo '<td><label for="receiptcategoryid">*</label></td>';
                    echo '</td>';
                    echo '</tr>';


                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="receiptdatetime">Receipt Date</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="receiptdatetime">जमा पावती दिनांक</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="receiptdatetime" id="receiptdatetime" style="width:300px"></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="bankbranchid">Bank Branch</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="bankbranchid">बँक शाखा</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="bankbranch" id="bankbranch" style="width:300px"></td>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="bankbranchid" id="bankbranchid" style="width:300px"></td>';
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
                        echo '<td><label for="chequenumber">चेक नंबर</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="chequenumber" id="chequenumber" style="width:300px"></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="chequedatetime">Cheque Date</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="chequedatetime">चेक दिनांक</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="chequedatetime" id="chequedatetime" style="width:300px"></td>';
                    echo '</tr>';

                    if ($_SESSION['lng']=="English")
                    {
                        echo '<tr>';
                        echo '<td><label for="chequeamount">Cheque Amount</label></td>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><label for="chequeamount">चेक रक्कम</label></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="chequeamount" id="chequeamount" style="width:300px"></td>';
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