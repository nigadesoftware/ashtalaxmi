<?php
include("../swappbase/formbase.php");
class saleheader extends swappform
{	
    public $transactionid;
	public $entityglobalgroupid;
	public $customertypecode;
	public $transactioncategoryid; 
	public $transactionsubcategoryid;
    public $documentnumberseriesid; 
	public $petrolpumpcode; 
    public $shiftcode;
	public $pumpcode; 
    public $invoicenumber; 
    public $invoicenumber_suffpref; 
    public $invoicedate; 
    public $customercode; 
    public $invoiceamount; 
	public $narration;
	public $entryspecial;
	//related data
	public $customernameeng;
	public $refcode;
	public function __construct(&$connection)
	{
		parent::__construct($connection);
        $this->transactionid= ''; 
		$this->customertypecode = ''; 
		$this->transactioncategoryid ='';
		$this->transactionsubcategoryid = '';
        //$this->documentnumberseriesid= ''; 
        $this->invoicenumber= ''; 
        $this->invoicenumber_suffpref= ''; 
        $this->invoicedate= ''; 
        $this->customercode= ''; 
		$this->petrolpumpcode='';
		$this->pumpcode='';
        $this->shiftcode= ''; 
        $this->invoiceamount= ''; 
		$this->narration= '';
		$this->entryspecial='';
	}

	private function entryvalidation()
	{
		$this->start_validation();
        $this->checkrequired($this->invoicedate,'Invoice Date'); 
		$this->checkrequired($this->pumpcode,'Pump');
		if ($this->transactioncategoryid==2)
		{
			$this->checkrequired($this->customertypecode,'Customer Type');
			$this->checkrequired($this->customercode,'Customer');
		}
		else
		{
			$this->customertypecode =248803647;
			$this->customercode =1933;
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

	public function generatesaleinvoice()
	{
		if (!isset($this->documentnumberseriesid))
		{
			$query = "select documentnumberseriesid from nst_nasaka_db.entitydocumenttransseries where entityglobalgroupid=".$this->entityglobalgroupid." and transactioncategoryid=".$this->transactioncategoryid." and transactionsubcategoryid=".$this->transactionsubcategoryid;
			$result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
			if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
			{
				$this->documentnumberseriesid = $row["DOCUMENTNUMBERSERIESID"];
			}
			else
			{
				$this->invalidid=-210;
				$this->invalidmessagetext='Document Series not defined';
				return 0;
				exit;
			}
		}
		if (!isset($this->invoicenumber) or $this->invoicenumber == '' )
		{
			$query = "select nvl(d.documentnumberprefix,'') as documentnumberprefix from nst_nasaka_db.documentnumberseries d where d.documentnumberseriesid=".$this->documentnumberseriesid;
			$result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
			if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
			{
				$pos = strpos($row["DOCUMENTNUMBERPREFIX"],'@fy',0);
				if ($pos !=false)
				{
					$prefix = substr($row["DOCUMENTNUMBERPREFIX"],0,$pos);
					$suffix = substr($row["DOCUMENTNUMBERPREFIX"],$pos+3,strlen($row["DOCUMENTNUMBERPREFIX"]));
					$query_11 = "select periodname_eng from nst_nasaka_db.yearperiod f where f.yearperiodcode=".$this->yearperiodcode;
					$result_11 = oci_parse($this->connection, $query_11);
            		$r = oci_execute($result_11);
					if ($row_11 = oci_fetch_array($result_11,OCI_ASSOC+OCI_RETURN_NULLS))
					{
						$prefix .= substr($row_11['PERIODNAME_ENG'],2,2).'-'.substr($row_11['PERIODNAME_ENG'],7,2).$suffix;
					}
					else
					{
						return 0;
						$this->invalidid=-200;
						$this->invalidmessagetext='Communication Error';
						exit;
					}
				}
				else
				{
					$prefix = $row["DOCUMENTNUMBERPREFIX"];
				}
			}
			else
			{
				return 0;
				$this->invalidid=-200;
				$this->invalidmessagetext='Communication Error';
				exit;
			}
			$query_12 = "select nvl(max(invoicenumber),0)+1 as invoicenumber from saleheader where entityglobalgroupid=".$this->entityglobalgroupid." and yearperiodcode=".$this->yearperiodcode." and documentnumberseriesid=".$this->documentnumberseriesid;
			$result_12 = oci_parse($this->connection, $query_12);
            $r = oci_execute($result_12);
			$row_12 = oci_fetch_array($result_12,OCI_ASSOC+OCI_RETURN_NULLS);
			$this->invoicenumber = $row_12["INVOICENUMBER"];
			$this->invoicenumber_suffpref = $prefix.str_pad($row_12["INVOICENUMBER"],5,'0',STR_PAD_LEFT);
			$documentnumber = $row_12["INVOICENUMBER"];
			$invoicenumber_suffpref = $prefix.str_pad($row_12["INVOICENUMBER"],5,'0',STR_PAD_LEFT);
		}
		else
		{
			$query = "select invoicenumber,invoicenumber_suffpref from saleheader d where d.transactionid=".$this->transactionid;
			$result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
			if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
			{
				$this->invoicenumber = $row["INVOICENUMBER"];
				$this->invoicenumber_prefixsuffix = $row["INVOICENUMBER_SUFFPREF"];
				$invoicenumber = $row["INVOICENUMBER"];
				$invoicenumber_suffpref = $row["INVOICENUMBER_SUFFPREF"];
			}
		}
		return 1;
		$this->invalidid=0;
	}

    //This is generated by Swapp Software Application on 21/10/2018 11:44:58 AM for PHP
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
            $query = "select nvl(max(transactionid),0)+1 as transactionid from saleheader";
            $result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
            $row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
            $this->transactionid = $row["TRANSACTIONID"];
        }
		if ($this->invoicedate!='')
		{
			$this->invoicedate = DateTime::createFromFormat('d/m/Y',$this->invoicedate)->format('d-M-Y');	
		}
		$this->generatesaleinvoice();
        $query = "
            insert into saleheader(
            transactionid
            ,entityglobalgroupid
            ,yearperiodcode
			,customertypecode
			,transactioncategoryid
            ,transactionsubcategoryid
            ,shiftcode
			,petrolpumpcode
            ,pumpcode
            ,invoicenumber
            ,documentnumberseriesid
            ,invoicenumber_suffpref
            ,invoicedate
            ,customercode
            ,invoiceamount
			,narration
			,entryspecial
	        )
	        values (
            ".$this->invl($this->transactionid,true)."
            ,".$this->invl($this->entityglobalgroupid,true)."
            ,".$this->invl($this->yearperiodcode,true)."
            ,".$this->invl($this->customertypecode,true)."
			,".$this->invl($this->transactioncategoryid,true)."
			,".$this->invl($this->transactionsubcategoryid,true)."
            ,".$this->invl($this->shiftcode,true)."
            ,".$this->invl($this->petrolpumpcode,true)."
			,".$this->invl($this->pumpcode,true)."
            ,".$this->invl($this->invoicenumber,true)."
            ,".$this->invl($this->documentnumberseriesid,true)."
            ,".$this->invl($this->invoicenumber_suffpref,false)."
            ,".$this->invl($this->invoicedate,false)."
            ,".$this->invl($this->customercode,true)."
            ,".$this->invl($this->invoiceamount,true)."
			,".$this->invl($this->narration,false)."
			,".$this->invl($this->entryspecial,true)."
            )";
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

public function display()
	{
		$this->dataoperationmode = operationmode::Select;
		$cond='';
		if ($this->entityglobalgroupid!='' and $this->entityglobalgroupid!=0)
		{
			if ($cond=='')
			{
				$cond="entityglobalgroupid = ".$this->entityglobalgroupid;
			}
			else
			{
				$cond=$cond." and entityglobalgroupid = ".$this->entityglobalgroupid;
			}
		}
		if ($this->yearperiodcode!='' and $this->yearperiodcode!=0)
		{
			if ($cond=='')
			{
				$cond="yearperiodcode = ".$this->yearperiodcode;
			}
			else
			{
				$cond=$cond." and yearperiodcode = ".$this->yearperiodcode;
			}
		}
		if ($this->customertypecode!='' and $this->customertypecode!=0)
		{
			if ($cond=='')
			{
				$cond="customertypecode = ".$this->customertypecode;
			}
			else
			{
				$cond=$cond." and customertypecode = ".$this->customertypecode;
			}
		}
		if ($this->transactioncategoryid!='' and $this->transactioncategoryid!=0)
		{
			if ($cond=='')
			{
				$cond="transactioncategoryid = ".$this->transactioncategoryid;
			}
			else
			{
				$cond=$cond." and transactioncategoryid = ".$this->transactioncategoryid;
			}
		}
		if ($this->transactionsubcategoryid!='' and $this->transactionsubcategoryid!=0)
		{
			if ($cond=='')
			{
				$cond="transactionsubcategoryid = ".$this->transactionsubcategoryid;
			}
			else
			{
				$cond=$cond." and transactionsubcategoryid = ".$this->transactionsubcategoryid;
			}
		}
		if ($this->shiftcode!='' and $this->shiftcode!=0)
		{
			if ($cond=='')
			{
				$cond="shiftcode = ".$this->shiftcode;
			}
			else
			{
				$cond=$cond." and shiftcode = ".$this->shiftcode;
			}
		}
		if ($this->pumpcode!='' and $this->pumpcode!=0)
		{
			if ($cond=='')
			{
				$cond="pumpcode = ".$this->pumpcode;
			}
			else
			{
				$cond=$cond." and pumpcode = ".$this->pumpcode;
			}
		}
		if ($this->documentnumberseriesid!='' and $this->documentnumberseriesid!=0)
		{
			if ($cond=='')
			{
				$cond="documentnumberseriesid = ".$this->documentnumberseriesid;
			}
			else
			{
				$cond=$cond." and documentnumberseriesid = ".$this->documentnumberseriesid;
			}
		}
		if ($this->invoicenumber_suffpref!='')
		{
			if ($cond=='')
			{
				$cond="invoicenumber_suffpref like %'".$this->invoicenumber_suffpref."%'";
			}
			else
			{
				$cond=$cond." and invoicenumber_suffpref like %'".$this->invoicenumber_suffpref."%'";
			}
		}
		if ($this->invoicedate!='')
		{
			$this->invoicedate = DateTime::createFromFormat('d/m/Y',$this->invoicedate)->format('d-M-Y');
			if ($cond=='')
			{
				$cond="invoicedate = '".$this->invoicedate."'";
			}
			else
			{
				$cond=$cond." and invoicedate = '".$this->invoicedate."'";
			}
		}
		if ($this->customercode!='' and $this->customercode!=0)
		{
			if ($cond=='')
			{
				$cond="customercode = ".$this->customercode;
			}
			else
			{
				$cond=$cond." and customercode = ".$this->customercode;
			}
		}
		if ($this->invoiceamount!='' and $this->invoiceamount!=0)
		{
			if ($cond=='')
			{
				$cond="invoiceamount = ".$this->invoiceamount;
			}
			else
			{
				$cond=$cond." and invoiceamount = ".$this->invoiceamount;
			}
		}
		if ($this->narration!='')
		{
			if ($cond=='')
			{
				$cond="narration like %'".$this->narration."%'";
			}
			else
			{
				$cond=$cond." and narration like %'".$this->narration."%'";
			}
		}
		if ($this->petrolpumpcode!='' and $this->petrolpumpcode!=0)
		{
			if ($cond=='')
			{
				$cond="petrolpumpcode = ".$this->petrolpumpcode;
			}
			else
			{
				$cond=$cond." and petrolpumpcode = ".$this->petrolpumpcode;
			}
		}
		if ($this->entryspecial!='' and $this->entryspecial!=0)
		{
			if ($cond=='')
			{
				$cond="entryspecial = ".$this->entryspecial;
			}
			else
			{
				$cond=$cond." and entryspecial = ".$this->entryspecial;
			}
		}
		
 		if ($cond!='')
		{
			$query = "select t.* from saleheader t where ".$cond;
			$result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
			return $result;
		}
		else
		{
			$query = "select t.* from saleheader t";
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
		if ($this->invoicedate!='')
		{
			$this->invoicedate = DateTime::createFromFormat('d/m/Y',$this->invoicedate)->format('d-M-Y');	
		}
		$query = "update saleheader 
		set invoicedate='".$this->invoicedate."'
		,customercode=".$this->customercode."
		,narration=".$this->invl($this->narration,false)."
		,entryspecial=".$this->invl($this->entryspecial,true)."
		,pumpcode=".$this->invl($this->pumpcode,true)."
		where transactionid=".$this->transactionid;
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
		$query = "delete from saledetail where reftransactionid=".$this->transactionid;
    	$result = oci_parse($this->connection, $query);
		if (oci_execute($result,OCI_NO_AUTO_COMMIT))
		{
			$query1 = "delete from saleheader where transactionid=".$this->transactionid;
			$result1 = oci_parse($this->connection, $query1);
			if (oci_execute($result1,OCI_NO_AUTO_COMMIT))
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

	public function fetch()
    {
	$this->dataoperationmode = operationmode::Select;
	$query = "select 
	transactionid
	,entityglobalgroupid
	,yearperiodcode
	,saleheader.customertypecode
	,transactioncategoryid
	,transactionsubcategoryid
	,shiftcode
	,pumpcode
	,invoicenumber
	,documentnumberseriesid
	,invoicenumber_suffpref
	,invoicedate
	,saleheader.customercode
	,invoiceamount
	,narration
	,entryspecial
	,petrolpumpcode
	,customer.customernameeng
	,customer.customernameuni 
	,customer.vehiclenumber
	,customer.refcode
    from saleheader,customer where saleheader.customercode=customer.customercode and   
    transactionid = ".$this->invl($this->transactionid,true)."
    ";
	$result = oci_parse($this->connection, $query);
	$r = oci_execute($result);
	if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
	{
			$this->transactionid = $row['TRANSACTIONID'];
			$this->entityglobalgroupid = $row['ENTITYGLOBALGROUPID'];
			$this->yearperiodcode = $row['YEARPERIODCODE'];
			$this->customertypecode = $row['CUSTOMERTYPECODE'];
			$this->transactioncategoryid = $row['TRANSACTIONCATEGORYID'];
			$this->transactionsubcategoryid = $row['TRANSACTIONSUBCATEGORYID'];
			$this->shiftcode = $row['SHIFTCODE'];
			$this->pumpcode = $row['PUMPCODE'];
			$this->invoicenumber = $row['INVOICENUMBER'];
			$this->documentnumberseriesid = $row['DOCUMENTNUMBERSERIESID'];
			$this->invoicenumber_suffpref = $row['INVOICENUMBER_SUFFPREF'];
			$this->invoicedate = $row['INVOICEDATE'];
			$this->customercode = $row['CUSTOMERCODE'];
			$this->invoiceamount = $row['INVOICEAMOUNT'];
			$this->narration = $row['NARRATION'];
			$this->entryspecial = $row['ENTRYSPECIAL'];
			$this->petrolpumpcode = $row['PETROLPUMPCODE'];
			$this->customernameeng = $row['CUSTOMERNAMEENG'];
			$this->customernameuni = $row['CUSTOMERNAMEUNI'];
			$this->refcode = $row['REFCODE'];
			$this->vehiclenumber = $row['VEHICLENUMBER'];
			$this->found=true;
			return true;
	}
	else
	{
		return false;
	}
}

}
?>