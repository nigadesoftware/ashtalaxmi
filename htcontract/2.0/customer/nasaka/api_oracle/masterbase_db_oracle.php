<?php
include("../api_base/formbase.php");
class masterbase extends swappform
{	
	public $namedetailid;
	public $name_eng;
	public $name_unicode;
	public $namecategoryid;
	public $masterlabelname;
	public function __construct(&$connection)
	{
		parent::__construct($connection);
		$this->namedetailid='';
		$this->name_eng='';
		$this->name_unicode='';
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->name_eng,'Name English');
		$this->englishtextdigitonly($this->name_eng,'Name English');
		$this->checkrequired($this->name_unicode,'Name Unicode');
		$this->unicodedevanagaritextonly($this->name_unicode,'Name Unicode');
		$this->end_validation();
		$masterlabelname=labelname($this->connection,$this->namecategoryid,0);
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		$query = "select count(*) as cnt from namedetail n where n.active=1 and n.name_eng='".$this->name_eng."' and n.name_unicode='".$this->name_unicode."'";
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
        if ($row["CNT"] > 0)
		{
			$this->invalidid=-201;
			$this->invalidmessagetext=$this->masterlabelname.' is already exists';
		}
		else
		{
			$this->invalidid=0;
			$this->invalidmessagetext='Validated';
		}
		$this->end_validation();
		return $this->invalidid;
	}

	private function idvalidation()
	{
		$this->start_validation();
		if (isset($this->namedetailid))
		{
			$this->namedetailid = 0;
		}
		$query = "select count(*) as cnt from namedetail n where n.active=1 and n.namedetailid=".$this->namedetailid;
		/* $result = mysqli_query($this->connection, "select count(*) as cnt from namedetail n,masterbase a where n.namedetailid=a.namedetailid and n.active=1 and a.active=1 and n.namedetailid=".$this->namedetailid);
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		if ($row["CNT"] > 0) */
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
        if ($row["CNT"] > 0)
		{
			$this->invalidid=-201;
			$this->invalidmessagetext=$this->masterlabelname.' Id is already exists';
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
		$this->dataoperationmode =operationmode::Insert;
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
		/* elseif ($this->idvalidation() <> 0)
		{
			return 0;
			exit;
		}	 */
		$query = "select nvl(max(namedetailid),248751523)+147 as namedetailid from namedetail";
		/* $result = mysqli_query($this->connection, "select nvl(max(namedetailid),248751523)+147 as namedetailid from namedetail");
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result); */
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
		$namedetailid = $row["NAMEDETAILID"];
		$this->name_eng = ucfirst(strtolower(trim($this->name_eng)));
		$this->name_unicode = trim($this->name_unicode);
		$query = "insert into namedetail(transactionid,namedetailid,namecategoryid,name_unicode,name_eng,active,cruserid) 
		values ((select nvl(max(transactionid),0)+1 from namedetail),$namedetailid,$this->namecategoryid,'$this->name_unicode','$this->name_eng',1,".$_SESSION["usersid"].")";
		/* $result = oci_parse($this->connection, $query); if (oci_execute($result,OCI_NO_AUTO_COMMIT)) */
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
		$this->dataoperationmode =operationmode::Select;
		$cond='';
		if ($this->name_eng!='')
		{
			if ($cond=='')
			{
				$cond=$cond."name_eng like '%".$this->name_eng."%'";
			}
			else
			{
				$cond=$cond." and name_eng like '%".$this->name_eng."%'";
			}
		}
		if ($this->name_unicode!='')
		{
			if ($cond=='')
			{
				$cond=$cond."name_unicode like '%".$this->name_unicode."%'";
			}
			else
			{
				$cond=$cond." and name_unicode like '%".$this->name_unicode."%'";
			}
		}
		//echo $cond;
		if ($cond!='')
		{
			$query = 'select namedetailid,name_unicode,name_eng from namedetail n where  n.active=1 and namecategoryid='.$this->namecategoryid.' and '.$cond;
			$result = oci_parse($this->connection, $query);
			$r = oci_execute($result);
			return $result;
		}
		else
		{
			$query = 'select namedetailid,name_unicode,name_eng from namedetail n where  n.active=1 and namecategoryid='.$this->namecategoryid;
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
		$query = "select namedetailid from namedetail where active=1 and namecategoryid=".$this->namecategoryid." and namedetailid=".$this->namedetailid;
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
		$namedetailid = $row["NAMEDETAILID"];
		$query = "update namedetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and namecategoryid=".$this->namecategoryid." and namedetailid=".$namedetailid;
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
		
		$query = "select namedetailid from namedetail where active=1 and namecategoryid=".$this->namecategoryid." and namedetailid=".$this->namedetailid;
		$result = oci_parse($connection, $query);
		$r = oci_execute($result);
		$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
		$namedetailid = $row["NAMEDETAILID"];
		$query = "update namedetail set active=0,dluserid=".$_SESSION["usersid"]." where active=1 and namedetailid=".$namedetailid;
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

	function labelname(&$connection,$namecategoryid,$lng)
    {
        $query = "select n.* from namecategory n where n.active=1 and namecategoryid=".$namecategoryid;
        $result=mysqli_query($connection, $query);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS)) 
        {
            if ($lng==0)
            {
                return $row['NAMECATEGORYNAME_ENG'];
            }
            else
            {
                return $row['NAMECATEGORYNAME'];
            }
        }
    }
}
?>