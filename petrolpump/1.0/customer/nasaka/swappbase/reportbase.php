<?php
class swappreport
{
	protected $connection;

	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

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
    public function moneyFormatIndia($num)
    {
        $explrestunits = "" ;
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
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++)
            {
                // creates each of the 2's group and adds a comma to the end
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
        return "$thecash.$des"; // writes the final format where $currency is the currency symbol.
    }
}
?>