<?php
	require("../info/phpsqlajax_dbinfo.php");
   	include("../info/phpgetloginview.php");
	require("../info/swapproutine.php");
    //System Admin,Admin
    if (isaccessible(621478512368915)==0 and isaccessible(785236954125917)==0)
    {
        echo 'Communication Error';
        exit;
    }
	$usersname = $_POST["misusername"];
	$usersaddress = $_POST["misuseraddress"];
	$aadharnumber = $_POST["aadharnumber"];
	$mobile = $_POST["misusermobile"];
	$email = $_POST["misemailaddress"];
	$suspended = $_POST["suspended"];
	// Opens a connection to a MySQL server
	$connection=mysqli_connect($hostname, $username, $password, $database);
	// Check connection
	if (mysqli_connect_errno())
	{
	  echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Communication Error</span>';
	  exit;
	}
	if (isset($usersname)==false or isset($aadharnumber)==false or isset($mobile)==false or isset($email)==false)
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Invalid data</span>';
		exit;
	}
	if (strlen($aadharnumber)!=12)
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Invalid aadhar number</span>';
		exit;
	}
	if (strlen($mobile)==10)
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Please include ISD (+91) with mobile number</span>';
		exit;
	}
	if (strlen($mobile)!=13)
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Invalid mobile number</span>';
		exit;
	}
	if (isset($usersaddress)==false or strlen($usersaddress)<=5)
	{
		echo("Invalid address");
  		exit;
	}
	if (empty($email)==False)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) 
		{
	  		echo("Not a valid email address");
	  		exit;
		}
	}
	$result1=mysqli_query($connection, "select count(*) as cnt from misuser where misuseractive=1 and aadharnumber=".$aadharnumber);
	$row1 = oci_fetch_array($result1,OCI_ASSOC);
	if ($row1["cnt"] > 0)
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">User already exists</span>';
		exit;
	}
	$connection -> autocommit(FALSE);
	$result = mysqli_query($connection, "select nvl(max(misuserid),0)+1325486 as misuserid from misuser");
	$row = oci_fetch_array($result,OCI_ASSOC);
	$userid = $row["misuserid"];
	$query = "insert into misuser(misuserid,misusername,aadharnumber,misusermobile,misuseraddress,misemailaddress,miscustomerid,suspended,misuseractive) values ($userid,'$usersname',$aadharnumber,$mobile,'$usersaddress','$email',$customerid,$suspended,1)";
	if (oci_parse($connection, $query))
	{
    	$retcode = resetpassword($connection,$userid);
    	if ($retcode != '')
			{
		    	//require("../info/datalog.php");
		    	//$datalogconnection = datalog_connection();
	    		//$grouptransactionid = getgrouptransactionid($datalogconnection);
		    	//logdata($datalogconnection,$grouptransactionid,$database_finance,$query);
		    	require("../PHPMailer-master/PHPMailerAutoload.php");
				$str = '';
				$str = $str.chr(rand(65,90));
				$str = $str.rand(0,9);
				$str = $str.chr(rand(65,90));
				$str = $str.rand(0,9);
				$str = $str.chr(rand(65,90));
				$str = $str.chr(rand(65,90));
				$str = $str.rand(0,9);
				$str = $str.chr(rand(65,90));
				$str = $str.rand(0,9);
				$str = $str.chr(rand(65,90));
				$mail = new PHPMailer;									// require 'PHPMailerAutoload.php';
				//$mail->SMTPDebug = 3;                               	// Enable verbose debug output
				$mail->isSMTP();                                      	// Set mailer to use SMTP
				$mail->Host = 'mail.swapp.co.in';  					  	// Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               	// Enable SMTP authentication
				$mail->Username = 'sandeep.nigade@swapp.co.in';       	// SMTP username
				$mail->Password = '';                           	  	// SMTP password
				//$mail->SMTPSecure = 'tls';                          	// Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587;                                    	// TCP port to connect to
				$mail->setFrom('sandeep.nigade@swapp.co.in', 'Mailer');
				$mail->addAddress($email, 'User');// Add a recipient
				//$mail->addAddress('ellen@example.com');             	// Name is optional
				//$mail->addReplyTo('info@example.com', 'Information');
				//$mail->addCC('cc@example.com');
				//$mail->addBCC('bcc@example.com');
				//$mail->addAttachment('/var/tmp/file.tar.gz');       	// Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');  	// Optional name
				//$mail->isHTML(true);                                  	// Set email format to HTML
				$mail->Subject = 'OTP Password';
				$mail->Body    = 'Dear User,</br>'.'Please enter OTP '.$str.'</br>This mail is generated.';
				if(!$mail->send()) 
				{
				    echo 'Message could not be sent.';
				    //echo 'Mailer Error: '.$mail->ErrorInfo;
				    $connection -> rollback();
				    exit;
				} 
				else 
				{
				    echo 'Message has been sent to your email';
				    session_start();
					$_SESSION['otppassword'] = $str;
				}

		    	$connection -> commit();
		    	//$datalogconnection -> commit();	
				echo '<span style="background-color:#0a0;color:#ff8;text-align:left;">User '.$usersname.' is created successfully. <br/> OTP '.$retcode.' is generated</span>';
				echo '<li><a style="color:#f48" class="navbar" href="../ip_view/user_list.php">User List</a><br/>';
				exit;
			}
		else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication Error</span>';
	    		$connection -> rollback();
			}
	}
	else
	{
    	echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication Error</span>';
    	$connection -> rollback();
	}
?>