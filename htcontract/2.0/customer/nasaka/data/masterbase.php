<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    include("../info/ncryptdcrypt.php");
    require("../info/rawmaterialroutine.php");
    
    //Raw Material Master Addition and Raw Material HT Master Addition
    if (isaccessible(452365784154249)==0 and isaccessible(658741245893258)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $namedetailid_de = fnDecrypt($_GET['namedetailid']);
    $namecategoryid_de = fnDecrypt($_GET['namecategoryid']);
    if (isset($_GET['flag']))
    {
        $flag_de = fnDecrypt($_GET['flag']);
    }
    if (!isset($_GET['namecategoryid']))
    {
        echo 'Communication Error';
        exit;
    }
    function labelname(&$connection,$namecategoryid,$lng)
    {
        $query = "select n.* from namecategory n where n.active=1 and namecategoryid=".$namecategoryid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS)) 
        {
            if ($lng==0)
            {
                return $row['NAMECATEGORYNAME_ENG'];
            }
            else
            {
                return $row['NAMECATEGORYNAME'];
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
        <title>Master Base</title>
        <style type="text/css">
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
            }
            label
            {
                color: #000;
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
            <div><img src="../img/masterbase.png" width="201" height="41px"></div>
            <?php
                // Opens a connection to a MySQL server
                $connection=rawmaterial_connection();
                $query = "select n.* from namedetail n where n.active=1 and namecategoryid=".$namecategoryid_de." and namedetailid=".$namedetailid_de;
                $result = oci_parse($connection, $query); $r = oci_execute($result);
                $r = oci_execute($result);
                if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS)) 
                {
                    echo '<section>';
                    if ($flag_de == 'Display')
                    {
                        echo '<form method="post" action="../api_action/masterbase_action.php">';
                    }
                    echo '<table border="0" >';

                    echo '<tr>';
                    echo '<td><label for="namedetailid">'.labelname($connection,$namecategoryid_de,0).' Id</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="namedetailid" id="namedetailid" value="'.$row['NAMEDETAILID'].'" readonly="readonly"></td>';

                    echo '<tr>';
                    echo '<td><label for="name_eng">'.labelname($connection,$namecategoryid_de,0).' Name</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="name_eng" id="name_eng" value="'.$row['NAME_ENG'].'"></td>';
                    echo '<td><label for="name_eng">*</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><label for="name_unicode">'.labelname($connection,$namecategoryid_de,1).'चे नाव</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="name_unicode" id="name_unicode" value="'.$row['NAME_UNICODE'].'"></td>';
                    echo '<td><label for="name_unicode">*</label></td>';
                    echo '</tr>';

                    echo '<tr>';  
                    echo '<td></td>';  
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="submit" name="btn" value="Reset" style="width:100px"</button>';
                    echo '</tr>';
                    if ($flag_de=="Display" and $_SESSION["responsibilitycode"]==658741245893258)
                    {
                        echo '<tr>';
                        echo '<td><input type="hidden" name="id" value="'.$id_de.'">';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><input type="submit" name="btn" value="Change" style="width:100px"</button>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><input type="submit" name="btn" value="Delete" style="width:100px"</button>';
                        echo '</tr>';
                        
                    }
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="namedetailid" id="namedetailid" value="'.$namedetailid_de.'"></td>'; 
                    
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="namecategoryid" id="namecategoryid" value="'.$namecategoryid_de.'"></td>'; 
                    echo '</table>';
                    echo '</form>';
                    echo '</section>';
                }
                else
                {
                    echo '<section>';
                    echo '<form method="post" action="../api_action/masterbase_action.php">';
                    echo '<table border="0" >';
                    
                    echo '<tr>';
                    echo '<td><label for="namedetailid">'.labelname($connection,$namecategoryid_de,0).' Id</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="namedetailid" id="namedetailid" readonly="readonly"></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><label for="name_eng">'.labelname($connection,$namecategoryid_de,0).' Name</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="name_eng" id="name_eng" ></td>';
                    echo '<td><label for="name_eng">*</label></td>';
                    echo '<tr>';
                    echo '<td><label for="name_unicode">'.labelname($connection,$namecategoryid_de,1).'चे नाव</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="name_unicode" id="name_unicode"></td>';
                    echo '<td><label for="name_unicode">*</label></td>';
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
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="namecategoryid" id="namecategoryid" value="'.$namecategoryid_de.'"></td>'; 
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