<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contractdocumentdetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractdocumentdetail1 = new contractdocumentdetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contractdocumentdetail1->contractid = $_POST["contractid"];
			$contractdocumentdetail1->documentid = $_POST["documentid"];
			$ret = $contractdocumentdetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract document is added successfully</span></br>';
				echo '<a href="../data/contractdocumentdetail_list.php?contractid='.fnEncrypt($contractdocumentdetail1->contractid).'&contractdocumentdetailid='.fnEncrypt($contractdocumentdetail1->contractdocumentdetailid).'">Contract document Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractdocumentdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contractdocumentdetail1->contractdocumentdetailid = $_POST["contractdocumentdetailid"];
			$contractdocumentdetail1->contractid = $_POST["contractid"];
			$contractdocumentdetail1->documentid = $_POST["documentid"];
			$ret = $contractdocumentdetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract document Detail is Updated successfully</span></br>';	
				echo '<a href="../data/contractdocumentdetail_list.php?contractid='.fnEncrypt($contractdocumentdetail1->contractid).'&contractdocumentdetailid='.fnEncrypt($contractdocumentdetail1->contractdocumentdetailid).'">Contract Harvest Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractdocumentdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contractdocumentdetail1->contractdocumentdetailid = $_POST["contractdocumentdetailid"];
			$contractdocumentdetail1->contractid = $_POST["contractid"];
			$contractdocumentdetail1->documentid = $_POST["documentid"];
            $result1 = $contractdocumentdetail1->display();
			if ($contractdocumentdetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../data/contractdocumentdetail.php?contractid='.fnEncrypt($contractdocumentdetail1->contractid).'&contractdocumentdetailid='.fnEncrypt($row1['CONTRACTDOCUMENTDETAILID']).'&flag='.fnencrypt('Display').'">FIELD PLOT LOCATION</BR>';
					}
					else
					{
						echo '<a href="../data/contractdocumentdetail.php?contractid='.fnEncrypt($contractdocumentdetail1->contractid).'&contractdocumentdetailid='.fnEncrypt($row1['CONTRACTDOCUMENTDETAILID']).'&flag='.fnencrypt('Display').'">जमिनीचे क्षेत्र</BR>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractdocumentdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contractdocumentdetail1->contractdocumentdetailid = $_POST["contractdocumentdetailid"];
			$contractdocumentdetail1->contractid = $_POST["contractid"];
			$contractdocumentdetail1->documentid = $_POST["documentid"];
			$ret = $contractdocumentdetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract document Detail is Deleted Successfully</span></br>';
				echo '<a href="../data/contractdocumentdetail_list.php?contractid='.fnEncrypt($contractdocumentdetail1->contractid).'&contractdocumentdetailid='.fnEncrypt($contractdocumentdetail1->contractdocumentdetailid).'">Contract document Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractdocumentdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contractdocumentdetail1->contractid = $_POST["contractid"];
			echo '<a href="../data/contractdocumentdetail_list.php?contractid='.fnEncrypt($contractdocumentdetail1->contractid).'&contractdocumentdetailid='.fnEncrypt($contractdocumentdetail1->contractdocumentdetailid).'">Contract document Detail List</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>