<?php
require_once("../api_base/formbase.php");
class contracttransporttrailerdetail extends swappform
{	
	public $transactionid;
	public $contracttransporttrailerdetailid;
	public $contracttransportdetailid;
	public $contractid;
	public $trailormfgid;
	public $trailernumber;
	public $rtopassingdatetime;
	public $insurancepaiddatetime;
	public $isrcattached;
	public $istcattached;
	//for information
	public $trailermfgname_eng;
	public $trailermfgname_unicode;
	

	public function __construct(&$connection)
	{
		parent::__construct($connection);
		$this->contracttransporttrailerdetailid = '';
		$this->contracttransportdetailid = '';
		$this->contractid = '';
		$this->trailernumber = '';
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->trailernumber,'Trailer Number');
		/*if (!preg_match("/^[A-Za-z]{2,3}(-\d{2}(-[A-Za-z]{1,2})?)?-\d{3,4}$/i", $this->vehiclenumber))
		{
			$this->invalidid=-202;
			$this->invalidmessagetext='Vehicle Number is Invalid e.g MH-01-AB-1234';
		}*/
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		if ($this->dataoperationmode == operationmode::Insert)
		{
			$query = "select count(*) as cnt from contracttransporttrailerdetail a where a.contractid='".$this->contractid."' and contracttransportdetailid=".$this->contracttransportdetailid." and a.active=1 and a.trailernumber='".$this->trailernumber."'";
		}
		else
		{
			$query = "select count(*) as cnt from contracttransporttrailerdetail a where contracttransporttrailerdetid<>".$this->contracttransporttrailerdetailid." and a.contractid='".$this->contractid."' and contracttransportdetailid=".$this->contracttransportdetailid." and a.active=1 and a.trailernumber='".$this->trailernumber."'";
		}
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
		if ($row["CNT"] > 0)
		{
			$this->invalidid=-201;
			$this->invalidmessagetext='Trailer Number is already exists';
		}
		else
		{
			$this->invalidid=0;
			$this->invalidmessagetext='Validated';
		}
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
		if ($this->rtopassingdatetime!='')
		{
			$this->rtopassingdatetime = DateTime::createFromFormat('d/m/Y',$this->rtopassingdatetime)->format('d-M-Y');	
		}
		if ($this->insurancepaiddatetime!='')
		{
			$this->insurancepaiddatetime = DateTime::createFromFormat('d/m/Y',$this->insurancepaiddatetime)->format('d-M-Y');	
		}
		/* if ($this->transactionid == '')
		{
			$query = "select nvl(max(transactionid),0)+1 as transactionid from contracttransporttrailerdetail";
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->transactionid = $row["TRANSACTIONID"];
		} */
		if ($this->contracttransporttrailerdetailid == '')
		{
			$query = "select nvl(max(contracttransporttrailerdetid),0)+1 as contracttransporttrailerdetid from contracttransporttrailerdetail";
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->contracttransporttrailerdetailid = $row["CONTRACTTRANSPORTTRAILERDETID"];
		}
    	$query = "insert into contracttransporttrailerdetail(transactionid,contracttransporttrailerdetid,contracttransportdetailid,contractid,trailermfgid,trailernumber,rtopassingdatetime,insurancepaiddatetime,isrcattached,istcattached,active,cruserid) values ((select nvl(max(transactionid),0)+1 from contracttransporttrailerdetail),$this->contracttransporttrailerdetailid,$this->contracttransportdetailid,$this->contractid,".$this->invl($this->trailermfgid,true).",'$this->trailernumber',".$this->invl($this->rtopassingdatetime,false).",".$this->invl($this->insurancepaiddatetime,false).",".$this->isrcattached.",".$this->istcattached.",1,".$_SESSION["usersid"].")";
    	//echo $query;
    	$result = oci_parse($this->connection, $query); if (oci_execute($result,OCI_NO_AUTO_COMMIT))
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
			$query = "select f.* from contracttransportdetail f where f.active=1 and ".$cond;
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			return $result;
		}
		else
		{
			$query = "select f.* from contracttransportdetail f where f.active=1 limit 100";
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
    	$query = "update contracttransporttrailerdetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contracttransporttrailerdetid=".$this->contracttransporttrailerdetailid;
    	$result = oci_parse($this->connection, $query); if (oci_execute($result,OCI_NO_AUTO_COMMIT))
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
		$query = "update contracttransporttrailerdetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contracttransporttrailerdetid=".$this->contracttransporttrailerdetailid;
    	$result = oci_parse($this->connection, $query); if (oci_execute($result,OCI_NO_AUTO_COMMIT))
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

	public function fetch($contracttransporttrailerdetid)
	{
		$this->dataoperationmode = operationmode::Select;
		$query = "select t.*,name_eng,name_unicode from contracttransporttrailerdetail t,namedetail n where t.active=1 and n.active=1 and t.trailermfgid=n.namedetailid and contracttransporttrailerdetid=".$contracttransporttrailerdetid;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->contracttransporttrailerdetid = $row['CONTRACTTRANSPORTTRAILERDETID'];
			$this->contracttransportdetailid = $row['CONTRACTTRANSPORTDETAILID'];
			$this->contractid = $row["CONTRACTID"];
			$this->trailernumber = $row["TRAILERNUMBER"];
			if (isset($row['RTOPASSINGDATETIME']))
			{
				$this->rtopassingdatetime = date('d/m/Y',strtotime($row['RTOPASSINGDATETIME']));
			}
			if (isset($row['INSURANCEPAIDDATETIME']))
			{
				$this->insurancepaiddatetime = date('d/m/Y',strtotime($row['INSURANCEPAIDDATETIME']));
			}
			$this->isrcattached = $row['ISRCATTACHED'];
			$this->istcattached = $row['ISTCATTACHED'];
			$this->trailermfgname_eng = $row['TRAILERMFGNAME_ENG'];
			$this->trailermfgname_unicode = $row['TRAILERMFGNAME_UNICODE'];
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>