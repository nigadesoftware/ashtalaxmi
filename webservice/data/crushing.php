<?php
/* 
A domain Class to demonstrate RESTful web services
*/
Class crushing
{
	public $connection;
	public $fromdatetime;
	public $todatetime;
	
	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

	public function getshiftwisecrushingrecord()
	{
		$query = "select shiftcode as nshift_no,sum(netweight) as nnet_weight from nst_nasaka_agriculture.weightslip t
		where t.weighmentdate>='".$this->fromdatetime."' 
		and t.weighmentdate<='".$this->todatetime."'
        group by shiftcode";
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}
	public function getvehiclewisecrushingrecord()
	{
		$query = "select nvehicle_type,sum(nnet_weight) as nnet_weight,sum(nnet_weight_todate) as nnet_weight_todate,sum(cnt) as cnt, sum(cnt_todate) as cnt_todate 
		from (select vehiclecategorycode  nvehicle_type,sum(netweight) as nnet_weight,0 as nnet_weight_todate,count(*) as cnt, 0 as cnt_todate 
		from nst_nasaka_agriculture.weightslip t,nst_nasaka_agriculture.fieldslip f
		where t.seasoncode=f.seasoncode
		and t.fieldslipnumber=f.fieldslipnumber
		and t.weighmentdate>='".$this->todatetime."' 
		and t.weighmentdate<='".$this->todatetime."'
		and t.netweight>0
		group by vehiclecategorycode
		union all
		select vehiclecategorycode nvehicle_type,0 as nnet_weight, sum(netweight) as nnet_weight_todate,0 as cnt, count(*) as cnt_todate 
		from nst_nasaka_agriculture.weightslip t,nst_nasaka_agriculture.fieldslip f
		where t.seasoncode=f.seasoncode
		and t.fieldslipnumber=f.fieldslipnumber
		and t.weighmentdate<='".$this->todatetime."'
		and t.netweight>0
		and t.seasoncode=get_season_year('".$this->todatetime."')
		group by f.vehiclecategorycode
		)
		group by nvehicle_type
		order by nvehicle_type";
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}
	public function getvehiclewiseshiftwisecrushingrecord()
	{
		$query="select nvehicle_type,
		sum(case when nshift_no=1 then nnet_weight else 0 end) as first,
		sum(case when nshift_no=1 then cnt else 0 end) as firstcnt,
		sum(case when nshift_no=2 then nnet_weight else 0 end) as second,
		sum(case when nshift_no=2 then cnt else 0 end) as secondcnt,
		sum(case when nshift_no=3 then nnet_weight else 0 end) as third,
		sum(case when nshift_no=3 then cnt else 0 end) as thirdcnt
		from (
		select f.vehiclecategorycode as nvehicle_type,t.shiftcode as nshift_no,sum(netweight) as nnet_weight,count(*) as cnt 
		from nst_nasaka_agriculture.weightslip t,nst_nasaka_agriculture.fieldslip f
		where t.seasoncode=f.seasoncode
		and t.fieldslipnumber=f.fieldslipnumber
		and t.weighmentdate>='".$this->fromdatetime."' 
		and t.weighmentdate<='".$this->todatetime."'
		and t.netweight>0
		group by vehiclecategorycode,shiftcode
		)
		group by nvehicle_type
		order by nvehicle_type";
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}
	public function getsectioncrushingrecord()
	{
		$query = "select vyear_code,
				ndivi_code,
				case 
				when ndivi_code = 1 then
					'कार्यक्षेत्रातील'
				when ndivi_code = 2 then
					'कार्यक्षेत्राबाहेरील'
				end as diviname,
				nsect_code,
				vsect_name_uni,
				get_section_todaytonnage(vyear_code,nsect_code,'".$this->todatetime."') as todaytonnage,
				get_section_todatetonnage(vyear_code,nsect_code,'".$this->todatetime."') as todatetonnage
				from (
				select t.VYEAR_CODE,s.ndivi_code,t.NSECT_CODE,s.vsect_name_uni 
				from com_weight_slip t,com_section_master s
				where t.NSECT_CODE=s.nsect_code 
				and t.VYEAR_CODE=get_season_year('".$this->todatetime."')
				group by t.VYEAR_CODE,s.ndivi_code,t.NSECT_CODE,s.vsect_name_uni)
				order by VYEAR_CODE,ndivi_code,NSECT_CODE,vsect_name_uni";
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}
	public function getshiftvehiclecrushingrecord()
	{
		$query = "
		select nshift_no,
		sum(case when nvehicle_type in (3) then todaytonnage else 0 end) as carttonnage,
		sum(case when nvehicle_type in (3) then todaycount else 0 end) as cartcount,
		sum(case when nvehicle_type in (1,2) then todaytonnage else 0 end) as vehicletonnage,
		sum(case when nvehicle_type in (1,2) then todaycount else 0 end) as vehiclecount
		from (
		select vyear_code,
				nshift_no,
				nvehicle_type,
				get_shiftvehicle_todaytonnage(vyear_code,nshift_no,nvehicle_type,'".$this->todatetime."') as todaytonnage,
				get_shiftvehicle_todatetonnage(vyear_code,nshift_no,nvehicle_type,'".$this->todatetime."') as todatetonnage,
				get_shiftvehicle_todaycount(vyear_code,nshift_no,nvehicle_type,'".$this->todatetime."') as todaycount,
				get_shiftvehicle_todatecount(vyear_code,nshift_no,nvehicle_type,'".$this->todatetime."') as todatecount
				from (
				select t.VYEAR_CODE,t.nshift_no,t.nvehicle_type 
				from com_weight_slip t
				where t.VYEAR_CODE=get_season_year('".$this->todatetime."')
				group by t.VYEAR_CODE,t.nshift_no,t.nvehicle_type))
				group by nshift_no";
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