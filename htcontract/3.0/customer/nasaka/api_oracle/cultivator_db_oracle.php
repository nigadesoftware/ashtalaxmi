<?php
require_once("../api_base/formbase.php");
class cultivator extends swappform
{	
	public $cultivatorid;
	public $name_unicode;
	public $name_eng;
	public $address_unicode;


	public function __construct(&$connection)
	{
		parent::__construct($connection);
	}

	public function fetch($cultivatorid)
	{
		$this->dataoperationmode = operationmode::Select;
		$query = "select t.* from cultivator t where t.cultivatorid=".$cultivatorid;
		$result = oci_parse($this->connection, $query);             $r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			$this->cultivatorid = $row["CULTIVATORID"];
			$this->name_eng = $row['NAME_ENG'];
			$this->name_unicode = $row['NAME_UNICODE'];
			//$this->address_unicode = $row['VADDRESS'];
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>