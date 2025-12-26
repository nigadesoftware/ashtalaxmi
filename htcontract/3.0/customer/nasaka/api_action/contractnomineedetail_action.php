<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contractnomineedetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractnomineedetail1 = new contractnomineedetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contractnomineedetail1->contractid = $_POST["contractid"];
			$contractnomineedetail1->name_unicode = $_POST["name_unicode"];
			$ret = $contractnomineedetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Nominee is added successfully</span></br>';
				echo '<a href="../data/contractnomineedetail_list.php?contractid='.fnEncrypt($contractnomineedetail1->contractid).'&contractnomineedetailid='.fnEncrypt($contractnomineedetail1->contractnomineedetailid).'">Contract Nominee Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractnomineedetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contractnomineedetail1->contractnomineedetailid = $_POST["contractnomineedetailid"];
			$contractnomineedetail1->contractid = $_POST["contractid"];
			$contractnomineedetail1->name_unicode = $_POST["name_unicode"];
			$ret = $contractnomineedetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Nominee Detail is Updated successfully</span></br>';	
				echo '<a href="../data/contractnomineedetail_list.php?contractid='.fnEncrypt($contractnomineedetail1->contractid).'&contractnomineedetailid='.fnEncrypt($contractnomineedetail1->contractnomineedetailid).'">Contract Harvest Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractnomineedetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contractnomineedetail1->contractnomineedetailid = $_POST["contractnomineedetailid"];
			$contractnomineedetail1->contractid = $_POST["contractid"];
			$contractnomineedetail1->name_unicode = $_POST["name_unicode"];
            $result1 = $contractnomineedetail1->display();
			if ($contractnomineedetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../data/contractnomineedetail.php?contractid='.fnEncrypt($contractnomineedetail1->contractid).'&contractnomineedetailid='.fnEncrypt($row1['CONTRACTNOMINEEDETAILID']).'&flag='.fnencrypt('Display').'">FIELD PLOT LOCATION</BR>';
					}
					else
					{
						echo '<a href="../data/contractnomineedetail.php?contractid='.fnEncrypt($contractnomineedetail1->contractid).'&contractnomineedetailid='.fnEncrypt($row1['CONTRACTNOMINEEDETAILID']).'&flag='.fnencrypt('Display').'">जमिनीचे क्षेत्र</BR>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractnomineedetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contractnomineedetail1->contractnomineedetailid = $_POST["contractnomineedetailid"];
			$contractnomineedetail1->contractid = $_POST["contractid"];
			$contractnomineedetail1->nomineeid = $_POST["name_unicode"];
			$ret = $contractnomineedetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Nominee Detail is Deleted Successfully</span></br>';
				echo '<a href="../data/contractnomineedetail_list.php?contractid='.fnEncrypt($contractnomineedetail1->contractid).'&contractnomineedetailid='.fnEncrypt($contractnomineedetail1->contractnomineedetailid).'">Contract Nominee Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractnomineedetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contractnomineedetail1->contractid = $_POST["contractid"];
			echo '<a href="../data/contractnomineedetail_list.php?contractid='.fnEncrypt($contractnomineedetail1->contractid).'&contractnomineedetailid='.fnEncrypt($contractnomineedetail1->contractnomineedetailid).'">Contract Nominee Detail List</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>