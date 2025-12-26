<?php
    ob_start();
    include_once("../info/routine.php");
    class htbillprocess
    {	
        public $billperiodtransnumber;
        public $connection;
        public function __construct(&$connection)
        {
            $this->connection = $connection;
        }
        Public function htbillprocess()
        {
            if ($this->billcategory()==3  or $this->billcategory()==4 or $this->billcategory()==5)
                $sql = 'BEGIN htdepcommbill(:p_billperiodtransnumber); END;';
            else
                $sql = 'BEGIN htbill(:p_billperiodtransnumber); END;';
            $result = oci_parse($this->connection,$sql);
            oci_bind_by_name($result,':p_billperiodtransnumber',$this->billperiodtransnumber,20,SQLT_INT);
            $ret=oci_execute($result);
            return $ret;
        }
        Public function htbillprocessunpost()
        {
            $sql = 'delete from htbillheader h
                where nvl(h.billperiodtransnumber,0)='.$this->billperiodtransnumber;
            $result = oci_parse($this->connection,$sql);
            $ret=oci_execute($result);
            return $ret;
        }
        Public function billcategory()
        {
            $query = 'select billcategorycode from billperiodheader h 
            where h.billperiodtransnumber='.$this->billperiodtransnumber;
            $group_result_1 = oci_parse($this->connection, $query);
            $r = oci_execute($group_result_1);
            if ($group_row_1 = oci_fetch_array($group_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                return $group_row_1['BILLCATEGORYCODE'];
            }
            else
            {
                return 0;
            }
        }
    }    
?>