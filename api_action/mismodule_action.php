<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/swapproutine.php");
	include("../api_mysql/mismodule_db_mysql.php");

	// Opens a connection to a MySQL server
	$connection = db_connection();
	$mismodule1 = new mismodule($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$mismodule1->mismodulename_eng = $_POST["mismodulename"];
			$mismodule1->modulefolder = $_POST["modulefolder"];
			$ret = $mismodule1->insert();
			$flag_en = fnEncrypt('Display');
			if ($ret==1)
			{
				$connection->commit();
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Mismodule is added successfully</span></br>';
				echo '<a href="../data/mismodule.php?mismoduleid='.fnEncrypt($mismodule1->mismoduleid).'&flag='.$flag_en.'">Add/Display Mismodule</a></br>';
			}
			else
			{
				$connection->rollback();
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$mismodule1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$mismodule1->mismoduleid = $_POST["mismoduleid"];
			$mismodule1->mismodulename_eng = $_POST["mismodulename_eng"];
			$mismodule1->modulefolder = $_POST["modulefolder"];
			$ret = $mismodule1->update();
			$flag_en = fnEncrypt('Display');
			if ($ret==1)
			{
				$connection->commit();
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Mismodule is Updated successfully</span></br>';	
				echo '<a href="../data/mismodule.php?mismoduleid='.fnEncrypt($mismodule1->mismoduleid).'&flag='.$flag_en.'">Add/Display Mismodule</a></br>';
			}
			else
			{
				$connection->rollback();
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$mismodule1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$mismodule1->mismoduleid = $_POST["mismoduleid"];
			$mismodule1->mismodulename_eng = $_POST["mismodulename_eng"];
			$mismodule1->modulefolder = $_POST["modulefolder"];
			$result1 = $mismodule1->display();
			$flag_en = fnEncrypt('Display');
			if ($mismodule1->Get_invalidid==0)
			{
				$i=1;
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">List of Module</span></br>';	
				while ($row1 = mysqli_fetch_assoc($result1))
				{
					echo '<a href="../data/mismodule.php?mismoduleid='.fnEncrypt($row1['mismoduleid']).'&flag='.fnEncrypt('Display').'">'.$i++.') '.$row1['mismodulename_eng'].'</br>';
				}
				echo '<a href="../data/mismodule.php?mismoduleid='.fnEncrypt($mismodule1->mismoduleid).'&flag='.$flag_en.'">Add/Display Mismodule Detail</a></br>';	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$mismodule1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$mismodule1->mismoduleid = $_POST["mismoduleid"];
			$mismodule1->mismodulename_eng = $_POST["mismodulename_eng"];
			$mismodule1->modulefolder = $_POST["modulefolder"];
			$ret = $mismodule1->delete();
			$flag_en = fnEncrypt('Display');
			if ($ret == 1)
			{
				$connection->commit();
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Mismodule is Deleted Successfully</span></br>';
				echo '<a href="../data/mismodule.php?mismoduleid='.fnEncrypt($mismodule1->mismoduleid).'">Add/Display Mismodule Detail</a></br>';
			}
			else
			{
				$connection->rollback();
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$mismodule1->Get_invalidmessagetext().'</span></br>';
				echo '<a href="../data/mismodule.php?mismoduleid='.fnEncrypt($mismodule1->mismoduleid).'&flag='.$flag_en.'">Add/Display Mismodule Detail</a></br>';
			}
			break;
		case 'Reset':
			$mismodule1->mismoduleid = 0;
			$flag_en = fnEncrypt('Display');
			echo '<a href="../data/mismodule.php?mismoduleid='.fnEncrypt($mismodule1->mismoduleid).'">Add/Display Mismodule</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>