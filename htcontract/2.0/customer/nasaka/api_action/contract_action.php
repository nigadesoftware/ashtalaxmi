<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contract_db_oracle.php");
	$connection = rawmaterial_connection();
	$contract1 = new contract($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contract1->seasonid = $_POST["seasonid"];
			$contract1->sugarfactoryid = $_POST["sugarfactoryid"];
			$contract1->servicecontractorid = $_POST["servicecontractorid"];
			$contract1->contractcategoryid = $_POST["contractcategoryid"];
			//$contract1->applicationnumber = $_POST["applicationnumber"];
			$contract1->applicationdatetime = $_POST["applicationdatetime"];
			//$contract1->contractnumber = $_POST["contractnumber"];
			//$contract1->contractnumber_prefixsuffix = $_POST["contractnumber_prefixsuffix"];
			$contract1->contractdatetime = $_POST["contractdatetime"];
			$contract1->casteid = $_POST["casteid"];
			$contract1->age = $_POST["age"];
			$contract1->entityglobalgroupid = $_POST["entityglobalgroupid"];
			$contract1->finalreportperiodid = $_POST["finalreportperiodid"];
			$contract1->fieldarea = $_POST["fieldarea"];
			$contract1->address = $_POST["address"];
			$contract1->isadvance = $_POST["isadvance"];
			$contract1->contractorzonecode = $_POST["contractorzonecode"];
			$ret = $contract1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract is added successfully</span></br>';
				echo '<a href="../data/contract.php?contractid='.fnEncrypt($contract1->contractid).'">Add/Display Contract</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contract1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contract1->contractid = $_POST["contractid"];
			$contract1->seasonid = $_POST["seasonid"];
			$contract1->sugarfactoryid = $_POST["sugarfactoryid"];
			$contract1->servicecontractorid = $_POST["servicecontractorid"];
			$contract1->contractcategoryid = $_POST["contractcategoryid"];
			$contract1->applicationnumber = $_POST["applicationnumber"];
			$contract1->applicationdatetime = $_POST["applicationdatetime"];
			$contract1->contractnumber = $_POST["contractnumber"];
			$contract1->contractnumber_prefixsuffix = $_POST["contractnumber_prefixsuffix"];
			$contract1->contractdatetime = $_POST["contractdatetime"];
			$contract1->casteid = $_POST["casteid"];
			$contract1->age = $_POST["age"];
			$contract1->entityglobalgroupid = $_POST["entityglobalgroupid"];
			$contract1->finalreportperiodid = $_POST["finalreportperiodid"];
			$contract1->fieldarea = $_POST["fieldarea"];
			$contract1->address = $_POST["address"];
			$contract1->isadvance = $_POST["isadvance"];
			$contract1->contractorzonecode = $_POST["contractorzonecode"];
			$ret = $contract1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract is Updated successfully</span></br>';	
				echo '<a href="../data/contract.php?contractid='.fnEncrypt($contract1->contractid).'">Add/Display Contract</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contract1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contract1->contractid = $_POST["contractid"];
			$contract1->seasonid = $_POST["seasonid"];
			$contract1->sugarfactoryid = $_POST["sugarfactoryid"];
			$contract1->servicecontractorid = $_POST["servicecontractorid"];
			$contract1->contractcategoryid = $_POST["contractcategoryid"];
			$contract1->applicationnumber = $_POST["applicationnumber"];
			$contract1->applicationdatetime = $_POST["applicationdatetime"];
			$contract1->contractnumber = $_POST["contractnumber"];
			$contract1->contractnumber_prefixsuffix = $_POST["contractnumber_prefixsuffix"];
			$contract1->contractdatetime = $_POST["contractdatetime"];
			$contract1->casteid = $_POST["casteid"];
			$contract1->age = $_POST["age"];
			$contract1->entityglobalgroupid = $_POST["entityglobalgroupid"];
			$contract1->finalreportperiodid = $_POST["finalreportperiodid"];
			$contract1->fieldarea = $_POST["fieldarea"];
			$contract1->address = $_POST["address"];
			$contract1->isadvance = $_POST["isadvance"];
			$contract1->contractorzonecode = $_POST["contractorzonecode"];
			$result1 = $contract1->display();
			if ($contract1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../data/contract.php?contractid='.fnEncrypt($row1['CONTRACTID']).'&flag='.fnEncrypt('Display').'">'.$row1['NAME_ENG'].' (Season:'.$row1['SEASONNAME_ENG'].')</br>';
					}
					else
					{
						echo '<a href="../data/contract.php?contractid='.fnEncrypt($row1['CONTRACTID']).'&flag='.fnEncrypt('Display').'">'.$row1['NAME_UNICODE'].' (हंगाम:'.$row1['SEASONNAME_UNICODE'].')</br>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contract1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contract1->contractid = $_POST["contractid"];
			$contract1->seasonid = $_POST["seasonid"];
			$contract1->sugarfactoryid = $_POST["sugarfactoryid"];
			$contract1->servicecontractorid = $_POST["servicecontractorid"];
			$contract1->contractcategoryid = $_POST["contractcategoryid"];
			$contract1->applicationnumber = $_POST["applicationnumber"];
			$contract1->applicationdatetime = $_POST["applicationdatetime"];
			$contract1->contractnumber = $_POST["contractnumber"];
			$contract1->contractnumber_prefixsuffix = $_POST["contractnumber_prefixsuffix"];
			$contract1->contractdatetime = $_POST["contractdatetime"];
			$contract1->casteid = $_POST["casteid"];
			$contract1->age = $_POST["age"];
			$contract1->entityglobalgroupid = $_POST["entityglobalgroupid"];
			$contract1->finalreportperiodid = $_POST["finalreportperiodid"];
			$contract1->fieldarea = $_POST["fieldarea"];
			$contract1->address = $_POST["address"];
			$contract1->isadvance = $_POST["isadvance"];
			$contract1->contractorzonecode = $_POST["contractorzonecode"];
			$ret = $contract1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract is Deleted Successfully</span></br>';
				echo '<a href="../data/contract.php">Add/Display Contract Detail</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contract1->Get_invalidmessagetext().'</span></br>';
				echo '<a href="../data/personnamedetail.php">Add/Query Contract Detail</a></br>';
			}
			break;
		case 'Reset':
			echo '<a href="../data/contract.php">Add/Display Contract</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>