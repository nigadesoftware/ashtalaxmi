<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/routine.php");
	include("../ip_model/supplier_db_oracle.php");
	$connection = petrolpump_connection();
	$supplier1 = new supplier($connection);
	$supplier1->suppliernameuni = $_POST["suppliernameuni"];
    $supplier1->suppliernameeng = $_POST["suppliernameeng"];
    $supplier1->address = $_POST["address"];
    $supplier1->contactnumber = $_POST["contactnumber"];
    $supplier1->emailid = $_POST["emailid"];
    $supplier1->gstnumber = $_POST["gstnumber"];
    $supplier1->vatnumber = $_POST["vatnumber"];

    switch ($_POST['btn'])
	{
        case 'Add':
			$ret = $supplier1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">supplier is added successfully</span></br>';
				//echo '<a href="../ip_view/supplier.php?suppliercode='.fnEncrypt($supplier1->suppliercode).'">Add/Display supplier</a></br>';
				echo '<a href="../ip_view/supplier.php">Add/Display supplier</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$supplier1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$supplier1->suppliercode = $_POST["suppliercode"];
			$ret = $supplier1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">supplier is Updated successfully</span></br>';	
				echo '<a href="../ip_view/supplier.php">Add/Display supplier</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$supplier1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$supplier1->suppliercode = $_POST["suppliercode"];
			$result1 = $supplier1->display();
			if ($supplier1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../ip_view/supplier.php?suppliercode='.fnEncrypt($row1['SUPPLIERCODE']).'&flag='.fnEncrypt('Display').'">'.$row1['SUPPLIERNAMEENG'].')</br>';
					}
					else
					{
						echo '<a href="../ip_view/supplier.php?suppliercode='.fnEncrypt($row1['SUPPLIERCODE']).'&flag='.fnEncrypt('Display').'">'.$row1['SUPPLIERNAMEUNI'].'</br>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$supplier1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$supplier1->suppliercode = $_POST["suppliercode"];
			$ret = $supplier1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">supplier is Deleted Successfully</span></br>';
				echo '<a href="../ip_view/supplier.php">Add/Display supplier Detail</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$supplier1->Get_invalidmessagetext().'</span></br>';
				echo '<a href="../ip_view/personnamedetail.php">Add/Query supplier Detail</a></br>';
			}
			break;
		case 'Reset':
			echo '<a href="../ip_view/supplier.php">Add/Display supplier</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>