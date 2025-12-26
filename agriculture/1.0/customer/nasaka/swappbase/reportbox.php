<?php
abstract class reportbox
{
	//protected $connection;
	public $connection;
    public $liney;
    public $maxlines;
    public $pdf;
	public $languagecode;
	public $rowheight;
	public $design=0;
	public $currentpage;
	public $totalpages;
	public $papersize;
	public $orientation;
	public $reportfile;
	public $reportcode;
	public $reportname;
	public $x;
	public $w;
	public $noerpsign;
	public $subject;
	public $pdffilename;
	public $totalgroupcount;
	public $isendofreport=0;
	public $groupsummary1;
	public $groupsummary2;
	public $groupsummary3;
	public $groupsummary4;
	public $groupsummary5;
	public $groupsummary6;
	public $groupsummary7;
	public $summary;
	public $groupfield1;
	public $groupfield2;
	public $groupfield3;
	public $groupfield4;
	public $groupfield5;
	public $groupfield6;
	public $groupfield7;
	public $groupfield8;
	public $groupfield9;
	public $groupfield10;
	public $groupfield11;
	public $groupfield12;
	public $groupfield13;
	public $groupfield14;
	public $groupfield15;

	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}
	abstract protected function pageheader();
	abstract protected function pagefooter($islastpage=false);
	abstract protected function groupheader_1(&$group_row);
	abstract protected function groupheader_2(&$group_row);
	abstract protected function groupheader_3(&$group_row);
	abstract protected function groupheader_4(&$group_row);
	abstract protected function groupheader_5(&$group_row);
	abstract protected function groupheader_6(&$group_row);
	abstract protected function groupheader_7(&$group_row);
	abstract protected function groupfooter_1(&$last_row);
	abstract protected function groupfooter_2(&$last_row);
	abstract protected function groupfooter_3(&$last_row);
	abstract protected function groupfooter_4(&$last_row);
	abstract protected function groupfooter_5(&$last_row);
	abstract protected function groupfooter_6(&$last_row);
	abstract protected function groupfooter_7(&$last_row);

	abstract protected function subreportgroupheader(&$subreportgroup_row,$subreportnumber,$groupnumber);
	abstract protected function subreportgroupfooter(&$subreportlast_row,$subreportnumber,$groupnumber);

    protected function day($date,$lng)
    {
        if ($lng == 0)
        {
            return date("l");
        }
        else if ($lng == 1)
        {
            if (date("l") == 'Monday')
            {
                return 'सोमवार';
            }
            elseif (date("l") == 'Tuesday')
            {
                return 'मंगळवार';
            }
            elseif (date("l") == 'Wednesday')
            {
                return 'बुधवार';
            }
            elseif (date("l") == 'Thursday')
            {
                return 'गुरुवार';
            }
            elseif (date("l") == 'Friday')
            {
                return 'शुक्रवार';
            }
            elseif (date("l") == 'Saturday')
            {
                return 'शनिवार';
            }
            elseif (date("l") == 'Sunday')
            {
                return 'रविवार';
            }
        }
    }
	function hexToRgb($hex, $alpha = false) {
		$hex      = str_replace('#', '', $hex);
		$length   = strlen($hex);
		$rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
		$rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
		$rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
		if ( $alpha ) {
		   $rgb['a'] = $alpha;
		}
		return $rgb;
	 }
	protected function textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='',$fontstyle='',$forecolor='#000000')
	{
		if ($forecolor!='#000000')
		{
			$hc = $this->hexToRgb($forecolor);
			$this->pdf->SetTextColor($hc['r'],$hc['g'],$hc['b']);
		}
		$height=0;
		if ($this->design==1)
		{
			$design=1;
		}
		else
		{
			$design=0;
		}
		if ($newline=='Y')
		{
			$this->newline(0);	
		}
		if ($languagecode == '' or $this->languagecode == 0)
		{
			if ($fontname=='')
			{
				$font = 'helvetica';
			}
			else
			{
				$font=$fontname;
			}
		}
		elseif (($languagecode==1 or $this->languagecode == 1) and $datatype=='S')
		{
			if ($fontname=='')
			{
				$font = 'siddhanta';
			}
			else
			{
				$font=$fontname;
			}
		}
		elseif (($languagecode==1 or $this->languagecode == 1) and $datatype=='N')
		{
			if ($fontname=='')
			{
				$font = 'SakalMarathiNormal922';
			}
			else
			{
				$font=$fontname;
			}
		}
		if ($datatype=='')
		{
			$datatype = 'S';
		}
		if ($datatype=='S')
		{
			if ($alighment=='')
			{
				$align = 'L';
			}
			else
			{
				$align = $alighment;
			}
			if ($font !='' and $fontsize !='')
			{
				$this->pdf->SetFont($font, $fontstyle, $fontsize, '', true);
			}
			else
			{
				$this->pdf->SetFont($font, $fontstyle, 11, '', true);
			}
			if ($cangrow=='Y')
			{
				$height = $this->height($data,$width);
			}
			else
			{
				$height = $this->rowheight;
			}
			if ($this->isnewpage($height))
			{
				$this->newpage(True);
				if ($font !='' and $fontsize !='')
				{
					$this->pdf->SetFont($font, $fontstyle, $fontsize, '', true);
				}
				else
				{
					$this->pdf->SetFont($font, $fontstyle, 11, '', true);
				}
			}

			if ($design==1)
			{
				//$this->pdf->SetTextColor(0,0,0);
				$this->pdf->multicell($width, 0,'R-'.$this->liney.',C-'.$col, 0,$align,false,1,$col,$this->liney,true,0,false,true,$height);
				//$this->pdf->SetTextColor(192,192,192);
				if ($fontstyle=='B')
				{
					$this->pdf->multicell($width, 0, $data, $design,$align,false,1,$col,$this->liney,true,0,false,true,$height);
					$this->pdf->multicell($width, 0, $data, $design,$align,false,1,$col+0.1,$this->liney,true,0,false,true,$height);
				}
				else
				{
					$this->pdf->multicell($width, 0, $data, $design,$align,false,1,$col,$this->liney,true,0,false,true,$height);
				}
				
			}
			else
			{
				//$this->pdf->SetTextColor(0,0,0);
				if ($fontstyle=='B')
				{
					$this->pdf->multicell($width, 0, $data, $design,$align,false,1,$col,$this->liney,true,0,false,true,$height);
					$this->pdf->multicell($width, 0, $data, $design,$align,false,1,$col+0.1,$this->liney,true,0,false,true,$height);
				}
				else
				{
					$this->pdf->multicell($width, 0, $data, $design,$align,false,1,$col,$this->liney,true,0,false,true,$height);
				}
			}
			if ($forecolor!='#000000')
			{
				$hc = $this->hexToRgb('#000000');
				$this->pdf->SetTextColor($hc['r'],$hc['g'],$hc['b']);
			}
			return $height;
		}
		elseif ($datatype=='C')
		{
			if ($alighment=='')
			{
				$align = 'R';
			}
			else
			{
				$align = $alighment;
			}
			$this->pdf->SetFont($fontname, '', $fontsize, '', true);
			if ($currencysymbol!='N')
			{
				//$this->pdf->SetTextColor(0,0,0);
				if ($design==1)
				{
					//$this->pdf->SetTextColor(0,0,0);
					$this->pdf->multicell($width, 0,'R-'.$this->liney.',C-'.$col, 0,$align,false,1,$col,$this->liney,true,0,false,true,$height);
					//$this->pdf->SetTextColor(192,192,192);
					$this->pdf->multicell($width, 0, $this->moneyFormatIndia($data),$design,$align,false,1,$col,$this->liney,true,0,false,true,$height);
				}
				else
				{
					//$this->pdf->SetTextColor(0,0,0);
					$this->pdf->multicell($width, 0, $this->moneyFormatIndia($data),$design,$align,false,1,$col,$this->liney,true,0,false,true,$height);
				}
			}
			else
			{
				//$this->pdf->SetTextColor(0,0,0);
				if ($design==1)
				{
					//$this->pdf->SetTextColor(0,0,0);
					$this->pdf->multicell($width, 0,'R-'.$this->liney.',C-'.$col, 0,$align,false,1,$col,$this->liney,true,0,false,true,$height);
					//$this->pdf->SetTextColor(192,192,192);
					$this->pdf->multicell($width, 0, '₹'.$this->moneyFormatIndia($data), $design,$align,false,1,$col,$this->liney,true,0,false,true,$height);
				}
				else
				{
					//$this->pdf->SetTextColor(0,0,0);
					$this->pdf->multicell($width, 0, '₹'.$this->moneyFormatIndia($data), $design,$align,false,1,$col,$this->liney,true,0,false,true,$height);
				}
			}
			return $height;
		}
		elseif ($datatype=='N')
		{
			if ($alighment=='')
			{
				$align = 'L';
			}
			else
			{
				$align = $alighment;
			}
			$this->pdf->SetFont($fontname, '', $fontsize, '', true);
			//$this->pdf->SetTextColor(0,0,0);
			if ($design==1)
			{
				//$this->pdf->SetTextColor(0,0,0);
				$this->pdf->multicell($width, 0,'R-'.$this->liney.',C-'.$col, 0,$align,false,1,$col,$this->liney,true,0,false,true,$height);
				//$this->pdf->SetTextColor(192,192,192);
				$this->pdf->multicell($width, 0, $data, $design,$align,false,1,$col,$this->liney,true,0,false,true,$height);
			}
			else
			{
				//$this->pdf->SetTextColor(0,0,0);
				$this->pdf->multicell($width, 0, $data, $design,$align,false,1,$col,$this->liney,true,0,false,true,$height);
			}
		}
		return $height;
	}
	protected function hline($startcol,$endcol,$row='',$type='')
	{
		if ($type=='C' or $type=='')
		{
			$this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
		}
		elseif ($type=='D')
		{
			$this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
		}
        if ($row=='')
		{
			$this->pdf->line($startcol,$this->liney,$endcol,$this->liney);
		}
		else
		{
			$this->pdf->line($startcol,$row,$endcol,$row);
		}
	}

	protected function vline($startrow,$endrow,$col,$type='')
	{
		if ($type=='C' or $type == '')
		{
			$this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
		}
		elseif ($type=='D')
		{
			$this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
		}
			$this->pdf->line($col,$startrow,$col,$endrow);
	}

	protected function newrow($rowheight='')
	{
		if ($rowheight=='')
		{
			if ($this->isnewpage($this->rowheight))
			{
				$this->newpage(True);
			}
			$this->liney=$this->liney+$this->rowheight;
		}
		else
		{
			if ($this->isnewpage($rowheight))
			{
				$this->newpage(True);
			}
			$this->liney=$this->liney+$rowheight;
		}
	}
    protected function invl($data,$isnumber=true,$makezeronull=false,$relopr="")
{
	if (isset($relopr) and $relopr!="")
	{
		$opr=' '.$relopr.' ';
	}
	else
	{
		$opr="";
	}
	if (isset($data) and $data != "" and $makezeronull==false)
	{
		if ($isnumber == true)
		{
			return $opr.$data;
		}
		else
		{
			return $opr."'".$data."'";
		}
	}
	elseif (isset($data) and $data != "" and $makezeronull==true)
	{
		if ($data=='0' or $data==0)
		{
			if (isset($relopr) and $relopr!="")
			{
				return ' is Null';
			}
			else
			{
				return 'Null';
			}
		}
		else
		{
			if ($isnumber == true)
			{
				return $opr.$data;
			}
			else
			{
				return $opr."'".$data."'";
			}
		}
	}
	else
	{
		if (isset($relopr) and $relopr!="")
		{
			return ' is Null';
		}
		else
		{
			return 'Null';
		}
	}
}

	public function height($data,$width)
    {
        // store current object
        $this->pdf->startTransaction();
        // store starting values
        $start_y = $this->pdf->GetY();
        $start_page = $this->pdf->getPage();
        // call your printing functions with your parameters
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        $this->pdf->MultiCell($w=$width, $h=0, $data, $border=1, $align='L', $fill=false, $ln=1, $x='', $y='',     $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // get the new Y
        $end_y = $this->pdf->GetY();
        $end_page = $this->pdf->getPage();
        // calculate height
        $height = 0;
        if ($end_page == $start_page) {
            $height = $end_y - $start_y;
        } else {
            for ($page=$start_page; $page <= $end_page; ++$page) {
                $this->pdf->setPage($page);
                if ($page == $start_page) {
                    // first page
                    $height = $this->pdf->h - $start_y - $this->pdf->bMargin;
                } elseif ($page == $end_page) {
                    // last page
                    $height = $end_y - $this->pdf->tMargin;
                } else {
                    $height = $this->pdf->h - $this->pdf->tMargin - $this->pdf->bMargin;
                }
            }
        }
        // restore previous object
        $this->pdf = $this->pdf->rollbackTransaction();
        return $height;
    }


    //NumberToWords(number,1)
    function moneyFormatIndia($num)
    {
        $explrestunits = "" ;
		if ($num<0)
		{
			$num = abs($num);
			$isnegative=true;
		}
		else
		{
			$isnegative=false;
		}
        $num=preg_replace('/,+/', '', $num);
        $words = explode(".", $num);
        $des="00";
        if(count($words)<=2)
        {
            $num=$words[0];
            if(count($words)>=2)
            {
                $des=$words[1];
            }
            if(strlen($des)<2)
            {
                $des=$des."0";
            }
            else
            {
                $des=substr($des,0,2);
            }
        }
        if(strlen($num)>3)
        {
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's scheduleing.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++)
            {
                // creates each of the 2's schedule and adds a comma to the end
                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                }
                else
                {
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } 
        else 
        {
            $thecash = $num;
        }
		if ($isnegative==false)
		{
			if ($thecash=="")
            {
                $thecash=0;
                return "$thecash.$des";
            }
            else
            {
                return "$thecash.$des";
            }
		}
		else
		{
			return '('."$thecash.$des".')';
		}
        
    }
		public function isnewpage($projected)
    {
        if ($this->liney+$projected>=$this->maxlines)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function newpage($force=false,$endforce=false)
    {
		if ($this->isendofreport==1 and $endforce==false)
		{
			return false;
		}
		if ($force==false)
        {
            if ($this->liney >= $this->maxlines)
            {
                $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
                //$this->pdf->line(15,$this->liney,80,$this->liney);
                
                //$resolution= array(80, 100);
                if ($this->currentpage==$this->totalpages)
                {
                    if ($this->pdf->getNumPages()>0)
					{
						$this->pagefooter();
					}
                    $this->pdf->addpage('L',$resolution);
                    $this->liney = 20;
					$this->totalpages = $this->pdf->getNumPages();
                    $this->currentpage = $this->totalpages;
                }
                else
                {
                    $this->currentpage++;
                    $this->pdf->setpage($this->currentpage);
                    $this->liney = 38;
                }
                
                // set color for background
                $this->pdf->SetFillColor(0, 0, 0);
                // set color for text
                $this->pdf->SetTextColor(0, 0, 0);
				$this->erpsign();
				$this->pageheader();
				return true;
            }
			else
			{
				return false;
			}
        }
        else
        {
            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
            //$this->pdf->line(15,$this->liney,100,$this->liney);
            
            //$resolution= array(80, 100);
            if ($this->currentpage==$this->totalpages)
            {
               if ($this->pdf->getNumPages()>0)
				{
					$this->pagefooter();
				}
                $this->pdf->addpage();
                $this->liney = 20;
				$this->totalpages = $this->pdf->getNumPages();
                $this->currentpage = $this->totalpages;
            }
            else
            {
                $this->currentpage++;
                $this->pdf->setpage($this->currentpage);
                $this->liney = 38;
            }
            // set color for background
            $this->pdf->SetFillColor(0, 0, 0);
            // set color for text
            $this->pdf->SetTextColor(0, 0, 0);
			$this->erpsign();
			$this->pageheader();
			return true;
        }
    }
	function setfieldwidth($width=10,$resetlocation=0)
	{
		if ($resetlocation>0)
		{
			$this->x=$resetlocation;
			$this->w=$width;
		}
		else
		{
			$this->x=$this->x+$this->w;
			$this->w=$width;
		}
	}
	function setcolwidth($width=10,$resetlocation=0)
	{
		if ($resetlocation>0)
		{
			$this->x=$resetlocation;
		}
		else
		{
			$this->x=$this->x+$width;
		}
	}
	function numformat($number,$decimal=0,$supresszero=true)
	{
		if ($supresszero==true and $number==0)
		{
			return '';
		}
		else 
		{
			return number_format($number,$decimal,'.','');
		}
		
	}
	function numifnulltozero($number)
	{
		if ($number=='')
		{
			return 0;
		}
		else
		{
			return $number;
		}
	}
	function erpsign()
	{
		if ($this->noerpsign=="")
		{
			$lny=$this->liney;
			$this->liney = 0;
			$this->pdf->Image('../img/nigadesoftwaretechnologies_logo_3.png', 0, 0, 7, 7, 'PNG', 'https://www.nigadesoftware.com', '', true, 150, '', false, false, 0, false, false, false);
			$this->liney=$lny;
		}
	}
	function grouptrigger(&$group_row,&$last_row,$flag='')
	{
		if ($last_row=='')
		{
			for ($i=0;$i<=$this->totalgroupcount-1;$i++)
			{
				$group_header="groupheader_".($i+1);
				call_user_func_array(array($this, $group_header), array(&$group_row));
			}
		}
		elseif ($last_row!='' and $flag=='E')
		{
			if ($flag=='E')
			$this->isendofreport=1;
			for ($i=$this->totalgroupcount-1;$i>=0;$i--)
			{
				$group_footer="groupfooter_".($i+1);
				call_user_func_array(array($this, $group_footer), array(&$last_row));
			}
		}
		elseif ($last_row!='' and $flag=='C')
		{
			for ($i=$this->totalgroupcount-1;$i>=0;$i--)
			{
				$group_footer="groupfooter_".($i+1);
				call_user_func_array(array($this, $group_footer), array(&$last_row));
			}
		}
		else
		{
			for ($k=0;$k<=$this->totalgroupcount-1;$k++)
			{
				$a=$group_row[$k];
				$b=$last_row[$k];
				if ($a!=$b)
				{
					for ($i=$this->totalgroupcount-1;$i>=$k;$i--)
					{
						$group_footer="groupfooter_".($i+1);
						call_user_func_array(array($this, $group_footer), array(&$last_row));
					}
					for ($j=$k;$j<=$this->totalgroupcount-1;$j++)
					{
						$group_header="groupheader_".($j+1);
						call_user_func_array(array($this, $group_header), array(&$group_row));
					}
					break;
				}
			}
		}
	}
	function subreportgrouptrigger(&$subreportgroup_row,&$subreportlast_row,$subreportnumber,$groupnumber,$totalgroupcount,$flag='')
	{
		if ($subreportlast_row=='')
		{
			for ($i=0;$i<=$totalgroupcount-1;$i++)
			{
				$group_header="subreportgroupheader";
				call_user_func_array(array($this, $group_header), array(&$subreportgroup_row,$subreportnumber,$groupnumber));
			}
		}
		elseif ($subreportlast_row!='' and $flag=='E')
		{
			for ($i=$totalgroupcount-1;$i>=0;$i--)
			{
				$group_footer="subreportgroupfooter";
				call_user_func_array(array($this, $group_footer), array(&$subreportlast_row,$subreportnumber,$groupnumber));
			}
		}
		else
		{
			for ($k=0;$k<=$totalgroupcount-1;$k++)
			{
				if ($group_row[$k]!=$last_row[$k])
				{
					for ($i=$totalgroupcount-1;$i>=$k;$i--)
					{
						$group_footer="subreportgroupfooter";
						call_user_func_array(array($this, $group_footer), array(&$subreportlast_row,$subreportnumber,$groupnumber));
					}
					for ($j=$k;$j<=$totalgroupcount-1;$j++)
					{
						$group_header="subreportgroupheader";
						call_user_func_array(array($this, $group_header), array(&$subreportgroup_row,$subreportnumber,$groupnumber));
					}
					break;
				}
			}
		}
	}

	function resetgroupsummary($groupnumber)
	{
		switch ($groupnumber)
		{
			case 0:
				for ($i=1;$i<=25;$i++)
				{
					$this->resetgroupfield($this->summary,$i);
				}
				break;
			case 1:
				for ($i=1;$i<=25;$i++)
				{
					$this->resetgroupfield($this->groupsummary1,$i);
				}
				break;	
			case 2:
				for ($i=1;$i<=25;$i++)
				{
					$this->resetgroupfield($this->groupsummary2,$i);
				}
				break;	
			case 3:
				for ($i=1;$i<=25;$i++)
				{
					$this->resetgroupfield($this->groupsummary3,$i);
				}
				break;
			case 4:
				for ($i=1;$i<=25;$i++)
				{
					$this->resetgroupfield($this->groupsummary4,$i);
				}
				break;
			case 5:
				for ($i=1;$i<=25;$i++)
				{
					$this->resetgroupfield($this->groupsummary5,$i);
				}
				break;
			case 6:
				for ($i=1;$i<=25;$i++)
				{
					$this->resetgroupfield($this->groupsummary6,$i);
				}
				break;	
			case 7:
				for ($i=1;$i<=25;$i++)
				{
					$this->resetgroupfield($this->groupsummary7,$i);
				}
				break;			
		}
		
	}
	function resetgroupfield(&$summary,$fieldnumber)
	{
		switch ($fieldnumber)
		{
		case 1:
			if ($this->groupfield1!='')
			{
				$summary[$this->groupfield1]=0;
			}
		case 2:
			if ($this->groupfield2!='')
			{
				$summary[$this->groupfield2]=0;
			}
		case 3:
			if ($this->groupfield3!='')
			{
				$summary[$this->groupfield3]=0;
			}
		case 4:
			if ($this->groupfield4!='')
			{
				$summary[$this->groupfield4]=0;
			}
		case 5:
			if ($this->groupfield5!='')
			{
				$summary[$this->groupfield5]=0;
			}
		case 6:
			if ($this->groupfield6!='')
			{
				$summary[$this->groupfield6]=0;
			}
		case 7:
			if ($this->groupfield7!='')
			{
				$summary[$this->groupfield7]=0;
			}
		case 8:
			if ($this->groupfield8!='')
			{
				$summary[$this->groupfield8]=0;
			}
		case 9:
			if ($this->groupfield9!='')
			{
				$summary[$this->groupfield9]=0;
			}
		case 10:
			if ($this->groupfield10!='')
			{
				$summary[$this->groupfield10]=0;
			}	
		case 11:
			if ($this->groupfield11!='')
			{
				$summary[$this->groupfield11]=0;
			}
		case 12:
			if ($this->groupfield12!='')
			{
				$summary[$this->groupfield12]=0;
			}
		case 13:
			if ($this->groupfield13!='')
			{
				$summary[$this->groupfield13]=0;
			}
		case 14:
			if ($this->groupfield14!='')
			{
				$summary[$this->groupfield14]=0;
			}
		case 15:
			if ($this->groupfield15!='')
			{
				$summary[$this->groupfield15]=0;
			}
			case 16:
				if ($this->groupfield16!='')
				{
					$summary[$this->groupfield16]=0;
				}
			case 17:
				if ($this->groupfield17!='')
				{
					$summary[$this->groupfield17]=0;
				}
			case 18:
				if ($this->groupfield18!='')
				{
					$summary[$this->groupfield18]=0;
				}
			case 19:
				if ($this->groupfield19!='')
				{
					$summary[$this->groupfield19]=0;
				}
			case 20:
				if ($this->groupfield20!='')
				{
					$summary[$this->groupfield20]=0;
				}	
			case 21:
				if ($this->groupfield21!='')
				{
					$summary[$this->groupfield21]=0;
				}
			case 22:
				if ($this->groupfield22!='')
				{
					$summary[$this->groupfield22]=0;
				}
			case 23:
				if ($this->groupfield23!='')
				{
					$summary[$this->groupfield23]=0;
				}
			case 24:
				if ($this->groupfield24!='')
				{
					$summary[$this->groupfield24]=0;
				}
			case 25:
				if ($this->groupfield25!='')
				{
					$summary[$this->groupfield25]=0;
				}	
		}				

	}
	function sumgroupsummary(&$row,$groupnumber)
	{
		switch ($groupnumber)
		{
			case 0:
				for ($i=1;$i<=25;$i++)
				{
					$this->sumgroupfield($row,$this->summary,$i);
				}
				break;
			case 1:
				for ($i=1;$i<=25;$i++)
				{
					$this->sumgroupfield($row,$this->groupsummary1,$i);
				}
				break;	
			case 2:
				for ($i=1;$i<=25;$i++)
				{
					$this->sumgroupfield($row,$this->groupsummary2,$i);
				}
				break;	
			case 3:
				for ($i=1;$i<=25;$i++)
				{
					$this->sumgroupfield($row,$this->groupsummary3,$i);
				}
				break;
			case 4:
				for ($i=1;$i<=25;$i++)
				{
					$this->sumgroupfield($row,$this->groupsummary4,$i);
				}
				break;
			case 5:
				for ($i=1;$i<=25;$i++)
				{
					$this->sumgroupfield($row,$this->groupsummary5,$i);
				}
				break;
			case 6:
				for ($i=1;$i<=25;$i++)
				{
					$this->sumgroupfield($row,$this->groupsummary6,$i);
				}
				break;	
			case 7:
				for ($i=1;$i<=25;$i++)
				{
					$this->sumgroupfield($row,$this->groupsummary7,$i);
				}
				break;			
		}
		
	}
	function sumgroupfield(&$row,&$summary,$fieldnumber)
	{
		switch ($fieldnumber)
		{
		case 1:
			if ($this->groupfield1!='')
			{
				$summary[$this->groupfield1]+=$row[$this->groupfield1];
				break;
			}
		case 2:
			if ($this->groupfield2!='')
			{
				$summary[$this->groupfield2]+=$row[$this->groupfield2];
				break;
			}
		case 3:
			if ($this->groupfield3!='')
			{
				$summary[$this->groupfield3]+=$row[$this->groupfield3];
				break;
			}
		case 4:
			if ($this->groupfield4!='')
			{
				$summary[$this->groupfield4]+=$row[$this->groupfield4];
				break;
			}
		case 5:
			if ($this->groupfield5!='')
			{
				$summary[$this->groupfield5]+=$row[$this->groupfield5];
				break;
			}
		case 6:
			if ($this->groupfield6!='')
			{
				$summary[$this->groupfield6]+=$row[$this->groupfield6];
				break;
			}
		case 7:
			if ($this->groupfield7!='')
			{
				$summary[$this->groupfield7]+=$row[$this->groupfield7];
				break;
			}
		case 8:
			if ($this->groupfield8!='')
			{
				$summary[$this->groupfield8]+=$row[$this->groupfield8];
				break;
			}
		case 9:
			if ($this->groupfield9!='')
			{
				$summary[$this->groupfield9]+=$row[$this->groupfield9];
				break;
			}
		case 10:
			if ($this->groupfield10!='')
			{
				$summary[$this->groupfield10]+=$row[$this->groupfield10];
				break;
			}	
		case 11:
			if ($this->groupfield11!='')
			{
				$summary[$this->groupfield11]+=$row[$this->groupfield11];
				break;
			}	
		case 12:
			if ($this->groupfield12!='')
			{
				$summary[$this->groupfield12]+=$row[$this->groupfield12];
				break;
			}	
		case 13:
			if ($this->groupfield13!='')
			{
				$summary[$this->groupfield13]+=$row[$this->groupfield13];
				break;
			}	
		case 14:
			if ($this->groupfield14!='')
			{
				$summary[$this->groupfield14]+=$row[$this->groupfield14];
				break;
			}
		case 15:
			if ($this->groupfield15
			!='')
			{
				$summary[$this->groupfield15]+=$row[$this->groupfield15];
				break;
			}	
			case 16:
				if ($this->groupfield16!='')
				{
					$summary[$this->groupfield16]+=$row[$this->groupfield16];
					break;
				}
			case 17:
				if ($this->groupfield17!='')
				{
					$summary[$this->groupfield17]+=$row[$this->groupfield17];
					break;
				}
			case 18:
				if ($this->groupfield18!='')
				{
					$summary[$this->groupfield18]+=$row[$this->groupfield18];
					break;
				}
			case 19:
				if ($this->groupfield19!='')
				{
					$summary[$this->groupfield19]+=$row[$this->groupfield19];
					break;
				}
			case 20:
				if ($this->groupfield20!='')
				{
					$summary[$this->groupfield20]+=$row[$this->groupfield20];
					break;
				}	
			case 21:
				if ($this->groupfield21!='')
				{
					$summary[$this->groupfield21]+=$row[$this->groupfield21];
					break;
				}	
			case 22:
				if ($this->groupfield22!='')
				{
					$summary[$this->groupfield22]+=$row[$this->groupfield22];
					break;
				}	
			case 23:
				if ($this->groupfield23!='')
				{
					$summary[$this->groupfield23]+=$row[$this->groupfield23];
					break;
				}	
			case 24:
				if ($this->groupfield24!='')
				{
					$summary[$this->groupfield24]+=$row[$this->groupfield24];
					break;
				}
			case 25:
				if ($this->groupfield25
				!='')
				{
					$summary[$this->groupfield25]+=$row[$this->groupfield25];
					break;
				}		
		
		}				

	}
	function showgroupsummary($groupnumber,$fieldnumber)
	{
		switch ($groupnumber)
		{
			case 0:
				return ($this->showgroupfield($this->summary,$fieldnumber));
				break;
			case 1:
				return ($this->showgroupfield($this->groupsummary1,$fieldnumber));
				break;	
			case 2:
				return ($this->showgroupfield($this->groupsummary2,$fieldnumber));
				break;	
			case 3:
				return ($this->showgroupfield($this->groupsummary3,$fieldnumber));
				break;
			case 4:
				return ($this->showgroupfield($this->groupsummary4,$fieldnumber));
				break;
			case 5:
				return ($this->showgroupfield($this->groupsummary5,$fieldnumber));
				break;
			case 6:
				return ($this->showgroupfield($this->groupsummary6,$fieldnumber));
				break;	
			case 7:
				return ($this->showgroupfield($this->groupsummary7,$fieldnumber));
				break;	
		}
		
	}
	function showgroupfield(&$summary,$fieldnumber)
	{
			if ($this->groupfield1!='' and $this->groupfield1==$fieldnumber)
			{
				return $summary[$this->groupfield1];
			}
			elseif ($this->groupfield2!='' and $this->groupfield2==$fieldnumber)
			{
				return $summary[$this->groupfield2];
			}
			elseif ($this->groupfield3!='' and $this->groupfield3==$fieldnumber)
			{
				return $summary[$this->groupfield3];
			}
			elseif ($this->groupfield4!='' and $this->groupfield4==$fieldnumber)
			{
				return $summary[$this->groupfield4];
			}
			elseif ($this->groupfield5!='' and $this->groupfield5==$fieldnumber)
			{
				return $summary[$this->groupfield5];
			}
			elseif ($this->groupfield6!='' and $this->groupfield6==$fieldnumber)
			{
				return $summary[$this->groupfield6];
			}
			elseif ($this->groupfield7!='' and $this->groupfield7==$fieldnumber)
			{
				return $summary[$this->groupfield7];
			}
			elseif ($this->groupfield8!='' and $this->groupfield8==$fieldnumber)
			{
				return $summary[$this->groupfield8];
			}
			elseif ($this->groupfield9!='' and $this->groupfield9==$fieldnumber)
			{
				return $summary[$this->groupfield9];
			}
			elseif ($this->groupfield10!='' and $this->groupfield10==$fieldnumber)
			{
				return $summary[$this->groupfield10];
			}
			elseif ($this->groupfield11!='' and $this->groupfield11==$fieldnumber)
			{
				return $summary[$this->groupfield11];
			}
			elseif ($this->groupfield12!='' and $this->groupfield12==$fieldnumber)
			{
				return $summary[$this->groupfield12];
			}
			elseif ($this->groupfield13!='' and $this->groupfield13==$fieldnumber)
			{
				return $summary[$this->groupfield13];
			}
			elseif ($this->groupfield14!='' and $this->groupfield14==$fieldnumber)
			{
				return $summary[$this->groupfield14];
			}
			elseif ($this->groupfield15!='' and $this->groupfield15==$fieldnumber)
			{
				return $summary[$this->groupfield15];
			}
			elseif ($this->groupfield16!='' and $this->groupfield16==$fieldnumber)
			{
				return $summary[$this->groupfield16];
			}
			elseif ($this->groupfield17!='' and $this->groupfield17==$fieldnumber)
			{
				return $summary[$this->groupfield17];
			}
			elseif ($this->groupfield18!='' and $this->groupfield18==$fieldnumber)
			{
				return $summary[$this->groupfield18];
			}
			elseif ($this->groupfield19!='' and $this->groupfield19==$fieldnumber)
			{
				return $summary[$this->groupfield19];
			}
			elseif ($this->groupfield20!='' and $this->groupfield20==$fieldnumber)
			{
				return $summary[$this->groupfield20];
			}
			elseif ($this->groupfield21!='' and $this->groupfield21==$fieldnumber)
			{
				return $summary[$this->groupfield21];
			}
			elseif ($this->groupfield22!='' and $this->groupfield22==$fieldnumber)
			{
				return $summary[$this->groupfield22];
			}
			elseif ($this->groupfield23!='' and $this->groupfield23==$fieldnumber)
			{
				return $summary[$this->groupfield23];
			}
			elseif ($this->groupfield24!='' and $this->groupfield24==$fieldnumber)
			{
				return $summary[$this->groupfield24];
			}
			elseif ($this->groupfield25!='' and $this->groupfield25==$fieldnumber)
			{
				return $summary[$this->groupfield25];
			}
			else
			{
				return 0;
			}	
	}
}
?>