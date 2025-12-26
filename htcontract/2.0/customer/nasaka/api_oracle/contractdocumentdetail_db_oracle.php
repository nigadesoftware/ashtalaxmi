<?php
require_once("../api_base/formbase.php");
class contractdocumentdetail extends swappform
{	
	public $contractdocumentdetailid;
	public $contractid;
	public $documentid;
	//information properties
	public $documentname_eng;
	public $documentname_unicode;

	public function __construct(&$connection)
	{
		parent::__construct($connection);
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->documentid,'Document');
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		$query = "select count(*) as cnt from contractdocumentdetail a where a.active=1 and a.documentid=".$this->documentid." and a.contractid=".$this->contractid;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
		if ($row["CNT"] > 0)
		{
			$this->invalidid=-201;
			$this->invalidmessagetext='Contract Document Name is already exists';
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
        if ($this->contractdocumentdetailid == '')
        {
			$query = "select nvl(max(contractdocumentdetailid),0)+1 as contractdocumentdetailid from contractdocumentdetail";
            $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->contractdocumentdetailid = $row["CONTRACTDOCUMENTDETAILID"];
        }
        $query = "insert into contractdocumentdetail(transactionid,contractdocumentdetailid,contractid,documentid,active,cruserid) values ((select nvl(max(transactionid),0)+1 from contractdocumentdetail),$this->contractdocumentdetailid,$this->contractid,$this->documentid,1,".$_SESSION["usersid"].")";
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
			$query = "select f.* from contractdocumentdetail f where f.active=1 and ".$cond;
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			return $result;
		}
		else
		{
			$query = "select f.* from contractdocumentdetail f where f.active=1 limit 100";
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
    	$query = "update contractdocumentdetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractdocumentdetailid=".$this->contractdocumentdetailid;
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
		$query = "update contractdocumentdetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractdocumentdetailid=".$this->contractdocumentdetailid;
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

	public function fetch($contractdocumentdetailid)
	{
		$this->dataoperationmode = operationmode::Select;
		$query = "select c.*,d.*,n.name_eng as documentname_eng,n.name_unicode as documentname_unicode from contract c,contractdocumentdetail d,namedetail n where c.active=1 and d.active=1 and n.active=1 and c.contractid=d.contractid and d.documentid=n.namedetailid and d.contractdocumentdetailid=".$contractdocumentdetailid;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->contractdocumentdetailid = $row['CONTRACTDOCUMENTDETAILID'];
			$this->contractid = $row['CONTRACTID'];
			$this->documentid = $row['DOCUMENTID'];
			$this->documentname_eng = $row['DOCUMENTNAME_ENG'];
			$this->documentname_unicode = $row['DOCUMENTNAME_UNICODE'];
			return true;
		}
		else
		{
			return false;
		}
	}

	public function insertall($contractid)
	{
        $query = "select namedetailid,name_eng,name_unicode from namedetail n where n.active=1 and n.namecategoryid=365214765 order by name_eng";
		$result = oci_parse($this->connection, $query);             
		$r = oci_execute($result);
		$i=0;
		while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->contractid=$contractid;
			$this->documentid=$row['NAMEDETAILID'];
			$this->contractdocumentdetailid=$contractid+100+$i++;
			$ret = $this->insert();
			if ($ret == 0)
			{
				return 0;
				exit;
			}
		}
		return 1;
	}
}
?>