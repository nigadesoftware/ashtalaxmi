<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contractmortgagedetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractmortgagedetail1 = new contractmortgagedetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contractmortgagedetail1->contractid = $_POST["contractid"];
			$contractmortgagedetail1->propertycategoryid = $_POST["propertycategoryid"];
			$contractmortgagedetail1->propertynumber = $_POST["propertynumber"];
			$contractmortgagedetail1->areaid = $_POST["areaid"];
            $ret = $contractmortgagedetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract property is added successfully</span></br>';
				echo '<a href="../data/contractmortgagedetail_list.php?contractid='.fnEncrypt($contractmortgagedetail1->contractid).'&contractmortgagedetailid='.fnEncrypt($contractmortgagedetail1->contractmortgagedetailid).'">Contract Property Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractmortgagedetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contractmortgagedetail1->contractmortgagedetailid = $_POST["contractmortgagedetailid"];
			$contractmortgagedetail1->contractid = $_POST["contractid"];
			$contractmortgagedetail1->propertycategoryid = $_POST["propertycategoryid"];
			$contractmortgagedetail1->propertynumber = $_POST["propertynumber"];
			$contractmortgagedetail1->areaid = $_POST["areaid"];
			$ret = $contractmortgagedetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract property Detail is Updated successfully</span></br>';	
				echo '<a href="../data/contractmortgagedetail_list.php?contractid='.fnEncrypt($contractmortgagedetail1->contractid).'&contractmortgagedetailid='.fnEncrypt($contractmortgagedetail1->contractmortgagedetailid).'">Contract Mortgage Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractmortgagedetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contractmortgagedetail1->contractmortgagedetailid = $_POST["contractmortgagedetailid"];
			$contractmortgagedetail1->contractid = $_POST["contractid"];
			$contractmortgagedetail1->propertycategoryid = $_POST["propertycategoryid"];
			$contractmortgagedetail1->propertynumber = $_POST["propertynumber"];
            $contractmortgagedetail1->areaid = $_POST["areaid"];
            $result1 = $contractmortgagedetail1->display();
			if ($contractmortgagedetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../data/contractmortgagedetail.php?contractid='.fnEncrypt($contractmortgagedetail1->contractid).'&contractmortgagedetailid='.fnEncrypt($row1['CONTRACTMORTGAGEDETAILID']).'&flag='.fnencrypt('Display').'">CONTRACT PROPERTY</BR>';
					}
					else
					{
						echo '<a href="../data/contractmortgagedetail.php?contractid='.fnEncrypt($contractmortgagedetail1->contractid).'&contractmortgagedetailid='.fnEncrypt($row1['CONTRACTMORTGAGEDETAILID']).'&flag='.fnencrypt('Display').'">करार मिळकत</BR>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractmortgagedetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contractmortgagedetail1->contractmortgagedetailid = $_POST["contractmortgagedetailid"];
			$contractmortgagedetail1->contractid = $_POST["contractid"];
			$contractmortgagedetail1->propertycategoryid = $_POST["propertycategoryid"];
			$contractmortgagedetail1->propertynumber = $_POST["propertynumber"];
			$contractmortgagedetail1->areaid = $_POST["areaid"];
            $ret = $contractmortgagedetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract property Detail is Deleted Successfully</span></br>';
				echo '<a href="../data/contractmortgagedetail_list.php?contractid='.fnEncrypt($contractmortgagedetail1->contractid).'&contractmortgagedetailid='.fnEncrypt($contractmortgagedetail1->contractmortgagedetailid).'">Contract Property Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractmortgagedetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contractmortgagedetail1->contractid = $_POST["contractid"];
			echo '<a href="../data/contractmortgagedetail_list.php?contractid='.fnEncrypt($contractmortgagedetail1->contractid).'&contractmortgagedetailid='.fnEncrypt($contractmortgagedetail1->contractmortgagedetailid).'">Contract Property Detail List</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>