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
    $mismoduleid_de = fnDecrypt($_GET['mismoduleid']);
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
        <title>Mis Mismodule</title>
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
                    /*$personnamedetailtypeid_en = fnEncrypt($personnamedetailtypeid_de);
                    $personnamedetailid_en = fnEncrypt($personnamedetailid_de);
                    echo '<li><a style="color:#f48;text-align:left;" href="../data/personnamedetail_find.php?personnamedetailtypeid='.$personnamedetailtypeid_en.'">personnamedetail Find</a><br/>';*/
                    $mismoduleid_en = fnEncrypt($mismoduleid_de);
                    echo '<li><a style="color:#f48;text-align:left;" href="../data/mismoduleresponsibility.php?mismoduleid='.$mismoduleid_en.'&flag='.fnEncrypt('Add').'">Add Misresponsibility</a><br/>';
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
                $query = "select n.* from mismodule n where n.active=1 and n.mismoduleid=".$mismoduleid_de;
                $result = mysqli_query($connection, $query);
                //echo $query;
                if ($row = @mysqli_fetch_assoc($result)) 
                {
                    echo '<section>';
                    if ($flag_de == 'Display')
                    {
                        echo '<form method="post" action="../api_action/mismodule_action.php">';
                    }
                    echo '<table border="0" >';
                    echo '<tr>';
                    echo '<td><label for="mismoduleid">Mismodule Id</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input readonly="readonly" type="text" style="font-size:12pt;height:30px" name="mismoduleid" id="mismoduleid" value="'.$row['mismoduleid'].'"></td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td><label for="mismodulename_eng">Mismodule Name</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input autofocus type="text" style="font-size:12pt;height:30px" name="mismodulename_eng" id="mismodulename_eng" value="'.$row['mismodulename_eng'].'"></td>';
                    echo '<td><label for="mismodulename_eng">*</label></td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td><label for="misfolder">Misfolder</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="modulefolder" id="modulefolder" value="'.$row['modulefolder'].'"></td>';
                    echo '<td><label for="modulefolder">*</label></td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td></td>';
                    echo '</tr>';

                    if ($flag_de=="Display" and $_SESSION["responsibilitycode"] == 621478512368915)
                    {
                        echo '<tr>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="mismoduleid" id="mismoduleid" value="'.$row['mismoduleid'].'" readonly="readonly"></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><input type="submit" name="btn" value="Change" style="width:100px"</button>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><input type="submit" name="btn" value="Delete" style="width:100px"</button>';
                        echo '</tr>';
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
                    $query1 = "select n.*,m.misresponsibilityname from mismoduleresponsibility n, misresponsibility m where n.active=1 and m.misactive=1 and n.misresponsibilityid=m.misresponsibilityid and n.mismoduleid=".$row['mismoduleid']." order by m.misresponsibilityname";
                    $result1 = mysqli_query($connection, $query1);
                    //echo $query;
                    $mismoduleid_en = fnEncrypt($mismoduleid_de);
                    $misresponsibilityid_en = fnEncrypt($misresponsibilityid_de);
                    echo '<tr>';
                    echo '<td><label for="mismodulename_eng">Misresponsibility List</label></td>';
                    echo '</tr>';
                    $i = 1;
                    while ($row1 = @mysqli_fetch_assoc($result1)) 
                    {
                        echo '<tr>';
                        echo '<td><a href="../data/mismoduleresponsibility.php?mismoduleresponsibilityid='.fnEncrypt($row1['mismoduleresponsibilityid']).'&mismoduleid='.fnEncrypt($row1['mismoduleid']).'&misresponsibilityid='.fnEncrypt($row1['misresponsibilityid']).'&flag='.fnEncrypt('Display').'">'.$i++.') '.$row1['misresponsibilityname'].'</a></br></td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                    echo '</form>';
                    echo '</section>';
                }
                else
                {
                    echo '<section>';
                    echo '<form method="post" action="../api_action/mismodule_action.php">';
                    echo '<table border="0" >';
                    
                    echo '<tr>';
                    echo '<td><label for="mismodulename">Mismodule Name</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input autofocus type="text" style="font-size:12pt;height:30px" name="mismodulename" id="mismodulename" ></td>';
                    echo '<td><label for="mismodulename">*</label></td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td><label for="modulefolder">Modulefolder</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="modulefolder" id="modulefolder"></td>';
                    echo '<td><label for="modulefolder">*</label></td>';
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