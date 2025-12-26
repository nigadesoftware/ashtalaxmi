<?php
class crypto
{
	function Encrypt($string)
	{
		$first='';
		for ($i=0;$i<strlen($string);$i++)
		{
			$rand0 = 4;
			$no = str_pad(ord($string[$i]),3,$rand0,STR_PAD_LEFT);
			$y = Date('Y');
			$m = Date('m');
			$d = Date('d');
			$rand1 = (($y%2)==0?8:9);
			$rand2 = (($m%2)==0?5:6);
			$rand3 = (($d%2)==0?2:3);
			$no1= $no[2].$rand1.$no[0].$rand2.$no[1].$rand3;
			$first.= $no1;
		}
		//echo $first.'</br>';
		$second='';
		$j=0;
		for ($i=0;$i<strlen($first);$i=$i+6)
		{
			$no1=chr(65+$first[$i]);
			$no2=chr(48+$first[$i+1]);
			$no3=chr(107+$first[$i+2]);
			$no4=chr(75+$first[$i+3]);
			$no5=chr(48+$first[$i+4]);
			$no6=chr(97+$first[$i+5]);
			$dv = (int)$j%5;
			if ($dv == 0)
			{
				$second.=$no3.$no4.$no5.$no6.$no1.$no2;
			}
			else if ($dv == 1)
			{
				$second.=$no5.$no6.$no1.$no2.$no3.$no4;
			}
			else if ($dv == 2)
			{
				$second.=$no1.$no2.$no3.$no4.$no5.$no6;
			}
			else if ($dv == 3)
			{
				$second.=$no4.$no5.$no6.$no1.$no2.$no3;
			}
			elseif ($dv == 4)
			{
				$second.=$no2.$no3.$no4.$no5.$no6.$no1;
			}
			//echo $second.'</br>';
			$j++;
		}
		//echo $second.'</br>';
		$y = Date('Y');
		$m = Date('m');
		$d = Date('d');
		$no= $d.$y.$m;
		$no1=chr(65+$no[0]);
		$no2=chr(48+$no[1]);
		$no3=chr(107+$no[2]);
		$no4=chr(75+$no[3]);
		$no5=chr(48+$no[4]);
		$no6=chr(97+$no[5]);
		$second.=$no1.$no2.$no3.$no4.$no5.$no6;
		return $second;
	}
	function Decrypt($string,$flag=0)
	{
		$third='';
		$j=0;
		for ($i=0;$i<strlen($string)-6;$i=$i+6)
		{
			$dv = (int) ($j%5);
			if ($dv == 0)
			{
				$no3=(ord($string[$i])-107);
				$no4=(ord($string[$i+1])-75);
				$no5=(ord($string[$i+2])-48);
				$no6=(ord($string[$i+3])-97);
				$no1=(ord($string[$i+4])-65);
				$no2=(ord($string[$i+5])-48);
				$third.=$no1.$no2.$no3.$no4.$no5.$no6;
			}
			else if ($dv == 1)
			{
				$no5=(ord($string[$i])-48);
				$no6=(ord($string[$i+1])-97);
				$no1=(ord($string[$i+2])-65);
				$no2=(ord($string[$i+3])-48);
				$no3=(ord($string[$i+4])-107);
				$no4=(ord($string[$i+5])-75);
				$third.=$no1.$no2.$no3.$no4.$no5.$no6;
			}
			else if ($dv == 2)
			{
				$no1=(ord($string[$i])-65);
				$no2=(ord($string[$i+1])-48);
				$no3=(ord($string[$i+2])-107);
				$no4=(ord($string[$i+3])-75);
				$no5=(ord($string[$i+4])-48);
				$no6=(ord($string[$i+5])-97);
				$third.=$no1.$no2.$no3.$no4.$no5.$no6;
			}
			else if ($dv == 3)
			{
				$no4=(ord($string[$i])-75);
				$no5=(ord($string[$i+1])-48);
				$no6=(ord($string[$i+2])-97);
				$no1=(ord($string[$i+3])-65);
				$no2=(ord($string[$i+4])-48);
				$no3=(ord($string[$i+5])-107);
				$third.=$no1.$no2.$no3.$no4.$no5.$no6;
			}
			elseif ($dv == 4)
			{
				$no2=(ord($string[$i])-48);
				$no3=(ord($string[$i+1])-107);
				$no4=(ord($string[$i+2])-75);
				$no5=(ord($string[$i+3])-48);
				$no6=(ord($string[$i+4])-97);
				$no1=(ord($string[$i+5])-65);
				$third.=$no1.$no2.$no3.$no4.$no5.$no6;
			}
			//$third.=$no1.$no2.$no3.$no4.$no5.$no6;
			//echo $third.'</br>';
			$j++;
		}
		//echo $third.'</br>';
		$fourth='';
		for ($i=0;$i<strlen($third);$i=$i+6)
		{
			/*$no1=$third[$i];
			$no2=$third[$i+2];
			$no3=$third[$i+4];
			$fourth.=$no2.$no3.$no1;*/

			$no1=$third[$i];
			$no2=$third[$i+1];
			$no3=$third[$i+2];
			$no4=$third[$i+3];
			$no5=$third[$i+4];
			$no6=$third[$i+5];

			if ($no2!='8' and $no2!='9')
			{
				$fourth.= '';
			}
			elseif ($no4!='5' and $no4!='6')
			{
				$fourth.= '';
			}
			if ($no6!='2' and $no6!='3')
			{
				$fourth.= '';
			}
			else
			{
				$fourth.=$no3.$no5.$no1;
			}
			//echo $fourth.'</br>';
		}
		//echo $fourth.'</br>';
		$fifth='';
		for ($i=0;$i<strlen($fourth);$i=$i+3)
		{
			$no1=$fourth[$i];
			$no2=$fourth[$i+1];
			$no3=$fourth[$i+2];
			if ($no1=='0' or $no1=='1'  or $no1=='2')
			{
				$fifth.=chr($no1.$no2.$no3);
			}
			elseif ($no1=='4')
			{
				$fifth.=chr($no2.$no3);
			}
			//echo $fifth.'</br>';
		}
		$y = Date('Y');
		$m = Date('m');
		$d = Date('d');
		$no= $d.$y.$m;
		$no1=(ord($string[$j*6+0])-65);
		$no2=(ord($string[$j*6+1])-48);
		$no3=(ord($string[$j*6+2])-107);
		$no4=(ord($string[$j*6+3])-75);
		$no5=(ord($string[$j*6+4])-48);
		$no6=(ord($string[$j*6+5])-97);
		$third.=$no1.$no2.$no3.$no4.$no5.$no6;
		if ($no!=$third and $flag==0)
		{
			$fifth.='';
		}
		//echo $fifth.'</br>';
		return $fifth;
	}
}
?>