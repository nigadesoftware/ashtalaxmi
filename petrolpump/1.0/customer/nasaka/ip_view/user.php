<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    include("../info/ncryptdcrypt.php");
    require("../info/swapproutine.php");
    //System Admin,Admin
    if (isaccessible(621478512368915)==0 and isaccessible(785236954125917)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $userid_de = fnDecrypt($_GET['userid']);
    $flag = $_GET['flag'];
?>  
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/w3.css">
        <title>User</title>
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
                <li><a class="navbar" href="../mis/mismenu.php">Menu</a>
                <li><a class="navbar" href="../ip_view/user_list.php">User List</a>
                <?php
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/newuser.png" width="130px" height="33px">
            </div>
             <section>
                <?php
                // Opens a connection to a MySQL server
                $connection=mysqli_connect($hostname, $username, $password, $database);
                // Check connection
                if (mysqli_connect_errno())
                {
                  echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication error</span>';
                  exit;
                }

                //mysqli_query($connection,'SET NAMES UTF8');
                
                $query = "select e.* from misuser e where e.misuseractive=1 and misuserid=".$userid_de;
                $result = oci_parse($connection, $query); $r = oci_execute($result);
                $row = oci_fetch_array($result,OCI_ASSOC);
                if (isset($row['MISUSERID']))
                {
                    echo '<section>';
                    if ($flag == 'change')
                    {
                        echo '<form method="post" action="../ip_view/user_update.php">';
                    }
                    echo '<table border="0" >';
                    echo '<tr>';
                    echo '<td><label for="misusername">User Name</label></td>';
                    echo '</tr>';
                    echo '<tr>';    
                    echo '<td><input type="text" name="misusername" style="width:200px" id="misusername" value="'.$row['MISUSERNAME'].'"></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><label for="misuseraddress">User Address</label></td>';
                    echo '</tr>';
                    echo '<tr>';    
                    echo '<td><input type="text" name="misuseraddress" style="width:200px" id="misuseraddress" value="'.$row['MISUSERADDRESS'].'"></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><label for="aadharnumber">Aadhar Number</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" name="aadharnumber" style="width:200px" id="aadharnumber" value="'.$row['AADHARNUMBER'].'"></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><label for="misusermobile">Mobile (+91...)</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" name="misusermobile" style="width:200px" id="misusermobile" value="+'.$row['MISUSERMOBILE'].'"></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><label for="misemailaddress">E-Mail</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" name="misemailaddress" style="width:200px" id="misemailaddress" value="'.$row['MISEMAILADDRESS'].'"></td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td><label for="suspended">User Suspended</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td>';

                    echo '<select name="suspended" style="height:35px;font-size:14px;width:200px;">';
                    if ($row['SUSPENDED'] == 0)
                    {
                        echo '<option value="0" Selected>No</option>';
                        echo '<option value="1">Suspended</option>';      
                    }
                    else if ($row['SUSPENDED'] == 1)
                    {    
                        echo '<option value="0">No</option>';
                        echo '<option value="1" Selected>Suspended</option>';    
                    }
                    echo '</select>';
                    echo '</td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="userid" id="userid"  value="'.$userid_de.'"></td>';                        
                    echo '</tr>';

                    if ($flag == 'change')
                    {
                        echo '<tr>';
                        echo '<td><input type="submit" value="Submit"/>';
                        echo '</tr>';
                    }
                        
                    echo '</table>';
                    echo '</form>';
                    echo '</section>';
                }
                else
                {
                    echo '<section>';
                    if ($flag == 'new')
                    {
                        echo '<form method="post" action="../ip_view/user_insert.php">';
                    }
                    echo '<table border="0" >';
                        echo '<tr>';
                        echo '<td><label for="misusername">User Name</label></td>';
                        echo '</tr>';
                        echo '<tr>';    
                        echo '<td><input type="text" name="misusername" style="width:200px" id="misusername" value="'.$row['MISUSERNAME'].'"></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><label for="misuseraddress">User Address</label></td>';
                        echo '</tr>';
                        echo '<tr>';    
                        echo '<td><input type="text" name="misuseraddress" style="width:200px" id="misuseraddress" value="'.$row['MISUSERADDRESS'].'"></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><label for="aadharnumber">Aadhar Number</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><input type="text" name="aadharnumber" style="width:200px" id="aadharnumber" value="'.$row['AADHARNUMBER'].'"></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><label for="misusermobile">Mobile (+91...)</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><input type="text" name="misusermobile" style="width:200px" id="misusermobile" value="'.$row['MISUSERMOBILE'].'"></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><label for="misemailaddress">E-Mail</label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><input type="text" name="misemailaddress" style="width:200px" id="misemailaddress" value="'.$row['MISEMAILADDRESS'].'"></td>';
                        echo '</tr>';

                            echo '<tr>';
                            echo '<td><label for="suspended">User Suspended</label></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>';

                            echo '<select name="suspended" style="height:35px;font-size:14px;width:200px;">';
                            if ($row['SUSPENDED'] == 0)
                            {
                                echo '<option value="0" Selected>No</option>';
                                echo '<option value="1">Suspended</option>';      
                            }
                            else if ($row['SUSPENDED'] == 1)
                            {    
                                echo '<option value="0">No</option>';
                                echo '<option value="1" Selected>Suspended</option>';    
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '</tr>';
                        
                        echo '<tr>';
                        echo '<td><input type="submit" value="Submit"/>';
                        echo '</tr>';

                        echo '</table>';
                        echo '</form>';
                        echo '</section>';
                }
                    ?>
                    </table>
                </form>
            </section>
        </article>
        <footer>
        </footer>
    </body>
</html>