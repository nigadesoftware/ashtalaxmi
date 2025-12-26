<?php
include("../api_base/formbase.php");
class mismodule extends swappform
{	
	public $mismoduleid;
	public $mismodulename_eng;
	public $modulefolder;

	public function __construct(&$connection)
	{
		parent::__construct($connection);
		$this->mismoduleid='';
		$this->mismodulename_eng='';
		$this->modulefolder='';
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->mismodulename_eng,'Module');
		$this->englishtextdigitonly($this->mismodulename_eng,'Module');
		$this->checkrequired($this->modulefolder,'Module Folder');
		$this->englishtextdigitonly($this->modulefolder,'Module Folder');
		$this->end_validation();
		return $this->invalidid;
	}

	private function datavalidation()
	{
		$this->start_validation();
		if ($this->mismoduleid =='')
		{
			$result1 = mysqli_query($this->connection, "select count(*) as cnt from mismodule a where a.active=1 and mismodulename_eng='".$this->mismodulename_eng."'");
		}
		else
		{
			$result1 = mysqli_query($this->connection, "select count(*) as cnt from mismodule a where a.active=1 and mismoduleid<>".$this->mismoduleid." and mismodulename_eng='".$this->mismodulename_eng."'");
		}
		$row1 = mysqli_fetch_assoc($result1);
		if ($row1["cnt"] > 0)
		{
			$this->invalidid=-201;
			$this->invalidmessagetext='Module is already exists';
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
		if ($this->mismoduleid=='')
        {
			$query = "select ifnull(max(mismoduleid),123456789)+317 as mismoduleid from mismodule";
			//echo $query;
			$result1 = mysqli_query($this->connection, $query);
			$row1 = mysqli_fetch_assoc($result1);
			$this->mismoduleid = $row1["mismoduleid"];
		}
		$this->mismodulename_eng = trim($this->mismodulename_eng);
		$query1 = "insert into mismodule(mismoduleid,mismodulename_eng,modulefolder,active,cruserid,crdatetime) values ($this->mismoduleid,'$this->mismodulename_eng','$this->modulefolder',1,".$_SESSION["usersid"].",'".currentdatetime()."')";
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
		if ($this->mismodulename_eng!='')
		{
			if ($cond=='')
			{
				$cond=$cond."mismodulename_eng like '%".$this->mismodulename_eng."%'";
			}
			else
			{
				$cond=$cond." and mismodulename_eng like '%".$this->mismodulename_eng."%'";
			}
		}
		//echo $cond;
		if ($cond!='')
		{
			$query = "select n.* from mismodule n where n.active=1 and ".$cond." order by mismodulename_eng";
			$result1 = mysqli_query($this->connection, $query);
			return $result1;
		}
		else
		{
			$query = "select n.* from mismodule n where n.active=1 order by mismodulename_eng limit 1000";
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
		$query1 = "update mismodule set active=0,dluserid=".$_SESSION["usersid"].",dldatetime='".currentdatetime()."' where active=1 and mismoduleid=".$this->mismoduleid;
		
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
		
		$query1 = "update mismodule set active=0,dluserid=".$_SESSION["usersid"].",dldatetime='".currentdatetime()."' where active=1 and mismoduleid=".$this->mismoduleid;
		
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