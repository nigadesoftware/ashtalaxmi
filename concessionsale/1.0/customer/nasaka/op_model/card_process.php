<?php
    ob_start();
    include_once("../info/routine.php");

class process
{	
    public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

    Public function sharecompletion()
    {
            $p_ret = 0;    
            $sql = 'BEGIN sharecompletion(p_seasonyear => :p_seasonyear,
            p_ret => :p_ret); END;';
            $result = oci_parse($this->connection,$sql);
            oci_bind_by_name($result,':p_seasonyear',$_SESSION['yearperiodcode'],8,SQLT_INT);
            oci_bind_by_name($result,':p_ret',$p_ret,20,SQLT_INT);
            $ret=oci_execute($result);
            return $ret;
    }
    Public function sharetransfer()
    {
            $p_ret = 0;    
            $sql = 'BEGIN newsharetransfer(p_seasonyear => :p_seasonyear,
            p_ret => :p_ret); END;';
            $result = oci_parse($this->connection,$sql);
            oci_bind_by_name($result,':p_seasonyear',$_SESSION['yearperiodcode'],8,SQLT_INT);
            oci_bind_by_name($result,':p_ret',$p_ret,20,SQLT_INT);
            $ret=oci_execute($result);
            return $ret;
    }
    Public function sharetransferallotment()
    {
            $p_ret = 0;    
            $sql = 'BEGIN newsharetransferedallotment(p_seasonyear => :p_seasonyear,
            p_ret => :p_ret); END;';
            $result = oci_parse($this->connection,$sql);
            oci_bind_by_name($result,':p_seasonyear',$_SESSION['yearperiodcode'],8,SQLT_INT);
            oci_bind_by_name($result,':p_ret',$p_ret,20,SQLT_INT);
            $ret=oci_execute($result);
            return $ret;
    }
    Public function dues()
    {
            $p_ret = 0;    
            $sql = 'BEGIN updatefarmerdueflag(p_seasonyear => :p_seasonyear,
            p_ret => :p_ret); END;';
            $result = oci_parse($this->connection,$sql);
            oci_bind_by_name($result,':p_seasonyear',$_SESSION['yearperiodcode'],8,SQLT_INT);
            oci_bind_by_name($result,':p_ret',$p_ret,20,SQLT_INT);
            $ret=oci_execute($result);
            return $ret;
    }
}    
?>
