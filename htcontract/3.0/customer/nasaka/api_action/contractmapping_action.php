<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contractmapping_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractmapping1 = new contractmapping($connection);

	function servicecontractorname(&$connection,$servicecontractorid,$lng)
    {
       $query = "select n.name_eng,n.name_unicode from servicecontractor s, personnamedetail p,namedetail n where s.active=1 and p.active=1 and n.active=1 and  s.personnamedetailid=p.personnamedetailid and p.namedetailid=n.namedetailid and s.servicecontractorid=".$servicecontractorid;
       //echo $query;
       $result = oci_parse($connection,$query);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($lng==0)
            {
                return $row['name_eng'];
            }
            else
            {
                return $row['name_unicode'];
            }
        } 
    }

	switch ($_POST['btn'])
	{
		case 'Add':
			$contractmapping1->seasonid = $_POST["seasonid"];
			$contractmapping1->sugarfactoryid = $_POST["sugarfactoryid"];
			$contractmapping1->servicecontractorid = $_POST["servicecontractorid"];
			$ret = $contractmapping1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Mapping is added successfully</span></br>';
				echo '<a href="../data/contractmapping.php?contractmappingid='.fnEncrypt($contractmapping1->contractmappingid).'">Add/Display Contract Mapping</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractmapping1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contractmapping1->contractmappingid = $_POST["contractmappingid"];
			$contractmapping1->seasonid = $_POST["seasonid"];
			$contractmapping1->sugarfactoryid = $_POST["sugarfactoryid"];
			$contractmapping1->servicecontractorid = $_POST["servicecontractorid"];
			$ret = $contractmapping1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Mapping is Updated successfully</span></br>';	
				echo '<a href="../data/contractmapping.php?contractmappingid='.fnEncrypt($contractmapping1->contractmappingid).'">Add/Display Contract Mapping</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractmapping1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contractmapping1->contractmappingid = $_POST["contractmappingid"];
			$contractmapping1->seasonid = $_POST["seasonid"];
			$contractmapping1->sugarfactoryid = $_POST["sugarfactoryid"];
			$contractmapping1->servicecontractorid = $_POST["servicecontractorid"];
			$result1 = $contractmapping1->display();
			if ($contractmapping1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../data/contractmapping.php?contractmappingid='.fnEncrypt($row1['CONTRACTMAPPINGID']).'&flag='.fnencrypt('Display').'">'.SERVICECONTRACTORNAME($CONNECTION,$row1['SERVICECONTRACTORID'],0).' (SEASON:'.$row1['SEASONNAME_ENG'].')</BR>';
					}
					else
					{
						echo '<a href="../data/contractmapping.php?contractmappingid='.fnEncrypt($row1['CONTRACTMAPPINGID']).'&flag='.fnencrypt('Display').'">'.SERVICECONTRACTORNAME($CONNECTION,$row1['SERVICECONTRACTORID'],1).' (हंगाम:'.$row1['SEASONNAME_UNICODE'].')</BR>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractmapping1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contractmapping1->contractmappingid = $_POST["contractmappingid"];
			$contractmapping1->seasonid = $_POST["seasonid"];
			$contractmapping1->sugarfactoryid = $_POST["sugarfactoryid"];
			$contractmapping1->servicecontractorid = $_POST["servicecontractorid"];
			$ret = $contractmapping1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Mapping is Deleted Successfully</span></br>';
				echo '<a href="../data/contractmapping.php">Add/Display Contract Mapping</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractmapping1->Get_invalidmessagetext().'</span></br>';
				echo '<a href="../data/personnamedetail.php">Add/Query Contract Mapping</a></br>';
			}
			break;
		case 'Reset':
			echo '<a href="../data/contractmapping.php">Add/Display Contract Mapping</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>