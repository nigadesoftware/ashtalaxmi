<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contractapproverdetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractapproverdetail1 = new contractapproverdetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contractapproverdetail1->contractid = $_POST["contractid"];
			$contractapproverdetail1->employeeid = $_POST["employeeid"];
			$contractapproverdetail1->responsibilityid = $_POST["responsibilityid"];
			$ret = $contractapproverdetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Approver is added successfully</span></br>';
				echo '<a href="../data/contractapproverdetail_list.php?contractid='.fnEncrypt($contractapproverdetail1->contractid).'&contractapproverdetailid='.fnEncrypt($contractapproverdetail1->contractapproverdetailid).'">Contract Approver Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractapproverdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contractapproverdetail1->contractapproverdetailid = $_POST["contractapproverdetailid"];
			$contractapproverdetail1->contractid = $_POST["contractid"];
			$contractapproverdetail1->employeeid = $_POST["employeeid"];
			$contractapproverdetail1->responsibilityid = $_POST["responsibilityid"];
			$ret = $contractapproverdetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Approver Detail is Updated successfully</span></br>';	
				echo '<a href="../data/contractapproverdetail_list.php?contractid='.fnEncrypt($contractapproverdetail1->contractid).'&contractapproverdetailid='.fnEncrypt($contractapproverdetail1->contractapproverdetailid).'">Contract Approver Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractapproverdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contractapproverdetail1->contractapproverdetailid = $_POST["contractapproverdetailid"];
			$contractapproverdetail1->contractid = $_POST["contractid"];
			$contractapproverdetail1->employeeid = $_POST["employeeid"];
			$contractapproverdetail1->responsibilityid = $_POST["responsibilityid"];
			$result1 = $contractapproverdetail1->display();
			if ($contractapproverdetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../data/contractapproverdetail.php?contractid='.fnEncrypt($contractapproverdetail1->contractid).'&contractapproverdetailid='.fnEncrypt($row1['CONTRACTGUARANTORDETAILID']).'&flag='.fnencrypt('Display').'">Approver</BR>';
					}
					else
					{
						echo '<a href="../data/contractapproverdetail.php?contractid='.fnEncrypt($contractapproverdetail1->contractid).'&contractapproverdetailid='.fnEncrypt($row1['CONTRACTGUARANTORDETAILID']).'&flag='.fnencrypt('Display').'">शिफारस</BR>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractapproverdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contractapproverdetail1->contractapproverdetailid = $_POST["contractapproverdetailid"];
			$contractapproverdetail1->contractid = $_POST["contractid"];
			$contractapproverdetail1->employeeid = $_POST["employeeid"];
			$contractapproverdetail1->responsibilityid = $_POST["responsibilityid"];
			$ret = $contractapproverdetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Approver Detail is Deleted Successfully</span></br>';
				echo '<a href="../data/contractapproverdetail_list.php?contractid='.fnEncrypt($contractapproverdetail1->contractid).'&contractapproverdetailid='.fnEncrypt($contractapproverdetail1->contractapproverdetailid).'">Contract Approver Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractapproverdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contractapproverdetail1->contractid = $_POST["contractid"];
			echo '<a href="../data/contractapproverdetail_list.php?contractid='.fnEncrypt($contractapproverdetail1->contractid).'&contractapproverdetailid='.fnEncrypt($contractapproverdetail1->contractapproverdetailid).'">Contract Approver Detail List</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>