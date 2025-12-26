<?php
	$str="../handler/plotmeasuredRestHandler.php";
	require_once($str);
	$view = "";
	$measurementuserid = "";
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
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
	/*
	controls the RESTful services
	URL mapping
	*/
	// to handle REST Url /mobile/list/
	switch ($view)
	{
		case 'upload':
		{
			$plotmeasuredRestHandler1 = new plotmeasuredRestHandler();
			$statusCode=$plotmeasuredRestHandler1->postmeasuredplot($view,$measurementuserid,$input);
			header("Access-Control-Allow-Origin: *");
			header("Content-Type: application/json; charset=UTF-8");
			header("Access-Control-Allow-Methods: POST");
			header("Access-Control-Max-Age: 3600");
			header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
			//$requestContentType = $_SERVER['HTTP_ACCEPT'];
			//$this ->setHttpHeaders($requestContentType, $statusCode);
			//$statusCode=201;
			http_response_code($statusCode);
			if ($statusCode==201)
			{
				//echo json_encode(array("message" => "Item was created."));		
				$result ='[{"success":"1","message":"Item Created"}]';
			}
			else
			{
				//echo json_encode((array("message" => "Item was not created."));	
				$result ='[{"success":"0",{"message":"Item not Created"}]';
			}
			echo $result;
			exit;
		}
	}
?>

