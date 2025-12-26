<?php
    ob_start();
    include_once("../info/routine.php");

class billprocess
{	
    public $billperiodtransnumber;
    public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

    Public function farmerbillprocess()
    {
            $sql = 'BEGIN farmerbill(:p_billperiodtransnumber); END;';
            $result = oci_parse($this->connection,$sql);
            oci_bind_by_name($result,':p_billperiodtransnumber',$this->billperiodtransnumber,20,SQLT_INT);
            $ret=oci_execute($result);
            return $ret;
    }
    Public function unpostdata()
    {
            $sql = 'BEGIN UNPOSTFARMERBILLDATA(:p_billperiodtransnumber); END;';
            $result = oci_parse($this->connection,$sql);
            oci_bind_by_name($result,':p_billperiodtransnumber',$this->billperiodtransnumber,20,SQLT_INT);
            $ret=oci_execute($result);
            return $ret;
    }

}    
?>
