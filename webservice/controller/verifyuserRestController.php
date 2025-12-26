<?php
	$str="../info/connection.php";
	require_once($str);
	require_once("../../info/crypto.php");
	$view = "";
    $measurementuserid = "";
	$measurementuserpwd = "";
	$devid = "";
	if (!isset($_SERVER['PHP_AUTH_USER']))
	{
		/* header('WWW-Authenticate: Basic realm="My Realm"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'Text to send if user hits Cancel button'; */
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		header("Access-Control-Allow-Methods: POST");
		header("Access-Control-Max-Age: 3600");
		header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
		$statusCode=401;
		http_response_code($statusCode);
		$result ='[{"message":"Auauthorised"}]';
		echo $result;
		exit;
	}
	else if ($_SERVER['PHP_AUTH_USER'] == 'makai' and $_SERVER['PHP_AUTH_PW'] == 'Makai@123')
	{
	}
	else
	{
		/* header('WWW-Authenticate: Basic realm="My Realm"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'Text to send if user hits Cancel button'; */
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		header("Access-Control-Allow-Methods: POST");
		header("Access-Control-Max-Age: 3600");
		header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
		$statusCode=401;
		http_response_code($statusCode);
		$result ='[{"message":"Auauthorised"}]';
		echo $result;
		exit;
	}
	if(isset($_GET["view"]))
	{
		$view = $_GET["view"];
	}
	else
	{
		/* header('WWW-Authenticate: Basic realm="My Realm"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'Text to send if user hits Cancel button'; */
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		header("Access-Control-Allow-Methods: POST");
		header("Access-Control-Max-Age: 3600");
		header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
		$statusCode=401;
		http_response_code($statusCode);
		$result ='[{"message":"Auauthorised"}]';
		echo $result;
		exit;
	}
	if(isset($_GET["measurementuserid"]))
	{
		$measurementuserid = $_GET["measurementuserid"];
	}
	else
	{
		$measurementuserid =0;
    }
    if(isset($_GET["measurementuserpwd"]))
	{
		$measurementuserpwd = $_GET["measurementuserpwd"];
	}
	else
	{
		$measurementuserpwd ="0";
	}
	if(isset($_GET["devid"]))
	{
		$devid = $_GET["devid"];
	}
	else
	{
		$devid ="0";
    }
    /*
	controls the RESTful services
	URL mapping
	*/
	// to handle REST Url /mobile/list/
	switch ($view)
	{
		case 'verifyuser':
		{
            $connection=mysqli_connect($hostname, $username, $password, $database);
            // Check connection
            if (mysqli_connect_errno())
            {
                echo "Communication Error";
                  exit;
			}
			$measurementuserid=decrypt($measurementuserid);
			$measurementuserpwd=decrypt($measurementuserpwd);
			//$e=decrypt($d);
			$connection ->autocommit(FALSE);
			$agri_connection = agriculture_connection();
            $query = "SELECT m.misuserid,m.misusername,p.mispassword,m.miscustomerid,p.isotppassword FROM misuser m,misuserpassword p WHERE m.misuserid=p.misuserid and m.misuseractive=1 and p.misactive=1 and m.aadharnumber = ".$measurementuserid;
            $result = mysqli_query($connection,$query);
            if ($row = @mysqli_fetch_assoc($result))
            {
                $dcpass = new crypto;
                $dcrpass = $dcpass->Decrypt($row["mispassword"],1);
                if($measurementuserpwd==$dcrpass)
                {
					$ret=1;
					if ($devid !='0')
					{
						$ret=checkimeinumber($agri_connection,$row['misuserid'],$devid);
					}
					if ($ret==1)
					{
						$verified=$row['misuserid'];    
					}
					else
					{
						$verified=99;    
					}
                }
                else
                {
                    $verified=0;    
                }
            }
            else
            {
                $verified=0;
            }
            header("Access-Control-Allow-Origin: *");
			header("Content-Type: application/json; charset=UTF-8");
			header("Access-Control-Allow-Methods: POST");
			header("Access-Control-Max-Age: 3600");
			header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
			//$requestContentType = $_SERVER['HTTP_ACCEPT'];
			//$this ->setHttpHeaders($requestContentType, $statusCode);
			$statusCode=200;
			http_response_code($statusCode);
            //$data = array();
            //$data[]=$verified;
			//$result = json_encode($data);
			echo $verified*2;
			exit;
		}
	}
	function checkimeinumber(&$connection,$userid,$devid)
	{
		return 1;
		/* $query = "select 1 as ret  
		from nst_nasaka_agriculture.slipboy s
		where userid=".$userid." and deviceid='".$devid."'";
		$result = oci_parse($connection, $query);
		$r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			return $row['RET'];
		}
		else
		{
			$query1 = "select 1 as ret  
			from nst_nasaka_agriculture.slipboy s
			where userid=".$userid." and (imei_1=".$devid." or imei_2=".$devid.")";
			$result1 = oci_parse($connection, $query1);
			$r1 = oci_execute($result1);
			if ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
			{
				return $row1['RET'];
			}
			else
			{
				return 0;
			}
			
		} */
	}
	function encrypt($txt)
    {
        $ret="";
        for ($i=0;$i<strlen($txt);$i++)
        {
            $no=ord(substr($txt,$i,1))+2304;
            $ret=$ret.str_pad($no,4,"0",STR_PAD_LEFT);
        }
        return $ret;
    }
    function decrypt($txt)
    {
        $ret="";
        for ($i=0;$i<strlen($txt);$i=$i+4)
        {
            $no=strval(substr($txt,$i,4))-2304;
            $ret=$ret.chr($no);
        }
        return $ret;
    }
?>

