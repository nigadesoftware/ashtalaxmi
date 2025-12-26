<?php
require_once("../api_base/formbase.php");
class contractperformancedetail extends swappform
{	
	public $contractperformancedetailid;
	public $contractid;
    public $lastseasonid;
    public $lastseasonhttonnage;
    public $balance;
    public $debitcredit;
	//information properties
	public $seasonname_eng;
	public $seasonname_unicode;
	public $debitcreditname_eng;
	public $debitcreditname_unicode;

	public function __construct(&$connection)
	{
		parent::__construct($connection);
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->lastseasonid,'Last Season');
		$this->checkrequired($this->lastseasonhttonnage,'Last Season HT Tonnage');
		$this->checkrequired($this->balance,'Balance');
		$this->checkrequired($this->debitcredit,'Balance Type');
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		$query = "select count(*) as cnt from contractperformancedetail a where a.active=1 and a.lastseasonid=".$this->lastseasonid." and a.contractid=".$this->contractid;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
		if ($row["CNT"] > 0)
		{
			$this->invalidid=-201;
			$this->invalidmessagetext='Contract Performance Detail is already exists';
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
        if ($this->contractperformancedetailid == '')
        {
			$query = "select nvl(max(contractperformancedetailid),0)+1 as contractperformancedetailid from contractperformancedetail";
            $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->contractperformancedetailid = $row["CONTRACTPERFORMANCEDETAILID"];
        }
        $query = "insert into contractperformancedetail(transactionid,contractperformancedetailid,contractid,lastseasonid,lastseasonhttonnage,balance,debitcredit,active,cruserid) values ((select nvl(max(transactionid),0)+1 from contractperformancedetail),$this->contractperformancedetailid,$this->contractid,$this->lastseasonid,$this->lastseasonhttonnage,$this->balance,$this->debitcredit,1,".$_SESSION["usersid"].")";
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
			$query = "select f.* from contractperformancedetail f where f.active=1 and ".$cond;
			$result = oci_parse($this->connection, $query);
			return $result;
		}
		else
		{
			$query = "select f.* from contractperformancedetail f where f.active=1";
			$result = oci_parse($this->connection, $query);
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
    	$query = "update contractperformancedetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractperformancedetailid=".$this->contractperformancedetailid;
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
		$query = "update contractperformancedetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractperformancedetailid=".$this->contractperformancedetailid;
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

	public function fetch($contractperformancedetailid)
	{
		$this->dataoperationmode = operationmode::Select;
		$query = "select c.*,name_eng as seasonname_eng,name_unicode as seasonname_unicode from contractperformancedetail c,season s where c.active=1 and s.active=1 and c.lastseasonid=s.seasonid and contractperformancedetailid=".$contractperformancedetailid;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->contractid = $row['CONTRACTID'];
			$this->contractperformancedetailid = $row['CONTRACTPERFORMANCEDETAILID'];
			$this->lastseasonid = $row['LASTSEASONID'];
			$this->lastseasonhttonnage = $row['LASTSEASONHTTONNAGE'];
			$this->balance = $row['BALANCE'];
			$this->debitcredit = $row['DEBITCREDIT'];
			$this->seasonname_eng = $row['SEASONNAME_ENG'];
			$this->seasonname_unicode = $row['SEASONNAME_UNICODE'];
			if ($row['DEBITCREDIT']==157489650)
			{
				$this->debitcreditname_unicode = 'नावे';
				$this->debitcreditname_eng = 'debit';	
			}
			elseif ($row['DEBITCREDIT']== 357481241)
			{
				$this->debitcreditname_unicode = 'जमा';
				$this->debitcreditname_eng = 'Credit';
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