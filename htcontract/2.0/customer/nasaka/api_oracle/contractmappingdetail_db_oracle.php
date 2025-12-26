<?php
include("../api_base/formbase.php");
class contractmappingdetail extends swappform
{	
	public $contractmappingdetailid;
	public $contracttransportdetailid;
	public $contractharvestdetailid;

	public function __construct(&$connection)
	{
		parent::__construct($connection);
		$this->contractmappingdetailid = '';
		$this->contracttransportdetailid = '';
		$this->contractharvestdetailid = '';
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->contracttransportdetailid,'Transport Vehicle');
		$this->checkrequired($this->contractharvestdetailid,'Harvest Group');
		$this->end_validation();
		return $this->invalidid;
	}

	private function seasonid()
	{
		$query = "select c.seasonid from contractmapping c where c.active=1 and c.contractmappingid=".$this->contractmappingid;
    	//echo $query;
    	$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
    	if ($row = mysqli_fetch_assoc($result))
		{
	    	return $row['SEASONID'];
		}
		else
		{
			return 0;
		}
	}

	private function datavalidation()
	{
		$this->start_validation();
		if ($this->dataoperationmode == operationmode::Insert)
		{
			$query = "select count(*) as cnt from contractmappingdetail a,contractmapping c where c.active=1 and a.contractmappingid=c.contractmappingid and c.seasonid=".$this->seasonid()." and a.active=1 and (a.contracttransportdetailid=".$this->contracttransportdetailid." or a.contractharvestdetailid=".$this->contractharvestdetailid.")";
			//echo $query;
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		}
		elseif ($this->dataoperationmode == operationmode::Update)
		{
			$query = "select count(*) as cnt from contractmappingdetail a,contractmapping c where c.active=1 and a.contractmappingid=c.contractmappingid and c.seasonid=".$this->seasonid()." and a.contractmappingdetailid<>".$this->contractmappingdetailid." and a.active=1 and (a.contracttransportdetailid=".$this->contracttransportdetailid." or a.contractharvestdetailid=".$this->contractharvestdetailid.")";
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		}
		//echo $query;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		if ($row["CNT"] > 0)
		{
			$this->invalidid=-201;
			$this->invalidmessagetext='Record is already exists';
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
		if ($this->contractmappingdetailid == '')
		{
			$result = mysqli_query($this->connection, "select nvl(max(contractmappingdetailid),0)+1 as contractmappingdetailid from contractmappingdetail");
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			$this->contractmappingdetailid = $row["CONTRACTMAPPINGDETAILID"];
		}
    	$query = "insert into contractmappingdetail(contractmappingdetailid,contractmappingid,contracttransportdetailid,contractharvestdetailid,active,cruserid,crdatetime) values ($this->contractmappingdetailid,$this->contractmappingid,$this->contracttransportdetailid,$this->contractharvestdetailid,1,".$_SESSION["usersid"].",'".currentdatetime()."')";
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
		$this->dataoperationmode = operationmode::Update;
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
		if ($this->uptodatetime!='')
		{
			$this->uptodatetime = DateTime::createFromFormat('d/m/Y',$this->uptodatetime)->format('d-M-Y');	
		}
    	$query = "update contractmappingdetail set uptodatetime='".$this->uptodatetime."',active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractmappingdetailid=".$this->contractmappingdetailid;
    	//echo $query;
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
    	$query = "update contractmappingdetail set uptodatetime='".$this->uptodatetime."',active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractmappingdetailid=".$this->contractmappingdetailid;
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
}
?>