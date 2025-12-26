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
	/*if (strlen($aadharnumber)!=12)
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Invalid aadhar number</span>';
		exit;
	}*/
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
	$row1 = mysqli_fetch_assoc($result1);
	if ($row1["cnt"] > 0)
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">User already exists</span>';
		exit;
	}
	$connection -> autocommit(FALSE);
	$result = mysqli_query($connection, "select ifnull(max(misuserid),0)+1325486 as misuserid from misuser");
	$row = mysqli_fetch_assoc($result);
	$userid = $row["misuserid"];
	$query = "insert into misuser(misuserid,misusername,aadharnumber,misusermobile,misuseraddress,misemailaddress,miscustomerid,suspended,misuseractive) values ($userid,'$usersname',$aadharnumber,$mobile,'$usersaddress','$email',".$_SESSION['factorycode'].",$suspended,1)";
	if (mysqli_query($connection, $query))
	{
    	$retcode = resetpassword($connection,$userid);
    	if ($retcode != '')
			{
		    	$connection -> commit();

		    	require("../PHPMailer-master/PHPMailerAutoload.php");
				$mail = new PHPMailer;									// require 'PHPMailerAutoload.php';
				//$mail->SMTPDebug = 3;                               	// Enable verbose debug output
				$mail->isSMTP();                                      	// Set mailer to use SMTP
				$mail->Host = 'mail.swapp.co.in';  					  	// Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               	// Enable SMTP authentication
				$mail->Username = 'autoresponse@swapp.co.in';       	// SMTP username
				$mail->Password = 'X^uEGqZ1yM0{';                           	  	// SMTP password
				//$mail->SMTPSecure = 'tls';                          	// Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587;                                    	// TCP port to connect to
				$mail->setFrom('autoresponse@swapp.co.in', 'Swapp');
				$mail->addAddress($email, 'User');// Add a recipient
				//$mail->addAddress('ellen@example.com');             	// Name is optional
				//$mail->addReplyTo('info@example.com', 'Information');
				//$mail->addCC('cc@example.com');
				//$mail->addBCC('bcc@example.com');
				//$mail->addAttachment('/var/tmp/file.tar.gz');       	// Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');  	// Optional name
				$mail->isHTML(true);                                  	// Set email format to HTML
				$mail->Subject = 'OTP Password';
				$mail->Body    = 'Dear User <b>'.$usersname.'</b>,<br>'.'Please enter OTP Password: <b>'.$retcode.'</b><br><br>This email is auto generated.';
				if(!$mail->send()) 
				{
				    //echo 'Mailer Error: '.$mail->ErrorInfo;
				    //$connection -> rollback();
					echo 'Password is Reset <br/>';
					echo "New Password Generated :".$retcode." <br/>";
					echo '<span style="background-color:#0a0;color:#ff8;text-align:left;">User password is regenerated successfully. <br/> OTP '.$retcode.' is generated</span>';
					echo '</br>Message could not be sent.';
					echo '<li><a style="color:#f48" class="navbar" href="../data/user_list.php">User List</a><br/>';
				    exit;
				} 
				else 
				{
					echo '<span style="background-color:#0a0;color:#ff8;text-align:left;">User password is regenerated successfully.</span>';
					echo '</br>Message has been sent to users email';
					echo '<li><a style="color:#f48" class="navbar" href="../data/user_list.php">User List</a><br/>';
				    //session_start();
					//$_SESSION['otppassword'] = $str;
				}
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