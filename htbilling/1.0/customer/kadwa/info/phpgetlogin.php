<?php
    date_default_timezone_set("UTC");
    //error_reporting(E_ERROR);
    //error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
    error_reporting( E_ALL & ~E_NOTICE & ~E_WARNING );
    session_start();
    $token = md5(uniqid(rand(), TRUE));
    $_SESSION['token'] = $token;
    $_SESSION['token_time'] = time();
    require('../info/phpsqlajax_dbinfo.php');
    // Opens a connection to a MySQL server
    $connection=mysqli_connect($hostname, $username, $password, $database);
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Communication Error";
        exit;
    }
    $connection ->autocommit(FALSE);
    if (isset($_SESSION['ip_address']) && ($_SESSION['ip_address'] != $_SERVER['REMOTE_ADDR']))
    {
        $query = "update misuserlogininformation set sessionenddatetime=current_timestamp,loggedoutflag=3 where sessionenddatetime is null and miscustomerid=".$_SESSION['factorycode']." and misuserid=".$_SESSION['usersid']." and sessionid='".$_SESSION['cursession']."'";
        //echo $query;
        if (mysqli_query($connection, $query)) 
        {
            $connection -> commit();
            // last request was more than 60 minutes ago
            session_unset();     // unset $_SESSION variable for the run-time 
            session_destroy();   // destroy session data in storage
            $flag=3;
        }
    }
	elseif (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 180000)) {
        $query = "update misuserlogininformation set sessionenddatetime=current_timestamp,loggedoutflag=2 where sessionenddatetime is null and miscustomerid=".$_SESSION['factorycode']." and misuserid=".$_SESSION['usersid']." and sessionid='".$_SESSION['cursession']."'";
        //echo $query;
        if (mysqli_query($connection, $query)) 
        {
            $connection -> commit();
            // last request was more than 60 minutes ago
            session_unset();     // unset $_SESSION variable for the run-time 
            session_destroy();   // destroy session data in storage
            $flag=2;
        }
    }
    else
    {
        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
    }
    if (isset($_SESSION['usersname']))
    {
        //header("Cache-Control: no-cache, no-store, must-revalidate");
        //header("Pragma: no-cache");
        //header("Expires: 0");
        echo '<header class="w3-container">';
        echo '<div><img src="../img/eye1.jpg" width="150px" height="95px"></div></br>';
        echo '<div><img src="../img/finance.png" width="120px" height="20px"></div>';
        echo '</header>';
        //echo '<p style="font-family:verdana;font-size:14px">';
        //echo '<table style="border=0px solid black; border-radius:10px; padding:0px; padding-left:6px;margin: 0px; font-family:verdana;font-size:14px" align="left" width=350px>';
        //echo '<col style="width:150px">';
        //echo '<col style="width:150px">';
        //echo '<col style="width:50px">';
        //echo '<p style="font-family:verdana;font-size:14px">';
        //echo $_SESSION['factoryname'].'</br>';
        if (isset($_SESSION['entityname']))
        {
            if ($_SESSION['entityname']!='')
            {
                //echo 'Entity........... '.$_SESSION['entityname'].'</br>';
                echo '<tr style="font-family:verdana;font-size:12px">';
                //echo '<td><label></br>Entity : </label></td>';
                echo '<td><label>'.$_SESSION['entityname'].'</br></label></td>';
                echo '</tr>';
            }
        }
        //echo 'Login User..... '.$_SESSION['usersname'].'</br>';
        echo '<tr style="font-size:14px">';
                echo '<td><label></br>Login User : </label></td>';
                echo '<td><label>'.$_SESSION['usersname'].'</br></label></td>';
                echo '</tr>';
        if (isset($_SESSION['responsibilityname']))
        {
            if ($_SESSION['responsibilityname']!="0")
            {
                //echo 'Responsibility '.$_SESSION['responsibilityname'].'</br>';
                echo '<tr style="font-size:14px">';
                echo '<td><label>Responsibility : </label></td>';
                echo '<td><label>'.$_SESSION['responsibilityname'].'</br></label></td>';
                echo '</tr>';
            }
        }
        if (isset($_SESSION['financialyear']))
        {
            if ($_SESSION['financialyear']!='0')
            {
                //echo 'Financial Year '.$_SESSION['financialyear'].'</br>';
                echo '<tr style="font-size:14px">';
                echo '<td><label>Financial Year : </label></td>';
                echo '<td><label>'.$_SESSION['financialyear'].'</br></label></td>';
                echo '</tr>';
            }
        }

        if (isset($_SESSION['fromdate']) and isset($_SESSION['todate']))
        {
                echo '<tr style="font-size:14px">';
                echo '<td><label>Report Period : </label></td>';
                echo '<td><label>'.date('d/m/Y',strtotime($_SESSION['fromdate'])).'-'.date('d/m/Y',strtotime($_SESSION['todate'])).'</br></label></td>';
                echo '</tr>';
        }
        if (isset($_SESSION['currentworkingday']))
        {
            echo '<tr style="font-size:14px">';
            echo '<td><label>Current Working Date : </label></td>';
            echo '<td><label>'.$_SESSION["currentworkingday"].'</br></label></td>';
            echo '</tr>';
        }
        
        //echo '</table>';
        //echo '</p>';
    }
    else
    {
        echo "Communication Error";
        exit;
    }
?>