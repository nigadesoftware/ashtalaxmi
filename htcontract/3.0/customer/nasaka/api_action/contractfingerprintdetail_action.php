<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contractfingerprintdetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractfingerprintdetail1 = new contractfingerprintdetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contractfingerprintdetail1->contractid = $_POST["contractid"];
			$contractfingerprintdetail1->contractreferencecategoryid = $_POST["contractreferencecategoryid"];
			$contractfingerprintdetail1->contractreferencedetailid = $_POST["contractreferencedetailid"];
            
			if (isset($_FILES['fingerprint']) && $_FILES['fingerprint']['size'] > 0) 
			{
				// Temporary file name stored on the server

				$tmpName = $_FILES['fingerprint']['tmp_name'];


				// Read the file

				$fp = fopen($tmpName, 'r');

				$data = fread($fp, filesize($tmpName));

				$data = addslashes($data);

				fclose($fp);
			}

			$contractfingerprintdetail1->fingerprint = $data;
			$ret = $contractfingerprintdetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Finger Print is added successfully</span></br>';
				echo '<a href="../data/contractfingerprintdetail_list.php?contractid='.fnEncrypt($contractfingerprintdetail1->contractid).'&contractreferencecategoryid='.fnEncrypt($contractfingerprintdetail1->contractreferencecategoryid).'&contractreferencedetailid='.fnEncrypt($contractfingerprintdetail1->contractreferencedetailid).'&contractfingerprintdetailid='.fnEncrypt($contractfingerprintdetail1->contractfingerprintdetailid).'">Contract Finger Print List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractfingerprintdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contractfingerprintdetail1->contractfingerprintdetailid = $_POST["contractfingerprintdetailid"];
			$contractfingerprintdetail1->contractid = $_POST["contractid"];
			$contractfingerprintdetail1->contractreferencecategoryid = $_POST["contractreferencecategoryid"];
            $contractfingerprintdetail1->contractreferencedetailid = $_POST["contractreferencedetailid"];
			
			if (isset($_FILES['fingerprint']) && $_FILES['fingerprint']['size'] > 0) 
			{
				// Temporary file name stored on the server

				$tmpName = $_FILES['fingerprint']['tmp_name'];


				// Read the file

				$fp = fopen($tmpName, 'r');

				$data = fread($fp, filesize($tmpName));

				$data = addslashes($data);

				fclose($fp);
			}

			$contractfingerprintdetail1->fingerprint = $data;

			$ret = $contractfingerprintdetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Finger Print is Updated successfully</span></br>';	
				echo '<a href="../data/contractfingerprintdetail_list.php?contractid='.fnEncrypt($contractfingerprintdetail1->contractid).'&contractreferencecategoryid='.fnEncrypt($contractfingerprintdetail1->contractreferencecategoryid).'&contractreferencedetailid='.fnEncrypt($contractfingerprintdetail1->contractreferencedetailid).'&contractfingerprintdetailid='.fnEncrypt($contractfingerprintdetail1->contractfingerprintdetailid).'">Contract Aadhar Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractfingerprintdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contractfingerprintdetail1->contractfingerprintdetailid = $_POST["contractfingerprintdetailid"];
			$contractfingerprintdetail1->contractid = $_POST["contractid"];
			$contractfingerprintdetail1->contractreferencecategoryid = $_POST["contractreferencecategoryid"];
			$contractfingerprintdetail1->contractreferencedetailid = $_POST["contractreferencedetailid"];
			$contractfingerprintdetail1->fingerprint = $_POST["fingerprint"];

			$result1 = $contractfingerprintdetail1->display();
			if ($contractfingerprintdetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					echo '<a href="../data/contractfingerprintdetail.php?contractid='.fnEncrypt($contractfingerprintdetail1->contractid).'&contractreferencecategoryid='.fnEncrypt($contractfingerprintdetail1->contractreferencecategoryid).'&contractreferencedetailid='.fnEncrypt($contractfingerprintdetail1->contractreferencedetailid).'&contractfingerprintdetailid='.fnEncrypt($row1['CONTRACTFINGERPRINTDETAILID']).'&flag='.fnencrypt('Display').'">AADHAR DETAIL</BR>';
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractfingerprintdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contractfingerprintdetail1->contractfingerprintdetailid = $_POST["contractfingerprintdetailid"];
			$contractfingerprintdetail1->contractid = $_POST["contractid"];
			$contractfingerprintdetail1->contractreferencecategoryid = $_POST["contractreferencecategoryid"];
            $contractfingerprintdetail1->contractreferencedetailid = $_POST["contractreferencedetailid"];

			$ret = $contractfingerprintdetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Finger Print is Deleted Successfully</span></br>';
				echo '<a href="../data/contractfingerprintdetail_list.php?contractid='.fnEncrypt($contractfingerprintdetail1->contractid).'&contractreferencecategoryid='.fnEncrypt($contractfingerprintdetail1->contractreferencecategoryid).'&contractreferencedetailid='.fnEncrypt($contractfingerprintdetail1->contractreferencedetailid).'&contractfingerprintdetailid='.fnEncrypt($contractfingerprintdetail1->contractfingerprintdetailid).'">Contract Finger Print List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractfingerprintdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contractfingerprintdetail1->contractfingerprintdetailid = $_POST["contractfingerprintdetailid"];
			$contractfingerprintdetail1->contractid = $_POST["contractid"];
			echo '<a href="../data/contractfingerprintdetail_list.php?contractid='.fnEncrypt($contractfingerprintdetail1->contractid).'&contractreferencecategoryid='.fnEncrypt($contractfingerprintdetail1->contractreferencecategoryid).'&contractreferencedetailid='.fnEncrypt($contractfingerprintdetail1->contractreferencedetailid).'&contractfingerprintdetailid='.fnEncrypt($contractfingerprintdetail1->contractfingerprintdetailid).'">Contract Finger Print List</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>