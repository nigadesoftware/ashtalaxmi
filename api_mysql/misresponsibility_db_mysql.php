<?php
include("../api_base/formbase.php");
class misresponsibility extends swappform
{	
	public $misresponsibilityid;
	public $misresponsibilityname;

	public function __construct(&$connection)
	{
		parent::__construct($connection);
		$this->misresponsibilityid='';
		$this->misresponsibilityname='';
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->misresponsibilityname,'Name English');
		$this->englishtextdigitonly($this->misresponsibilityname,'Name English');
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		$result1 = mysqli_query($this->connection, "select count(*) as cnt from nasaka_db.misresponsibility a where a.active=1 and misresponsibilityname='".$this->misresponsibilityname."'");
		$row1 = mysqli_fetch_assoc($result1);
		if ($row1["cnt"] > 0)
		{
			$this->invalidid=-201;
			$this->invalidmessagetext='Misresponsibility is already exists';
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
		if ($this->misresponsibilityid=='')
        {
			$query = "select ifnull(max(misresponsibilityid),123456789)+317 as misresponsibilityid 
			from nasaka_db.misresponsibility 
			where misresponsibilityid<999999999";
			//echo $query;
			$result1 = mysqli_query($this->connection, $query);
			$row1 = mysqli_fetch_assoc($result1);
			$this->misresponsibilityid = $row1["misresponsibilityid"];
		}
		$this->misresponsibilityname = trim($this->misresponsibilityname);
		$query1 = "insert into nasaka_db.misresponsibility(misresponsibilityid,misresponsibilityname,misactive,cruserid,crdatetime) values ($this->misresponsibilityid,'$this->misresponsibilityname',1,".$_SESSION["usersid"].",'".currentdatetime()."')";
		if (mysqli_query($this->connection, $query1))
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
		if ($this->misresponsibilityname!='')
		{
			if ($cond=='')
			{
				$cond=$cond."misresponsibilityname like '%".$this->misresponsibilityname."%'";
			}
			else
			{
				$cond=$cond." and misresponsibilityname like '%".$this->misresponsibilityname."%'";
			}
		}
		//echo $cond;
		if ($cond!='')
		{
			$query = "select n.* from nasaka_db.misresponsibility n where n.misactive=1 and ".$cond." order by misresponsibilityname";
			$result1 = mysqli_query($this->connection, $query);
			return $result1;
		}
		else
		{
			$query = "select n.* from nasaka_db.misresponsibility n where n.misactive=1 order by misresponsibilityname limit 1000";
			$result1 = mysqli_query($this->connection, $query);
			return $result1;
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
		$query1 = "update nasaka_db.misresponsibility set misactive=0,dluserid=".$_SESSION["usersid"].",dldatetime='".currentdatetime()."' where misactive=1 and misresponsibilityid=".$this->misresponsibilityid;
		
		if (mysqli_query($this->connection, $query1))
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
		
		$query1 = "update nasaka_db.misresponsibility set misactive=0,dluserid=".$_SESSION["usersid"].",dldatetime='".currentdatetime()."' where misactive=1 and misresponsibilityid=".$this->misresponsibilityid;
		
		if (mysqli_query($this->connection, $query1))
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