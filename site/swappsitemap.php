<?php
    require("../info/phpsqlajax_dbinfo.php");
	$connection=mysqli_connect($hostname, $username, $password, $database);
	// Check connection
	if (mysqli_connect_errno())
	  {
	  	echo "Communication Error1";
	  }
	$filename='swappsitemap.xml';  
    $myfile = fopen($filename, "w") or die("Unable to open file!");  
    $query = "select e.* from area e order by areaid";
    $result=mysqli_query($connection, $query);
    while ($row = @mysqli_fetch_assoc($result)) 
    {
    	$txt='<url>'
	    .'<loc>http://www.swapp.co.in/village/'.($row['areaid']*2+10).'.php</loc>'
	    ."\n"
	  	.'</url>'
	  	."\n";
	  	fwrite($myfile, $txt);
    }
     
?>