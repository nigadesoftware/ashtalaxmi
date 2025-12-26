<?php
	//require("../info/phpsqlajax_dbinfo.php");
	require("../info/phpgetloginview.php");
	require("../info/ncryptdcrypt.php");
	require("../info/rawmaterialroutine.php");
	include("../api_oracle/contractreceiptdetail_db_oracle.php");
	$connection = rawmaterial_connection();
	$contractreceiptdetail1 = new contractreceiptdetail($connection);
	switch ($_POST['btn'])
	{
		case 'Add':
			$contractreceiptdetail1->contractid = $_POST["contractid"];
			$contractreceiptdetail1->receiptcategoryid = $_POST["receiptcategoryid"];
			$contractreceiptdetail1->receiptdatetime = $_POST["receiptdatetime"];
			$contractreceiptdetail1->bankbranchid = $_POST["bankbranchid"];
			$contractreceiptdetail1->chequenumber = $_POST["chequenumber"];
			$contractreceiptdetail1->chequedatetime = $_POST["chequedatetime"];
            $contractreceiptdetail1->chequeamount = $_POST["chequeamount"];
			$ret = $contractreceiptdetail1->insert();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Receipt is added successfully</span></br>';
				echo '<a href="../data/contractreceiptdetail_list.php?contractid='.fnEncrypt($contractreceiptdetail1->contractid).'&contractreceiptdetailid='.fnEncrypt($contractreceiptdetail1->contractreceiptdetailid).'">Contract Receipt Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractreceiptdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Change':
			$contractreceiptdetail1->contractreceiptdetailid = $_POST["contractreceiptdetailid"];
			$contractreceiptdetail1->contractid = $_POST["contractid"];
			$contractreceiptdetail1->receiptcategoryid = $_POST["receiptcategoryid"];
			$contractreceiptdetail1->receiptdatetime = $_POST["receiptdatetime"];
			$contractreceiptdetail1->bankbranchid = $_POST["bankbranchid"];
			$contractreceiptdetail1->chequenumber = $_POST["chequenumber"];
			$contractreceiptdetail1->chequedatetime = $_POST["chequedatetime"];
            $contractreceiptdetail1->chequeamount = $_POST["chequeamount"];
			$ret = $contractreceiptdetail1->update();
			if ($ret==1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Receipt Detail is Updated successfully</span></br>';	
				echo '<a href="../data/contractreceiptdetail_list.php?contractid='.fnEncrypt($contractreceiptdetail1->contractid).'&contractreceiptdetailid='.fnEncrypt($contractreceiptdetail1->contractreceiptdetailid).'">Contract Receipt Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractreceiptdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Display':
			$contractreceiptdetail1->contractreceiptdetailid = $_POST["contractreceiptdetailid"];
			$contractreceiptdetail1->contractid = $_POST["contractid"];
			$contractreceiptdetail1->receiptcategoryid = $_POST["receiptcategoryid"];
			$contractreceiptdetail1->receiptdatetime = $_POST["receiptdatetime"];
			$contractreceiptdetail1->bankbranchid = $_POST["bankbranchid"];
			$contractreceiptdetail1->chequenumber = $_POST["chequenumber"];
			$contractreceiptdetail1->chequedatetime = $_POST["chequedatetime"];
            $contractreceiptdetail1->chequeamount = $_POST["chequeamount"];
			$result1 = $contractreceiptdetail1->display();
			if ($contractreceiptdetail1->Get_invalidid()==0)
			{
				while ($row1 = oci_fetch_array($result1))
				{
					if ($_SESSION['lng']=='English')
					{
						echo '<a href="../data/contractreceiptdetail.php?contractid='.fnEncrypt($contractreceiptdetail1->contractid).'&contractreceiptdetailid='.fnEncrypt($row1['CONTRACTRECEIPTDETAILID']).'&flag='.fnencrypt('Display').'">RECEIPT DETAIL</BR>';
					}
					else
					{
						echo '<a href="../data/contractreceiptdetail.php?contractid='.fnEncrypt($contractreceiptdetail1->contractid).'&contractreceiptdetailid='.fnEncrypt($row1['CONTRACTRECEIPTDETAILID']).'&flag='.fnencrypt('Display').'">जमा पावती माहिती</BR>';
					}
				}	
			}
			else
			{
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractreceiptdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Delete':
			$contractreceiptdetail1->contractreceiptdetailid = $_POST["contractreceiptdetailid"];
			$contractreceiptdetail1->contractid = $_POST["contractid"];
			$contractreceiptdetail1->receiptcategoryid = $_POST["receiptcategoryid"];
			$contractreceiptdetail1->receiptdatetime = $_POST["receiptdatetime"];
			$contractreceiptdetail1->bankbranchid = $_POST["bankbranchid"];
			$contractreceiptdetail1->chequenumber = $_POST["chequenumber"];
			$contractreceiptdetail1->chequedatetime = $_POST["chequedatetime"];
            $contractreceiptdetail1->chequeamount = $_POST["chequeamount"];
			$ret = $contractreceiptdetail1->delete();
			if ($ret == 1)
			{
				oci_commit($connection);
				echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Contract Receipt Detail is Deleted Successfully</span></br>';
				echo '<a href="../data/contractreceiptdetail_list.php?contractid='.fnEncrypt($contractreceiptdetail1->contractid).'&contractreceiptdetailid='.fnEncrypt($contractreceiptdetail1->contractreceiptdetailid).'">Contract Receipt Detail List</a></br>';
			}
			else
			{
				oci_rollback($connection);
				echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$contractreceiptdetail1->Get_invalidmessagetext().'</span></br>';
			}
			break;
		case 'Reset':
			$contractreceiptdetail1->contractid = $_POST["contractid"];
			echo '<a href="../data/contractreceiptdetail_list.php?contractid='.fnEncrypt($contractreceiptdetail1->contractid).'&contractreceiptdetailid='.fnEncrypt($contractreceiptdetail1->contractreceiptdetailid).'">Contract Receipt Detail List</a></br>';
			break;
		default:
			echo 'Communication Error';
			break;
	}
?>