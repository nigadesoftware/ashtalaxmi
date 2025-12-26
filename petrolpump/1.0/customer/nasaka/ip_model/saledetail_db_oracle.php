<?php
//This is geneperd by Swapp Software Application on 23/10/2018 04:40:52 PM for PHP
include("../swappbase/formbase.php");
class saledetail extends swappform
{
    Public $transactionid;
    Public $reftransactionid;
    Public $itemcode;
    Public $qty;
    Public $rate;
    Public $amount;
    public $wbslipnumber;
    //
    Public $itemnameeng;
    Public $itemnameuni;
    Public $maxqty;
    public function __construct(&$connection)
    {
     	 parent::__construct($connection);
         $this->transactionid='';
         $this->reftransactionid='';
         $this->itemcode='';
         $this->qty='';
         $this->rate='';
         $this->amount='';
         $this->wbslipnumber = '';
	   }
    private function entryvalidation()
    {
    	$this->start_validation();
        $this->checkrequired($this->itemcode,'Item');
        $this->checkrequired($this->qty,'Quantity');
        $this->checkrequired($this->rate,'Rate');
        $this->amount = $this->qty * $this->rate;
        if ($this->maxqty>0)
        {
            if ($this->qty>$this->maxqty)
            {
                $this->invalidid=-1;
    		    $this->invalidmessagetext='Qty Invalid';
            }
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
    //This is geneperd by Swapp Software Application on 23/10/2018 04:40:52 PM for PHP
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
            $query = "select nvl(max(transactionid),0)+1 as transactionid from saledetail";
            $result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
            $row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
            $this->transactionid = $row["TRANSACTIONID"];
        }
        $query = "
            insert into saledetail(
            itemcode
            ,qty
            ,rate
            ,amount
            ,reftransactionid
            ,transactionid
            ,wbslipnumber
	        )
	        values (
            ".$this->invl($this->itemcode,true)."
            ,".$this->invl($this->qty,true)."
            ,".$this->invl($this->rate,true)."
            ,".$this->invl($this->amount,true)."
            ,".$this->invl($this->reftransactionid,true)."
            ,".$this->invl($this->transactionid,true)."
            ,".$this->invl($this->wbslipnumber,true)."
            )";
        $result = oci_parse($this->connection, $query);
        if (oci_execute($result,OCI_NO_AUTO_COMMIT))
        {
            if ($this->updateheader()==1)
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
    public function display()
	  {
		    $this->dataoperationmode = operationmode::Select;
		    $cond='';
		    if ($this->itemcode!='' and $this->itemcode!=0)
		    {
			      if ($cond=='')
			      {
				        $cond="itemcode = ".$this->itemcode;
			       }
			       else
			       {
				         $cond=$cond." and itemcode = ".$this->itemcode;
			       }
		    }
		    if ($this->qty!='' and $this->qty!=0)
		    {
			      if ($cond=='')
			      {
				        $cond="qty = ".$this->qty;
			       }
			       else
			       {
				         $cond=$cond." and qty = ".$this->qty;
			       }
		    }
		    if ($this->rate!='' and $this->rate!=0)
		    {
			      if ($cond=='')
			      {
				        $cond="rate = ".$this->rate;
			       }
			       else
			       {
				         $cond=$cond." and rate = ".$this->rate;
			       }
            }
            if ($this->webslipnumber!='' and $this->webslipnumber!=0)
            {
                if ($cond=='')
                {
                    $cond="webslipnumber = ".$this->webslipnumber;
                }
                else
                {
                    $cond=$cond." and webslipnumber = ".$this->webslipnumber;
                }
            }
        if ($cond!='')
		    {
		        $query = "select t.* from saledetail t where ".$cond;
			      $result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
			      return $result;
		     }
		     else
		     {
		         $query = "select t.* from saledetail t";
			       $result = oci_parse($this->connection, $query);
             $r = oci_execute($result);
			       return $result;
		     }
	  }
    //This is geneperd by Swapp Software Application on 23/10/2018 04:40:52 PM for PHP
    public function update()
    {
        $this->dataoperationmode = operationmode::Update;
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
        $query = "
        update saledetail set 
        transactionid = ".$this->invl($this->transactionid,true)."
        ,reftransactionid = ".$this->invl($this->reftransactionid,true)."
        ,itemcode = ".$this->invl($this->itemcode,true)."
        ,qty = ".$this->invl($this->qty,true)."
        ,rate = ".$this->invl($this->rate,true)."
        ,amount = ".$this->invl($this->amount,true)."
        ,wbslipnumber = ".$this->invl($this->wbslipnumber,true)."
        where 
        transactionid = ".$this->invl($this->transactionid,true)."
        ";
        $result = oci_parse($this->connection, $query);
        if (oci_execute($result,OCI_NO_AUTO_COMMIT))
        {
            if ($this->updateheader()==1)
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
    //This is geneperd by Swapp Software Application on 23/10/2018 04:40:52 PM for PHP
    public function delete()
    {
        $this->dataoperationmode = operationmode::Update;
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
        $query = "
        delete from saledetail
        where 
        transactionid = ".$this->invl($this->transactionid,true)."
        ";
        $result = oci_parse($this->connection, $query);
        if (oci_execute($result,OCI_NO_AUTO_COMMIT))
        {
             if ($this->updateheader()==1)
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
        ,reftransactionid
        ,d.itemcode
        ,qty
        ,rate
        ,amount
        ,itemnameuni
        ,itemnameeng
        ,wbslipnumber
        from saledetail d,item i where d.itemcode=i.itemcode and 
        transactionid = ".$this->invl($this->transactionid,true)."
        ";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
                $this->transactionid = $row['TRANSACTIONID'];
                $this->reftransactionid = $row['REFTRANSACTIONID'];
                $this->itemcode = $row['ITEMCODE'];
                $this->qty = $row['QTY'];
                $this->rate = $row['RATE'];
                $this->amount = $row['AMOUNT'];
                $this->itemnameuni = $row['ITEMNAMEUNI'];
                $this->itemnameeng = $row['ITEMNAMEENG'];
                $this->wbslipnumber = $row['WBSLIPNUMBER'];
                $this->found=true;
                return true;
        }
        else
        {
        return false;
        }
    }
    public function updateheader()
    {
        $this->dataoperationmode = operationmode::Update;
        $query = "select nvl(sum(s.amount),0) as amount
        from saledetail s 
        where transactionid=".$this->transactionid;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $invoiceamount = $row['AMOUNT'];
            $query1 = 
            "update saleheader
             set invoiceamount=".$invoiceamount."
             where transactionid=".$this->reftransactionid;
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
    }
}
?>
