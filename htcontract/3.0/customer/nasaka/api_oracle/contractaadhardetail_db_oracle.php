<?php
require_once("../api_base/formbase.php");
class contractaadhardetail extends swappform
{	
	public $contractaadhardetailid;
	public $contractid;
	public $contractreferencecategoryid;
	public $contractreferencedetailid;
    public $aadharnumber;
	public $aadharverification;
	//information
	

	public function __construct(&$connection)
	{
		parent::__construct($connection);
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->aadharnumber,'Aadhar Number');
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		
		if ($this->contractreferencecategoryid == 254156358)
		{
			$query = "select s.aadharnumber from contractharvestdetail h, servicecontractor s where h.active=1 and s.active=1 and h.servicecontractorid=s.servicecontractorid and contractid=".$this->contractid." and h.contractharvestdetailid=".$this->contractreferencedetailid;
		}
		elseif ($this->contractreferencecategoryid == 584251658)
		{
			$query = "select s.aadharnumber from contracttransportdetail h, servicecontractor s where h.active=1 and s.active=1 and h.servicecontractorid=s.servicecontractorid and contractid=".$this->contractid." and h.contractharvestdetailid=".$this->contractreferencedetailid;
		}
		elseif ($this->contractreferencecategoryid == 753621495)
		{
			$query = "select s.aadharnumber from contractguarantordetail h, servicecontractor s where h.active=1 and s.active=1 and h.servicecontractorid=s.servicecontractorid and contractid=".$this->contractid." and h.contractharvestdetailid=".$this->contractreferencedetailid;
		}

		//$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		$result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			if ($row['AADHARNUMBER'] == $this->aadharnumber)
			{
				$this->invalidid=0;
				$this->invalidmessagetext='Validated';
			}
			else
			{
				$this->invalidid = -201;
				$this->invalidmessagetext='Invalid Aadhar Card';
			}
		}
		else
		{
			$this->invalidid = -201;
			$this->invalidmessagetext='Invalid Aadhar Card';
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
		if ($this->contractaadhardetailid == '')
		{
			$query = "select nvl(max(contractaadhardetailid),0)+1 as contractaadhardetailid from contractaadhardetail";
			//$result = mysqli_query($this->connection, "select nvl(max(contractaadhardetailid),0)+1 as contractaadhardetailid from contractaadhardetail");
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->contractaadhardetailid = $row["CONTRACTAADHARDETAILID"];
		}
		$this->aadharnumber = $this->scan($this->aadharnumber);
		$query = "insert into contractaadhardetail(contractaadhardetailid,contractid,contractreferencecategoryid,contractreferencedetailid,aadharnumber,active,cruserid,crdatetime) values ($this->contractaadhardetailid,$this->contractid,$this->contractreferencecategoryid,$this->contractreferencedetailid,$this->aadharnumber,1,".$_SESSION["usersid"].",'".currentdatetime()."')";
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

	function scan($aadhar)
	{
		$xmlpos = strpos($aadhar,'xml');
		
		if ($xmlpos ==false)
		{
			$uid = $aadhar;
		}
		else
		{
			$xml2 = $aadhar;
			$xmlpos = strpos($xml2,'uid=');
			if ($xmlpos >=0)
			{
				$uid = substr($xml2,$xmlpos+5,12);
			}
			else
			{
				$uid = '';
			}
		}
		return $uid;
	}

	public function display()
	{
		$this->dataoperationmode = operationmode::Select;
		$cond='';
		if ($cond!='')
		{
			$query = "select f.* from contractaadhardetail f where f.active=1 and ".$cond;
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			return $result;
		}
		else
		{
			$query = "select f.* from contractaadhardetail f where f.active=1 limit 100";
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
    	$query = "update contractaadhardetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractaadhardetailid=".$this->contractaadhardetailid;
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
		$query = "update contractaadhardetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractaadhardetailid=".$this->contractaadhardetailid;
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

	public function fetch($contractaadhardetailid)
	{
		$this->dataoperationmode = operationmode::Select;
		$query = "select c.* from contractaadhardetail c where c.active=1 and c.contractaadhardetailid=".$contractaadhardetailid;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		if ($row = @oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->contractid = $row['CONTRACTID'];
			$this->contractreferencecategoryid = $row['CONTRACTREFERENCECATEGORYID'];
			$this->aadharnumber = $row['AADHARNUMBER'];
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>