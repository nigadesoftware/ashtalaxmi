<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contractharvestdetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractcategoryid = contractcategoryid($connection,$_POST["contractid"]);
	$contractharvestdetail1 = new contractharvestdetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contractharvestdetail1->contractid = $_POST["contractid"];
			$contractharvestdetail1->servicecontractorid = $_POST["servicecontractorid"];
			$contractharvestdetail1->noofvehicles = $_POST["noofvehicles"];
			$contractharvestdetail1->noofharvesterlabour = $_POST["noofharvesterlabour"];
			$contractharvestdetail1->transportationuptovehicleid = $_POST["transportationuptovehicleid"];
			$contractharvestdetail1->bankbranchid = $_POST["bankbranchid"];
			$contractharvestdetail1->chequenumber = $_POST["chequenumber"];
			$ret = $contractharvestdetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Harvest is added successfully</span></br>';
				echo '<a href="../data/contractharvestdetail_list.php?contractid='.fnEncrypt($contractharvestdetail1->contractid).'&contractharvestdetailid='.fnEncrypt($contractharvestdetail1->contractharvestdetailid).'">Contract Harvest Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractharvestdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contractharvestdetail1->contractharvestdetailid = $_POST["contractharvestdetailid"];
			$contractharvestdetail1->contractid = $_POST["contractid"];
			$contractharvestdetail1->servicecontractorid = $_POST["servicecontractorid"];
			$contractharvestdetail1->noofvehicles = $_POST["noofvehicles"];
			$contractharvestdetail1->noofharvesterlabour = $_POST["noofharvesterlabour"];
			$contractharvestdetail1->transportationuptovehicleid = $_POST["transportationuptovehicleid"];
			$contractharvestdetail1->bankbranchid = $_POST["bankbranchid"];
			$contractharvestdetail1->chequenumber = $_POST["chequenumber"];
			$ret = $contractharvestdetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Harvest Detail is Updated successfully</span></br>';	
				echo '<a href="../data/contractharvestdetail_list.php?contractid='.fnEncrypt($contractharvestdetail1->contractid).'&contractharvestdetailid='.fnEncrypt($contractharvestdetail1->contractharvestdetailid).'&contractcategoryid='.fnEncrypt($contractcategoryid).'">Contract Harvest Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractharvestdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contractharvestdetail1->contractharvestdetailid = $_POST["contractharvestdetailid"];
			$contractharvestdetail1->contractid = $_POST["contractid"];
			$contractharvestdetail1->servicecontractorid = $_POST["servicecontractorid"];
			$contractharvestdetail1->noofvehicles = $_POST["noofvehicles"];
			$contractharvestdetail1->noofharvesterlabour = $_POST["noofharvesterlabour"];
			$contractharvestdetail1->transportationuptovehicleid = $_POST["transportationuptovehicleid"];
			$contractharvestdetail1->bankbranchid = $_POST["bankbranchid"];
			$contractharvestdetail1->chequenumber = $_POST["chequenumber"];
			$result1 = $contractharvestdetail1->display();
			if ($contractharvestdetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../data/contractharvestdetail.php?contractid='.fnEncrypt($contractharvestdetail1->contractid).'&contractharvestdetailid='.fnEncrypt($row1['CONTRACTHARVESTDETAILID']).'&flag='.fnencrypt('Display').'">FIELD PLOT LOCATION</BR>';
					}
					else
					{
						echo '<a href="../data/contractharvestdetail.php?contractid='.fnEncrypt($contractharvestdetail1->contractid).'&contractharvestdetailid='.fnEncrypt($row1['CONTRACTHARVESTDETAILID']).'&flag='.fnencrypt('Display').'">जमिनीचे क्षेत्र</BR>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractharvestdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contractharvestdetail1->contractharvestdetailid = $_POST["contractharvestdetailid"];
			$contractharvestdetail1->contractid = $_POST["contractid"];
			$contractharvestdetail1->servicecontractorid = $_POST["servicecontractorid"];
			$contractharvestdetail1->noofvehicles = $_POST["noofvehicles"];
			$contractharvestdetail1->noofharvesterlabour = $_POST["noofharvesterlabour"];
			$contractharvestdetail1->transportationuptovehicleid = $_POST["transportationuptovehicleid"];
			$contractharvestdetail1->bankbranchid = $_POST["bankbranchid"];
			$contractharvestdetail1->chequenumber = $_POST["chequenumber"];
			$ret = $contractharvestdetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Harvest Detail is Deleted Successfully</span></br>';
				echo '<a href="../data/contractharvestdetail_list.php?contractid='.fnEncrypt($contractharvestdetail1->contractid).'&contractharvestdetailid='.fnEncrypt($contractharvestdetail1->contractharvestdetailid).'">Contract Harvest Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractharvestdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contractharvestdetail1->contractid = $_POST["contractid"];
			echo '<a href="../data/contractharvestdetail_list.php?contractid='.fnEncrypt($contractharvestdetail1->contractid).'&contractharvestdetailid='.fnEncrypt($contractharvestdetail1->contractharvestdetailid).'">Contract Harvest Detail List</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
	function contractcategoryid(&$connection,$contractid)
    {
        $query = "select c.contractcategoryid from contract c where c.active=1 
        and c.contractid=".$contractid;
		$result = oci_parse($connection, $query);
		$r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['CONTRACTCATEGORYID'];
        }
        else
        {
            return 0;
        }
    }
?>