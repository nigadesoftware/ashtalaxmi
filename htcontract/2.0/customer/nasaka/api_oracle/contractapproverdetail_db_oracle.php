<?php
require_once("../api_base/formbase.php");
class contractapproverdetail extends swappform
{	
	public $contractapproverdetailid;
    public $contractid;
    public $responsibilityid;
	public $empid;
	//information properties
	public $name_eng;
	public $name_unicode;

	public function __construct(&$connection)
	{
		parent::__construct($connection);
		$this->contractapproverdetailid = '';
        $this->responsibilityid = '';
        $this->empid = '';
		$this->contractid = '';
	}

	private function entryvalidation()
	{
        $this->start_validation();
        $this->checkrequired($this->responsibilityid,'Responsibility');
		$this->checkrequired($this->employeeid,'Employee');
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		$query = "select count(*) as cnt from contractapproverdetail a where a.active=1 and a.employeeid=".$this->employeeid." and a.contractid=".$this->contractid;
		/* $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result); */
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
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
        if ($this->contractapproverdetailid == '')
        {
            $query = "select nvl(max(contractapproverdetailid),478541524)+743 as contractapproverdetailid from contractapproverdetail";
            /* $result = mysqli_query($this->connection, "select nvl(max(contractapproverdetailid),478541524)+743 as contractapproverdetailid from contractapproverdetail");
            $result = oci_parse($this->connection, $query);             $r = oci_execute($result); */
            $result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
            $row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
            $this->contractapproverdetailid = $row["CONTRACTAPPROVERDETAILID"];
        }
        $query = "insert into contractapproverdetail(transactionid,contractapproverdetailid,contractid,responsibilityid,employeeid,active,cruserid) values ((select nvl(max(transactionid),0)+1 from contractapproverdetail),$this->contractapproverdetailid,$this->contractid,$this->responsibilityid,$this->employeeid,1,".$_SESSION["usersid"].")";
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
			$query = "select f.* from contractapproverdetail f where f.active=1 and ".$cond;
			//$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
            $result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
            return $result;
		}
		else
		{
			$query = "select f.* from contractapproverdetail f where f.active=1";
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
    	$query = "update contractapproverdetail set active=0,dluserid=".$_SESSION["usersid"].",dldatetime=sysdate where active=1 and contractapproverdetailid=".$this->contractapproverdetailid;
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
		$query = "update contractapproverdetail set active=0,dluserid=".$_SESSION["usersid"].",dldatetime=sysdate where active=1 and contractapproverdetailid=".$this->contractapproverdetailid;
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

	public function fetch($contractapproverdetailid)
	{
		$this->dataoperationmode = operationmode::Select;
        $query = "select c.contractid,d.employeeid,d.contractapproverdetailid,t.name_eng,t.name_unicode from contract c,contractapproverdetail d,employee t where c.active=1 and d.active=1 and c.contractid=d.contractid and d.employeeid=t.employeeid and d.contractapproverdetailid=".$contractapproverdetailid;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->contractapproverdetailid = $row['CONTRACTGUARANTORDETAILID'];
            $this->contractid = $row['CONTRACTID'];
            $this->employeeid = $row['EMPLOYEEID'];
            $this->name_eng = $row['NAME_ENG'];
            $this->name_unicode = $row['NAME_UNICODE'];
            return true;
        }
        else
        {
            return false;
        }
	}
	public function fetchbyresponsibility($contractid,$responsibilityid)
	{
		$this->dataoperationmode = operationmode::Select;
        $query = "select c.contractid,d.employeeid,d.contractapproverdetailid,t.name_eng,t.name_unicode from contract c,contractapproverdetail d,employee t where c.active=1 and d.active=1 and c.contractid=d.contractid and d.employeeid=t.employeeid and c.contractid=".$contractid." and d.responsibilityid=".$responsibilityid;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->contractapproverdetailid = $row['CONTRACTAPPROVERDETAILID'];
            $this->contractid = $row['CONTRACTID'];
            $this->employeeid = $row['EMPLOYEEID'];
            $this->name_eng = $row['NAME_ENG'];
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