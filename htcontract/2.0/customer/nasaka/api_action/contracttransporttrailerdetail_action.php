<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contracttransporttrailerdetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contracttransporttrailerdetail1 = new contracttransporttrailerdetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contracttransporttrailerdetail1->contracttransportdetailid = $_POST["contracttransportdetailid"];
			$contracttransporttrailerdetail1->contractid = $_POST["contractid"];
			$contracttransporttrailerdetail1->trailernumber = $_POST["trailernumber"];
			$contracttransporttrailerdetail1->rtopassingdatetime = $_POST["rtopassingdatetime"];
			$contracttransporttrailerdetail1->insurancepaiddatetime = $_POST["insurancepaiddatetime"];
			$contracttransporttrailerdetail1->isrcattached = $_POST["isrcattached"];
			$contracttransporttrailerdetail1->istcattached = $_POST["istcattached"];
			$contracttransporttrailerdetail1->trailermfgid = $_POST["trailermfgid"];
			$ret = $contracttransporttrailerdetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Trailer is added successfully</span></br>';
				echo '<a href="../data/contracttransportdetail.php?contractid='.fnEncrypt($contracttransporttrailerdetail1->contractid).'&contracttransportdetailid='.fnEncrypt($contracttransporttrailerdetail1->contracttransportdetailid).'&flag=view">Contract Transport Detail</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contracttransporttrailerdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contracttransporttrailerdetail1->contracttransporttrailerdetailid = $_POST["contracttransporttrailerdetid"];
			$contracttransporttrailerdetail1->contracttransportdetailid = $_POST["contracttransportdetailid"];
			$contracttransporttrailerdetail1->contractid = $_POST["contractid"];
			$contracttransporttrailerdetail1->trailernumber = $_POST["trailernumber"];
			$contracttransporttrailerdetail1->rtopassingdatetime = $_POST["rtopassingdatetime"];
			$contracttransporttrailerdetail1->insurancepaiddatetime = $_POST["insurancepaiddatetime"];
			$contracttransporttrailerdetail1->isrcattached = $_POST["isrcattached"];
			$contracttransporttrailerdetail1->istcattached = $_POST["istcattached"];
			$contracttransporttrailerdetail1->trailermfgid = $_POST["trailermfgid"];
			$ret = $contracttransporttrailerdetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Trailer is Updated successfully</span></br>';	
				echo '<a href="../data/contracttransportdetail.php?contractid='.fnEncrypt($contracttransporttrailerdetail1->contractid).'&contracttransportdetailid='.fnEncrypt($contracttransporttrailerdetail1->contracttransportdetailid).'&flag=view">Contract Transport Detail</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contracttransporttrailerdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contracttransporttrailerdetail1->contracttransporttrailerdetailid = $_POST["contracttransporttrailerdetid"];
			$contracttransporttrailerdetail1->contracttransportdetailid = $_POST["contracttransportdetailid"];
			$contracttransporttrailerdetail1->contractid = $_POST["contractid"];
			$contracttransporttrailerdetail1->trailernumber = $_POST["trailernumber"];
			$contracttransporttrailerdetail1->rtopassingdatetime = $_POST["rtopassingdatetime"];
			$contracttransporttrailerdetail1->insurancepaiddatetime = $_POST["insurancepaiddatetime"];
			$contracttransporttrailerdetail1->isrcattached = $_POST["isrcattached"];
			$contracttransporttrailerdetail1->istcattached = $_POST["istcattached"];
			$contracttransporttrailerdetail1->trailermfgid = $_POST["trailermfgid"];
			$result1 = $contracttransporttrailerdetail1->display();
			if ($contracttransporttrailerdetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../data/contracttransporttrailerdetail.php?contractid='.fnEncrypt($contracttransporttrailerdetail1->contractid).'&contracttransporttrailerdetid='.fnEncrypt($row1['contracttransporttrailerdetid']).'&flag='.fnencrypt('Display').'">FIELD PLOT LOCATION</BR>';
					}
					else
					{
						echo '<a href="../data/contracttransporttrailerdetail.php?contractid='.fnEncrypt($contracttransporttrailerdetail1->contractid).'&contracttransporttrailerdetid='.fnEncrypt($row1['contracttransporttrailerdetid']).'&flag='.fnencrypt('Display').'">जमिनीचे क्षेत्र</BR>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contracttransporttrailerdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contracttransporttrailerdetail1->contracttransporttrailerdetailid = $_POST["contracttransporttrailerdetid"];
			$contracttransporttrailerdetail1->contracttransportdetailid = $_POST["contracttransportdetailid"];
			$contracttransporttrailerdetail1->contractid = $_POST["contractid"];
			$contracttransporttrailerdetail1->trailernumber = $_POST["trailernumber"];
			$contracttransporttrailerdetail1->rtopassingdatetime = $_POST["rtopassingdatetime"];
			$contracttransporttrailerdetail1->insurancepaiddatetime = $_POST["insurancepaiddatetime"];
			$contracttransporttrailerdetail1->isrcattached = $_POST["isrcattached"];
			$contracttransporttrailerdetail1->istcattached = $_POST["istcattached"];
			$contracttransporttrailerdetail1->trailermfgid = $_POST["trailermfgid"];
			$ret = $contracttransporttrailerdetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Trailer is Deleted Successfully</span></br>';
				echo '<a href="../data/contracttransportdetail.php?contractid='.fnEncrypt($contracttransporttrailerdetail1->contractid).'&contracttransportdetailid='.fnEncrypt($contracttransporttrailerdetail1->contracttransportdetailid).'&flag=view">Contract Transport Detail</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contracttransporttrailerdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contracttransporttrailerdetail1->contracttransporttrailerdetailid = $_POST["contracttransporttrailerdetid"];
			$contracttransporttrailerdetail1->contracttransportdetailid = $_POST["contracttransportdetailid"];
			$contracttransporttrailerdetail1->contractid = $_POST["contractid"];
			$contracttransporttrailerdetail1->trailernumber = $_POST["trailernumber"];
			$contracttransporttrailerdetail1->rtopassingdatetime = $_POST["rtopassingdatetime"];
			$contracttransporttrailerdetail1->insurancepaiddatetime = $_POST["insurancepaiddatetime"];
			$contracttransporttrailerdetail1->isrcattached = $_POST["isrcattached"];
			$contracttransporttrailerdetail1->istcattached = $_POST["istcattached"];
			$contracttransporttrailerdetail1->trailermfgid = $_POST["trailermfgid"];
			echo '<a href="../data/contracttransportdetail.php?contractid='.fnEncrypt($contracttransporttrailerdetail1->contractid).'&contracttransportdetailid='.fnEncrypt($contracttransporttrailerdetail1->contracttransportdetailid).'&flag=view">Contract Transport Detail</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>