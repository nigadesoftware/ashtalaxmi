<?php
    ob_start();
    include_once("../info/routine.php");

class dieselvouchertransferprocess
{	
    public $seasoncode;
    public $fromdate;
    public $todate;
    public $claimdate;
    public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

    Public function dieselvouchertransferprocess()
    {
            $sql = 'BEGIN dieselvoucherposting(:p_seasoncode,:p_fromdate,:p_todate,:p_claimdate); END;';
            $result = oci_parse($this->connection,$sql);
            oci_bind_by_name($result,':p_seasoncode',$this->seasoncode,20,SQLT_INT);
            oci_bind_by_name($result,':p_fromdate',$this->fromdate);
            oci_bind_by_name($result,':p_todate',$this->todate);
            oci_bind_by_name($result,':p_claimdate',$this->claimdate);
            $ret=oci_execute($result);
            return $ret;
    }

}    
?>
