<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contractguarantordetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractguarantordetail1 = new contractguarantordetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contractguarantordetail1->contractid = $_POST["contractid"];
			$contractguarantordetail1->servicecontractorid = $_POST["servicecontractorid"];
			$contractguarantordetail1->iscultivator = $_POST["iscultivator"];
			$contractguarantordetail1->fieldarea = $_POST["fieldarea"];
			$contractguarantordetail1->plantationarea = $_POST["plantationarea"];
			$contractguarantordetail1->address = $_POST["address"];
			$ret = $contractguarantordetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Guarantor is added successfully</span></br>';
				echo '<a href="../data/contractguarantordetail_list.php?contractid='.fnEncrypt($contractguarantordetail1->contractid).'&contractguarantordetailid='.fnEncrypt($contractguarantordetail1->contractguarantordetailid).'">Contract Guarantor Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractguarantordetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contractguarantordetail1->contractguarantordetailid = $_POST["contractguarantordetailid"];
			$contractguarantordetail1->contractid = $_POST["contractid"];
			$contractguarantordetail1->servicecontractorid = $_POST["servicecontractorid"];
			$contractguarantordetail1->iscultivator = $_POST["iscultivator"];
			$contractguarantordetail1->fieldarea = $_POST["fieldarea"];
			$contractguarantordetail1->plantationarea = $_POST["plantationarea"];
			$contractguarantordetail1->address = $_POST["address"];
			$ret = $contractguarantordetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Guarantor Detail is Updated successfully</span></br>';	
				echo '<a href="../data/contractguarantordetail_list.php?contractid='.fnEncrypt($contractguarantordetail1->contractid).'&contractguarantordetailid='.fnEncrypt($contractguarantordetail1->contractguarantordetailid).'">Contract Harvest Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractguarantordetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contractguarantordetail1->contractguarantordetailid = $_POST["contractguarantordetailid"];
			$contractguarantordetail1->contractid = $_POST["contractid"];
			$contractguarantordetail1->servicecontractorid = $_POST["servicecontractorid"];
			$contractguarantordetail1->iscultivator = $_POST["iscultivator"];
			$contractguarantordetail1->fieldarea = $_POST["fieldarea"];
			$contractguarantordetail1->plantationarea = $_POST["plantationarea"];
			$contractguarantordetail1->address = $_POST["address"];
			$result1 = $contractguarantordetail1->display();
			if ($contractguarantordetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../data/contractguarantordetail.php?contractid='.fnEncrypt($contractguarantordetail1->contractid).'&contractguarantordetailid='.fnEncrypt($row1['CONTRACTGUARANTORDETAILID']).'&flag='.fnencrypt('Display').'">FIELD PLOT LOCATION</BR>';
					}
					else
					{
						echo '<a href="../data/contractguarantordetail.php?contractid='.fnEncrypt($contractguarantordetail1->contractid).'&contractguarantordetailid='.fnEncrypt($row1['CONTRACTGUARANTORDETAILID']).'&flag='.fnencrypt('Display').'">जमिनीचे क्षेत्र</BR>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractguarantordetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contractguarantordetail1->contractguarantordetailid = $_POST["contractguarantordetailid"];
			$contractguarantordetail1->contractid = $_POST["contractid"];
			$contractguarantordetail1->servicecontractorid = $_POST["servicecontractorid"];
			$contractguarantordetail1->iscultivator = $_POST["iscultivator"];
			$contractguarantordetail1->fieldarea = $_POST["fieldarea"];
			$contractguarantordetail1->plantationarea = $_POST["plantationarea"];
			$contractguarantordetail1->address = $_POST["address"];
			$ret = $contractguarantordetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Guarantor Detail is Deleted Successfully</span></br>';
				echo '<a href="../data/contractguarantordetail_list.php?contractid='.fnEncrypt($contractguarantordetail1->contractid).'&contractguarantordetailid='.fnEncrypt($contractguarantordetail1->contractguarantordetailid).'">Contract Guarantor Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractguarantordetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contractguarantordetail1->contractid = $_POST["contractid"];
			echo '<a href="../data/contractguarantordetail_list.php?contractid='.fnEncrypt($contractguarantordetail1->contractid).'&contractguarantordetailid='.fnEncrypt($contractguarantordetail1->contractguarantordetailid).'">Contract Harvest Detail List</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>