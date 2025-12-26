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
    if ($searchTerm == '**')
    {
        $query = "select c.employeeid,c.name_eng,c.name_unicode from empolyee c"; 
    }
    else
    {
        $query = "select c.employeeid,c.name_eng,c.name_unicode from employee c where upper(c.name_eng) like upper('".$searchTerm."%') or c.name_unicode like '".$searchTerm."%' or to_char(employeeid)='".$searchTerm."'"; 
    }
    $result = oci_parse($connection, $query); $r = oci_execute($result);
    $r = oci_execute($result);
                
    while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
	{
        $id = $row['EMPLOYEEID'];
        
        if ($_SESSION['lng'] == "English")
        {
            if (isset($row['NAME_ENG']) and $row['NAME_ENG']!='-')
            {
                $name = $row['NAME_ENG'];
            }
            else
            {
                $name = $row['NAME_UNICODE'];
            }
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
    if (oci_num_rows($result)==0)
    {
        $data[] = array( 
            'id' => 0
            ,'label' => '[No]'
            ,'value' => '[No]'
            );
    }
    else
    {
        if (sizeof($data)==0)
        {
        $data[] = array( 
                'id' => 0
                ,'label' => '[No]'
                ,'value' => '[No]'
                );
        }
    }
    //return json data
    echo json_encode($data);
?>