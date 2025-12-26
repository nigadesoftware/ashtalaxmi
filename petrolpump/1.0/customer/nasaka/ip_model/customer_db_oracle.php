<?php
include("../swappbase/formbase.php");
class customer extends swappform
{	
    public $customercode;
    public $customernameuni;
    public $customernameeng;
    public $vehiclenumber;
    public $refcode;
    public $customertypecode;


	public function __construct(&$connection)
	{
		parent::__construct($connection);
		$this->customercode = '';
        $this->customernameuni = '';
        $this->customernameeng = '';
        $this->vehiclenumber = '';
        $this->refcode='';
        $this->customertypecode='';
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->customernameuni,'Customer Name Unicode');
		$this->unicodedevanagaritextonly($this->customernameuni,'Customer Name Unicode');
		
		$this->checkrequired($this->customernameeng,'Customer Name English');
		$this->englishtextonly($this->customernameeng,'Customer Name English');
		
		$this->checkrequired($this->vehiclenumber,'Vehicle No.');
		$this->englishtextdigitonly($this->refcode,'Reference code');
		
		$this->checkrequired($this->customertypecode,'Cusomer Type code');
        $this->englishtextdigitonly($this->customertypecode,'Cusomer Type code');

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
		if ($this->customercode == '')
		{
			$query = "select nvl(max(customercode),0)+1 as customercode from customer";
			$result = oci_parse($this->connection, $query);             
			$r = oci_execute($result);
			$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->customercode = $row["CUSTOMERCODE"];
		}
		$query = "insert into customer(customercode,customernameuni,customernameeng,vehiclenumber, refcode,customertypecode) values (".$this->customercode.",'".$this->customernameuni."','".$this->customernameeng."','".$this->vehiclenumber."',".$this->invl($this->refcode,false).",".$this->customertypecode.")";
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
		if ($this->customernameuni!='')
		{
			if ($cond=='')
			{
				$cond="s.customernameuni like %'".$this->customernameuni."%'";
			}
			else
			{
				$cond=$cond." and customernameuni like %'".$this->customernameuni."%'";
			}
		}

        if ($this->customernameeng!='')
		{
			if ($cond=='')
			{
				$cond="s.customernameeng like %'".$this->customernameeng."%'";
			}
			else
			{
				$cond=$cond." and customernameeng like %'".$this->customernameeng."%'";
			}
		}
        if ($this->vehiclenumber!='')
		{
			if ($cond=='')
			{
				$cond="s.vehiclenumber like %'".$this->vehiclenumber."%'";
			}
			else
			{
				$cond=$cond." and vehiclenumber like %'".$this->vehiclenumber."%'";
			}
		}

         if ($this->refcode!='')
		{
			if ($cond=='')
			{
				$cond="s.refcode like %'".$this->refcode."%'";
			}
			else
			{
				$cond=$cond." and refcode like %'".$this->refcode."%'";
			}
		}


        if ($this->customertypecode!='' and $this->customertypecode!="0")
            {
                if ($cond=='')
                {
                    $cond="s.customertypecode =".$this->customertypecode;
                }
                else
                {
                    $cond=$cond." and customertypecode =".$this->customertypecode;
                }
            }

        if ($cond!='')
		{
			$query = "select s.* from customer s where ".$cond;
			$result = oci_parse($this->connection, $query);            
            $r = oci_execute($result);
			return $result;
		}
		else
		{
			$query = "select f.* from customer f";
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
    	$query = "update customer 
		set customernameuni='".$this->customernameuni."',
		customernameeng='".$this->customernameeng."', 
		vehiclenumber='".$this->vehiclenumber."',
        refcode=".$this->refcode.",
        customertypecode=".$this->customertypecode."
		where customercode=".$this->customercode;
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
		$query = "delete from customer where customercode=".$this->customercode;
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

	public function fetch($customercode)
	{
		$this->dataoperationmode = operationmode::Select;
		$query = "select customercode,customernameuni,customernameeng,vehiclenumber, refcode, customertypecode 
		from customer i where i.customercode=".$customercode;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->customercode = $row['CUSTOMERCODE'];
			$this->customernameuni = $row['CUSTOMERNAMEUNI'];
			$this->customernameeng = $row['CUSTOMERNAMEENG'];
			$this->vehiclenumber = $row['VEHICLENUMBER'];
            $this->customertypecode = $row['CUSTOMERTYPECODE'];
            $this->refcode = $row['REFCODE'];
			return true;
		}
		else
		{
			return false;
		}
	}

}
?>