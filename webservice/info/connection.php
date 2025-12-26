<?php
    $username="root";
    $password="sandee1976";
    $database="nasaka_db";
    $hostname = "localhost";
    function oracle_connection()
    {
        $host = "192.168.1.36";
        $dbname = "orclkadwa";
        //$dbname = "orcl";
        //$host = "localhost";
        $pwd="swapp123";
        $db= "(DESCRIPTION =
              (ADDRESS = (PROTOCOL = TCP)(HOST = ".$host.")(PORT = 1521))
              (CONNECT_DATA =
              (SERVER = DEDICATED)
                (SERVICE_NAME = ".$dbname.")
              )
           )";
        $conn = oci_connect("nst_nasaka_webpub", $pwd, $db,"AL32UTF8");
        if (!$conn)
        {
            $m = oci_error();
            echo $m['message'], "\n";
            exit;
        }
        else
        {
            //print "Connected to Oracle!";
        }
        return $conn;
    }
    function production_connection()
    {
        $host = "192.168.1.36";
        $dbname = "orclkadwa";
        //$dbname = "orcl";
        //$host = "localhost";
        $pwd="swapp123";
        $db= "(DESCRIPTION =
              (ADDRESS = (PROTOCOL = TCP)(HOST = ".$host.")(PORT = 1521))
              (CONNECT_DATA =
              (SERVER = DEDICATED)
                (SERVICE_NAME = ".$dbname.")
              )
           )";
        $conn = oci_connect("nst_nasaka_webpub", $pwd, $db,"AL32UTF8");
        if (!$conn)
        {
            $m = oci_error();
            echo $m['message'], "\n";
            exit;
        }
        else
        {
            //print "Connected to Oracle!";
        }
        return $conn;
    }
    function agriculture_connection()
    {
        $host = "NSSK_SERVER";
        $dbname = "orcl";
        //$dbname = "xepdb1";
        //$host = "localhost";
        $pwd="swapp123";
        $db= "(DESCRIPTION =
              (ADDRESS = (PROTOCOL = TCP)(HOST = ".$host.")(PORT = 1521))
              (CONNECT_DATA =
              (SERVER = DEDICATED)
                (SERVICE_NAME = ".$dbname.")
              )
           )";
        $conn = oci_connect("nst_nasaka_agriculture", $pwd, $db,"AL32UTF8");
        if (!$conn)
        {
            $m = oci_error();
            echo $m['message'], "\n";
            exit;
        }
        else
        {
            //print "Connected to Oracle!";
        }
        return $conn;
    }
?>