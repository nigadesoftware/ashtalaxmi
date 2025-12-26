<?php
/* 
A domain Class to demonstrate RESTful web services
*/
Class weighment
{
	public $connection;
	public $fromdatetime;
	public $todatetime;
	public $farmercode;
	public $totalrecords;
	public $currentpage;
	public $recordperpage;

	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

	public function getrecordtotal()
	{
		
		if ($this->farmercode == 0)
		{
			$query = "select null as name_unicode,count(*) as cnt,sum(nettonnage) as nettonnage from weighment d where weighmentdatetime>='".$this->fromdatetime."' and weighmentdatetime<='".$this->todatetime."'";
		}
		else
		{
			$query = "select d.farmercode,f.name_unicode,count(*) as cnt,sum(nettonnage) as nettonnage from weighment d,farmer f  where d.farmercode=f.code and d.farmercode=".$this->farmercode." and weighmentdatetime>='".$this->fromdatetime."' and weighmentdatetime<='".$this->todatetime."' group by d.farmercode,f.name_unicode";
		}
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		if ($row = oci_fetch_array($result))
		{
			$data['totalrecords'] = $row['CNT'];
			$data['nettonnage'] = $row['NETTONNAGE'];
			$data['name_unicode'] = $row['NAME_UNICODE'];
		}
		else
		{
			$data['totalrecords'] = 0;
			$data['nettonnage'] = 0;
			$data['name_unicode'] = '';
		}
		return $data;
	}

	public function getfarmerweighmentrecord()
	{
		$startrecord = ($this->currentpage-1) * $this->recordperpage;
		$endrecord = $startrecord + $this->recordperpage;
		if ($this->farmercode == 0)
		{
			$query = "select * from (select k.*,rownum as rn from (select slipnumber,d.farmercode,f.name_unicode,weighmentdatetime,nettonnage 
			from weighment d, farmer f 
			where d.farmercode=f.code and weighmentdatetime>='".$this->fromdatetime."' and weighmentdatetime<='".$this->todatetime."'
			order by weighmentdatetime,slipnumber)k order by rownum) where rn >".$startrecord." and rn <=".$endrecord;
		}
		else
		{
			$query = "select * from (select k.*,rownum as rn from (select slipnumber,d.farmercode,f.name_unicode,weighmentdatetime,nettonnage 
			from weighment d, farmer f 
			where d.farmercode=f.code and d.farmercode=".$this->farmercode." 
			and weighmentdatetime>='".$this->fromdatetime."' and weighmentdatetime<='".$this->todatetime."'
			order by weighmentdatetime,slipnumber)k order by rownum) where rn >".$startrecord." and rn <=".$endrecord;
		}
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