<?php
/* 
	A domain Class to demonstrate RESTful web services
*/
Class onlineplantation
{
	public $connection;
	public $seasonyear;
	public $sectioncode;
    public $offset;
    public $rowsperpage;

	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

	public function getonlineplantationrecord()
	{
		$query = "select * from 
		(select t.nplot_no,dplantation_date,row_number() over (order by dplantation_date,t.nplot_no) as rn,
		t.nfarmer_code,vfarmer_name_uni,narea,
		r.navariety_code,vvariety_name,h.nhangam_code,vhangam_name_uni,
		vgat_sarve_no,'0' as vsarve_no_detail,
		s.vsect_name_uni,v.vvill_name_uni,
		TO_CHAR(t.dcreate_date, 'DD/MM/YYYY HH24:MI:SS') as plottime
		from agri_plantation@agrilink t,com_farmer_master f,
		com_village_master v,com_section_master s,
		agri_variety_master@agrilink r,com_hangam_master h
    	where t.nfarmer_code=f.nfarmer_code 
		and t.nvill_shivar_code=v.nvill_code and v.nsect_code=s.nsect_code 
		and t.nvariety_code=r.navariety_code
		and t.nhangam_code=h.nhangam_code
		and t.vseason_year='".$this->seasonyear."' 
		and s.nsect_code=".$this->sectioncode."
		)
        where rn >".$this->offset." 
		and rn <=".($this->offset+$this->rowsperpage);
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
		$query = "select s.circlenameuni vsect_name_uni,count(*) as cnt  
        from nst_nasaka_agriculture.plantationheader t,nst_nasaka_agriculture.farmer f,
		nst_nasaka_agriculture.village v,nst_nasaka_agriculture.circle s,
		nst_nasaka_agriculture.variety r,nst_nasaka_agriculture.plantationhangam h
    	where t.farmercode=f.farmercode 
		and t.villagecode=v.villagecode and v.circlecode=s.circlecode 
		and t.varietycode=r.varietycode
		and t.plantationhangamcode=h.plantationhangamcode
		and t.seasoncode=".$this->seasonyear." 
		and s.circlecode=".$this->sectioncode." 
    	group by s.circlenameuni"
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		if ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}
	
	public function getsectvillhanrecord()
	{
		$query="select * from (select nvill_code,vvill_name_uni,
		sum(case when nhangam_code=3 or nhangam_code=5 then narea else 0 end) as aadsali,
		sum(case when nhangam_code=1 then narea else 0 end) as purva,
		sum(case when nhangam_code=2 then narea else 0 end) as suru,
		sum(case when nhangam_code=4 then narea else 0 end) as khodava,
		sum(narea) as total,
		sum(cnt) as cnt  
		from (
		select t.seasoncode vseason_year,s.circlecode nsect_code,s.circlenameuni vsect_name_uni,v.villagecode as nvill_code,v.villagenameuni as vvill_name_uni,h.plantationhangamcode nhangam_code,h.plantationhangamnameuni vhangam_name_uni,sum(t.area) as narea,count(*) as cnt
		from nst_nasaka_agriculture.plantationheader t,nst_nasaka_agriculture.farmer f,
		nst_nasaka_agriculture.village v,nst_nasaka_agriculture.circle s,
		nst_nasaka_agriculture.variety r,nst_nasaka_agriculture.plantationhangam h
		  where t.farmercode=f.farmercode 
		and t.villagecode=v.villagecode and v.circlecode=s.circlecode 
		and t.varietycode=r.varietycode
		and t.plantationhangamcode=h.plantationhangamcode
		group by t.seasoncode,s.circlecode,s.circlenameuni,v.villagecode,v.villagenameuni,h.plantationhangamcode,plantationhangamnameuni
		having t.vseason_year='".$this->seasonyear."'
		and s.nsect_code=".$this->sectioncode.")
		group by nvill_code,vvill_name_uni)
		order by nvill_code"
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}
	
	public function getsecthanrecord()
	{
		$query = "select * from (select nsect_code,vsect_name_uni,
		sum(case when nhangam_code=3 or nhangam_code=5 then narea else 0 end) as aadsali,
		sum(case when nhangam_code=1 then narea else 0 end) as purva,
		sum(case when nhangam_code=2 then narea else 0 end) as suru,
		sum(case when nhangam_code=4 then narea else 0 end) as khodava,
		sum(narea) as total,
		sum(cnt) as cnt  
		from (
		select t.seasoncode vseason_year,s.circlecode nsect_code,s.circlenameuni vsect_name_uni,h.plantationhangamcode nhangam_code,h.plantationhangamnameuni vhangam_name_uni,sum(t.area) as narea,count(*) as cnt
		from nst_nasaka_agriculture.plantationheader t,nst_nasaka_agriculture.farmer f,
		nst_nasaka_agriculture.village v,nst_nasaka_agriculture.circle s,
		nst_nasaka_agriculture.variety r,nst_nasaka_agriculture.plantationhangam h
		  where t.farmercode=f.farmercode 
		and t.villagecode=v.villagecode and v.circlecode=s.circlecode 
		and t.varietycode=r.varietycode
		and t.plantationhangamcode=h.plantationhangamcode
		group by t.seasoncode,s.circlecode,s.circlenameuni,h.plantationhangamcode,plantationhangamnameuni
		having t.seasoncode='".$this->seasonyear."')
		group by nsect_code,vsect_name_uni)
		order by nsect_code"
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}

	public function getsectionlist()
	{
		$query = "select circlecode as nsect_code,circlenameuni as vsect_name_uni  
		from nst_nasaka_db.circle s order by circlecode";
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}
	public function getseasonyearlist()
	{
		$query = "select yearperiodcode as vyear_code  
		from nst_nasaka_db.yearperiod s order by yearperiodcode desc";
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