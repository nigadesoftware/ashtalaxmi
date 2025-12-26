<?php
require_once("../api_base/formbase.php");
class contractitemloandetail extends swappform
{	
	public $contractitemloandetailid;
	public $contractid;
    public $itemid;
    public $qty;
    public $rate;
    public $amount;
	//information properties
	public $name_unicode;
	public $name_eng;
	public function __construct(&$connection)
	{
		parent::__construct($connection);
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->itemid,'Item');
        $this->checkrequired($this->qty,'Qty');
        $this->checkrequired($this->rate,'Rate');
		$this->amount = $this->qty * $this->rate;
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		if ($this->itemloandemanddatetime!='')
		{
			$this->itemloandemanddatetime = DateTime::createFromFormat('d/m/Y',$this->itemloandemanddatetime)->format('d-M-Y');	
		}
		if ($this->approveddatetime!='')
		{
			$this->approveddatetime = DateTime::createFromFormat('d/m/Y',$this->approveddatetime)->format('d-M-Y');	
		}
		$query = "select count(*) as cnt from contractitemloandetail a where a.active=1 and a.itemid='".$this->itemid."' and a.contractid=".$this->contractid;
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
        if ($this->contractitemloandetailid == '')
        {
			$query = "select nvl(max(contractitemloandetailid),0)+1 as contractitemloandetailid from contractitemloandetail";
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->contractitemloandetailid = $row["CONTRACTITEMLOANDETAILID"];
        }
        $query = "insert into contractitemloandetail(transactionid,contractitemloandetailid,contractid,itemid,qty,rate,amount,active,cruserid) values ((select nvl(max(transactionid),0)+1 from contractitemloandetail),$this->contractitemloandetailid,$this->contractid,".$this->invl($this->itemid,false).",".$this->invl($this->qty,true).",".$this->invl($this->rate,false).",".$this->invl($this->amount,true).",1,".$_SESSION["usersid"].")";
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
		$cond='contractid='.$this->contractid;
		if ($cond!='')
		{
			$query = "select f.* from contractitemloandetail f where f.active=1 and ".$cond;
			$result = oci_parse($this->connection, $query);
			$r = oci_execute($result);
			return $result;
		}
		else
		{
			$query = "select f.* from contractitemloandetail f where f.active=1";
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
    	$query = "update contractitemloandetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractitemloandetailid=".$this->contractitemloandetailid;
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
		$query = "update contractitemloandetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractitemloandetailid=".$this->contractitemloandetailid;
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
	
	public function fetch($contractitemloandetailid)
	{
		$this->dataoperationmode = operationmode::Select;
		$query = "select c.*,i.name_eng,name_unicode from contractitemloandetail c, namedetail i where c.active=1 and i.active=1 and c.itemid=i.namedetailid and contractitemloandetailid=".$contractitemloandetailid;
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->contractid = $row['CONTRACTID'];
			$this->contractitemloandetailid = $row['CONTRACTITEMLOANDETAILID'];
			$this->itemid = $row['ITEMID'];
			$this->qty = $row['QTY'];
			$this->rate = $row['RATE'];
			$this->amount = $row['AMOUNT'];
			$this->name_unicode = $row['NAME_UNICODE'];
			$this->name_eng = $row['NAME_ENG'];
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>