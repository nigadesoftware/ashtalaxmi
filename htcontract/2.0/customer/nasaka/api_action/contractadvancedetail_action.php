<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contractadvancedetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractadvancedetail1 = new contractadvancedetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contractadvancedetail1->contractid = $_POST["contractid"];
			$contractadvancedetail1->advancedemanddatetime = $_POST["advancedemanddatetime"];
			$contractadvancedetail1->advancedemandamount = $_POST["advancedemandamount"];
			$contractadvancedetail1->approveddatetime = $_POST["approveddatetime"];
			$contractadvancedetail1->approvedamount = $_POST["approvedamount"];
			$ret = $contractadvancedetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Transport is added successfully</span></br>';
				echo '<a href="../data/contractadvancedetail_list.php?contractid='.fnEncrypt($contractadvancedetail1->contractid).'&contractadvancedetailid='.fnEncrypt($contractadvancedetail1->contractadvancedetailid).'">Contract Advance Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractadvancedetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contractadvancedetail1->contractadvancedetailid = $_POST["contractadvancedetailid"];
			$contractadvancedetail1->contractid = $_POST["contractid"];
			$contractadvancedetail1->advancedemanddatetime = $_POST["advancedemanddatetime"];
			$contractadvancedetail1->advancedemandamount = $_POST["advancedemandamount"];
			$contractadvancedetail1->approveddatetime = $_POST["approveddatetime"];
			$contractadvancedetail1->approvedamount = $_POST["approvedamount"];
			$ret = $contractadvancedetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Transport Detail is Updated successfully</span></br>';	
				echo '<a href="../data/contractadvancedetail_list.php?contractid='.fnEncrypt($contractadvancedetail1->contractid).'&contractadvancedetailid='.fnEncrypt($contractadvancedetail1->contractadvancedetailid).'">Contract Advance Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractadvancedetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contractadvancedetail1->contractadvancedetailid = $_POST["contractadvancedetailid"];
			$contractadvancedetail1->contractid = $_POST["contractid"];
			$contractadvancedetail1->advancedemanddatetime = $_POST["advancedemanddatetime"];
			$contractadvancedetail1->advancedemandamount = $_POST["advancedemandamount"];
			$contractadvancedetail1->approveddatetime = $_POST["approveddatetime"];
			$contractadvancedetail1->approvedamount = $_POST["approvedamount"];
			$result1 = $contractadvancedetail1->display();
			if ($contractadvancedetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../data/contractadvancedetail.php?contractid='.fnEncrypt($contractadvancedetail1->contractid).'&contractadvancedetailid='.fnEncrypt($row1['CONTRACTADVANCEDETAILID']).'&flag='.fnencrypt('Display').'">PERFORMANCE DETAIL</BR>';
					}
					else
					{
						echo '<a href="../data/contractadvancedetail.php?contractid='.fnEncrypt($contractadvancedetail1->contractid).'&contractadvancedetailid='.fnEncrypt($row1['CONTRACTADVANCEDETAILID']).'&flag='.fnencrypt('Display').'">कामगिरी माहिती</BR>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractadvancedetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contractadvancedetail1->contractadvancedetailid = $_POST["contractadvancedetailid"];
			$contractadvancedetail1->contractid = $_POST["contractid"];
			$contractadvancedetail1->advancedemanddatetime = $_POST["advancedemanddatetime"];
			$contractadvancedetail1->advancedemandamount = $_POST["advancedemandamount"];
			$contractadvancedetail1->approveddatetime = $_POST["approveddatetime"];
			$contractadvancedetail1->approvedamount = $_POST["approvedamount"];
			$ret = $contractadvancedetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Transport Detail is Deleted Successfully</span></br>';
				echo '<a href="../data/contractadvancedetail_list.php?contractid='.fnEncrypt($contractadvancedetail1->contractid).'&contractadvancedetailid='.fnEncrypt($contractadvancedetail1->contractadvancedetailid).'">Contract Transport Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractadvancedetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contractadvancedetail1->contractid = $_POST["contractid"];
			echo '<a href="../data/contractadvancedetail_list.php?contractid='.fnEncrypt($contractadvancedetail1->contractid).'&contractadvancedetailid='.fnEncrypt($contractadvancedetail1->contractadvancedetailid).'">Contract Transport Detail List</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>