<?php
include("../swappbase/formbase.php");
class supplier extends swappform
{	
    public $suppliercode;
    public $suppliernameuni;
    public $suppliernameeng;
    public $address;
    public $contactnumber;
    public $emailid;
     public $gstnumber;
    public $vatnumber;


	public function __construct(&$connection)
	{
		parent::__construct($connection);
		$this->suppliercode = '';
        $this->suppliernameuni = '';
        $this->suppliernameeng = '';
        $this->address = '';
        $this->contactnumber='';
        $this->emailid='';
        $this->gstnumber='';
        $this->vatnumber='';

       
	}

	private function entryvalidation()
	{
		$this->start_validation();
		$this->checkrequired($this->suppliernameuni,'supplier Name Unicode');
		$this->unicodedevanagaritextonly($this->suppliernameuni,'supplier Name Unicode');
		
		$this->checkrequired($this->suppliernameeng,'supplier Name English');
		$this->englishtextonly($this->suppliernameeng,'supplier Name English');

		if ($this->contactnumber!='')
        {
        $this->englishdigitonly($this->contactnumber,'Contact number in digit');
        $this->checkmindatalength($this->contactnumber,'Contact Number',9);
        }

       	if ($this->emailid!='')
        {
        $this->checkemail($this->emailid,'Email ID');
        } 
        if ($this->gstnumber!='')
        {
        $this->englishtextdigitonly($this->gstnumber,'GST Number');
        $this->checkfixedlength($this->gstnumber,'GST Number',15);
        } 
        if ($this->vatnumber!='')
        {
        $this->englishtextdigitonly($this->vatnumber,'VAT Number');
        $this->checkfixedlength($this->vatnumber,'VAT Number',9);
        } 

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
		if ($this->suppliercode == '')
		{
			$query = "select nvl(max(suppliercode),0)+1 as suppliercode from supplier";
			$result = oci_parse($this->connection, $query);             
			$r = oci_execute($result);
			$row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->suppliercode = $row["SUPPLIERCODE"];
		}
		$query = "insert into supplier(suppliercode,suppliernameuni,suppliernameeng,address, contactnumber, emailid, gstnumber, vatnumber) 
        values (".$this->suppliercode.",'".$this->suppliernameuni."','".$this->suppliernameeng."',".$this->invl($this->address,false).",".$this->invl($this->contactnumber,false).","
        .$this->invl($this->emailid,false).",".$this->invl($this->gstnumber,false).",".$this->invl($this->vatnumber,false).")";
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
		if ($this->suppliernameuni!='')
		{
			if ($cond=='')
			{
				$cond="s.suppliernameuni like %'".$this->suppliernameuni."%'";
			}
			else
			{
				$cond=$cond." and suppliernameuni like %'".$this->suppliernameuni."%'";
			}
		}

        if ($this->suppliernameeng!='')
		{
			if ($cond=='')
			{
				$cond="s.suppliernameeng like %'".$this->suppliernameeng."%'";
			}
			else
			{
				$cond=$cond." and suppliernameeng like %'".$this->suppliernameeng."%'";
			}
		}
        if ($this->contactnumber!='')
		{
			if ($cond=='')
			{
				$cond="s.contactnumber like %'".$this->contactnumber."%'";
			}
			else
			{
				$cond=$cond." and contactnumber like %'".$this->contactnumber."%'";
			}
		}

         if ($this->emailid!='')
		{
			if ($cond=='')
			{
				$cond="s.emailid like %'".$this->emailid."%'";
			}
			else
			{
				$cond=$cond." and emailid like %'".$this->emailid."%'";
			}
		}

         if ($this->gstnumber!='')
		{
			if ($cond=='')
			{
				$cond="s.gstnumber like %'".$this->gstnumber."%'";
			}
			else
			{
				$cond=$cond." and gstnumber like %'".$this->gstnumber."%'";
			}
		}


        if ($cond!='')
		{
			$query = "select f.* from supplier f where ".$cond;
			$result = oci_parse($this->connection, $query);            
            $r = oci_execute($result);
			return $result;
		}
		else
		{
			$query = "select f.* from supplier f";
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
    	$query = "update supplier 
		set suppliernameuni='".$this->suppliernameuni."',
		suppliernameeng='".$this->suppliernameeng."', 
		address=".$this->invl($this->address,false).",
        contactnumber=".$this->invl($this->contactnumber,false).",
        emailid=".$this->invl($this->emailid,false).",
        gstnumber=".$this->invl($this->gstnumber,false).",
        vatnumber=".$this->invl($this->vatnumber,false)."
		where suppliercode=".$this->suppliercode;
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
		$query = "delete from supplier where suppliercode=".$this->suppliercode;
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

	public function fetch($suppliercode)
	{
		$this->dataoperationmode = operationmode::Select;
		$query = "select suppliercode,suppliernameuni,suppliernameeng, address, contactnumber, emailid, gstnumber, vatnumber 
		from supplier i where i.suppliercode=".$suppliercode;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->suppliercode = $row['SUPPLIERCODE'];
			$this->suppliernameuni = $row['SUPPLIERNAMEUNI'];
			$this->suppliernameeng = $row['SUPPLIERNAMEENG'];
			$this->address = $row['ADDRESS'];
            $this->contactnumber = $row['CONTACTNUMBER'];
            $this->emailid = $row['EMAILID'];
            $this->gstnumber = $row['GSTNUMBER'];
            $this->vatnumber = $row['VATNUMBER'];
            
			return true;
		}
		else
		{
			return false;
		}
	}

}
?>