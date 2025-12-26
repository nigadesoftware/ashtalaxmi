<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contractwitnessdetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractwitnessdetail1 = new contractwitnessdetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contractwitnessdetail1->contractid = $_POST["contractid"];
			$contractwitnessdetail1->name_unicode = $_POST["name_unicode"];
			$ret = $contractwitnessdetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract witness is added successfully</span></br>';
				echo '<a href="../data/contractwitnessdetail_list.php?contractid='.fnEncrypt($contractwitnessdetail1->contractid).'&contractwitnessdetailid='.fnEncrypt($contractwitnessdetail1->contractwitnessdetailid).'">Contract witness Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractwitnessdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contractwitnessdetail1->contractwitnessdetailid = $_POST["contractwitnessdetailid"];
			$contractwitnessdetail1->contractid = $_POST["contractid"];
			$contractwitnessdetail1->name_unicode = $_POST["name_unicode"];
			$ret = $contractwitnessdetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract witness Detail is Updated successfully</span></br>';	
				echo '<a href="../data/contractwitnessdetail_list.php?contractid='.fnEncrypt($contractwitnessdetail1->contractid).'&contractwitnessdetailid='.fnEncrypt($contractwitnessdetail1->contractwitnessdetailid).'">Contract Harvest Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractwitnessdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contractwitnessdetail1->contractwitnessdetailid = $_POST["contractwitnessdetailid"];
			$contractwitnessdetail1->contractid = $_POST["contractid"];
			$contractwitnessdetail1->name_unicode = $_POST["name_unicode"];
            $result1 = $contractwitnessdetail1->display();
			if ($contractwitnessdetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../data/contractwitnessdetail.php?contractid='.fnEncrypt($contractwitnessdetail1->contractid).'&contractwitnessdetailid='.fnEncrypt($row1['CONTRACTwitnessDETAILID']).'&flag='.fnencrypt('Display').'">FIELD PLOT LOCATION</BR>';
					}
					else
					{
						echo '<a href="../data/contractwitnessdetail.php?contractid='.fnEncrypt($contractwitnessdetail1->contractid).'&contractwitnessdetailid='.fnEncrypt($row1['CONTRACTwitnessDETAILID']).'&flag='.fnencrypt('Display').'">जमिनीचे क्षेत्र</BR>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractwitnessdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contractwitnessdetail1->contractwitnessdetailid = $_POST["contractwitnessdetailid"];
			$contractwitnessdetail1->contractid = $_POST["contractid"];
			$contractwitnessdetail1->witnessid = $_POST["name_unicode"];
			$ret = $contractwitnessdetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract witness Detail is Deleted Successfully</span></br>';
				echo '<a href="../data/contractwitnessdetail_list.php?contractid='.fnEncrypt($contractwitnessdetail1->contractid).'&contractwitnessdetailid='.fnEncrypt($contractwitnessdetail1->contractwitnessdetailid).'">Contract witness Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractwitnessdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contractwitnessdetail1->contractid = $_POST["contractid"];
			echo '<a href="../data/contractwitnessdetail_list.php?contractid='.fnEncrypt($contractwitnessdetail1->contractid).'&contractwitnessdetailid='.fnEncrypt($contractwitnessdetail1->contractwitnessdetailid).'">Contract witness Detail List</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>