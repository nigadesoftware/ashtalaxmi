<?php
    require("../info/phpgetlogin.php");
    require("../../../../../info/crypto.php");
    require('../info/ncryptdcrypt.php');
    require_once("../../../../../sqlproc/defaultusersettings.php");
    $mismoduleid = new crypto;
    if (isset($_GET['mismoduleid']))
    {
        $mismoduleid_de = $mismoduleid->Decrypt($_GET['mismoduleid']);
        $_SESSION['mismoduleid'] = $mismoduleid_de;
    }
    else if (isset($_SESSION['mismoduleid']))
    {
        $mismoduleid_de = $_SESSION['mismoduleid'];
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/w3.css">
        <title>Select Responsibility</title>
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
        <header class="w3-container">
        </header>
        <nav "w3-container">
            <ul class="navbar">
                <li><a class="navbar" href="../../../../../index.php">Home</a>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/userlogin.png" width="146px" height="33px">
            </div>
            <section>
                <form method="post" action="../sqlproc/selectedresponsibility.php">
                    <table style="border=1px solid black; border-radius:10px; padding:0px; padding-top:6px;margin: 0px;" align="left" width="200px">
                        <?php
                        require('../info/phpsqlajax_dbinfo.php');
                        // Opens a connection to a MySQL server
                        $connection=mysqli_connect($hostname, $username, $password, $database);
                        // Check connection
                        if (mysqli_connect_errno())
                        {
                          //echo "Failed to connect to MySQL: " . mysqli_connect_error();
                          exit;
                        }
                        echo '<tr>';
                        echo '<td><label for="responsibility_code">Responsibility Code</label></td>';
                        echo '</tr>';
                        $query ="select * from misuserresponsibility m,misresponsibility r,mismoduleresponsibility b where m.misactive=1 and r.misactive=1 and b.active=1 and m.misresponsibilityid=r.misresponsibilityid and r.misresponsibilityid=b.misresponsibilityid and misuserid=".$_SESSION["usersid"]." and b.mismoduleid=".$mismoduleid_de." and (misfactoryid=0 or misfactoryid=".$customerid.') order by r.misresponsibilityname';
                        //echo $query;
                        echo '<tr>';
                        echo '<td>';
                        //$defaultresponsibilityid = getdefaultresponsibilityid($connection);
                        $defaultresponsibilityid = $_SESSION['defresponsibilityid'];
                        if ($defaultresponsibilityid!=0 and !isset($_SESSION['responsibilitycode']))
                        {
                            $responsibilityid_en = fnEncrypt($defaultresponsibilityid);
                            header('location: ../sqlproc/selectedresponsibility.php?responsibility_code='.$responsibilityid_en);
                            exit;
                        }
                        echo '<select name="responsibility_code" style="height:35px;font-size:14px;">';
                        $result1=mysqli_query($connection, $query);
                        while ($row1 = mysqli_fetch_assoc($result1))
                        {
                            if ($row1['misresponsibilityid'] == $_SESSION['responsibilitycode'])
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

                        echo '<tr>';
                        echo '<td>';
                        echo 'Predefined Data Language-English?';
                        echo '<input type="checkbox" name="lng" id="lng" value="Yes"/>';
                        echo '</tr>';
                        ?>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td height="30px"><input type="submit" value="Submit" />
                        </tr>
                    </table>
                </form>
            </section>
        </article>
        <footer>
        </footer>
    </body>
</html>