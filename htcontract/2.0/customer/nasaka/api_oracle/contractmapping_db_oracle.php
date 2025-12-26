<?php
include("../api_base/formbase.php");
class contractmapping extends swappform
{	
	public $contractmappingid;
	public $seasonid;
	public $sugarfactoryid;
	public $servicecontractorid;
	public function __construct(&$connection)
	{
		parent::__construct($connection);
		$this->contractmappingid = '';
		$this->seasonid = '';
		$this->sugarfactoryid = '';
		$this->servicecontractorid ='';
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->seasonid,'Season');
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		if ($this->contractmappingid == '')
		{
			if ($this->servicecontractorid == '')
			{
				$query = "select count(*) as cnt from contractmapping a where a.active=1 and a.seasonid=".$this->seasonid." and a.sugarfactoryid=".$this->sugarfactoryid." and servicecontractorid is null";
			}
			else
			{
				$query = "select count(*) as cnt from contractmapping a where a.active=1 and a.seasonid=".$this->seasonid." and a.sugarfactoryid=".$this->sugarfactoryid." and a.servicecontractorid=".$this->servicecontractorid;
			}
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		}
		else
		{
			if ($this->servicecontractorid == '')
			{
				$query = "select count(*) as cnt from contractmapping a where a.active=1 and a.contractmappingid<>".$this->contractmappingid." and a.seasonid=".$this->seasonid." and a.sugarfactoryid=".$this->sugarfactoryid." and servicecontractorid is null";
			}
			else
			{
				$query = "select count(*) as cnt from contractmapping a where a.active=1 and a.contractmappingid<>".$this->contractmappingid." and a.seasonid=".$this->seasonid." and a.sugarfactoryid=".$this->sugarfactoryid." and a.servicecontractorid=".$this->servicecontractorid;
			}
			echo $query;
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		}
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		if ($row["CNT"] > 0)
		{
			$this->invalidid=-201;
			$this->invalidmessagetext='Contract Mapping is already exists';
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
		if ($this->contractmappingid == '')
		{
			$result = mysqli_query($this->connection, "select nvl(max(contractmappingid),0)+1 as contractmappingid from contractmapping");
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			$this->contractmappingid = $row["CONTRACTMAPPINGID"];
		}
		if ($this->servicecontractorid == '')
		{
			$this->servicecontractorid = 'null';
		}
		$query = "insert into contractmapping(contractmappingid,seasonid,sugarfactoryid,servicecontractorid,active,cruserid,crdatetime) values ($this->contractmappingid,$this->seasonid,$this->sugarfactoryid,$this->servicecontractorid,1,".$_SESSION["usersid"].",'".currentdatetime()."')";
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
		if ($this->seasonid==0)
		{
			$this->seasonid = '';
		}
		if ($this->sugarfactoryid==0)
		{
			$this->sugarfactoryid = '';
		}
		$cond='';
		if ($this->seasonid!='')
		{
			if ($cond=='')
			{
				$cond=$cond."s.seasonid = ".$this->seasonid;
			}
			else
			{
				$cond=$cond." and s.seasonid = ".$this->seasonid;
			}
		}
		if ($this->sugarfactoryid!='')
		{
			if ($cond=='')
			{
				$cond=$cond."c.sugarfactoryid = ".$this->sugarfactoryid;
			}
			else
			{
				$cond=$cond." and c.sugarfactoryid = ".$this->sugarfactoryid;
			}
		}
		if ($this->servicecontractorid!='')
		{
			if ($cond=='')
			{
				$cond=$cond."c.servicecontractorid = ".$this->servicecontractorid;
			}
			else
			{
				$cond=$cond." and c.servicecontractorid = ".$this->servicecontractorid;
			}
		}
		if ($this->servicecontractorid!='')
		{
			if ($cond=='')
			{
				$cond=$cond."c.servicecontractorid = ".$this->servicecontractorid;
			}
			else
			{
				$cond=$cond." and c.servicecontractorid = ".$this->servicecontractorid;
			}
		}
		if ($cond!='')
		{
			$query = "select c.contractmappingid,c.servicecontractorid,s.name_eng as seasonname_eng,s.name_unicode as seasonname_unicode from contractmapping c, season  s where c.active=1 and s.active=1 and c.seasonid=s.seasonid and ".$cond;
			//echo $query;
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			return $result;
		}
		else
		{
			$query = "select c.contractmappingid,c.servicecontractorid,s.name_eng as seasonname_eng,s.name_unicode as seasonname_unicode from contractmapping c, season  s where c.active=1 and s.active=1 and c.seasonid=s.seasonid limit 100";
			//echo $query;
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
    	$query = "update contractmapping set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractmappingid=".$this->contractmappingid;
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
		$this->dataoperationmode =operationmode::Delete;
		if ($this->entryvalidation() <> 0)
		{
			return 0;
			exit;
		}
		$query = "update contractmapping set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractmappingid=".$this->contractmappingid;
    	//echo $query;
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