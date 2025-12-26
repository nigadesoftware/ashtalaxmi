<?php
/* 
A domain Class to demonstrate RESTful web services
*/
Class import
{
	public $connection;
	public $fromdatetime;
	public $todatetime;
	public $categoryid;

	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

	public function getimportrecord()
	{
		$query = "select c.finishedgoodscatnmeng,c.finishedgoodscatnmuni,
		f.finishedgoodsnameeng, f.finishedgoodsnameuni, today, todate
		from import d, finishedgoods f, finishedgoodscategory c
		where d.finishedgoodsid=f.finishedgoodsid 
		and f.finishedgoodscategoryid=c.finishedgoodscategoryid
		and d.importdate>='".$this->fromdatetime."' 
		and d.importdate<='".$this->todatetime."'
		and c.finishedgoodscategoryid=".$this->categoryid;
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}
}
?>