<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/swapproutine.php");
	include("../api_mysql/misresponsibility_db_mysql.php");

	// Opens a connection to a MySQL server
	$connection = db_connection();
	$misresponsibility1 = new misresponsibility($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$misresponsibility1->misresponsibilityname = $_POST["misresponsibilityname"];
			$ret = $misresponsibility1->insert();
			$flag_de = fnEncrypt('Display');
			if ($ret==1)
			{
				$connection->commit();
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Misresponsibility is added successfully</span></br>';
				echo '<a href="../data/misresponsibility.php?misresponsibilityid='.fnEncrypt($misresponsibility1->misresponsibilityid).'&flag='.$flag_en.'">Add/Display Misresponsibility</a></br>';
			}
			else
			{
				$connection->rollback();
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$misresponsibility1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$misresponsibility1->misresponsibilityid = $_POST["misresponsibilityid"];
			$misresponsibility1->misresponsibilityname = $_POST["misresponsibilityname"];
			$ret = $misresponsibility1->update();
			$flag_de = fnEncrypt('Display');
			if ($ret==1)
			{
				$connection->commit();
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Misresponsibility is Updated successfully</span></br>';	
				echo '<a href="../data/misresponsibility.php?misresponsibilityid='.fnEncrypt($misresponsibility1->misresponsibilityid).'&flag='.$flag_en.'">Add/Display Misresponsibility</a></br>';
			}
			else
			{
				$connection->rollback();
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$misresponsibility1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$misresponsibility1->misresponsibilityid = $_POST["misresponsibilityid"];
			$misresponsibility1->misresponsibilityname = $_POST["misresponsibilityname"];
			$result1 = $misresponsibility1->display();
			$flag_de = fnEncrypt('Display');
			if ($misresponsibility1->Get_invalidid==0)
			{
				$i=1;
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">List of Responsilities</span></br>';	
				while ($row1 = mysqli_fetch_assoc($result1))
				{
					echo '<a href="../data/misresponsibility.php?misresponsibilityid='.fnEncrypt($row1['misresponsibilityid']).'&flag='.fnEncrypt('Display').'">'.$i++.') '.$row1['misresponsibilityname'].'</br>';
				}
				echo '<a href="../data/misresponsibility.php?misresponsibilityid='.fnEncrypt($misresponsibility1->misresponsibilityid).'&flag='.$flag_en.'">Add/Display Misresponsibility Detail</a></br>';	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$misresponsibility1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$misresponsibility1->misresponsibilityid = $_POST["misresponsibilityid"];
			$misresponsibility1->misresponsibilityname = $_POST["misresponsibilityname"];
			$ret = $misresponsibility1->delete();
			$flag_de = fnEncrypt('Display');
			if ($ret == 1)
			{
				$connection->commit();
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Misresponsibility is Deleted Successfully</span></br>';
				echo '<a href="../data/misresponsibility.php?misresponsibilityid='.fnEncrypt($misresponsibility1->misresponsibilityid).'">Add/Display Misresponsibility Detail</a></br>';
			}
			else
			{
				$connection->rollback();
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$misresponsibility1->Get_invalidmessagetext().'</span></br>';
				echo '<a href="../data/misresponsibility.php?misresponsibilityid='.fnEncrypt($misresponsibility1->misresponsibilityid).'&flag='.$flag_en.'">Add/Display Misresponsibility Detail</a></br>';
			}
			break;
		case 'Reset':
			$misresponsibility1->misresponsibilityid = 0;
			$flag_de = fnEncrypt('Display');
			echo '<a href="../data/misresponsibility.php?misresponsibilityid='.fnEncrypt($misresponsibility1->misresponsibilityid).'">Add/Display Misresponsibility</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>