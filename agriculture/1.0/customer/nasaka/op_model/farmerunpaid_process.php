<?php
    ob_start();
    include_once("../info/routine.php");

class farmerunpaid
{	
    public $billperiodtransnumber;
    public $voucherdate;
    public $bankledgercode;
    public $chequenumber;
    public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

    Public function farmerunpaidprocess()
    {
            $sql = 'BEGIN farmerpayunpaidvoucherposting(:p_billperiodtransnumber,:p_voucherdate,:p_bankledgercode,:p_chequenumber); END;';
            $result = oci_parse($this->connection,$sql);
            oci_bind_by_name($result,':p_billperiodtransnumber',$this->billperiodtransnumber,20,SQLT_INT);
            oci_bind_by_name($result,':p_voucherdate',$this->voucherdate);
            oci_bind_by_name($result,':p_bankledgercode',$this->bankledgercode,20,SQLT_INT);
            oci_bind_by_name($result,':p_chequenumber',$this->chequenumber,20,SQLT_INT);
            $ret=oci_execute($result);
            return $ret;
    }

}    
?>
