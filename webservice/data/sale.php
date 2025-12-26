<?php
/* 
A domain Class to demonstrate RESTful web services
*/
Class sale
{
	public $connection;
	public $fromdatetime;
	public $todatetime;
	public $categoryid;

	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

	public function getsalerecord()
	{
		$query = "select c.finishedgoodscatnmeng,c.finishedgoodscatnmuni,
		f.finishedgoodsnameeng, f.finishedgoodsnameuni, today, rate,u.unitnameeng,u.unitnameuni
		from sale d, finishedgoods f, finishedgoodscategory c, unit u
		where d.finishedgoodsid=f.finishedgoodsid 
		and f.finishedgoodscategoryid=c.finishedgoodscategoryid
		and c.unitid=u.unitid
		and d.saledate>='".$this->fromdatetime."' 
		and d.saledate<='".$this->todatetime."'
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