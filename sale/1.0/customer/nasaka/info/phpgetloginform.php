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
        echo '<div><img src="../img/nigadesoftwaretechnologies_logo_3.png" width="35px" height="35px"></div>';
        echo '<div><img src="../img/nigadesoftwaretechnologies_logo.png" width="150px" height="33px"></div>';
        //echo '<div><img src="../img/erp.png" width="49px" height="26px"></div>';
        echo '<div><img src="../img/sugarsale.png" width="120px" height="31px"></div>';
        echo '<div><img src="../img/kadwa.jpg" width="35px" height="35px"></div>';
        echo '<div><label style="font-family:siddhanta;font-size:25px">'.$_SESSION['factoryname'].'</label></div>';
        echo '</header>';

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
