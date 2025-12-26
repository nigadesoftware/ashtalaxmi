<?php
    require_once('../info/phpsqlajax_dbinfo.php');
    require("../info/phpgetlogin.php");
    require ("../info/ncryptdcrypt.php");
    require_once("../info/crypto.php");
    require_once("../sqlproc/defaultusersettings.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./js/w3.css">
        <title>Organization</title>
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
            article
            {
                background-color: #fff;
                display: table;
                margin-left: 0px;
                padding-left: 3px;
                font-family: Arial Unicode Ms;
                font-size: 15px;
            }
            section
            {
                margin-left: 0px;
                margin-right: 3px;
                float: left;
                text-align: left;
                color: #000;
                line-height: 23px;
            }
            a.navbar
            {
                color: #f48;
            }
            a.servicebar
            {
                color: #b00;
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
            ul
            {
                line-height: 30px;
            }
        </style>
        <script src="./js/1.11.0/jquery.min.js">
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
        <header class="w3-container">
            <!--<div><img src="../img/swapp_namelogo.png" width="93px" height="31px">
                </div>-->
        </header>
        <nav "w3-container">
            <ul class="navbar">
                <li><a class="navbar" href="../index.php">MIS Home</a>
                <li><a class="navbar" href="../index.php">Default User Settings</a>
                <li><a href="../mis/changepassword.php">Change Password</a>
            </ul>
        </nav>
        <article class="w3-container">
            <!-- <div><img src="../img/company.png" width="50px" height="50px">
            </div> -->
            <section>
                    <table style="border=1px solid black; border-radius:10px; padding:0px; padding-top:6px;margin: 0px;" align="left" width=300px>
                       <div class="form-group">
                        <tr>
                            <td><h4 for="">Factory</h4></td>
                        </tr>
                        <?php
                            // Opens a connection to a MySQL server
                            $connection=mysqli_connect($hostname, $username, $password, $database);
                            // Check connection
                            if (mysqli_connect_errno())
                            {
                                echo "Communication Error1";
                                exit;
                            }
                            $connection ->autocommit(FALSE);
                            $custid = new crypto;
                            
                            if ($_SESSION['usersid'] == 621754328954127)
                            {
                                $query = "SELECT c.miscustomerid,c.miscustomername,c.basefolder 
                                FROM miscustomer c
                                group by c.miscustomerid,c.miscustomername,c.basefolder";
                            }
                            else
                            {
                                $query = "SELECT c.miscustomerid,c.miscustomername,c.basefolder 
                                FROM miscustomer c,misuserresponsibility r,misuser u 
                                WHERE c.misactive=1 and r.misactive=1 and c.miscustomerid=r.misfactoryid
                                and r.misuserid=u.misuserid and u.misuserid=".$_SESSION['usersid']."
                                group by c.miscustomerid,c.miscustomername,c.basefolder";
                            }
                            
                            $defaultfactorycode = getdefaultfactorycode($connection);
                            $result = mysqli_query($connection,$query);
                            $i = 1 ;
                            while ($row = @mysqli_fetch_assoc($result))
                            {
                                if ($i==1)
                                {
                                    $customerid_en = $custid->Encrypt($row["miscustomerid"]);
                                    $basefolder_en = $custid->Encrypt($row["basefolder"]);
                                    header('location:../mis/selectmodule.php?customerid='.$customerid_en.'&basefolder='.$basefolder_en);
                                    exit;
                                }
                                else
                                {
                                    $customerid_en = $custid->Encrypt($row["miscustomerid"]);
                                    $basefolder_en = $custid->Encrypt($row["basefolder"]);
                                    echo '<tr>';
                                    echo '<td><img src="../img/company.png" width="20px" height="20px"><a style="color:#000;font-size:18px" href="../mis/selectmodule.php?customerid='.$customerid_en.'&basefolder='.$basefolder_en.'">'.$row['miscustomername'].'</a></br></td>';
                                    echo '</tr>';
                                    $i++;
                                }
                            }
                        ?>
                    </table>
            </section>
        </article>
        <footer>
        </footer>
    </body>
</html>