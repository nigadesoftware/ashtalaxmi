<?php
require_once("../api_base/formbase.php");
class servicecontractor extends swappform
{	
	public $servicecontractorid;
	public $name_unicode;
	public $name_eng;
	public $address_unicode;
	public $pannumber;
	public $contactnumber;
	public $bankaccountnumber;
	public $age;
	public $castename_unicode;
	public $mobileno;
	public function __construct(&$connection)
	{
		parent::__construct($connection);
	}

	public function fetch($servicecontractorid)
	{
		$this->dataoperationmode = operationmode::Select;
		$query = "select t.* from servicecontractor t where t.servicecontractorid=".$servicecontractorid;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->servicecontractorid = $row["SERVICECONTRACTORID"];
			$this->name_eng = $row['NAME_ENG'];
			$this->name_unicode = $row['NAME_UNICODE'];
			$this->address_unicode = $row['VADDRESS'];
			$this->pannumber = $row["VPAN_NO"];
			$this->contactnumber = $row["NMOBLIE_NO"];
			$this->bankaccountnumber = $row['VBANK_ACC_NO'];
			$this->mobileno = $row['NMOBILENO'];
			
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>