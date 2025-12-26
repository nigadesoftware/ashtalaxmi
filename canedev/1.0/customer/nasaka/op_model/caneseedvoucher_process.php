<?php
    ob_start();
    include_once("../info/routine.php");

class caneseedvoucher
{	
    public $fromdate;
    public $todate;
    public $voucherdate;
    public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

    Public function caneseedvoucherprocess()
    {
            $sql = 'BEGIN CANESEEDVOUCHERPOSTING(:p_fromdate,:p_todate,:p_voucherdate); END;';
            $result = oci_parse($this->connection,$sql);
            oci_bind_by_name($result,':p_fromdate',$this->fromdate);
            oci_bind_by_name($result,':p_todate',$this->todate);
            oci_bind_by_name($result,':p_voucherdate',$this->voucherdate);
            $ret=oci_execute($result);
            return $ret;
    }

}    
?>
