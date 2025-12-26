<?php
require_once("../api_base/formbase.php");
class contractguarantordetail extends swappform
{	
	public $contractguarantordetailid;
	public $contractid;
	public $servicecontractorid;
	//information properties
	public $servicecontractorname_eng;
	public $servicecontractorname_unicode;
	public $referencecode;
	//public $areaid;
	public $contactnumber;
	public $pannumber;
	public $aadharnumber;
	public $iscultivator;
	public $address_unicode;
	public $fieldarea;
	public $plantationarea;
	public $address;
	public function __construct(&$connection)
	{
		parent::__construct($connection);
		$this->contractguarantordetailid = '';
		$this->servicecontractorid = '';
		$this->contractid = '';
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->servicecontractorid,'Service Contractor');
		if ($this->iscultivator==1)
		{
			$this->checkrequired($this->fieldarea,'Field Area');
			$this->checkrequired($this->plantationarea,'Plantation Area');
			
		}
		$this->checkrequired($this->address,'address');
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		if ($this->dataoperationmode == operationmode::Insert)
		{
			$query = "select count(*) as cnt from contractguarantordetail a where a.active=1 and a.servicecontractorid=".$this->servicecontractorid." and a.contractid=".$this->contractid;
		}
		else
		{
			$query = "select count(*) as cnt from contractguarantordetail a where a.active=1 and a.contractguarantordetailid<>".$this->contractguarantordetailid." and a.servicecontractorid=".$this->servicecontractorid." and a.contractid=".$this->contractid;
		}
		/* $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result); */
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
		$query = "select count(*) as cnt from contract c where c.active=1 and c.servicecontractorid=".$this->servicecontractorid." and c.contractid=".$this->contractid;
		/* $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result); */
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $row1 = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
		
		if ($row["CNT"] > 0)
		{
			$this->invalidid=-201;
			$this->invalidmessagetext='Contract Guarantor Name is already exists';
		}
		elseif ($row1["CNT"] > 0)
		{
			$this->invalidid=-202;
			$this->invalidmessagetext='Self Guarantor Name is not allowed';
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
        if ($this->contractguarantordetailid == '')
        {
            $query = "select nvl(max(contractguarantordetailid),478541524)+743 as contractguarantordetailid from contractguarantordetail";
            /* $result = mysqli_query($this->connection, "select nvl(max(contractguarantordetailid),478541524)+743 as contractguarantordetailid from contractguarantordetail");
            $result = oci_parse($this->connection, $query);             $r = oci_execute($result); */
            $result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
            $row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
            $this->contractguarantordetailid = $row["CONTRACTGUARANTORDETAILID"];
        }
        $query = "insert into contractguarantordetail(transactionid,contractguarantordetailid,contractid,servicecontractorid,iscultivator,fieldarea,plantationarea,address,active,cruserid) values ((select nvl(max(transactionid),0)+1 from contractguarantordetail),$this->contractguarantordetailid,$this->contractid,$this->servicecontractorid,$this->iscultivator,".$this->invl($this->fieldarea,true).",".$this->invl($this->plantationarea,true).",".$this->invl($this->address,false).",1,".$_SESSION["usersid"].")";
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
			$query = "select f.* from contractguarantordetail f where f.active=1 and ".$cond;
			//$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
            $result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
            return $result;
		}
		else
		{
			$query = "select f.* from contractguarantordetail f where f.active=1 limit 100";
			//$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
            $result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
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
    	$query = "update contractguarantordetail set active=0,dluserid=".$_SESSION["usersid"].",dldatetime=sysdate where active=1 and contractguarantordetailid=".$this->contractguarantordetailid;
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
		$query = "update contractguarantordetail set active=0,dluserid=".$_SESSION["usersid"].",dldatetime=sysdate where active=1 and contractguarantordetailid=".$this->contractguarantordetailid;
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

	public function fetch($contractguarantordetailid,$category)
	{
		$this->dataoperationmode = operationmode::Select;
		if ($category == 1)
		{
			$query = "select c.contractid,d.servicecontractorid,d.contractguarantordetailid,t.name_eng as servicecontractorname_eng,t.name_unicode as servicecontractorname_unicode,null as vpan_no,d.fieldarea,d.address from contract c,contractguarantordetail d,servicecontractor t where c.active=1 and d.active=1 and c.contractid=d.contractid and d.servicecontractorid=t.servicecontractorid and d.contractguarantordetailid=".$contractguarantordetailid;
			$result = oci_parse($this->connection, $query);
			$r = oci_execute($result);
			if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
			{
				$this->contractguarantordetailid = $row['CONTRACTGUARANTORDETAILID'];
				$this->contractid = $row['CONTRACTID'];
				$this->servicecontractorid = $row['SERVICECONTRACTORID'];
				$this->servicecontractorname_eng = $row['SERVICECONTRACTORNAME_ENG'];
				$this->servicecontractorname_unicode = $row['SERVICECONTRACTORNAME_UNICODE'];
				//$this->referencecode = $row['REFERENCECODE'];
				//$this->areaid = $row["AREAID"];
				$this->contactnumber = $row["NMOBLIE_NO"];
				$this->pannumber = $row["VPAN_NO"];
				//$this->aadharnumber = $row["AADHARNUMBER"];
				$this->address_unicode = $row["VADDRESS"];
				//$this->fieldarea = $row['FIELDAREA'];
				//$this->plantationarea = $row["PLANTATIONAREA"];
				$this->address = $row['ADDRESS'];
				return true;
			}
			else
			{
				return false;
			}
		}
		elseif ($category == 2)
		{
			$query = "select c.contractid,d.servicecontractorid,d.contractguarantordetailid,t.name_eng as servicecontractorname_eng,t.name_unicode as servicecontractorname_unicode,nvl(d.address,'') as address,d.fieldarea,d.plantationarea from contract c,contractguarantordetail d,cultivator t where c.active=1 and d.active=1 and c.contractid=d.contractid and d.servicecontractorid=t.cultivatorid and d.contractguarantordetailid=".$contractguarantordetailid;
			$result = oci_parse($this->connection, $query);
			$r = oci_execute($result);
			if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
			{
				$this->contractguarantordetailid = $row['CONTRACTGUARANTORDETAILID'];
				$this->contractid = $row['CONTRACTID'];
				$this->servicecontractorid = $row['SERVICECONTRACTORID'];
				$this->servicecontractorname_eng = $row['SERVICECONTRACTORNAME_ENG'];
				$this->servicecontractorname_unicode = $row['SERVICECONTRACTORNAME_UNICODE'];
				//$this->referencecode = $row['REFERENCECODE'];
				//$this->areaid = $row["AREAID"];
				/* $this->contactnumber = $row["NMOBLIE_NO"];
				$this->pannumber = $row["VPAN_NO"]; */
				//$this->aadharnumber = $row["AADHARNUMBER"];
				$this->address_unicode = $row["ADDRESS"];
				$this->fieldarea=$row['FIELDAREA'];
				$this->plantationarea = $row["PLANTATIONAREA"];
				return true;
			}
			else
			{
				return false;
			}
		}
	}
}
?>