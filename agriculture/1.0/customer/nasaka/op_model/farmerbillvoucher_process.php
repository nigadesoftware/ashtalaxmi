<?php
    ob_start();
    include_once("../info/routine.php");

class farmerbillvoucher
{	
    public $billperiodtransnumber;
    public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

    Public function farmerbillvoucherprocess()
    {
            $sql = 'BEGIN farmerpaymentvoucherposting(:p_billperiodtransnumber,:p_voucherdate); END;';
            $result = oci_parse($this->connection,$sql);
            oci_bind_by_name($result,':p_billperiodtransnumber',$this->billperiodtransnumber,20,SQLT_INT);
            oci_bind_by_name($result,':p_voucherdate',$this->voucherdate);
            $ret=oci_execute($result);
            return $ret;
    }

}    
?>
