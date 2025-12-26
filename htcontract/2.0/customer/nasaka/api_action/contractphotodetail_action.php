<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contractphotodetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractphotodetail1 = new contractphotodetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contractphotodetail1->contractid = $_POST["contractid"];
			$contractphotodetail1->contractreferencecategoryid = $_POST["contractreferencecategoryid"];
			$contractphotodetail1->contractreferencedetailid = $_POST["contractreferencedetailid"];
            
			if (isset($_SESSION['photofile'])) 
			{
				// Temporary file name stored on the server

				$tmpName = $_SESSION['photofile'];


				// Read the file

				$fp = fopen($tmpName, 'r');

				$data = fread($fp, filesize($tmpName));

				$data = addslashes($data);

				fclose($fp);
			}

			$contractphotodetail1->photo = $data;
			$ret = $contractphotodetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Photo is added successfully</span></br>';
				echo '<a href="../data/contractphotodetail_list.php?contractid='.fnEncrypt($contractphotodetail1->contractid).'&contractreferencecategoryid='.fnEncrypt($contractphotodetail1->contractreferencecategoryid).'&contractreferencedetailid='.fnEncrypt($contractphotodetail1->contractreferencedetailid).'&contractphotodetailid='.fnEncrypt($contractphotodetail1->contractphotodetailid).'">Contract Photo Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractphotodetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contractphotodetail1->contractphotodetailid = $_POST["contractphotodetailid"];
			$contractphotodetail1->contractid = $_POST["contractid"];
			$contractphotodetail1->contractreferencecategoryid = $_POST["contractreferencecategoryid"];
            $contractphotodetail1->contractreferencedetailid = $_POST["contractreferencedetailid"];
			
			if (isset($_SESSION['photofile'])) 
			{
				// Temporary file name stored on the server

				$tmpName = $_SESSION['photofile'];


				// Read the file

				$fp = fopen($tmpName, 'r');

				$data = fread($fp, filesize($tmpName));

				$data = addslashes($data);

				fclose($fp);
			}

			$contractphotodetail1->photo = $data;
			$ret = $contractphotodetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Photo Detail is Updated successfully</span></br>';	
				echo '<a href="../data/contractphotodetail_list.php?contractid='.fnEncrypt($contractphotodetail1->contractid).'&contractreferencecategoryid='.fnEncrypt($contractphotodetail1->contractreferencecategoryid).'&contractreferencedetailid='.fnEncrypt($contractphotodetail1->contractreferencedetailid).'&contractphotodetailid='.fnEncrypt($contractphotodetail1->contractphotodetailid).'">Contract Photo Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractphotodetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contractphotodetail1->contractphotodetailid = $_POST["contractphotodetailid"];
			$contractphotodetail1->contractid = $_POST["contractid"];
			$contractphotodetail1->contractreferencecategoryid = $_POST["contractreferencecategoryid"];
			$contractphotodetail1->contractreferencedetailid = $_POST["contractreferencedetailid"];
			$contractphotodetail1->photo = $_POST["photo"];

			$result1 = $contractphotodetail1->display();
			if ($contractphotodetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					echo '<a href="../data/contractphotodetail.php?contractid='.fnEncrypt($contractphotodetail1->contractid).'&contractreferencecategoryid='.fnEncrypt($contractphotodetail1->contractreferencecategoryid).'&contractreferencedetailid='.fnEncrypt($contractphotodetail1->contractreferencedetailid).'&contractphotodetailid='.fnEncrypt($row1['CONTRACTPHOTODETAILID']).'&flag='.fnencrypt('Display').'">PHOTO DETAIL</BR>';
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractphotodetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contractphotodetail1->contractphotodetailid = $_POST["contractphotodetailid"];
			$contractphotodetail1->contractid = $_POST["contractid"];
			$contractphotodetail1->contractreferencecategoryid = $_POST["contractreferencecategoryid"];
            $contractphotodetail1->contractreferencedetailid = $_POST["contractreferencedetailid"];


			$ret = $contractphotodetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Photo Detail is Deleted Successfully</span></br>';
				echo '<a href="../data/contractphotodetail_list.php?contractid='.fnEncrypt($contractphotodetail1->contractid).'&contractreferencecategoryid='.fnEncrypt($contractphotodetail1->contractreferencecategoryid).'&contractreferencedetailid='.fnEncrypt($contractphotodetail1->contractreferencedetailid).'&contractphotodetailid='.fnEncrypt($contractphotodetail1->contractphotodetailid).'">Contract Photo Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractphotodetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contractphotodetail1->contractphotodetailid = $_POST["contractphotodetailid"];
			$contractphotodetail1->contractid = $_POST["contractid"];
			echo '<a href="../data/contractphotodetail_list.php?contractid='.fnEncrypt($contractphotodetail1->contractid).'&contractreferencecategoryid='.fnEncrypt($contractphotodetail1->contractreferencecategoryid).'&contractreferencedetailid='.fnEncrypt($contractphotodetail1->contractreferencedetailid).'&contractphotodetailid='.fnEncrypt($contractphotodetail1->contractphotodetailid).'">Contract Photo Detail List</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>