<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/routine.php");
	include("../ip_model/item_db_oracle.php");
	$connection = petrolpump_connection();
	$item1 = new item($connection);
	$item1->itemnameuni = $_POST["itemnameuni"];
    $item1->itemnameeng = $_POST["itemnameeng"];
    $item1->unit = $_POST["unit"];
    switch ($_POST['btn'])
	{
        case 'Add':
			$ret = $item1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Item is added successfully</span></br>';
				echo '<a href="../ip_view/Item.php?itemcode='.fnEncrypt($item1->Itemcode).'">Add/Display Item</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$item1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$item1->itemcode = $_POST["itemcode"];
			$ret = $item1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Item is Updated successfully</span></br>';	
				echo '<a href="../ip_view/Item.php?itemcode='.fnEncrypt($item1->itemcode).'">Add/Display Item</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$item1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$item1->itemcode = $_POST["itemcode"];
			$result1 = $item1->display();
			if ($item1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../ip_view/Item.php?itemcode='.fnEncrypt($row1['ITEMCODE']).'&flag='.fnEncrypt('Display').'">'.$row1['ITEMNAMEENG'].' ('.$row1['UNIT'].')</br>';
					}
					else
					{
						echo '<a href="../ip_view/Item.php?itemcode='.fnEncrypt($row1['ITEMCODE']).'&flag='.fnEncrypt('Display').'">'.$row1['ITEMNAMEUNI'].' ('.$row1['UNIT'].')</br>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$item1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$item1->itemcode = $_POST["itemcode"];
			$ret = $item1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Item is Deleted Successfully</span></br>';
				echo '<a href="../ip_view/Item.php">Add/Display Item Detail</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$item1->Get_invalidmessagetext().'</span></br>';
				echo '<a href="../ip_view/personnamedetail.php">Add/Query Item Detail</a></br>';
			}
			break;
		case 'Reset':
			echo '<a href="../ip_view/Item.php">Add/Display Item</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>