<?php
require_once("../api_base/formbase.php");
class contractwitnessdetail extends swappform
{	
	public $contractwitnessdetailid;
	public $contractid;
	public $name_unicode;

	public function __construct(&$connection)
	{
		parent::__construct($connection);
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->name_unicode,'witness Name');
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		$query = "select count(*) as cnt from contractwitnessdetail a where a.active=1 and a.name_unicode='".$this->name_unicode."' and a.contractid=".$this->contractid;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
		if ($row["CNT"] > 0)
		{
			$this->invalidid=-201;
			$this->invalidmessagetext='Contract Guarantor Name is already exists';
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
        if ($this->contractwitnessdetailid == '')
        {
			$query = "select nvl(max(contractwitnessdetailid),478541524)+743 as contractwitnessdetailid from contractwitnessdetail";
            $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->contractwitnessdetailid = $row["CONTRACTWITNESSDETAILID"];
        }
        $query = "insert into contractwitnessdetail(transactionid,contractwitnessdetailid,contractid,name_unicode,active,cruserid) values ((select nvl(max(transactionid),0)+1 from contractwitnessdetail),$this->contractwitnessdetailid,$this->contractid,'$this->name_unicode',1,".$_SESSION["usersid"].")";
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
			$query = "select f.* from contractwitnessdetail f where f.active=1 and ".$cond;
			$result = oci_parse($this->connection, $query);
			$r = oci_execute($result);
			return $result;
		}
		else
		{
			$query = "select f.* from contractwitnessdetail f where f.active=1";
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
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
    	$query = "update contractwitnessdetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractwitnessdetailid=".$this->contractwitnessdetailid;
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
		/* if ($this->entryvalidation() <> 0)
		{
			return 0;
			exit;
		} */
		$query = "update contractwitnessdetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractwitnessdetailid=".$this->contractwitnessdetailid;
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

	public function fetch($contractwitnessdetailid)
	{
		$this->dataoperationmode = operationmode::Select;
		$query = "select c.*,d.* from contract c,contractwitnessdetail d where c.active=1 and d.active=1 and c.contractid=d.contractid and d.contractwitnessdetailid=".$contractwitnessdetailid;
		$result = oci_parse($this->connection, $query);             
		$r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->contractwitnessdetailid = $row['CONTRACTWITNESSDETAILID'];
			$this->contractid = $row['CONTRACTID'];
			$this->name_unicode = $row['NAME_UNICODE'];
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>