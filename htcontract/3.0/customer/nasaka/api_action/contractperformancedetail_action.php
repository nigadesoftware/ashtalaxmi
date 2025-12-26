<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contractperformancedetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractperformancedetail1 = new contractperformancedetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contractperformancedetail1->contractperformancedetailid = $_POST["contractperformancedetailid"];
			$contractperformancedetail1->contractid = $_POST["contractid"];
			$contractperformancedetail1->lastseasonid = $_POST["lastseasonid"];
			$contractperformancedetail1->lastseasonhttonnage = $_POST["lastseasonhttonnage"];
			$contractperformancedetail1->balance = $_POST["balance"];
			$contractperformancedetail1->debitcredit = $_POST["debitcredit"];
			$ret = $contractperformancedetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Transport is added successfully</span></br>';
				echo '<a href="../data/contractperformancedetail_list.php?contractid='.fnEncrypt($contractperformancedetail1->contractid).'&contractperformancedetailid='.fnEncrypt($contractperformancedetail1->contractperformancedetailid).'">Contract Performance Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractperformancedetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contractperformancedetail1->contractperformancedetailid = $_POST["contractperformancedetailid"];
			$contractperformancedetail1->contractid = $_POST["contractid"];
			$contractperformancedetail1->lastseasonid = $_POST["lastseasonid"];
			$contractperformancedetail1->lastseasonhttonnage = $_POST["lastseasonhttonnage"];
			$contractperformancedetail1->balance = $_POST["balance"];
			$contractperformancedetail1->debitcredit = $_POST["debitcredit"];
			$ret = $contractperformancedetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Transport Detail is Updated successfully</span></br>';	
				echo '<a href="../data/contractperformancedetail_list.php?contractid='.fnEncrypt($contractperformancedetail1->contractid).'&contractperformancedetailid='.fnEncrypt($contractperformancedetail1->contractperformancedetailid).'">Contract Performance Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractperformancedetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contractperformancedetail1->contractperformancedetailid = $_POST["contractperformancedetailid"];
			$contractperformancedetail1->contractid = $_POST["contractid"];
			$contractperformancedetail1->lastseasonid = $_POST["lastseasonid"];
			$contractperformancedetail1->lastseasonhttonnage = $_POST["lastseasonhttonnage"];
			$contractperformancedetail1->balance = $_POST["balance"];
			$contractperformancedetail1->debitcredit = $_POST["debitcredit"];
			$result1 = $contractperformancedetail1->display();
			if ($contractperformancedetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../data/contractperformancedetail.php?contractid='.fnEncrypt($contractperformancedetail1->contractid).'&contractperformancedetailid='.fnEncrypt($row1['CONTRACTPERFORMANCEDETAILID']).'&flag='.fnencrypt('Display').'">PERFORMANCE DETAIL</BR>';
					}
					else
					{
						echo '<a href="../data/contractperformancedetail.php?contractid='.fnEncrypt($contractperformancedetail1->contractid).'&contractperformancedetailid='.fnEncrypt($row1['CONTRACTPERFORMANCEDETAILID']).'&flag='.fnencrypt('Display').'">कामगिरी माहिती</BR>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractperformancedetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contractperformancedetail1->contractperformancedetailid = $_POST["contractperformancedetailid"];
			$contractperformancedetail1->contractid = $_POST["contractid"];
			$contractperformancedetail1->lastseasonid = $_POST["lastseasonid"];
			$contractperformancedetail1->lastseasonhttonnage = $_POST["lastseasonhttonnage"];
			$contractperformancedetail1->balance = $_POST["balance"];
			$contractperformancedetail1->debitcredit = $_POST["debitcredit"];
			$ret = $contractperformancedetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Transport Detail is Deleted Successfully</span></br>';
				echo '<a href="../data/contractperformancedetail_list.php?contractid='.fnEncrypt($contractperformancedetail1->contractid).'&contractperformancedetailid='.fnEncrypt($contractperformancedetail1->contractperformancedetailid).'">Contract Transport Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractperformancedetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contractperformancedetail1->contractid = $_POST["contractid"];
			echo '<a href="../data/contractperformancedetail_list.php?contractid='.fnEncrypt($contractperformancedetail1->contractid).'&contractperformancedetailid='.fnEncrypt($contractperformancedetail1->contractperformancedetailid).'">Contract Transport Detail List</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>