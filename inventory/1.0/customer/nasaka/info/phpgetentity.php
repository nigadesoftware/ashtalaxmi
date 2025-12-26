<?php
    require("../info/phpgetloginview.php");
    if (isset($_SESSION["finalreportperiodid"]))
    {
    }
    else
    {
        header("location:../mis/usermenu.php");
        exit;
    }
?>