<?php
    require("../info/phpgetlogin.php");
    require("../info/ncryptdcrypt.php");
    require("../info/swapproutine.php");
    //System Admin,Admin
    if (isaccessible(621478512368915)==0 and isaccessible(785236954125917)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $misuserresponsibilityid_de = fnDecrypt($_GET['misuserresponsibilityid']);
    $userid_de = fnDecrypt($_GET['userid']);
    $flag = $_GET['flag'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/w3.css">
        <title>Grant User Responsibility</title>
        <style type="text/css">
            body
            {
                background-color: #fff;
            }
            header
            {
                background-color: #fff;
                min-height: 70px;
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
                color: #111;
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
    </head>
    <body>
        <nav "w3-container">
            <ul class="navbar">
                <li><a class="navbar" href="../mis/usermenu.php">Menu</a>
            <?php
                $userid_en = fnEncrypt($userid_de);
                echo '<li><a class="navbar" href="../ip_view/userresponsibility_list.php?userid='.$userid_en.'">User Responsibility List</a>';
                echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
            ?>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/grantrole.png" width="146px" height="33px">
            </div>
          <section>
                        <?php
                        require('../info/phpsqlajax_dbinfo.php');
                        // Opens a connection to a MySQL server
                        $connection=mysqli_connect($hostname, $username, $password, $database);
                        // Check connection
                        if (mysqli_connect_errno())
                        {
                          //echo "Failed to connect to MySQL: ".mysqli_connect_error();
                          exit;
                        }
                        //mysqli_query($connection,'SET NAMES UTF8');
                        $query = "select m.* from misuserresponsibility m where m.misactive=1 and m.misuserresponsibilityid=".$misuserresponsibilityid_de;
                        $result = oci_parse($connection, $query); $r = oci_execute($result);
                        $row = oci_fetch_array($result,OCI_ASSOC);
                        if (isset($row['MISUSERRESPONSIBILITYID'])) 
                        {
                            echo '<section>';
                            if ($flag == 'delete')
                            {
                                echo '<form method="post" action="../ip_view/userresponsibility_delete.php">';
                            }
                            echo '<table border="0" >';
                            echo '<tr>';
                            echo '<td><label for="user">User</label></td>';
                            echo '</tr>';
                            $query ="select * from misuser m where m.misuseractive=1 and miscustomerid=".$customerid." and m.misuserid=".$userid_de." order by misusername";
                            echo '<tr>';
                            echo '<td>';
                            echo '<select name="misuserid" style="height:35px;font-size:14px;">';
                            $result1=oci_parse($connection, $query); $r = oci_execute($result);
                            while ($row1 = oci_fetch_array($result1,OCI_ASSOC))
                            {
                                if ($row['MISUSERID'] == $row1['MISUSERID'])
                                {
                                    echo '<option value="'.$row1['misuserid'].'" Selected>'.$row1['misusername'].'</option>';                                   
                                } 
                                else
                                {
                                    echo '<option value="'.$row1['misuserid'].'">'.$row1['misusername'].'</option>';                                                                       
                                }   
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '</tr>';
                            echo '<tr>';

                            echo '<tr>';
                            echo '<td><label for="responsibility">Responsibility</label></td>';
                            echo '</tr>';
                            if ($_SESSION["responsibilitycode"] == 621478512368915)
                            {
                                $query ="select * from misresponsibility m where m.misactive=1 and misresponsibilityid=".$row['MISRESPONSIBILITYID']." ORDER BY MISRESPONSIBILITYNAME";
                            }
                            else
                            {
                                $query ="select * from misresponsibility m where m.misactive=1 and misresponsibilityid <> 785236954125917 and misresponsibilityid=".$row['MISRESPONSIBILITYID']." ORDER BY MISRESPONSIBILITYNAME";
                            }
                            echo '<tr>';
                            echo '<td>';
                            echo '<select name="misresponsibilityid" style="height:35px;font-size:14px;">';
                            $result1=oci_parse($connection, $query); $r = oci_execute($result);
                            while ($row1 = oci_fetch_array($result1,OCI_ASSOC))
                            {
                                if ($row['MISRESPONSIBILITYID'] == $row1['MISRESPONSIBILITYID'])
                                {
                                    echo '<option value="'.$row1['misresponsibilityid'].'" Selected>'.$row1['misresponsibilityname'].'</option>';                                   
                                }
                                else
                                {
                                    echo '<option value="'.$row1['misresponsibilityid'].'">'.$row1['misresponsibilityname'].'</option>';                                   
                                }
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '</tr>';

                            if ($flag=="new")
                            {
                                echo '<tr>';
                                echo '<td><input type="submit" style="width:200px;font-size:13pt;" value="जतन (Save)"/>';
                                echo '</tr>';
                            }
                            else if ($flag=="change")
                            {
                                echo '<tr>';
                                echo '<td><input type="submit" style="width:200px;font-size:13pt;" value="बदल (Change)"/>';
                                echo '</tr>';
                            }
                            else if ($flag=="delete")    
                            {
                                echo '<tr>';
                                echo '<td><input type="submit" style="width:200px;font-size:13pt;" value="खोड (Delete)"/>';
                                echo '</tr>';
                            }

                            echo '</table>';
                            echo '</form>';
                            echo '</section>';

                        }
                        else
                        {
                            echo '<section>';
                            echo '<form method="post" action="../ip_view/userresponsibility_insert.php">';
                            echo '<table border="0" >';

                            echo '<tr>';
                            echo '<td><label for="user">User</label></td>';
                            echo '</tr>';

                            $query ="select * from misuser m where m.misuseractive=1 and (miscustomerid = 0 or miscustomerid=".$customerid.") and m.misuserid=".$userid_de." order by misusername";
                            echo '<tr>';
                            echo '<td>';
                            echo '<select name="misuserid" style="height:35px;font-size:14px;">';
                            $result1=oci_parse($connection, $query); $r = oci_execute($result);
                            while ($row1 = oci_fetch_array($result1,OCI_ASSOC))
                            {
                                echo '<option value="'.$row1['misuserid'].'">'.$row1['misusername'].'</option>';                                   
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '</tr>';
                            echo '<tr>';

                            echo '<tr>';
                            echo '<td><label for="responsibility">Responsibility</label></td>';
                            echo '</tr>';
                            if ($_SESSION["responsibilitycode"] == 621478512368915)
                            {
                                $query ="select * from misresponsibility m where m.misactive=1 order by misresponsibilityname";
                            }
                            else
                            {
                                $query ="select * from misresponsibility m where m.misactive=1 and misresponsibilityid <> 785236954125917 and misresponsibilityid <> 621478512368915 order by misresponsibilityname";
                            }
                            echo '<tr>';
                            echo '<td>';
                            echo '<select name="misresponsibilityid" style="height:35px;font-size:14px;">';
                            $result1=oci_parse($connection, $query); $r = oci_execute($result);
                            while ($row1 = oci_fetch_array($result1,OCI_ASSOC))
                            {
                                echo '<option value="'.$row1['misresponsibilityid'].'">'.$row1['misresponsibilityname'].'</option>';                                   
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '</tr>';

                            echo '<tr>';  
                            echo '<td></td>';  
                            echo '</tr>';
                            
                            echo '<tr>';
                            echo '<td><input type="submit" style="width:200px;font-size:13pt;" value="जतन"/>';
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