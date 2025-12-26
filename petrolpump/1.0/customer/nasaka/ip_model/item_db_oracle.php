<?php
include("../swappbase/formbase.php");
class item extends swappform
{	
    public $itemcode;
    public $itemnameuni;
    public $itemnameeng;
    public $unit;

	public function __construct(&$connection)
	{
		parent::__construct($connection);
		$this->itemcode = '';
        $this->itemnameuni = '';
        $this->itemnameeng = '';
        $this->unit = '';
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->itemnameuni,'Item Name Unicode');
		$this->unicodedevanagaritextonly($this->itemnameuni,'Item Name Unicode');
		$this->checkrequired($this->itemnameeng,'Item Name English');
		$this->englishtextonly($this->itemnameeng,'Item Name English');
		$this->checkrequired($this->unit,'Unit');
		$this->englishtextonly($this->unit,'Unit');
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
		if ($this->itemcode == '')
		{
			$query = "select nvl(max(itemcode),0)+1 as itemcode from item";
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->itemcode = $row["ITEMCODE"];
		}
		$query = "insert into item(itemcode,itemnameuni,itemnameeng,unit) values (".$this->itemcode.",'".$this->itemnameuni."','".$this->itemnameeng."','".$this->unit."')";
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
			$query = "select f.* from item f where ".$cond;
			$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
			return $result;
		}
		else
		{
			$query = "select f.* from item f";
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
    	$query = "update item 
		set itemnameuni='".$this->itemnameuni."',
		itemnameeng='".$this->itemnameeng."', 
		unit='".$this->unit."'
		where itemcode=".$this->itemcode;
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

	public function delete()
	{
		$this->dataoperationmode = operationmode::Delete;
		if ($this->entryvalidation() <> 0)
		{
			return 0;
			exit;
		}
		$query = "delete from item where itemcode=".$this->itemcode;
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

	public function fetch($itemcode)
	{
		$this->dataoperationmode = operationmode::Select;
		$query = "select itemcode,itemnameuni,itemnameeng,unit 
		from item i where i.itemcode=".$itemcode;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->itemcode = $row['ITEMCODE'];
			$this->itemnameuni = $row['ITEMNAMEUNI'];
			$this->itemnameeng = $row['ITEMNAMEENG'];
			$this->unit = $row['UNIT'];
			return true;
		}
		else
		{
			return false;
		}
	}

}
?>