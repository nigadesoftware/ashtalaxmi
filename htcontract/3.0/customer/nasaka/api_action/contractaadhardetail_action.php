<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contractaadhardetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractaadhardetail1 = new contractaadhardetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contractaadhardetail1->contractid = $_POST["contractid"];
			$contractaadhardetail1->contractreferencecategoryid = $_POST["contractreferencecategoryid"];
			$contractaadhardetail1->contractreferencedetailid = $_POST["contractreferencedetailid"];
            $contractaadhardetail1->aadharnumber = $_POST["aadharnumber"];
			$ret = $contractaadhardetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Aadhar is added successfully</span></br>';
				echo '<a href="../data/contractaadhardetail_list.php?contractid='.fnEncrypt($contractaadhardetail1->contractid).'&contractreferencecategoryid='.fnEncrypt($contractaadhardetail1->contractreferencecategoryid).'&contractreferencedetailid='.fnEncrypt($contractaadhardetail1->contractreferencedetailid).'&contractaadhardetailid='.fnEncrypt($contractaadhardetail1->contractaadhardetailid).'">Contract Aadhar Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractaadhardetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contractaadhardetail1->contractaadhardetailid = $_POST["contractaadhardetailid"];
			$contractaadhardetail1->contractid = $_POST["contractid"];
			$contractaadhardetail1->contractreferencecategoryid = $_POST["contractreferencecategoryid"];
            $contractaadhardetail1->contractreferencedetailid = $_POST["contractreferencedetailid"];
			$contractaadhardetail1->aadharnumber = $_POST["aadharnumber"];
			$ret = $contractaadhardetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Aadhar Detail is Updated successfully</span></br>';	
				echo '<a href="../data/contractaadhardetail_list.php?contractid='.fnEncrypt($contractaadhardetail1->contractid).'&contractreferencecategoryid='.fnEncrypt($contractaadhardetail1->contractreferencecategoryid).'&contractreferencedetailid='.fnEncrypt($contractaadhardetail1->contractreferencedetailid).'&contractaadhardetailid='.fnEncrypt($contractaadhardetail1->contractaadhardetailid).'">Contract Aadhar Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractaadhardetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contractaadhardetail1->contractaadhardetailid = $_POST["contractaadhardetailid"];
			$contractaadhardetail1->contractid = $_POST["contractid"];
			$contractaadhardetail1->contractreferencecategoryid = $_POST["contractreferencecategoryid"];
			$contractaadhardetail1->contractreferencedetailid = $_POST["contractreferencedetailid"];
            $contractaadhardetail1->aadharnumber = $_POST["aadharnumber"];
			$result1 = $contractaadhardetail1->display();
			if ($contractaadhardetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					echo '<a href="../data/contractaadhardetail.php?contractid='.fnEncrypt($contractaadhardetail1->contractid).'&contractreferencecategoryid='.fnEncrypt($contractaadhardetail1->contractreferencecategoryid).'&contractreferencedetailid='.fnEncrypt($contractaadhardetail1->contractreferencedetailid).'&contractaadhardetailid='.fnEncrypt($row1['CONTRACTAADHARDETAILID']).'&flag='.fnencrypt('Display').'">AADHAR DETAIL</BR>';
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractaadhardetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contractaadhardetail1->contractaadhardetailid = $_POST["contractaadhardetailid"];
			$contractaadhardetail1->contractid = $_POST["contractid"];
			$contractaadhardetail1->contractreferencecategoryid = $_POST["contractreferencecategoryid"];
            $contractaadhardetail1->contractreferencedetailid = $_POST["contractreferencedetailid"];
			$contractaadhardetail1->aadharnumber = $_POST["aadharnumber"];
			$ret = $contractaadhardetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Aadhar Detail is Deleted Successfully</span></br>';
				echo '<a href="../data/contractaadhardetail_list.php?contractid='.fnEncrypt($contractaadhardetail1->contractid).'&contractreferencecategoryid='.fnEncrypt($contractaadhardetail1->contractreferencecategoryid).'&contractreferencedetailid='.fnEncrypt($contractaadhardetail1->contractreferencedetailid).'&contractaadhardetailid='.fnEncrypt($contractaadhardetail1->contractaadhardetailid).'">Contract Aadhar Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractaadhardetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contractaadhardetail1->contractaadhardetailid = $_POST["contractaadhardetailid"];
			$contractaadhardetail1->contractid = $_POST["contractid"];
			echo '<a href="../data/contractaadhardetail_list.php?contractid='.fnEncrypt($contractaadhardetail1->contractid).'&contractreferencecategoryid='.fnEncrypt($contractaadhardetail1->contractreferencecategoryid).'&contractreferencedetailid='.fnEncrypt($contractaadhardetail1->contractreferencedetailid).'&contractaadhardetailid='.fnEncrypt($contractaadhardetail1->contractaadhardetailid).'">Contract Aadhar Detail List</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>