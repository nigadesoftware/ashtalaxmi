<?php
    function getdefaultfactorycode(&$connection)
    {
        $query = "select factorycode from userdefault d where d.active=1 and d.misuserid=".$_SESSION['usersid']." and active=1";
        //echo $query;
        $result=mysqli_query($connection, $query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            $factorycode = $row['factorycode'];
            return $factorycode;
        }
        else
        {
            return 0;
        }
    }

    function getdefaultmoduleid(&$connection)
    {
        $query = "select mismoduleid from userdefault d where d.active=1 and d.misuserid=".$_SESSION['usersid']." and active=1";
        //echo $query;
        $result=mysqli_query($connection, $query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            $moduleid = $row['mismoduleid'];
            return $moduleid;
        }
        else
        {
            return 0;
        }
    }

    function getdefaultresponsibilityid(&$connection)
    {
        $query = "select misresponsibilityid from userdefault d where d.active=1 and d.misuserid=".$_SESSION['usersid']." and active=1";
        //echo $query;
        $result=mysqli_query($connection, $query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            $responsibilityid = $row['misresponsibilityid'];
            return $responsibilityid;
        }
        else
        {
            return 0;
        }
    }

    function getdefaultentityid(&$connection)
    {
        $query = "select d.entityid from userdefault d where d.active=1 and d.misuserid=".$_SESSION['usersid']." and active=1";
        //echo $query;
        $result=mysqli_query($connection, $query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            $entityid = $row['entityid'];
            return $entityid;
        }
        else
        {
            return 0;
        }
    }

    function getdefaultsubentityid(&$connection)
    {
        $query = "select d.subentityid from userdefault d where d.active=1 and d.misuserid=".$_SESSION['usersid']." and active=1";
        //echo $query;
        $result=mysqli_query($connection, $query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            $subentityid = $row['subentityid'];
            return $subentityid;
        }
        else
        {
            return 0;
        }
    }

    function getdefaultfinalreportperiodid(&$connection)
    {
        $query = "select d.finalreportperiodid from userdefault d where d.active=1 and d.misuserid=".$_SESSION['usersid']." and active=1";
        //echo $query;
        $result=mysqli_query($connection, $query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            $finalreportperiodid = $row['finalreportperiodid'];
            return $finalreportperiodid;
        }
        else
        {
            return 0;
        }
    }
?>