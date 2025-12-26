<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contracttransportdetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractcategoryid = contractcategoryid($connection,$_POST["contractid"]);
	$contracttransportdetail1 = new contracttransportdetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contracttransportdetail1->contractid = $_POST["contractid"];
			$contracttransportdetail1->servicecontractorid = $_POST["servicecontractorid"];
			$contracttransportdetail1->transportationvehicleid = $_POST["transportationvehicleid"];
			$contracttransportdetail1->vehiclenumber = $_POST["vehiclenumber"];
			if ($contractcategoryid == 521478963)
			{
				$contracttransportdetail1->rtopassingdatetime = $_POST["rtopassingdatetime"];
				$contracttransportdetail1->insurancepaiddatetime = $_POST["insurancepaiddatetime"];
				$contracttransportdetail1->bankbranchid = $_POST["bankbranchid"];
				$contracttransportdetail1->chequenumber = $_POST["chequenumber"];
				$contracttransportdetail1->isrcattached = $_POST["isrcattached"];
				$contracttransportdetail1->istcattached = $_POST["istcattached"];
				$contracttransportdetail1->vehiclemfgid = $_POST["vehiclemfgid"];
			}
			else
			{
				$contracttransportdetail1->vehiclemfgid = $_POST["vehiclemfgid"];
			}
			$ret = $contracttransportdetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Transport is added successfully</span></br>';
				echo '<a href="../data/contracttransportdetail_list.php?contractid='.fnEncrypt($contracttransportdetail1->contractid).'&contracttransportdetailid='.fnEncrypt($contracttransportdetail1->contracttransportdetailid).'">Contract Transport Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contracttransportdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contracttransportdetail1->contracttransportdetailid = $_POST["contracttransportdetailid"];
			$contracttransportdetail1->contractid = $_POST["contractid"];
			$contracttransportdetail1->servicecontractorid = $_POST["servicecontractorid"];
			$contracttransportdetail1->transportationvehicleid = $_POST["transportationvehicleid"];
			$contracttransportdetail1->vehiclenumber = $_POST["vehiclenumber"];
			if ($contractcategoryid == 521478963)
			{
				$contracttransportdetail1->rtopassingdatetime = $_POST["rtopassingdatetime"];
				$contracttransportdetail1->insurancepaiddatetime = $_POST["insurancepaiddatetime"];
				$contracttransportdetail1->bankbranchid = $_POST["bankbranchid"];
				$contracttransportdetail1->chequenumber = $_POST["chequenumber"];
				$contracttransportdetail1->isrcattached = $_POST["isrcattached"];
				$contracttransportdetail1->istcattached = $_POST["istcattached"];
				$contracttransportdetail1->vehiclemfgid = $_POST["vehiclemfgid"];
			}
			else
			{
				$contracttransportdetail1->vehiclemfgid = $_POST["vehiclemfgid"];
			}
			$ret = $contracttransportdetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Transport Detail is Updated successfully</span></br>';	
				echo '<a href="../data/contracttransportdetail_list.php?contractid='.fnEncrypt($contracttransportdetail1->contractid).'&contracttransportdetailid='.fnEncrypt($contracttransportdetail1->contracttransportdetailid).'">Contract Transport Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contracttransportdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contracttransportdetail1->contracttransportdetailid = $_POST["contracttransportdetailid"];
			$contracttransportdetail1->contractid = $_POST["contractid"];
			$contracttransportdetail1->servicecontractorid = $_POST["servicecontractorid"];
			$contracttransportdetail1->transportationvehicleid = $_POST["transportationvehicleid"];
			$contracttransportdetail1->vehiclenumber = $_POST["vehiclenumber"];
			if ($contractcategoryid == 521478963)
			{
				$contracttransportdetail1->rtopassingdatetime = $_POST["rtopassingdatetime"];
				$contracttransportdetail1->insurancepaiddatetime = $_POST["insurancepaiddatetime"];
				$contracttransportdetail1->bankbranchid = $_POST["bankbranchid"];
				$contracttransportdetail1->chequenumber = $_POST["chequenumber"];
				$contracttransportdetail1->isrcattached = $_POST["isrcattached"];
				$contracttransportdetail1->istcattached = $_POST["istcattached"];
				$contracttransportdetail1->vehiclemfgid = $_POST["vehiclemfgid"];
			}
			$result1 = $contracttransportdetail1->display();
			if ($contracttransportdetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../data/contracttransportdetail.php?contractid='.fnEncrypt($contracttransportdetail1->contractid).'&contracttransportdetailid='.fnEncrypt($row1['CONTRACTTRANSPORTDETAILID']).'&flag='.fnencrypt('Display').'">FIELD PLOT LOCATION</BR>';
					}
					else
					{
						echo '<a href="../data/contracttransportdetail.php?contractid='.fnEncrypt($contracttransportdetail1->contractid).'&contracttransportdetailid='.fnEncrypt($row1['CONTRACTTRANSPORTDETAILID']).'&flag='.fnencrypt('Display').'">जमिनीचे क्षेत्र</BR>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contracttransportdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contracttransportdetail1->contracttransportdetailid = $_POST["contracttransportdetailid"];
			$contracttransportdetail1->contractid = $_POST["contractid"];
			$contracttransportdetail1->servicecontractorid = $_POST["servicecontractorid"];
			$contracttransportdetail1->transportationvehicleid = $_POST["transportationvehicleid"];
			$contracttransportdetail1->vehiclenumber = $_POST["vehiclenumber"];
			if ($contractcategoryid == 521478963)
			{
				$contracttransportdetail1->rtopassingdatetime = $_POST["rtopassingdatetime"];
				$contracttransportdetail1->insurancepaiddatetime = $_POST["insurancepaiddatetime"];
				$contracttransportdetail1->bankbranchid = $_POST["bankbranchid"];
				$contracttransportdetail1->chequenumber = $_POST["chequenumber"];
				$contracttransportdetail1->isrcattached = $_POST["isrcattached"];
				$contracttransportdetail1->istcattached = $_POST["istcattached"];
				$contracttransportdetail1->vehiclemfgid = $_POST["vehiclemfgid"];
			}
			$ret = $contracttransportdetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Transport Detail is Deleted Successfully</span></br>';
				echo '<a href="../data/contracttransportdetail_list.php?contractid='.fnEncrypt($contracttransportdetail1->contractid).'&contracttransportdetailid='.fnEncrypt($contracttransportdetail1->contracttransportdetailid).'">Contract Transport Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contracttransportdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contracttransportdetail1->contractid = $_POST["contractid"];
			echo '<a href="../data/contracttransportdetail_list.php?contractid='.fnEncrypt($contracttransportdetail1->contractid).'&contracttransportdetailid='.fnEncrypt($contracttransportdetail1->contracttransportdetailid).'">Contract Transport Detail List</a></br>';
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