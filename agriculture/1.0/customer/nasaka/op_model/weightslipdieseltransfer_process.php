<?php
    ob_start();
    include_once("../info/routine.php");

class weightslipdieseltransferprocess
{	
    public $pumpcode;
    public $fromdate;
    public $todate;
    public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

    Public function weightslipdieseltransferprocess()
    {
            $sql = 'BEGIN transferdieseldata(:p_pumpcode,:p_fromdate,:p_todate); END;';
            $result = oci_parse($this->connection,$sql);
            oci_bind_by_name($result,':p_pumpcode',$this->pumpcode,20,SQLT_INT);
            oci_bind_by_name($result,':p_fromdate',$this->fromdate);
            oci_bind_by_name($result,':p_todate',$this->todate);
            $ret=oci_execute($result);
            return $ret;
    }

}    
?>
