<?php
require_once("../api_base/formbase.php");
class contractharvestdetail extends swappform
{	
	public $contractharvestdetailid;
	public $contractid;
	public $servicecontractorid;
	public $noofvehicles;
	public $noofharvesterlabour;
	public $transportationuptovehicleid;
	public $bankbranchid;
	public $chequenumber;
	//information
	public $transportationuptovehiclename_eng;
	public $transportationuptovehiclename_unicode;
	public $bankbranchname_eng;
	public $bankbranchname_unicode;
	
	public function __construct(&$connection)
	{
		parent::__construct($connection);
		$this->contractharvestdetailid = '';
		$this->servicecontractorid = '';
		$this->contractid = '';
		$this->noofharvesterlabour = '';
		$this->transportationuptovehicleid = '';
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->servicecontractorid,'Service Contractor');
		$this->checkrequired($this->noofharvesterlabour,'No. of Harvester Labour');
		$this->checkrequired($this->transportationuptovehicleid,'Transportation Upto Vehicle');
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		$this->invalidid=0;
		$this->invalidmessagetext='Validated';
		$this->end_validation();
		return $this->invalidid;
	}

	public function insert()
	{
		$this->dataoperationmode = operationmode::Insert;
		if ($this->entryvalidation() <> 0)
		{
			return 0;
			exit;
		}
		elseif ($this->datavalidation() <> 0)
		{
			return 0;
			exit;
		}
		if ($this->contractharvestdetailid == '')
		{
			$query = "select nvl(max(contractharvestdetailid),0)+1 as contractharvestdetailid from contractharvestdetail";
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->contractharvestdetailid = $row["CONTRACTHARVESTDETAILID"];
		}
		$query = "insert into contractharvestdetail(transactionid,contractharvestdetailid,contractid,servicecontractorid,noofvehicles,noofharvesterlabour,transportationuptovehicleid,bankbranchid,chequenumber,active,cruserid) values ((select nvl(max(transactionid),0)+1 from contractharvestdetail),$this->contractharvestdetailid,$this->contractid,$this->servicecontractorid,".$this->invl($this->noofvehicles,true).",$this->noofharvesterlabour,$this->transportationuptovehicleid,".$this->invl($this->bankbranchid,true).",".$this->invl($this->chequenumber,false).",1,".$_SESSION["usersid"].")";
		//echo $query;
		$result = oci_parse($this->connection, $query);
		if (oci_execute($result,OCI_NO_AUTO_COMMIT))
		{
			return 1;
			exit;
		}
		else
		{
			return 0;
			$this->invalidid=-200;
			$this->invalidmessagetext='Communication Error';
			exit;
		}
	}

	public function display()
	{
		$this->dataoperationmode = operationmode::Select;
		$cond='';
		if ($cond!='')
		{
			$query = "select f.* from contractharvestdetail f where f.active=1 and ".$cond;
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			return $result;
		}
		else
		{
			$query = "select f.* from contractharvestdetail f where f.active=1 limit 100";
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			return $result;
		}
	}

	public function update()
	{
		$this->dataoperationmode =operationmode::Update;
		if ($this->entryvalidation() <> 0)
		{
			return 0;
			exit;
		}
		elseif ($this->datavalidation() <> 0)
		{
			return 0;
			exit;
		}
    	$query = "update contractharvestdetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractharvestdetailid=".$this->contractharvestdetailid;
		$result = oci_parse($this->connection, $query);
		if (oci_execute($result,OCI_NO_AUTO_COMMIT))
		{
    		$ret1 = $this->insert();
	    	if ($ret1 == 1)
			{
	    		return 1;
				exit;
			}
			else
			{
    			return 0;
				exit;
			}
	    }
		else
		{
    		return 0;
			exit;
		}
	}

	public function delete()
	{
		$this->dataoperationmode = operationmode::Delete;
		if ($this->entryvalidation() <> 0)
		{
			return 0;
			exit;
		}
		$query = "update contractharvestdetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractharvestdetailid=".$this->contractharvestdetailid;
    	$result = oci_parse($this->connection, $query);
		if (oci_execute($result,OCI_NO_AUTO_COMMIT))
		{
	   		return 1;
			exit;
	    }
		else
		{
    		return 0;
			exit;
		}
	}

	public function fetch($contractharvestdetailid)
	{
		$this->dataoperationmode = operationmode::Select;
		$query = "select c.*,name_eng as transportationuptovehname_eng,name_unicode as transportationuptovehname_uni from contractharvestdetail c,namedetail n where c.active=1 and n.active=1 and c.transportationuptovehicleid=n.namedetailid and c.contractharvestdetailid=".$contractharvestdetailid;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->contractid = $row['CONTRACTID'];
			$this->servicecontractorid = $row['SERVICECONTRACTORID'];
			$this->noofvehicles = $row['NOOFVEHICLES'];
			$this->noofharvesterlabour = $row['NOOFHARVESTERLABOUR'];
			$this->transportationuptovehicleid = $row['TRANSPORTATIONUPTOVEHICLEID'];
			$this->chequenumber = $row['CHEQUENUMBER'];
			$this->transportationuptovehiclename_eng = $row['TRANSPORTATIONUPTOVEHNAME_ENG'];
			$this->transportationuptovehiclename_unicode = $row['TRANSPORTATIONUPTOVEHNAME_UNI'];
			$query1 = "select h.name_eng as bankbranchname_eng,
			h.name_unicode as bankbranchname_unicode
			from contract c,servicecontractor t,
			contractharvestdetail r,bankbranch h 
			where c.active=1 and t.active=1 
			and r.active=1 
			and c.servicecontractorid=t.servicecontractorid 
			and c.contractid=r.contractid 
			and r.bankbranchid=h.bankbranchid 
			and r.contractharvestdetailid=".$contractharvestdetailid;
			$result1 = oci_parse($this->connection, $query1);             $r = oci_execute($result1);
			if ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
			{
				$this->bankbranchname_eng = $row1['BANKBRANCHNAME_ENG'];
				$this->bankbranchname_unicode = $row1['BANKBRANCHNAME_UNICODE'];
			}
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>