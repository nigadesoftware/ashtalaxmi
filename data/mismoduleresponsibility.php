<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    include("../info/ncryptdcrypt.php");
    require("../info/swapproutine.php");
    //System Admin
    if (isaccessible(621478512368915)==0)
    {
        echo 'Communication Error';
        exit;
    }
    if (isset($_GET['mismoduleresponsibilityid']))
    {
        $mismoduleresponsibilityid_de = fnDecrypt($_GET['mismoduleresponsibilityid']);
    }
    else
    {
        $mismoduleresponsibilityid_de = 0;
    }
    if (isset($_GET['mismoduleid']))
    {
        $mismoduleid_de = fnDecrypt($_GET['mismoduleid']);
    }
    else
    {
        $mismoduleid_de = 0;
    }
    if (isset($_GET['misresponsibilityid']))
    {
        $misresponsibilityid_de = fnDecrypt($_GET['misresponsibilityid']);
    }
    else
    {
        $misresponsibilityid_de = 0;
    }
    if (isset($_GET['flag']))
    {
        $flag_de = fnDecrypt($_GET['flag']);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/w3.css">
        <title>Module Responsibility</title>
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
                background-color: #fff
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
                background-color: #fef
            }
            input[readonly],select:disabled
            {
                border-radius: 10px;
                outline: none;
                background-color:lightblue;
                color:black;
            }
            label
            {
                color: #000;
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
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    </head>
    <body>
        <nav "w3-container">
            <ul class="navbar">
                <li><a class="navbar" href="../mis/usermenu.php">User Menu</a><br/>
                <?php
                    $mismoduleid_en = fnEncrypt($mismoduleid_de);
                    echo '<li><a style="color:#f48;text-align:left;" href="../data/mismodule.php?mismoduleid='.$mismoduleid_en.'&flag='.fnEncrypt('Display').'">Mismodule</a><br/>';
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/module.png" height="41px"></div>
            <?php
                // Opens a connection to a MySQL server
                $connection=mysqli_connect($hostname, $username, $password, $database);
                // Check connection
                if (mysqli_connect_errno())
                {
                  echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication error</span>';
                  exit;
                }
                mysqli_query($connection,'SET NAMES UTF8');
                $query = "select n.* from swappcoi_db.mismoduleresponsibility n where n.active=1 and n.mismoduleresponsibilityid=".$mismoduleresponsibilityid_de;
                $result = mysqli_query($connection, $query);
                //echo $query;
                if ($row = @mysqli_fetch_assoc($result)) 
                {
                    echo '<section>';
                    if ($flag_de == 'Display')
                    {
                        echo '<form method="post" action="../api_action/mismoduleresponsibility_action.php">';
                    }
                    echo '<table border="0" >';
                    echo '<tr>';
                    echo '<td><label for="mismodule">Mismodule</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td>';
                    echo '<select name="mismoduleid" style="height:35px;font-size:14px;">';
                    $result1=mysqli_query($connection, "select * from mismodule m where m.active=1 order by mismodulename_eng");
                    while ($row1 = mysqli_fetch_assoc($result1)) 
                    {
                        if ( $row1['mismoduleid'] == $row['mismoduleid'])
                        {
                            echo '<option value="'.$row1['mismoduleid'].'" Selected>'.$row1['mismodulename_eng'].'</option>';   
                        }
                        else
                        {
                            echo '<option value="'.$row1['mismoduleid'].'">'.$row1['mismodulename_eng'].'</option>';
                        }
                    }
                    echo '</select>';
                    echo '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td><label for="misresponsibility">Misresponsibility</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td>';
                    echo '<select name="misresponsibilityid" style="height:35px;font-size:14px;">';
                    $query2 = "select * from misresponsibility m where m.misactive=1 order by misresponsibilityname";
                    $result2=mysqli_query($connection, $query2);
                    while ($row2 = mysqli_fetch_assoc($result2)) 
                    {
                        if ( $row2['misresponsibilityid'] == $row['misresponsibilityid'])
                        {
                            echo '<option value="'.$row2['misresponsibilityid'].'" Selected>'.$row2['misresponsibilityname'].'</option>';   
                        }
                        else
                        {
                            echo '<option value="'.$row2['misresponsibilityid'].'">'.$row2['misresponsibilityname'].'</option>';                                   
                        }
                    }
                    echo '</select>';
                    echo '</td>';
                    echo '</tr>';

                    if ($flag_de=="Display" and $_SESSION["responsibilitycode"] == 621478512368915)
                    {
                        echo '<tr>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="mismoduleresponsibilityid" id="mismoduleresponsibilityid" value="'.$row['mismoduleresponsibilityid'].'" readonly="readonly"></td>';
                        echo '</tr>';
                        /*echo '<tr>';
                        echo '<td><input type="submit" name="btn" value="Change" style="width:100px"</button>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><input type="submit" name="btn" value="Delete" style="width:100px"</button>';
                        echo '</tr>';*/
                        echo '<tr>';
                        echo '<td><input type="submit" name="btn" value="Reset" style="width:100px"</button>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td><input type="submit" name="btn" value="Reset" style="width:100px"</button>';
                        echo '</tr>';
                    }
                    echo '</table>';
                    echo '</form>';
                    echo '</section>';
                }
                else
                {
                    echo '<section>';
                    echo '<form method="post" action="../api_action/mismoduleresponsibility_action.php">';
                    echo '<table border="0" >';
                    
                    echo '<tr>';
                    echo '<td>';
                    echo '<select name="mismoduleid" style="height:35px;font-size:14px;">';
                    $result1=mysqli_query($connection, "select * from mismodule m where m.active=1 order by mismodulename_eng");
                    while ($row1 = mysqli_fetch_assoc($result1)) 
                    {
                        if ($row1['mismoduleid'] == $mismoduleid_de)
                        {
                            echo '<option value="'.$row1['mismoduleid'].'" Selected>'.$row1['mismodulename_eng'].'</option>';
                        }
                        else
                        {
                            echo '<option value="'.$row1['mismoduleid'].'">'.$row1['mismodulename_eng'].'</option>';
                        }
                    }
                    echo '</select>';
                    echo '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>';
                    echo '<select name="misresponsibilityid" style="height:35px;font-size:14px;">';
                    $result1=mysqli_query($connection, "select * from misresponsibility m where m.misactive=1 order by misresponsibilityname");
                    while ($row1 = mysqli_fetch_assoc($result1)) 
                    {
                        echo '<option value="'.$row1['misresponsibilityid'].'">'.$row1['misresponsibilityname'].'</option>';                                   
                    }
                    echo '</select>';
                    echo '</td>';
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