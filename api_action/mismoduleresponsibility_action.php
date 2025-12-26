<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/swapproutine.php");
	include("../api_mysql/mismoduleresponsibility_db_mysql.php");

	// Opens a connection to a MySQL server
	$connection = db_connection();
	$mismoduleresponsibility1 = new mismoduleresponsibility($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$mismoduleresponsibility1->mismoduleid = $_POST["mismoduleid"];
			$mismoduleresponsibility1->misresponsibilityid = $_POST["misresponsibilityid"];
			$ret = $mismoduleresponsibility1->insert();
			$flag_en = fnEncrypt('Display');
			if ($ret==1)
			{
				$connection->commit();
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">mismoduleresponsibility is added successfully</span></br>';
				echo '<a href="../data/mismoduleresponsibility.php?mismoduleresponsibilityid='.fnEncrypt($mismoduleresponsibility1->mismoduleresponsibilityid).'&flag='.$flag_en.'">Add/Display mismoduleresponsibility</a></br>';
			}
			else
			{
				$connection->rollback();
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$mismoduleresponsibility1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$mismoduleresponsibility1->mismoduleresponsibilityid = $_POST["mismoduleresponsibilityid"];
			$mismoduleresponsibility1->mismoduleid = $_POST["mismoduleid"];
			$mismoduleresponsibility1->misresponsibilityid = $_POST["misresponsibilityid"];
			$ret = $mismoduleresponsibility1->update();
			$flag_en = fnEncrypt('Display');
			if ($ret==1)
			{
				$connection->commit();
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">mismoduleresponsibility is Updated successfully</span></br>';	
				echo '<a href="../data/mismoduleresponsibility.php?mismoduleresponsibilityid='.fnEncrypt($mismoduleresponsibility1->mismoduleresponsibilityid).'&flag='.$flag_en.'">Add/Display mismoduleresponsibility</a></br>';
			}
			else
			{
				$connection->rollback();
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$mismoduleresponsibility1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$mismoduleresponsibility1->mismoduleresponsibilityid = $_POST["mismoduleresponsibilityid"];
			$mismoduleresponsibility1->mismoduleid = $_POST["mismoduleid"];
			$mismoduleresponsibility1->misresponsibilityid = $_POST["misresponsibilityid"];
			$result1 = $mismoduleresponsibility1->display();
			$flag_en = fnEncrypt('Display');
			if ($mismoduleresponsibility1->Get_invalidid==0)
			{
				$i=1;
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">List of Module</span></br>';	
				while ($row1 = mysqli_fetch_assoc($result1))
				{
					echo '<a href="../data/mismoduleresponsibility.php?mismoduleresponsibilityid='.fnEncrypt($row1['mismoduleresponsibilityid']).'&flag='.fnEncrypt('Display').'">'.$i++.') '.$row1['mismoduleresponsibilityname_eng'].'</br>';
				}
				echo '<a href="../data/mismoduleresponsibility.php?mismoduleresponsibilityid='.fnEncrypt($mismoduleresponsibility1->mismoduleresponsibilityid).'&flag='.$flag_en.'">Add/Display mismoduleresponsibility Detail</a></br>';	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$mismoduleresponsibility1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$mismoduleresponsibility1->mismoduleresponsibilityid = $_POST["mismoduleresponsibilityid"];
			$mismoduleresponsibility1->mismoduleid = $_POST["mismoduleid"];
			$mismoduleresponsibility1->misresponsibilityid = $_POST["misresponsibilityid"];
			$ret = $mismoduleresponsibility1->delete();
			$flag_en = fnEncrypt('Display');
			if ($ret == 1)
			{
				$connection->commit();
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">mismoduleresponsibility is Deleted Successfully</span></br>';
				echo '<a href="../data/mismoduleresponsibility.php?mismoduleresponsibilityid='.fnEncrypt($mismoduleresponsibility1->mismoduleresponsibilityid).'">Add/Display mismoduleresponsibility Detail</a></br>';
			}
			else
			{
				$connection->rollback();
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$mismoduleresponsibility1->Get_invalidmessagetext().'</span></br>';
				echo '<a href="../data/mismoduleresponsibility.php?mismoduleresponsibilityid='.fnEncrypt($mismoduleresponsibility1->mismoduleresponsibilityid).'&flag='.$flag_en.'">Add/Display mismoduleresponsibility Detail</a></br>';
			}
			break;
		case 'Reset':
			$mismoduleresponsibility1->mismoduleresponsibilityid = 0;
			$flag_en = fnEncrypt('Display');
			echo '<a href="../data/mismoduleresponsibility.php?mismoduleresponsibilityid='.fnEncrypt($mismoduleresponsibility1->mismoduleresponsibilityid).'">Add/Display mismoduleresponsibility</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>