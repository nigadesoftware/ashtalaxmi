<?php
    ob_start();
    include_once("../info/routine.php");

class salary
{	
    public $financialyearcode;
    public $calendermonthcode;
    public $employeecategorycode;
    public $paymentcategorycode;
    public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

    Public function salaryprocess()
    {
            $p_ret=0;
            $sql = 'BEGIN salary(:p_financialyearcode,:p_monthcode,:p_paymentcategorycode,:p_employeecategorycode,:p_ret); END;';
            $result = oci_parse($this->connection,$sql);
            oci_bind_by_name($result,':p_financialyearcode',$this->financialyearcode,20,SQLT_INT);
            oci_bind_by_name($result,':p_monthcode',$this->calendermonthcode,20,SQLT_INT);
            oci_bind_by_name($result,':p_paymentcategorycode',$this->paymentcategorycode,20,SQLT_INT);
            oci_bind_by_name($result,':p_employeecategorycode',$this->employeecategorycode,20,SQLT_INT);
            oci_bind_by_name($result,':p_ret',$p_ret);
            $ret=oci_execute($result);
            return $p_ret;
    }

}    
?>
