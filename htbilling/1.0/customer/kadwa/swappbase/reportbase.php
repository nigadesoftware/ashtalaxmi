<?php
abstract class swappreport
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
	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}
	abstract protected function pageheader();
	abstract protected function pagefooter($islastpage=false);
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
	protected function textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')
	{
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
			if ($fontname='')
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
				$this->pdf->SetFont($font, '', $fontsize, '', true);
			}
			else
			{
				$this->pdf->SetFont($font, '', 11, '', true);
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
					$this->pdf->SetFont($font, '', $fontsize, '', true);
				}
				else
				{
					$this->pdf->SetFont($font, '', 11, '', true);
				}
			}

			if ($design==1)
			{
				$this->pdf->SetTextColor(0,0,0);
				$this->pdf->multicell($width, 0,'R-'.$this->liney.',C-'.$col, 0,$align,false,1,$col,$this->liney,true,0,false,true,$height);
				$this->pdf->SetTextColor(192,192,192);
				$this->pdf->multicell($width, 0, $data, $design,$align,false,1,$col,$this->liney,true,0,false,true,$height);
			}
			else
			{
				$this->pdf->SetTextColor(0,0,0);
				$this->pdf->multicell($width, 0, $data, $design,$align,false,1,$col,$this->liney,true,0,false,true,$height);
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
				$this->pdf->SetTextColor(0,0,0);
				if ($design==1)
				{
					$this->pdf->SetTextColor(0,0,0);
					$this->pdf->multicell($width, 0,'R-'.$this->liney.',C-'.$col, 0,$align,false,1,$col,$this->liney,true,0,false,true,$height);
					$this->pdf->SetTextColor(192,192,192);
					$this->pdf->multicell($width, 0, $this->moneyFormatIndia($data),$design,$align,false,1,$col,$this->liney,true,0,false,true,$height);
				}
				else
				{
					$this->pdf->SetTextColor(0,0,0);
					$this->pdf->multicell($width, 0, $this->moneyFormatIndia($data),$design,$align,false,1,$col,$this->liney,true,0,false,true,$height);
				}
			}
			else
			{
				$this->pdf->SetTextColor(0,0,0);
				if ($design==1)
				{
					$this->pdf->SetTextColor(0,0,0);
					$this->pdf->multicell($width, 0,'R-'.$this->liney.',C-'.$col, 0,$align,false,1,$col,$this->liney,true,0,false,true,$height);
					$this->pdf->SetTextColor(192,192,192);
					$this->pdf->multicell($width, 0, '₹'.$this->moneyFormatIndia($data), $design,$align,false,1,$col,$this->liney,true,0,false,true,$height);
				}
				else
				{
					$this->pdf->SetTextColor(0,0,0);
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
			$this->pdf->SetTextColor(0,0,0);
			if ($design==1)
			{
				$this->pdf->SetTextColor(0,0,0);
				$this->pdf->multicell($width, 0,'R-'.$this->liney.',C-'.$col, 0,$align,false,1,$col,$this->liney,true,0,false,true,$height);
				$this->pdf->SetTextColor(192,192,192);
				$this->pdf->multicell($width, 0, $data, $design,$align,false,1,$col,$this->liney,true,0,false,true,$height);
			}
			else
			{
				$this->pdf->SetTextColor(0,0,0);
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
    public function newpage($force=false)
    {
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
            $this->pageheader();
			return true;
        }
    }

}
?>