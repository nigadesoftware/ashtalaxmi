<?php
/* 
A domain Class to demonstrate RESTful web services
*/
Class onlinecrushing
{
	public $connection;
	public $fromdatetime;
	public $todatetime;
    public $offset;
    public $rowsperpage;
	public $farmercode;
	public function __construct(&$connection)
	{
		$this->connection = $connection;
		$this->farmercode=0;
	}

	public function getonlinecrushingrecord()
	{
		if ($this->farmercode==0)
		{
			$query = "select * from 
			(select t.weightslipnumber as nslip_no,t.weighmentdate as dslip_date,row_number() over (order by t.emptydatetime desc) as rn,t.emptydatetime as vout_time,f.farmercode as nfarmer_code,f.farmernameuni as vfarmer_name_uni,t.shiftcode as nshift_no,t.netweight as nnet_weight,
			to_char(t.emptydatetime,'DD/MM/YYYY HH:MI:SS AM') as sliptime,
			v.villagenameuni as vvill_name_uni,c.circlenameuni as vsect_name_uni
			from nst_nasaka_agriculture.weightslip t,nst_nasaka_agriculture.fieldslip s
			,nst_nasaka_agriculture.farmer f,nst_nasaka_agriculture.village v
			,nst_nasaka_agriculture.circle c
			where t.seasoncode=s.seasoncode and t.fieldslipnumber=s.fieldslipnumber
			and s.farmercode=f.farmercode 
			and s.villagecode=v.villagecode and v.circlecode=c.circlecode
			and nvl(t.netweight,0)>0
			and t.weighmentdate>='".$this->fromdatetime."' 
			and t.weighmentdate<='".$this->todatetime."'
			)
			where rn >".$this->offset." 
			and rn <=".($this->offset+$this->rowsperpage);
		}
		else
		{
			$query = "select * from 
			(select t.weightslipnumber as nslip_no,t.weighmentdate as dslip_date,row_number() over (order by t.emptydatetime desc) as rn,t.emptydatetime as vout_time,f.farmercode as nfarmer_code,f.farmernameuni as vfarmer_name_uni,t.shiftcode as nshift_no,t.netweight as nnet_weight,
			to_char(t.emptydatetime,'DD/MM/YYYY HH:MI:SS AM') as sliptime,
			v.villagenameuni as vvill_name_uni,c.circlenameuni as vsect_name_uni
			from nst_nasaka_agriculture.weightslip t,nst_nasaka_agriculture.fieldslip s
			,nst_nasaka_agriculture.farmer f,nst_nasaka_agriculture.village v
			,nst_nasaka_agriculture.circle c
			where t.seasoncode=s.seasoncode and t.fieldslipnumber=s.fieldslipnumber
			and s.farmercode=f.farmercode 
			and s.villagecode=v.villagecode and v.circlecode=c.circlecode
			and nvl(t.netweight,0)>0
			and f.farmercode=".$this->farmercode." 
			and t.weighmentdate>='".$this->fromdatetime."' 
			and t.weighmentdate<='".$this->todatetime."'
			)
			where rn >".$this->offset." 
			and rn <=".($this->offset+$this->rowsperpage);
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
	public function gettotalrecords()
	{
		if ($this->farmercode==0)
		{

			$query = "select count(*) as cnt from 
			(select t.weightslipnumber as nslip_no,t.weighmentdate as dslip_date,row_number() over (order by t.emptydatetime desc) as rn,t.emptydatetime as vout_time,f.farmercode as nfarmer_code,f.farmernameuni as vfarmer_name_uni,t.shiftcode as nshift_no,t.netweight as nnet_weight,
			to_char(t.emptydatetime,'DD/MM/YYYY HH:MI:SS AM') as sliptime,
			v.villagenameuni as vvill_name_uni,c.circlenameuni as vsect_name_uni
			from nst_nasaka_agriculture.weightslip t,nst_nasaka_agriculture.fieldslip s
			,nst_nasaka_agriculture.farmer f,nst_nasaka_agriculture.village v
			,nst_nasaka_agriculture.circle c
			where t.seasoncode=s.seasoncode and t.fieldslipnumber=s.fieldslipnumber
			and s.farmercode=f.farmercode 
			and s.villagecode=v.villagecode and v.circlecode=c.circlecode
			and nvl(t.netweight,0)>0
			and t.weighmentdate>='".$this->fromdatetime."' 
			and t.weighmentdate<='".$this->todatetime."')";

		}
		else
		{
			$query = "select count(*) as cnt 
			from nst_nasaka_agriculture.weightslip t,nst_nasaka_agriculture.fieldslip s
			,nst_nasaka_agriculture.farmer f,nst_nasaka_agriculture.village v
			,nst_nasaka_agriculture.circle c
			where t.seasoncode=s.seasoncode and t.fieldslipnumber=s.fieldslipnumber
			and s.farmercode=f.farmercode 
			and s.villagecode=v.villagecode and v.circlecode=c.circlecode
			and nvl(t.netweight,0)>0
			and t.farmercode=".$this->farmercode." 
			and t.weighmentdate>='".$this->fromdatetime."' 
			and t.weighmentdate<='".$this->todatetime."'";

		}
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		if ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}
}
?>