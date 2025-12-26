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

}
?>