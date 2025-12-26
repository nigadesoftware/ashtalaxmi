<?php
include("../api_base/formbase.php");
class mismoduleresponsibility extends swappform
{	
	public $mismoduleresponsibilityid;
    public $mismoduleid;
	public $misresponsibilityid;

	public function __construct(&$connection)
	{
		parent::__construct($connection);
		$this->mismoduleresponsibilityid='';
		$this->misresponsibilityid='';
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->mismoduleid,'Module');
		$this->checkrequired($this->misresponsibilityid,'Responsibility');
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		if ($this->mismoduleresponsibilityid =='')
		{
			$result1 = mysqli_query($this->connection, "select count(*) as cnt from mismoduleresponsibility a where a.active=1 and mismoduleid=".$this->mismoduleid." and misresponsibilityid=".$this->misresponsibilityid);
		}
		else
		{
			$result1 = mysqli_query($this->connection, "select count(*) as cnt from mismoduleresponsibility a where a.active=1 and mismoduleresponsibilityid<>".$this->mismoduleresponsibilityid." and mismoduleid=".$this->mismoduleid." and misresponsibilityid=".$this->misresponsibilityid);
		}
		$row1 = mysqli_fetch_assoc($result1);
		if ($row1["cnt"] > 0)
		{
			$this->invalidid=-201;
			$this->invalidmessagetext='Module Responsibility is already exists';
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
		if ($this->mismoduleresponsibilityid=='')
        {
			$query = "select ifnull(max(mismoduleresponsibilityid),123456789)+317 as mismoduleresponsibilityid from mismoduleresponsibility where mismoduleresponsibilityid<99999999";
			//echo $query;
			$result1 = mysqli_query($this->connection, $query);
			$row1 = mysqli_fetch_assoc($result1);
			$this->mismoduleresponsibilityid = $row1["mismoduleresponsibilityid"];
		}
		$query1 = "insert into mismoduleresponsibility(mismoduleresponsibilityid,mismoduleid,misresponsibilityid,active,cruserid,crdatetime) values ($this->mismoduleresponsibilityid,$this->mismoduleid,$this->misresponsibilityid,1,".$_SESSION["usersid"].",'".currentdatetime()."')";
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
		$this->dataoperationmode=operationmode::Select;
		$cond='';
		if ($this->mismoduleresponsibilityname_eng!='')
		{
			if ($cond=='')
			{
				$cond=$cond."mismoduleid = ".$this->mismoduleid;
			}
			else
			{
				$cond=$cond." and mismoduleid = ".$this->mismoduleid;
			}
            if ($cond=='')
			{
				$cond=$cond."misresponsibilityid = ".$this->misresponsibilityid;
			}
			else
			{
				$cond=$cond." and misresponsibilityid = ".$this->misresponsibilityid;
			}
		}
		//echo $cond;
		if ($cond!='')
		{
			$query = "select n.* from mismoduleresponsibility n where n.active=1 and ".$cond;
			$result1 = mysqli_query($this->connection, $query);
			return $result1;
		}
		else
		{
			$query = "select n.* from mismoduleresponsibility n where n.active=1 limit 1000";
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
		$query1 = "update mismoduleresponsibility set active=0,dluserid=".$_SESSION["usersid"].",dldatetime='".currentdatetime()."' where active=1 and mismoduleresponsibilityid=".$this->mismoduleresponsibilityid;
		
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
		
		$query1 = "update mismoduleresponsibility set active=0,dluserid=".$_SESSION["usersid"].",dldatetime='".currentdatetime()."' where active=1 and mismoduleresponsibilityid=".$this->mismoduleresponsibilityid;
		
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