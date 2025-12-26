<?php
require_once("../api_base/formbase.php");
class contracttransportdetail extends swappform
{	
	public $contracttransportdetailid;
	public $contractid;
	public $servicecontractorid;
	public $transportationvehicleid;
	public $vehiclenumber;
	public $rtopassingdatetime;
	public $insurancepaiddatetime;
	public $bankbranchid;
	public $chequenumber;
	public $isrcattached;
	public $istcattached;
	public $vehiclemfgid;
	//information properties
	public $transportationvehiclename_eng;
	public $transportationvehiclename_unicode;
	public $bankbranchname_eng;
	public $bankbranchname_unicode;
	public $vehiclemfgname_eng;
	public $vehiclemfgname_unicode;
	
	public function __construct(&$connection)
	{
		parent::__construct($connection);
		$this->contracttransportdetailid = '';
		$this->contractid = '';
		$this->servicecontractorid = '';
		$this->transportationvehicleid = '';
		$this->vehiclenumber = '';
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->servicecontractorid,'Service Contractor');
		$this->checkrequired($this->transportationvehicleid,'Transportation Vehicle');
		if ($this->transportationvehicleid != 248767942)
		{
			$this->checkrequired($this->vehiclenumber,'Vehicle Number');
			if (!preg_match("/^[A-Za-z]{2,3}(-\d{2}(-[A-Za-z]{1,2})?)?-\d{3,4}$/i", $this->vehiclenumber))
			{
				$this->invalidid=-202;
				$this->invalidmessagetext='Vehicle Number is Invalid e.g MH-01-AB-1234';
			}
		}
		
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		if ($this->dataoperationmode == operationmode::Insert)
		{
			$query = "select count(*) as cnt from contracttransportdetail a where a.contractid=".$this->contractid." and a.active=1 and a.vehiclenumber='".$this->vehiclenumber."'";
		}
		else
		{
			$query = "select count(*) as cnt from contracttransportdetail a where a.contracttransportdetailid<>".$this->contracttransportdetailid." and a.contractid=".$this->contractid." and a.active=1 and a.vehiclenumber='".$this->vehiclenumber."'";
		}
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
		if ($row["CNT"] > 0)
		{
			$this->invalidid=-201;
			$this->invalidmessagetext='Contract Vehicle Number is already exists';
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
		if ($this->contracttransportdetailid == '')
		{
			$query = "select nvl(max(contracttransportdetailid),0)+1 as contracttransportdetailid from contracttransportdetail";
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->contracttransportdetailid = $row["CONTRACTTRANSPORTDETAILID"];
		}

		$this->vehiclenumber = strtoupper($this->vehiclenumber);
    	
		$contractcategoryid = $this->contractcategoryid($this->connection,$this->contractid);
		
		if ($contractcategoryid == 521478963)
		{
			if ($this->rtopassingdatetime!='')
			{
				$this->rtopassingdatetime = DateTime::createFromFormat('d/m/Y',$this->rtopassingdatetime)->format('d-M-Y');	
			}
			if ($this->insurancepaiddatetime!='')
			{
				$this->insurancepaiddatetime = DateTime::createFromFormat('d/m/Y',$this->insurancepaiddatetime)->format('d-M-Y');	
			}
			$query = "insert into contracttransportdetail(transactionid,contracttransportdetailid,contractid,servicecontractorid,transportationvehicleid,vehiclemfgid,vehiclenumber,rtopassingdatetime,insurancepaiddatetime,bankbranchid,chequenumber,isrcattached,istcattached,active,cruserid) values ((select nvl(max(transactionid),0)+1 from contracttransportdetail),$this->contracttransportdetailid,$this->contractid,$this->servicecontractorid,$this->transportationvehicleid,".$this->invl($this->vehiclemfgid,true).",'$this->vehiclenumber',".$this->invl($this->rtopassingdatetime,false).",".$this->invl($this->insurancepaiddatetime,false).",".$this->invl($this->bankbranchid,true).",".$this->invl($this->chequenumber,true).",".$this->isrcattached.",".$this->istcattached.",1,".$_SESSION["usersid"].")";
		}
		else
		{
			$query = "insert into contracttransportdetail(transactionid,contracttransportdetailid,contractid,servicecontractorid,transportationvehicleid,vehiclemfgid,vehiclenumber,active,cruserid) values ((select nvl(max(transactionid),0)+1 from contracttransportdetail),$this->contracttransportdetailid,$this->contractid,$this->servicecontractorid,$this->transportationvehicleid,".$this->invl($this->vehiclemfgid,true).","."'$this->vehiclenumber',1,".$_SESSION["usersid"].")";
		}
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
    	$query = "update contracttransportdetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contracttransportdetailid=".$this->contracttransportdetailid;
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
		$query = "update contracttransportdetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contracttransportdetailid=".$this->contracttransportdetailid;
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
	public function trailerlist()
	{
		$query = "select contracttransporttrailerdetid from contracttransporttrailerdetail c where c.active=1 and c.contracttransportdetailid=".$this->contracttransportdetailid;
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$data[] = $row['CONTRACTTRANSPORTTRAILERDETID'];
		}
		return $data;
	}
	public function fetch($contracttransportdetailid)
	{
		$this->dataoperationmode = operationmode::Select;
		$query = "select c.*,n.name_eng as transportationvehiclename_eng,n.name_unicode as transportationvehiclename_uni,m.name_eng as vehiclemfgname_eng,m.name_unicode as vehiclemfgname_uni from contracttransportdetail c,namedetail n, namedetail m where c.active=1 and n.active=1 and m.active=1 and c.transportationvehicleid=n.namedetailid and c.vehiclemfgid=m.namedetailid and contracttransportdetailid=".$contracttransportdetailid;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->contracttransportdetailid = $row['CONTRACTTRANSPORTDETAILID'];
			$this->contractid = $row["CONTRACTID"];
			$this->servicecontractorid = $row["SERVICECONTRACTORID"];
			$this->transportationvehicleid = $row["TRANSPORTATIONVEHICLEID"];
			$this->vehiclenumber = $row["VEHICLENUMBER"];
			if (isset($row['RTOPASSINGDATETIME']))
			{
				$this->rtopassingdatetime = date('d/m/Y',strtotime($row['RTOPASSINGDATETIME']));
			}
			if (isset($row['INSURANCEPAIDDATETIME']))
			{
				$this->insurancepaiddatetime = date('d/m/Y',strtotime($row['INSURANCEPAIDDATETIME']));
			}
			$this->chequenumber = $row['CHEQUENUMBER'];
			$this->transportationvehiclename_eng = $row['TRANSPORTATIONVEHICLENAME_ENG'];
			$this->transportationvehiclename_unicode = $row['TRANSPORTATIONVEHICLENAME_UNI'];
			$this->isrcattached = $row['ISRCATTACHED'];
			$this->istcattached = $row['ISTCATTACHED'];
			$this->vehiclemfgid = $row['VEHICLEMFGID'];
			$this->vehiclemfgname_eng = $row['VEHICLEMFGNAME_ENG'];
			$this->vehiclemfgname_unicode = $row['VEHICLEMFGNAME_UNI'];
			
			$query = "select h.name_eng as bankbranchname_eng,h.name_unicode as bankbranchname_unicode
			from contract c,contracttransportdetail r,bankbranch h 
			where c.active=1 and r.active=1 and c.contractid=r.contractid and r.contracttransportdetailid=".$contracttransportdetailid;
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
			{
				$this->bankbranchname_eng = $row['BANKBRANCHNAME_ENG'];
				$this->bankbranchname_unicode = $row['BANKBRANCHNAME_UNICODE'];
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	function contractcategoryid(&$connection,$contractid)
    {
        $query = "select c.contractcategoryid from contract c where c.active=1 
        and c.contractid=".$contractid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['CONTRACTCATEGORYID'];
        }
        else
        {
            return 0;
        }
    }
}
?>