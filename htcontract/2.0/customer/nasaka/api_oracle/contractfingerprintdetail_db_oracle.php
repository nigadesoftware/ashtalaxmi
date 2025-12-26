<?php
require_once("../api_base/formbase.php");
class contractfingerprintdetail extends swappform
{	
	public $transactionid;
	public $contractfingerprintdetailid;
	public $contractid;
	public $contractreferencecategoryid;
	public $contractreferencedetailid;
    public $fingerprint;
	//information
	

	public function __construct(&$connection)
	{
		parent::__construct($connection);
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->fingerprint,'Finger Print');
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		$this->invalidid=0;
		$this->invalidmessagetext='Validated';
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
		if ($this->transactionid == '')
		{
			$query = "select nvl(max(transactionid),0)+1 as transactionid from contractfingerprintdetail";
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->transactionid = $row["TRANSACTIONID"];
		}
		if ($this->contractfingerprintdetailid == '')
		{
			$query = "select nvl(max(contractfingerprintdetailid),0)+1 as contractfingerprintdetailid from contractfingerprintdetail";
			$result = oci_parse($this->connection, $query);             
			$r = oci_execute($result);
			$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->contractfingerprintdetailid = $row["CONTRACTFINGERPRINTDETAILID"];
		}
		if ($this->contractreferencedetailid=="null" or $this->contractreferencedetailid=="")
		{
			$this->contractreferencedetailid = NULL;
		}
		/* //$this->fingerprint = $this->scan($this->fingerprint);
		$query = "insert into contractfingerprintdetail(contractfingerprintdetailid,contractid,contractreferencecategoryid,contractreferencedetailid,fingerprint,active,cruserid,crdatetime) values ($this->contractfingerprintdetailid,$this->contractid,$this->contractreferencecategoryid,$this->contractreferencedetailid,'$this->fingerprint',1,".$_SESSION["usersid"].",'".currentdatetime()."')";
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
		} */

		$action=1;
		$lob = oci_new_descriptor($this->connection, OCI_D_LOB);

		$query = 'INSERT INTO contractfingerprintdetail (transactionid,contractfingerprintdetailid,
		contractid,contractreferencecategoryid,contractreferencedetailid,active,cruserid,fingerprint) '
		.'VALUES(:TRANSACTIONID,:CONTRACTFINGERPRINTDETAILID,:CONTRACTID,
		:CONTRACTREFERENCECATEGORYID,:CONTRACTREFERENCEDETAILID,:ACTIVE,:CRUSERID,
		EMPTY_BLOB()) RETURNING FINGERPRINT INTO :FINGERPRINT';
		//:CONTRACTREFERENCEDETAILID,contractreferencedetailid,
		$stmt = oci_parse($this->connection,$query);
		oci_bind_by_name($stmt, ':TRANSACTIONID', $this->transactionid);
		oci_bind_by_name($stmt, ':CONTRACTFINGERPRINTDETAILID', $this->contractfingerprintdetailid);
		oci_bind_by_name($stmt, ':CONTRACTID', $this->contractid);
		oci_bind_by_name($stmt, ':CONTRACTREFERENCECATEGORYID', $this->contractreferencecategoryid);
		oci_bind_by_name($stmt, ':CONTRACTREFERENCEDETAILID', $this->contractreferencedetailid);
		oci_bind_by_name($stmt, ':ACTIVE', $action);
		oci_bind_by_name($stmt, ':CRUSERID', $_SESSION["usersid"]); 
		oci_bind_by_name($stmt, ':FINGERPRINT', $lob, -1, OCI_B_BLOB);
		oci_execute($stmt, OCI_DEFAULT);

		// The function $lob->savefile(...) reads from the uploaded file.
		// If the data was already in a PHP variable $myv, the
		// $lob->save($myv) function could be used instead.
		/* if ($lob->savefile($_FILES['lob_upload']['tmp_name']))  */
		if ($lob->savefile($_FILES['fingerprint']['tmp_name']))
		//if ($lob->save($this->fingerprint))
		{
			return 1;
		}
		else 
		{
			return 0;
		}
		$lob->free();
		oci_free_statement($stmt);

	}

	function scan($aadhar)
	{
		$xmlpos = strpos($aadhar,'xml');
		
		if ($xmlpos ==false)
		{
			$uid = $aadhar;
		}
		else
		{
			$xml2 = $aadhar;
			$xmlpos = strpos($xml2,'uid=');
			if ($xmlpos >=0)
			{
				$uid = substr($xml2,$xmlpos+5,12);
			}
			else
			{
				$uid = '';
			}
		}
		return $uid;
	}

	public function display()
	{
		$this->dataoperationmode = operationmode::Select;
		$cond='';
		if ($cond!='')
		{
			$query = "select f.* from contractfingerprintdetail f where f.active=1 and ".$cond;
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			return $result;
		}
		else
		{
			$query = "select f.* from contractfingerprintdetail f where f.active=1 limit 100";
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
    	$query = "update contractfingerprintdetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractfingerprintdetailid=".$this->contractfingerprintdetailid;
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
		/*if ($this->entryvalidation() <> 0)
		{
			return 0;
			exit;
		}*/
		$query = "update contractfingerprintdetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and contractfingerprintdetailid=".$this->contractfingerprintdetailid;
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

	public function fetch($contractfingerprintdetailid)
	{
		$this->dataoperationmode = operationmode::Select;
		$query = "select c.* from contractfingerprintdetail c where c.active=1 and c.contractfingerprintdetailid=".$contractfingerprintdetailid;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->contractid = $row['CONTRACTID'];
			$this->contractreferencecategoryid = $row['CONTRACTREFERENCECATEGORYID'];
			$this->fingerprint = $row['FINGERPRINT']->load();
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>