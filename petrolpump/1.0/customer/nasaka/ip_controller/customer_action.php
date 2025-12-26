<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/routine.php");
	include("../ip_model/customer_db_oracle.php");
	$connection = petrolpump_connection();
	$customer1 = new customer($connection);
	$customer1->customernameuni = $_POST["customernameuni"];
    $customer1->customernameeng = $_POST["customernameeng"];
    $customer1->vehiclenumber = $_POST["vehiclenumber"];
    $customer1->refcode = $_POST["refcode"];
    $customer1->customertypecode = $_POST["customertypecode"];


    switch ($_POST['btn'])
	{
        case 'Add':
			$ret = $customer1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Customer is added successfully</span></br>';
				echo '<a href="../ip_view/customer.php?customercode='.fnEncrypt($customer1->customercode).'">Add/Display customer</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$customer1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$customer1->customercode = $_POST["customercode"];
			$ret = $customer1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Customer is Updated successfully</span></br>';	
				echo '<a href="../ip_view/customer.php?customercode='.fnEncrypt($customer1->customercode).'">Add/Display customer</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$customer1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$customer1->customercode = $_POST["customercode"];
			$result1 = $customer1->display();
			if ($customer1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../ip_view/customer.php?customercode='.fnEncrypt($row1['CUSTOMERCODE']).'&flag='.fnEncrypt('Display').'">'.$row1['CUSTOMERNAMEENG'].'</br>';
					}
					else
					{
						echo '<a href="../ip_view/customer.php?customercode='.fnEncrypt($row1['CUSTOMERCODE']).'&flag='.fnEncrypt('Display').'">'.$row1['CUSTOMERNAMEUNI'].'</br>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$customer1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$customer1->customercode = $_POST["customercode"];
			$ret = $customer1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Customer is Deleted Successfully</span></br>';
				echo '<a href="../ip_view/customer.php">Add/Display Customer Detail</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$customer1->Get_invalidmessagetext().'</span></br>';
				echo '<a href="../ip_view/personnamedetail.php">Add/Query Customer Detail</a></br>';
			}
			break;
		case 'Reset':
			echo '<a href="../ip_view/customer.php">Add/Display Customer</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>