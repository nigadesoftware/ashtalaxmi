<?php
require_once("../api_base/formbase.php");
class contractadvancedetail extends swappform
{	
	public $contractadvancedetailid;
	public $contractid;
    public $advancedemanddatetime;
    public $advancedemandamount;
    public $approveddatetime;
    public $approvedamount;
	//information properties

	public function __construct(&$connection)
	{
		parent::__construct($connection);
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->advancedemanddatetime,'Advance Demand Date');
		$this->checkrequired($this->advancedemandamount,'Advance Demand Amount');
        if ($this->approveddatetime!='')
        {
		    $this->checkrequired($this->approvedamount,'Approved Amount');
        }
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		if ($this->advancedemanddatetime!='')
		{
			$this->advancedemanddatetime = DateTime::createFromFormat('d/m/Y',$this->advancedemanddatetime)->format('d-M-Y');	
		}
		if ($this->approveddatetime!='')
		{
			$this->approveddatetime = DateTime::createFromFormat('d/m/Y',$this->approveddatetime)->format('d-M-Y');	
		}
		$query = "select count(*) as cnt from contractadvancedetail a where a.active=1 and a.advancedemanddatetime='".$this->advancedemanddatetime."' and a.contractid=".$this->contractid;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
		if ($row["CNT"] > 0)
		{
			$this->invalidid=-201;
			$this->invalidmessagetext='Contract Advance Detail is already exists';
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
        if ($this->contractadvancedetailid == '')
        {
			$query = "select nvl(max(contractadvancedetailid),0)+1 as contractadvancedetailid from contractadvancedetail";
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->contractadvancedetailid = $row["CONTRACTADVANCEDETAILID"];
        }
        $query = "insert into contractadvancedetail(transactionid,contractadvancedetailid,contractid,advancedemanddatetime,advancedemandamount,approveddatetime,approvedamount,active,cruserid) values ((select nvl(max(transactionid),0)+1 from contractadvancedetail),$this->contractadvancedetailid,$this->contractid,".$this->invl($this->advancedemanddatetime,false).",".$this->invl($this->advancedemandamount,true).",".$this->invl($this->approveddatetime,false).",".$this->invl($this->approvedamount,true).",1,".$_SESSION["usersid"].")";
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
			$query = "select f.* from contractadvancedetail f where f.active=1 and ".$cond;
			$result = oci_parse($this->connection, $query);
			$r = oci_execute($result);
			return $result;
		}
		else
		{
			$query = "select f.* from contractadvancedetail f where f.active=1 limit 100";
			$result = mysqli_parse($this->connection, $query);
			$r = oci_execute($result);
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
    	$query = "update contractadvancedetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractadvancedetailid=".$this->contractadvancedetailid;
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
		$query = "update contractadvancedetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractadvancedetailid=".$this->contractadvancedetailid;
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

	public function fetch($contractadvancedetailid)
	{
		$this->dataoperationmode = operationmode::Select;
		$query = "select c.* from contractadvancedetail c where c.active=1 and contractadvancedetailid=".$contractadvancedetailid;
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->contractid = $row['CONTRACTID'];
			$this->contractadvancedetailid = $row['CONTRACTADVANCEDETAILID'];
			$this->advancedemanddatetime = date('d/m/Y',strtotime($row['ADVANCEDEMANDDATETIME']));
			$this->advancedemandamount = $row['ADVANCEDEMANDAMOUNT'];
			if (isset($row['APPROVEDDATETIME']))
			{
				$this->approveddatetime = date('d/m/Y',strtotime($row['APPROVEDDATETIME']));
			}
			$this->approvedamount = $row['APPROVEDAMOUNT'];
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>