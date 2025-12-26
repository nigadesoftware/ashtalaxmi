<?php
    ob_start();
    include_once("../info/routine.php");

class htbillvoucher
{	
    public $billperiodtransnumber;
    public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

    Public function htbillvoucherprocess()
    {
            $sql = 'BEGIN htpaymentvoucherposting(:p_seasoncode,:p_billcategorycode,:p_periodnumber,:p_trothercode,:p_voucherdate); END;';
            $result = oci_parse($this->connection,$sql);
            oci_bind_by_name($result,':p_seasoncode',$this->seasoncode,20,SQLT_INT);
            oci_bind_by_name($result,':p_billcategorycode',$this->billcategorycode,20,SQLT_INT);
            oci_bind_by_name($result,':p_periodnumber',$this->periodnumber,20,SQLT_INT);
            oci_bind_by_name($result,':p_trothercode',$this->trothercode,20,SQLT_INT);
            oci_bind_by_name($result,':p_voucherdate',$this->voucherdate);
            $ret=oci_execute($result);
            return $ret;
    }

}    
?>
