<?php
	require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
	require("../info/swapproutine.php");
	// Grab User submitted information
	$usersid = $_POST["userid"];
	// Opens a connection to a MySQL server
	$connection=mysqli_connect($hostname, $username, $password, $database);
	// Check connection
	if (mysqli_connect_errno())
	{
	  echo "Communication Error";
	  exit;
	}
	$result=mysqli_query($connection, "select m.misuserid,m.misemailaddress,m.misusername from misuser m where m.misuseractive=1 and m.misuserid=".$usersid);
	$row = mysqli_fetch_assoc($result);
	if (isset($row['misuserid']))
	{
		$connection ->autocommit(FALSE);
		$retcode = resetpassword($connection,$usersid);
    	if ($retcode != '')
			{
				$connection ->commit();	
				
		    	require("../PHPMailer-master/PHPMailerAutoload.php");
				$email = $row['misemailaddress'];
				
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
				$mail->Body    = 'Dear User <b>'.$row['misusername'].'</b>,<br>'.'Please enter OTP Password: <b>'.$retcode.'</b><br><br>This email is auto generated.';
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

			}
		else
			{
				echo "Communication Error";
				$connection ->rollback();
			}
	}
	else
	{
		echo "Communication Error";
	}
?>