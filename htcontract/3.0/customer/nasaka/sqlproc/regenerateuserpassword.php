<?php
   require("../info/phpgetloginview.php");
   require("../info/phpsqlajax_dbinfo.php");
   require("../info/swapproutine.php");
   include("../info/ncryptdcrypt.php");

   $userid=$_POST['userid'];
   // Opens a connection to a MySQL server
   $connection=mysqli_connect($hostname, $username, $password, $database);
   // Check connection
   if (mysqli_connect_errno())
   {
   		echo "Communication Error";
   }
   $connection ->autocommit(FALSE);
   $retcode = resetpassword($connection,$userid);

   if ($retcode !='')
   {
   		$connection->commit();
   		echo 'Password is reset. OTP '.$retcode.' is generated'.'</br>';
   		$userid_en = fnEncryptpass($userid);
   		echo '<tr>';
   		echo '<td><img src="../img/U.png" width="16px" height="31px"><a style="color:#000" class="servicebar" href="../data/userresponsibility_list.php?userid='.$userid_en.'">Responsibility List</a>';
   		echo '</tr>';
   }
   else
   {
      $connection->rollback();
      echo 'Password is not reset'.'</br>';
   }
?>