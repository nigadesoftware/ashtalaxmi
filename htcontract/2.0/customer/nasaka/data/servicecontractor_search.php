<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/rawmaterialroutine.php");
    require("../info/phpgetloginview.php");
    //session_start();
    //require("../info/phpgetlogin.php");
    //connect with the database
    //$connection = new mysqli($hostname_rawmaterial, $username_rawmaterial, $password_rawmaterial, $database_rawmaterial);
    $connection = rawmaterial_connection();
    //mysqli_query($connection,'SET NAMES UTF8');
    //get search term
    $searchTerm = $_GET['term'];
    $iscultivator = $_GET['iscultivator'];
    if ($iscultivator == 0)
    {
        $servicecontractorcategoryid = 452168578;
    }
    elseif ($iscultivator == 1)
    {
        $servicecontractorcategoryid = 324152658;
    }
    elseif ($iscultivator == 2)
    {
        $servicecontractorcategoryid = 845689712;
    }
    if ($searchTerm == '**')
    {
        if ($iscultivator == 1)
        {
            $query = "select c.cultivatorid,c.name_eng,c.name_unicode from cultivator c"; 
        }
        else
        {
            $query = "select c.servicecontractorid,c.name_eng,c.name_unicode,c.refcode from servicecontractor c"; 
        }
    }
    else
    {
        if ($iscultivator == 1)
        {
            $query = "select c.cultivatorid,c.name_eng,c.name_unicode from cultivator c where upper(c.name_eng) like upper('".$searchTerm."%') or c.name_unicode like '".$searchTerm."%' or to_char(cultivatorid)='".$searchTerm."'"; 
        }
        else
        {
            $query = "select c.servicecontractorid,c.name_eng,c.name_unicode,c.refcode from servicecontractor c where upper(c.name_eng) like upper('%".$searchTerm."%') or c.name_unicode like '".$searchTerm."%' or upper(refcode)=upper('".$searchTerm."')"; 
        }
    }
    $result = oci_parse($connection, $query); $r = oci_execute($result);
    $r = oci_execute($result);
                
    while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
	{
        if ($iscultivator == 0)
        {
            $id = $row['SERVICECONTRACTORID'];
        }
        else
        {
            $id = $row['CULTIVATORID'];
        }
        
        if ($_SESSION['lng'] == "English")
        {
            $name = $row['NAME_ENG'].' ('.$row['REFCODE'].')';
        }
        else
        {
            $name = $row['NAME_UNICODE'].' ('.$row['REFCODE'].')';
        }
        $data[] = array( 
            'id' => $id
            ,'label' => $name
            ,'value' => $name
            );
    }
    if (sizeof($data)==0)
    {
       $data[] = array( 
            'id' => 0
            ,'label' => '[No]'
            ,'value' => '[No]'
            );
    }
    //return json data
    echo json_encode($data);
?>