<?php
require_once("../api_base/formbase.php");
class contractreceiptdetail extends swappform
{	
	public $contractreceiptdetailid;
	public $contractid;
	public $receiptcategoryid;
    public $receiptdatetime;
    public $bankbranchid;
    public $chequenumber;
    public $chequedatetime;
    public $chequeamount;
	//information properties
    public $bankbranchname_eng;
    public $bankbranchname_unicode;

	public function __construct(&$connection)
	{
		parent::__construct($connection);
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->receiptcategoryid,'Receipt Category');
		$this->checkrequired($this->receiptdatetime,'Receipt Date');
		/* $this->checkrequired($this->bankbranchid,'Bank Branch'); */
        $this->checkrequired($this->chequenumber,'Cheque Number');
		$this->checkrequired($this->chequedatetime,'Cheque Date');
        $this->checkrequired($this->chequeamount,'Cheque Amount');
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		if($this->dataoperationmode == operationmode::Insert)
		{
			$query = "select count(*) as cnt from contractreceiptdetail a where a.active=1 and a.chequenumber=".$this->chequenumber." and a.contractid=".$this->contractid." and a.receiptcategoryid=".$this->receiptcategoryid;
		}
		else
		{
			$query = "select count(*) as cnt from contractreceiptdetail a where a.active=1 and a.chequenumber=".$this->chequenumber." and a.contractid=".$this->contractid." and a.receiptcategoryid=".$this->receiptcategoryid." and a.contractreceiptdetailid<>".$this->contractreceiptdetailid;
		}
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
		if ($row["CNT"] > 0)
		{
			$this->invalidid=-201;
			$this->invalidmessagetext='Contract receipt Detail is already exists';
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
		if ($this->receiptdatetime!='')
		{
			$this->receiptdatetime = DateTime::createFromFormat('d/m/Y',$this->receiptdatetime)->format('d-M-Y');	
		}
		if ($this->chequedatetime!='')
		{
			$this->chequedatetime = DateTime::createFromFormat('d/m/Y',$this->chequedatetime)->format('d-M-Y');	
		}
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
        if ($this->contractreceiptdetailid == '')
        {
			$query = "select nvl(max(contractreceiptdetailid),0)+1 as contractreceiptdetailid from contractreceiptdetail";
            $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->contractreceiptdetailid = $row["CONTRACTRECEIPTDETAILID"];
        }
        $query = "insert into contractreceiptdetail(transactionid,contractreceiptdetailid,contractid,receiptcategoryid,receiptdatetime,bankbranchid,chequenumber,chequedatetime,chequeamount,active,cruserid) values ((select nvl(max(transactionid),0)+1 from contractreceiptdetail),$this->contractreceiptdetailid,$this->contractid,$this->receiptcategoryid,".$this->invl($this->receiptdatetime,false).",".$this->invl($this->bankbranchid,true).",".$this->invl($this->chequenumber,true).",".$this->invl($this->chequedatetime,false).",".$this->invl($this->chequeamount,true).",1,".$_SESSION["usersid"].")";
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
			$query = "select f.* from contractreceiptdetail f where f.active=1 and ".$cond;
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			$r = oci_execute($result);
			return $result;
		}
		else
		{
			$query = "select f.* from contractreceiptdetail f where f.active=1";
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
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
    	$query = "update contractreceiptdetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractreceiptdetailid=".$this->contractreceiptdetailid;
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
		$query = "update contractreceiptdetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractreceiptdetailid=".$this->contractreceiptdetailid;
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

	public function fetch($contractreceiptdetailid)
	{
		$this->dataoperationmode = operationmode::Select;
		$query = "select c.* from contractreceiptdetail c where c.active=1 and contractreceiptdetailid=".$contractreceiptdetailid;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->contractid = $row['CONTRACTID'];
			$this->contractreceiptdetailid = $row['CONTRACTRECEIPTDETAILID'];
			$this->receiptcategoryid = $row['RECEIPTCATEGORYID'];
			if (isset($row['RECEIPTDATETIME']))
			{
				$this->receiptdatetime = date('d/m/Y',strtotime($row['RECEIPTDATETIME']));
			}
			$this->bankbranchid = $row['BANKBRANCHID'];
			$this->chequenumber = $row['CHEQUENUMBER'];
			if (isset($row['CHEQUEDATETIME']))
			{
				$this->chequedatetime = date('d/m/Y',strtotime($row['CHEQUEDATETIME']));
			}
			$this->chequeamount = $row['CHEQUEAMOUNT'];

            $query = "select concat(m.name_eng,' ',n.name_eng) as bankbranchname_eng,concat(m.name_unicode,' ',n.name_unicode) as bankbranchname_unicode
			from contract c,contractreceiptdetail r,bankbranch h,bank b,namedetail n,namedetail m 
			where c.active=1 and r.active=1 and h.active=1 and b.active=1 and n.active=1 and m.active=1 and c.contractid=r.contractid and r.bankbranchid=h.bankbranchid and h.bankid=b.bankid and b.namedetailid=m.namedetailid and h.namedetailid=n.namedetailid and r.contractreceiptdetailid=".$contractreceiptdetailid;
			$result = oci_parse($this->connection, $query);
			$r = oci_execute($result);
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
}
?>