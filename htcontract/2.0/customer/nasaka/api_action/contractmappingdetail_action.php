<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contractmappingdetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractmappingdetail1 = new contractmappingdetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contractmappingdetail1->contractmappingid = $_POST["contractmappingid"];
			$contractmappingdetail1->contracttransportdetailid = $_POST["contracttransportdetailid"];
			$contractmappingdetail1->contractharvestdetailid = $_POST["contractharvestdetailid"];
			$ret = $contractmappingdetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Transport Harvest Mapping is added successfully</span></br>';
				echo '<a href="../data/contractmappingdetail_list.php?contractmappingid='.fnEncrypt($contractmappingdetail1->contractmappingid).'&contractmappingdetailid='.fnEncrypt($contractmappingdetail1->contractmappingdetailid).'">Contract Transport Harvest Mapping Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractmappingdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contractmappingdetail1->contractmappingdetailid = $_POST["contractmappingdetailid"];
			$contractmappingdetail1->contractmappingid = $_POST["contractmappingid"];
			$contractmappingdetail1->contracttransportdetailid = $_POST["contracttransportdetailid"];
			$contractmappingdetail1->contractharvestdetailid = $_POST["contractharvestdetailid"];			$ret = $contractmappingdetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Transport Detail is Updated successfully</span></br>';	
				echo '<a href="../data/contractmappingdetail_list.php?contractmappingid='.fnEncrypt($contractmappingdetail1->contractmappingid).'&contractmappingdetailid='.fnEncrypt($contractmappingdetail1->contractmappingdetailid).'">Contract Transport Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractmappingdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contractmappingdetail1->contractmappingdetailid = $_POST["contractmappingdetailid"];
			$contractmappingdetail1->contractmappingid = $_POST["contractmappingid"];
			$contractmappingdetail1->contracttransportdetailid = $_POST["contracttransportdetailid"];
			$contractmappingdetail1->contractharvestdetailid = $_POST["contractharvestdetailid"];			$result1 = $contractmappingdetail1->display();
			if ($contractmappingdetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../data/contractmappingdetail.php?contractmappingid='.fnEncrypt($contractmappingdetail1->contractmappingid).'&contractmappingdetailid='.fnEncrypt($row1['CONTRACTMAPPINGDETAILID']).'&flag='.fnencrypt('Display').'">FIELD PLOT LOCATION</BR>';
					}
					else
					{
						echo '<a href="../data/contractmappingdetail.php?contractmappingid='.fnEncrypt($contractmappingdetail1->contractmappingid).'&contractmappingdetailid='.fnEncrypt($row1['CONTRACTMAPPINGDETAILID']).'&flag='.fnencrypt('Display').'">जमिनीचे क्षेत्र</BR>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractmappingdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contractmappingdetail1->contractmappingdetailid = $_POST["contractmappingdetailid"];
			$contractmappingdetail1->contractmappingid = $_POST["contractmappingid"];
			$contractmappingdetail1->contracttransportdetailid = $_POST["contracttransportdetailid"];
			$contractmappingdetail1->contractharvestdetailid = $_POST["contractharvestdetailid"];			$ret = $contractmappingdetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Transport Detail is Deleted Successfully</span></br>';
				echo '<a href="../data/contractmappingdetail_list.php?contractmappingid='.fnEncrypt($contractmappingdetail1->contractmappingid).'&contractmappingdetailid='.fnEncrypt($contractmappingdetail1->contractmappingdetailid).'">Contract Transport Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractmappingdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contractmappingdetail1->contractmappingid = $_POST["contractmappingid"];
			echo '<a href="../data/contractmappingdetail_list.php?contractmappingid='.fnEncrypt($contractmappingdetail1->contractmappingid).'&contractmappingdetailid='.fnEncrypt($contractmappingdetail1->contractmappingdetailid).'">Contract Transport Detail List</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>