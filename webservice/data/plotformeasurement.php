<?php
/* 
A domain Class to demonstrate RESTful web services
*/
Class plotformeasurement
{
	public $connection;
	public $measurementuserid;
	public function __construct(&$connection)
	{
		$this->connection = $connection;
		$this->measurementuserid=0;
	}

	public function getpendingformeasurementplot()
	{
        //,c.divisioncode inareaoutarea
		$query = "select p.seasoncode,p.plotnumber,f.farmernameuni,v.villagenameuni
		,p.gutnumber,y.varietynameuni
		,2 inareaoutarea
        from plantationheader p,measurementplot t,farmer f,village v,variety y,circle c
        where p.seasoncode=t.seasoncode
        and p.plotnumber=t.plotnumber
        and p.farmercode=f.farmercode
        and p.villagecode=v.villagecode
        and p.varietycode=y.varietycode
		and v.circlecode=c.circlecode
        and t.measurementuserid=".$this->measurementuserid."
		and isselfieuploaded is null
		order by p.seasoncode,p.plotnumber";
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}

	public function getuploadcount($seasoncode,$plotnumber)
	{
        $selfiecount=0;
		$idcount=0;
		$pbcount=0;

		$name=$seasoncode.'_'.$plotnumber;
		if (file_exists("../upload/".$name.".jpeg"))
		{
			$selfiecount=1;
		}
		if (file_exists("../upload/id".$name.".jpeg"))
		{
			$idcount=1;
		}
		if (file_exists("../upload/pb".$name.".jpeg"))
		{
			$pbcount=1;
		}

		$query = "select h.seasoncode,h.plotnumber,count(t.serialnumber) pointcount,".$selfiecount." selfiecount,".$idcount." idcount,".$pbcount." pbcount from plantationheader h,plantationplotareadetail t where h.seasoncode=t.seasoncode(+) and h.plotnumber=t.plotnumber(+)
		and h.seasoncode={$seasoncode} and h.plotnumber={$plotnumber} group by h.seasoncode,h.plotnumber";
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