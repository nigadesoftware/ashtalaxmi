<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/routine.php");
	include("../ip_model/masterbase_db_oracle.php");

	function labelname(&$connection,$namecategoryid,$lng)
    {
        $query = "select n.* from namecategory n where  namecategoryid=".$namecategoryid;
        $result=oci_parse($connection, $query);
		$r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS)) 
        {
            if ($lng==0)
            {
                return $row['NAMECATEGORYNAME_ENG'];
            }
            else
            {
                return $row['NAMECATEGORYNAME'];
            }
        }
    }
	$connection=swappdb_connection();
	$masterbase1 = new masterbase($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$masterbase1->namedetailid = $_POST["namedetailid"];
			$masterbase1->name_eng = $_POST["name_eng"];
			$masterbase1->name_unicode = $_POST["name_unicode"];
			$masterbase1->namecategoryid = $_POST["namecategoryid"];
			$labelname_eng = labelname($connection,$masterbase1->namecategoryid,0);
			$ret = $masterbase1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">'.$labelname_eng.' is added successfully</span></br>';
				echo '<a href="../ip_view/masterbase.php?namecategoryid='.fnEncrypt($masterbase1->namecategoryid).'">Add/Display '.$labelname_eng.'</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$masterbase1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$masterbase1->namedetailid = $_POST["namedetailid"];
			$masterbase1->name_eng = $_POST["name_eng"];
			$masterbase1->name_unicode = $_POST["name_unicode"];
			$masterbase1->namecategoryid = $_POST["namecategoryid"];
			$labelname_eng = labelname($connection,$masterbase1->namecategoryid,0);
			$ret = $masterbase1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">'.labelname($connection,$masterbase1->namecategoryid,0).' is Updated successfully</span></br>';	
				echo '<a href="../ip_view/masterbase.php?namecategoryid='.fnEncrypt($masterbase1->namecategoryid).'">Add/Display '.$labelname_eng.'</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$masterbase1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$masterbase1->namedetailid = $_POST["namedetailid"];
			$masterbase1->name_eng = $_POST["name_eng"];
			$masterbase1->name_unicode = $_POST["name_unicode"];
			$masterbase1->namecategoryid = $_POST["namecategoryid"];
			$result1 = $masterbase1->display();
			if ($masterbase1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
				{
					echo '<a href="../ip_view/masterbase.php?namedetailid='.fnEncrypt($row1['NAMEDETAILID']).'&namecategoryid='.fnEncrypt($masterbase1->namecategoryid).'&flag='.fnEncrypt('Display').'">'.$row1['NAME_UNICODE'].'</br>'.$row1['NAME_ENG'].'</br>';
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$masterbase1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$masterbase1->namedetailid = $_POST["namedetailid"];
			$masterbase1->name_eng = $_POST["name_eng"];
			$masterbase1->name_unicode = $_POST["name_unicode"];
			$masterbase1->namecategoryid = $_POST["namecategoryid"];
			$labelname_eng = labelname($connection,$masterbase1->namecategoryid,0);
			$ret = $masterbase1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">'.$labelname_eng.' is Deleted Successfully</span></br>';
				echo '<a href="../ip_view/masterbase.php?namecategoryid='.fnEncrypt($masterbase1->namecategoryid).'">Add/Display '.$labelname_eng.' Detail</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$masterbase1->Get_invalidmessagetext().'</span></br>';
				echo '<a href="../ip_view/personnamedetail.php">Add/Query '.$labelname_eng.' Detail</a></br>';
			}
			break;
		case 'Reset':
			$masterbase1->namecategoryid = $_POST["namecategoryid"];
			echo '<a href="../ip_view/masterbase.php?namecategoryid='.fnEncrypt($masterbase1->namecategoryid).'">Add/Display '.$labelname_eng.' </a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>