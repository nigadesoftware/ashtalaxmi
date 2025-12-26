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
    $contractreferencecategoryid_de = fnDecrypt($_GET['contractreferencecategoryid']);
    $contractreferencedetailid_de = fnDecrypt($_GET['contractreferencedetailid']);
    if (isset($_GET['contractfingerprintdetailid']))
    {
        $contractfingerprintdetailid_de = fnDecrypt($_GET['contractfingerprintdetailid']);    
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
        <title>Contract Aadhar Detail</title>
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
            function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

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
                    $contractreferencecategoryid_en = fnEncrypt($contractreferencecategoryid_de);
                    $contractreferencedetailid_en = fnEncrypt($contractreferencedetailid_de);
                    echo '<li><a class="navbar" href="../data/contract.php?contractid='.fnEncrypt($contractid_de).'&flag='.fnEncrypt('Display').'">Add/Display Contract</a></br>';
                    if ($contractreferencecategoryid_de == 254156358)
                    {
                        echo '<li><a class="navbar" href="../data/contractharvestdetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractreferencecategoryid='.$_GET['contractreferencecategoryid'].'&flag='.fnEncrypt('Display').'">Add/Display Contract Harvest Detail List</a></br>';    
                    }
                    elseif ($contractreferencecategoryid_de == 584251658)
                    {
                        echo '<li><a class="navbar" href="../data/contracttransportdetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractreferencecategoryid='.$_GET['contractreferencecategoryid'].'&flag='.fnEncrypt('Display').'">Add/Display Contract Transport Detail List</a></br>';    
                    }
                    elseif ($contractreferencecategoryid_de == 753621495)
                    {
                        echo '<li><a class="navbar" href="../data/contractguarantordetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractreferencecategoryid='.$_GET['contractreferencecategoryid'].'&flag='.fnEncrypt('Display').'">Add/Display Contract Guarantor Detail List</a></br>';    
                    }
                    echo '<li><a class="navbar" href="../data/contractfingerprintdetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractreferencecategoryid='.$contractreferencecategoryid_en.'&contractreferencedetailid='.$contractreferencedetailid_en.'&flag='.fnEncrypt('Display').'">Add/Display Contract Aadhar Detail List</a></br>';
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/contract.png" width="201" height="41px"></div>
            <?php
                if ($contractreferencedetailid_de=="")
                {
                    $query = "select f.* from contractfingerprintdetail f where f.active=1 and contractreferencecategoryid=".$contractreferencecategoryid_de." and contractreferencedetailid is null and f.contractid = ".$contractid_de;
                }
                else
                {
                    $query = "select f.* from contractfingerprintdetail f where f.active=1 and contractreferencecategoryid=".$contractreferencecategoryid_de." and contractreferencedetailid=".$contractreferencedetailid_de." and f.contractid = ".$contractid_de;
                }
                //$query = "select f.* from contractfingerprintdetail f where f.active=1 and f.contractid = ".$contractid_de." and f.contractreferencecategoryid=".$contractreferencecategoryid_de." and f.contractreferencedetailid=".$contractreferencedetailid_de;
                //echo $query;
                $result = oci_parse($connection, $query); $r = oci_execute($result);
                if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    echo '<section>';
                    //if ($flag_de == 'Display')
                    //{
                        echo '<form enctype="multipart/form-data" action="../api_action/contractfingerprintdetail_action.php" method="post" name="changer">';
                    //}
                        echo '<table border="0" >';

                        echo '<tr>';  
                        echo '<td></td>';  
                        echo '</tr>';

                        echo '<input name="MAX_FILE_SIZE" value="102400" type="hidden">';
                        echo '<img src="data:image/jpeg;base64,'.base64_encode( $row['FINGERPRINT']->load() ).'" height="100" width="100"/>';
                        echo '<input name="fingerprint" accept="image/jpeg" type="file">';
                        
                        echo '<tr>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractid" id="contractid" style="width:300px" value ="'.$row['CONTRACTID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractreferencecategoryid" id="contractreferencecategoryid" style="width:300px" value ="'.$row['CONTRACTREFERENCECATEGORYID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractreferencedetailid" id="contractreferencedetailid" style="width:300px" value ="'.$row['CONTRACTREFERENCEDETAILID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractfingerprintdetailid" id="contractfingerprintdetailid" style="width:300px" value ="'.$row['CONTRACTFINGERPRINTDETAILID'].'"></td>';
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
                    //echo '<form method="post" action="../api_action/contractfingerprintdetail_action.php">';
                    echo '<form enctype="multipart/form-data" action="../api_action/contractfingerprintdetail_action.php" method="post" name="changer">';
                    echo '<table border="0" >';

                    echo '<tr>';
                    echo '<td<input name="MAX_FILE_SIZE" value="102400" type="hidden"></td>';
                    echo '<td><input name="fingerprint" accept="image/jpeg" type="file" onchange="readURL(this);"></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><img id="blah" src="#" alt="your image" /></td>';
                    echo '</tr>';
                    //echo<input value="Submit" type="submit">

                    /*echo '<tr>';
                    echo '<td><label for="aadharnumber">Aadhar</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="aadharnumber" id="aadharnumber" ></td>';
                    echo '</tr>';*/

                    echo '<tr>';
                    echo '<td></td>';  
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractid" id="contractid" style="width:300px" value ="'.$contractid_de.'"></td>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractreferencecategoryid" id="contractreferencecategoryid" style="width:300px" value ="'.$contractreferencecategoryid_de.'"></td>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractreferencedetailid" id="contractreferencedetailid" style="width:300px" value ="'.$contractreferencedetailid_de.'"></td>';
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