<?php
    ob_start();
    include_once("../info/routine.php");
    include_once("../ip_model/employeetransaction_db_oracle.php");
class seasonbreak
{	
    public $employeecategorycode;
    public $date;
    public $connection;
    public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

    Public function seasonbreakprocess()
    {
        $employeetransaction1 = new employeetransaction($this->connection);
        $ret = $employeetransaction1->seasonbreakemployee($this->employeecategorycode,$this->date);
        return $ret;
        
    }

}    
?>
