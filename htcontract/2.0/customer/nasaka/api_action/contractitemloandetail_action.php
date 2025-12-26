<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contractitemloandetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractitemloandetail1 = new contractitemloandetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contractitemloandetail1->contractid = $_POST["contractid"];
			$contractitemloandetail1->itemid = $_POST["itemid"];
			$contractitemloandetail1->qty = $_POST["qty"];
			$contractitemloandetail1->rate = $_POST["rate"];
			$ret = $contractitemloandetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Item Loan is added successfully</span></br>';
				echo '<a href="../data/contractitemloandetail_list.php?contractid='.fnEncrypt($contractitemloandetail1->contractid).'&contractitemloandetailid='.fnEncrypt($contractitemloandetail1->contractitemloandetailid).'">Contract Item Loan Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractitemloandetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contractitemloandetail1->contractitemloandetailid = $_POST["contractitemloandetailid"];
			$contractitemloandetail1->contractid = $_POST["contractid"];
			$contractitemloandetail1->itemid = $_POST["itemid"];
			$contractitemloandetail1->qty = $_POST["qty"];
			$contractitemloandetail1->rate = $_POST["rate"];
			$ret = $contractitemloandetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Item Loan is Updated successfully</span></br>';	
				echo '<a href="../data/contractitemloandetail_list.php?contractid='.fnEncrypt($contractitemloandetail1->contractid).'&contractitemloandetailid='.fnEncrypt($contractitemloandetail1->contractitemloandetailid).'">Contract Item Loan Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractitemloandetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contractitemloandetail1->contractitemloandetailid = $_POST["contractitemloandetailid"];
			$contractitemloandetail1->contractid = $_POST["contractid"];
			$contractitemloandetail1->itemid = $_POST["itemid"];
			$contractitemloandetail1->qty = $_POST["qty"];
			$contractitemloandetail1->rate = $_POST["rate"];
			$contractitemloandetail1->amount = $_POST["amount"];
			$result1 = $contractitemloandetail1->display();
			if ($contractitemloandetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../data/contractitemloandetail.php?contractid='.fnEncrypt($contractitemloandetail1->contractid).'&contractitemloandetailid='.fnEncrypt($row1['CONTRACTADVANCEDETAILID']).'&flag='.fnencrypt('Display').'">Item Loan Detail</BR>';
					}
					else
					{
						echo '<a href="../data/contractitemloandetail.php?contractid='.fnEncrypt($contractitemloandetail1->contractid).'&contractitemloandetailid='.fnEncrypt($row1['CONTRACTADVANCEDETAILID']).'&flag='.fnencrypt('Display').'">वस्तू उधार माहिती</BR>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractitemloandetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contractitemloandetail1->contractitemloandetailid = $_POST["contractitemloandetailid"];
			$contractitemloandetail1->contractid = $_POST["contractid"];
			$contractitemloandetail1->itemid = $_POST["itemid"];
			$contractitemloandetail1->qty = $_POST["qty"];
			$contractitemloandetail1->rate = $_POST["rate"];
			$contractitemloandetail1->amount = $_POST["amount"];
			$ret = $contractitemloandetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Transport Detail is Deleted Successfully</span></br>';
				echo '<a href="../data/contractitemloandetail_list.php?contractid='.fnEncrypt($contractitemloandetail1->contractid).'&contractitemloandetailid='.fnEncrypt($contractitemloandetail1->contractitemloandetailid).'">Contract Item Loan Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractitemloandetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contractitemloandetail1->contractid = $_POST["contractid"];
			echo '<a href="../data/contractitemloandetail_list.php?contractid='.fnEncrypt($contractitemloandetail1->contractid).'&contractitemloandetailid='.fnEncrypt($contractitemloandetail1->contractitemloandetailid).'">Contract Item Loan Detail List</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>