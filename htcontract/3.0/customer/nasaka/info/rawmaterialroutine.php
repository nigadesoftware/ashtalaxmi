<?php

    function isentriesallowed()
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection = inventory_connection();
        // Check connection
        if (mysqli_connect_errno())
        {
            //echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication error</span>';
            exit;
        }
        mysqli_query($connection,'SET NAMES UTF8');
        $entityglobalgroupid = $_SESSION['entityglobalgroupid'];
        $query = "select isentriesallowed from finalreportperiod e where e.active=1 and finalreportperiodid=".$_SESSION['finalreportperiodid'];
        $result=mysqli_query($connection,$query);
        $row = mysqli_fetch_assoc($result);
        //echo $query;
        if (isset($row['isentriesallowed']))
        {
            return $row['isentriesallowed'];
        }
        else
        {
            return 0;
        }
    }
    function db_connection()
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname, $username, $password, $database);
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Communication Error";
        }
        mysqli_query($connection,'SET NAMES UTF8');
        $connection ->autocommit(FALSE);
        return $connection;
    }
    function inventory_connection()
	{
		require("../info/phpsqlajax_dbinfo.php");
		// Opens a connection to a MySQL server
		$connection=mysqli_connect($hostname_inventory, $username_inventory, $password_inventory, $database_inventory);
		// Check connection
		if (mysqli_connect_errno())
		{
		 	echo "Communication Error";
		}
		mysqli_query($connection,'SET NAMES UTF8');
		$connection ->autocommit(FALSE);
		return $connection;
	}
    function finance_connection()
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Communication Error";
        }
        mysqli_query($connection,'SET NAMES UTF8');
        $connection ->autocommit(FALSE);
        return $connection;
    }
    function swapp_database_settings(&$dbuser,&$host,&$dbname,&$pwd)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname, $username, $password, $database);
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Communication Error";
        }
        mysqli_query($connection,'SET NAMES UTF8');
        $connection ->autocommit(FALSE);
        $query = "select * from databaseserver d,miscustomermodules c where d.miscustomerid=c.miscustomerid and d.miscustomerid=".$_SESSION["factorycode"]." and dbuser='".$dbuser."'";
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            $host = $row["host"];
            $dbname = $row["dbname"];
            $pwd = $row["dbpassword"];
        }
        $connection->close();
    }
    function rawmaterial_connection()
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        //$dbname = "scdbsnew";
        //$host = "192.168.1.254";
        //$dbname = "orclweb";
        //$dbname = "orcl";
        //$host = "localhost";
        //$pwd="kadwa123";
        $pwd="swapp123";
        $dbuser = "nigade_kadwa_htcontract";
        $host="";
        $dbname="";
        $pwd="";
        swapp_database_settings($dbuser,$host,$dbname,$pwd);
        $db= "(DESCRIPTION =
              (ADDRESS = (PROTOCOL = TCP)(HOST = ".$host.")(PORT = 1521))
              (CONNECT_DATA =
              (SERVER = DEDICATED)
                (SERVICE_NAME = ".$dbname.")
              )
           )";
        $conn = oci_connect($dbuser, $pwd, $db,"AL32UTF8");
        //$connection=mysqli_connect($hostname_rawmaterial, $username_rawmaterial, $password_rawmaterial, $database_rawmaterial);
        // Check connection
        if (!$conn)
        {
            $m = oci_error();
            echo $m['message'], "\n";
            exit;
        }
        else
        {
            //print "Connected to Oracle!";
        }
        return $conn;
    }
    /* function rawmaterial_connection()
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        //$dbname = "scdbsnew";
        //$host = "192.168.1.1";
        $dbname = "orclweb";
        $host = "192.168.1.254";
        $db= "(DESCRIPTION =
              (ADDRESS = (PROTOCOL = TCP)(HOST = ".$host.")(PORT = 1521))
              (CONNECT_DATA =
              (SERVER = DEDICATED)
                (SERVICE_NAME = ".$dbname.")
              )
           )";
        $conn = oci_connect("nst_nasaka_htcontract", "swapp123", $db,"AL32UTF8");
        //$connection=mysqli_connect($hostname_rawmaterial, $username_rawmaterial, $password_rawmaterial, $database_rawmaterial);
        // Check connection
        if (!$conn)
        {
            $m = oci_error();
            echo $m['message'], "\n";
            exit;
        }
        else
        {
            //print "Connected to Oracle!";
        }
        return $conn;
    } */
	function isaccessible($responsibilityid)
    {
        require('../info/phpsqlajax_dbinfo.php');
        if ($_SESSION["responsibilitycode"] == $responsibilityid and ($_SESSION["factorycode"]==$customerid or $_SESSION["responsibilitycode"]==621478512368915))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    function currentdate()
    {
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y',$dt);
        date_default_timezone_set("UTC");
        return $dt;
    }

    function currentdatetime()
    {
        date_default_timezone_set("UTC");
        $dt = time();
        $dt = date('d-M-Y H:i:sP',$dt);
        return $dt;
    }
    function number_format_indian($no='',$decplac=2,$iscurr=false,$commsep=false)
    {
        $no = str_replace(',', '', $no);
        $no = str_replace('Rs', '', $no);
        $decpos = strpos($no,'.');
        if (empty($decpos))
        {
            $decpos = strlen($no);
        }
        $intno = substr($no, 0,$decpos);
        
        $frano = substr($no,$decpos+1,strlen($no));
        $l=-3;
        $ln=strlen($intno);
        $strprn='';
        if ($commsep == true)
        {
            for ($i=$ln;$i>1;)
            {
                if ($strprn == '')
                {
                    $strprn = substr($intno,$l);    
                }
                else
                {
                    $strprn = substr($intno,$l).','.$strprn;
                }
                
                $intno = substr($intno,0,strlen($intno)+$l);
                $l=-2;
                $i=$i+$l;
            }
        }
        else
        {
            $strprn = $intno;
        }
        if ($intno == '0')
        {
            $strprn = '0';
        }
        $frano = substr($frano, 0, $decplac);
        $frano = str_pad($frano, $decplac,"0", STR_PAD_RIGHT);
        if (!empty($frano))
        {
            $strprn = $strprn.'.'.$frano;
        }
        if ($iscurr == true)
        {
            $strprn = 'Rs'.$strprn;
        }
        return $strprn;
    }

	function ntw_eng($number)
	{
        //A function to convert numbers into Indian readable words with Cores, Lakhs and Thousands.
        $words = array(
        '0'=> '' ,'1'=> 'one' ,'2'=> 'two' ,'3' => 'three','4' => 'four','5' => 'five',
        '6' => 'six','7' => 'seven','8' => 'eight','9' => 'nine','10' => 'ten',
        '11' => 'eleven','12' => 'twelve','13' => 'thirteen','14' => 'fouteen','15' => 'fifteen',
        '16' => 'sixteen','17' => 'seventeen','18' => 'eighteen','19' => 'nineteen','20' => 'twenty',
        '30' => 'thirty','40' => 'fourty','50' => 'fifty','60' => 'sixty','70' => 'seventy',
        '80' => 'eighty','90' => 'ninty');
       
        //First find the length of the number
        $number_length = strlen($number);
        //Initialize an empty array
        $number_array = array(0,0,0,0,0,0,0,0,0);       
        $received_number_array = array();
       
        //Store all received numbers into an array
        for($i=0;$i<$number_length;$i++){    $received_number_array[$i] = substr($number,$i,1);    }

        //Populate the empty array with the numbers received - most critical operation
        for($i=9-$number_length,$j=0;$i<9;$i++,$j++){ $number_array[$i] = $received_number_array[$j]; }
        $number_to_words_string = "";       
        //Finding out whether it is teen ? and then multiplying by 10, example 17 is seventeen, so if 1 is preceeded with 7 multiply 1 by 10 and add 7 to it.
        for($i=0,$j=1;$i<9;$i++,$j++){
            if($i==0 || $i==2 || $i==4 || $i==7){
                if($number_array[$i]=="1"){
                    $number_array[$j] = 10+$number_array[$j];
                    $number_array[$i] = 0;
                }       
            }
        }
       
        $value = "";
        for($i=0;$i<9;$i++){
            if($i==0 || $i==2 || $i==4 || $i==7){    $value = $number_array[$i]*10; }
            else{ $value = $number_array[$i];    }           
            if($value!=0){ $number_to_words_string.= $words["$value"]." "; }
            if($i==1 && $value!=0){    $number_to_words_string.= "Crores "; }
            if($i==3 && $value!=0){    $number_to_words_string.= "Lakhs ";    }
            if($i==5 && $value!=0){    $number_to_words_string.= "Thousand "; }
            if($i==6 && $value!=0 && $number%100!=0){    $number_to_words_string.= "Hundred and "; }
            elseif($i==6 && $value!=0 && $number%100==0){    $number_to_words_string.= "Hundred "; }
        }
        if($number_length>9){ $number_to_words_string = "Sorry This does not support more than 99 Crores"; }
        return ucwords(strtolower($number_to_words_string));
    }

function ntw_mar($number)
	{
        //A function to convert numbers into Indian readable words with Cores, Lakhs and Thousands.
        $words = array(
        '0'=> '' ,'1'=> 'एक' ,'2'=> 'दोन' ,'3' => 'तीन','4' => 'चार','5' => 'पाच',
        '6' => 'सहा','7' => 'सात','8' => 'आठ','9' => 'नऊ','10' => 'दहा',
        '11' => 'अकरा','12' => 'बारा','13' => 'तेरा','14' => 'चौदा','15' => 'पंधरा',
        '16' => 'सोळा','17' => 'सतरा','18' => 'अठरा','19' => 'एकोणीस','20' => 'वीस',
        '21' => 'एकवीस','22' => 'बावीस','23' => 'तेवीस','24' => 'चोवीस','25' => 'पंचवीस',
        '26' => 'सव्वीस','27' => 'सत्तावीस','28' => 'अठठावीस','29' => 'एकोणतीस','30' => 'तीस',
		'31' => 'एकतीस','32' => 'बत्तीस','33' => 'तेहतीस','34' => 'चौतीस','35' => 'पस्तीस',
        '36' => 'छत्तीस','37' => 'सदतीस','38' => 'अडतीस','39' => 'एकोणचाळीस','40' => 'चाळीस',
        '41' => 'एक्केचाळीस','42' => 'बेचाळीस','43' => 'त्रेचाळीस','44' => 'चव्वेचाळीस','45' => 'पंचेचाळीस',
        '46' => 'शेचाळीस','47' => 'सत्तेचाळीस','48' => 'अठ्ठेचाळीस','49' => 'एकोणपन्नास','50' => 'पन्नास',
		'51' => 'एक्कावन','52' => 'बावन्न','53' => 'त्रेपन्नास','54' => 'चौपन्न','55' => 'पन्नास',
        '56' => 'छपन्न','57' => 'सत्तापन्न','58' => 'अठ्ठावन','59' => 'एकोणसाठ','60' => 'साठ',
        '61' => 'एकसष्ठ','62' => 'बासष्ठ','63' => 'त्रेसष्ठ','64' => 'चौसष्ठ','65' => 'पासष्ठ',
        '66' => 'सहासष्ठ','67' => 'सदूसष्ठ','68' => 'अडूसष्ठ','69' => 'एकोणसत्तर','70' => 'सत्तर',
        '71' => 'एक्काहत्तर','72' => 'बाहत्तर','73' => 'त्र्याहत्तर','74' => 'चौऱ्याहत्तर','75' => 'पंच्याहत्तर',
        '76' => 'शाहत्तर','77' => 'सत्त्याहत्तर','78' => 'अष्टयाहत्तर','79' => 'एकोणऐंशी','80' => 'ऐंशी',
        '81' => 'एक्क्याऐंशी','82' => 'ब्याऐंशी','83' => 'त्र्याऐंशी','84' => 'चौऱ्याऐंशी','85' => 'पंच्याऐंशी',
        '86' => 'शहाऐंशी','87' => 'सत्त्याऐंशी','88' => 'अठ्ठ्याऐंशी','89' => 'एकोणनव्वद','90' => 'नव्वद',
		'91' => 'एक्क्यानौ','92' => 'ब्यानौ','93' => 'त्र्यानौ','94' => 'चौऱ्यानौ','95' => 'पंच्यानौ',
        '96' => 'शहानौ','97' => 'सत्त्यानौ','98' => 'अठ्ठ्यानौ','99' => 'नव्यानौ');
       
        //First find the length of the number
        $number_length = strlen($number);
        //Initialize an empty array
        $number_array = array(0,0,0,0,0,0,0,0,0);       
        $received_number_array = array();
       
        //Store all received numbers into an array
        for($i=0;$i<$number_length;$i++){    $received_number_array[$i] = substr($number,$i,1);    }

        //Populate the empty array with the numbers received - most critical operation
        for($i=9-$number_length,$j=0;$i<9;$i++,$j++){ $number_array[$i] = $received_number_array[$j]; }
        $number_to_words_string = "";       
        //Finding out whether it is teen ? and then multiplying by 10, example 17 is seventeen, so if 1 is preceeded with 7 multiply 1 by 10 and add 7 to it.
        for($i=0,$j=1;$i<9;$i++,$j++){
            if($i==0 || $i==2 || $i==4 || $i==7){
                if($number_array[$i]=="1")
                {
                    $number_array[$j] = 10+$number_array[$j];
                    $number_array[$i] = 0;
                }
                else if($number_array[$i]=="2")
                {
                    $number_array[$j] = 20+$number_array[$j];
                    $number_array[$i] = 0;
                }
                else if($number_array[$i]=="3")
                {
                    $number_array[$j] = 30+$number_array[$j];
                    $number_array[$i] = 0;
                }
                else if($number_array[$i]=="4")
                {
                    $number_array[$j] = 40+$number_array[$j];
                    $number_array[$i] = 0;
                }
                else if($number_array[$i]=="5")
                {
                    $number_array[$j] = 50+$number_array[$j];
                    $number_array[$i] = 0;
                }
                else if($number_array[$i]=="6")
                {
                    $number_array[$j] = 60+$number_array[$j];
                    $number_array[$i] = 0;
                }
                else if($number_array[$i]=="7")
                {
                    $number_array[$j] = 70+$number_array[$j];
                    $number_array[$i] = 0;
                }
                else if($number_array[$i]=="8")
                {
                    $number_array[$j] = 80+$number_array[$j];
                    $number_array[$i] = 0;
                }
                else if($number_array[$i]=="9")
                {
                    $number_array[$j] = 90+$number_array[$j];
                    $number_array[$i] = 0;
                }       
            }
        }
       
        $value = "";
        for($i=0;$i<9;$i++){
            if($i==0 || $i==2 || $i==4 || $i==7)
            	{ 
            		$value = $number_array[$i]*10; 
            	}
            else
            	{ 
            		$value = $number_array[$i]; 
            	}           
            if($value!=0 && $i !=6)
            	{ 
            		$number_to_words_string.= $words["$value"]." "; 
        		}
        	elseif($value!=0 && $i ==6)
	        	{
					if (($number % 100 ==0) and ((int)$number / 100) ==1)
	            	{
	            		$number_to_words_string.= "शंभर "; 
	            	}
	            	else
	            	{
	            		$no = (int)($number / 100);
                        if ($no <10)
                        {
                            $number_to_words_string.= $words["$no"]."शे ";     
                        }
                        else
                        {
                            $no = $no % 10;
                            $number_to_words_string.= $words["$no"]."शे ";     
                        }
	            	}
	        	}
            if($i==1 && $value!=0){    $number_to_words_string.= "कोटी "; }
            if($i==3 && $value!=0){    $number_to_words_string.= "लाख ";    }
            if($i==5 && $value!=0){    $number_to_words_string.= "हजार "; }
        }
        if($number_length>9){ $number_to_words_string = "Sorry This does not support more than 99 Crores"; }
        return $number_to_words_string;
    }

    function NumberToWords($number,$lang)
    {
    	$number=abs($number);
        if ($lang == 0)
    	{
	    	if ($number < 10000000)
	    	{
	    		return "Rs ".ntw_eng($number)."Only";
	    	}
	    	else
	    	{
	    		$acrore = floor($number/10000000);
	    		//echo $acrore.'</br>';
	    		$bcrore = fmod($number,10000000);
	    		//echo $bcrore.'</br>';
	    		return "Rs ".ntw_eng($acrore).'Crores '.ntw_eng($bcrore)."Only";
	    	}
    	}
    	else if ($lang == 1)
    	{
    		if ($number < 10000000)
	    	{
	    		return "Rs ".ntw_mar($number)."फक्त";
	    	}
	    	else
	    	{
	    		$acrore = floor($number/10000000);
	    		//echo $acrore.'</br>';
	    		$bcrore = fmod($number,10000000);
	    		//echo $bcrore.'</br>';
	    		return "Rs ".ntw_mar($acrore)."करोड ".ntw_mar($bcrore)."फक्त";
	    	}
    	}
    }
?>