<?php
require("../info/phpsqlajax_dbinfo.php");
require("../info/routine.php");
require("../info/phpgetloginview.php");
//session_start();
//require("../info/phpgetlogin.php");
//connect with the database
    $connection = petrolpump_connection();
    //get search term
    $searchTerm = $_GET['term'];
    if ($searchTerm == '**')
    {
        $query = "select bankbranchid,
        bankid,
        name_unicode,
        name_eng
        from bankbranch";
    }
    else
    {
        $query = "select bankbranchid,
        bankid,
        name_unicode,
        name_eng
        from bankbranch where (upper(name_eng) like upper('%".$searchTerm."%') 
        or name_unicode like '%".$searchTerm."%'
        or to_char(bankbranchid) like '".$searchTerm."')";
    }
    $result = oci_parse($connection, $query); $r = oci_execute($result);
    $r = oci_execute($result);
    
    while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
	{
        $id = $row['BANKBRANCHID'];
        if ($_SESSION['lng'] == "English")
        {
            $name = $row['NAME_ENG'];
        }
        else
        {
            $name = $row['NAME_UNICODE'];
        }
        $data[] = array( 
            'id' => $id
            ,'label' => $name
            ,'value' => $name
            );
    }
    //return json data
    echo json_encode($data);
?>