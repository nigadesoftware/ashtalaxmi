<?php
    date_default_timezone_set("UTC");
    //error_reporting(E_ERROR);
    error_reporting( E_ALL & ~E_NOTICE & ~E_WARNING );
    session_start();
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 180000)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
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
        echo '<div><img src="../img/logo.jpg" width="70px" height="70px"></div></br>';
        echo '<div><img src="../img/canedev.png" width="170px" height="30px"></div>';
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
        echo '</header>';
    }
    else
    {
        echo "Communication Error";
        exit;
    }
?>
