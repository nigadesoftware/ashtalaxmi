<?php
/*header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
require("../info/ncryptdcrypt_old.php");
require("../info/phpsqlajax_dbinfo.php");

// Opens a connection to a MySQL server
$connection=mysqli_connect($hostname, $username, $password, $database);
mysqli_query($connection,'SET NAMES UTF8');
// Check connection
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: ".mysqli_connect_error();
}

mysqli_autocommit($connection,false);

// Select all the rows in the markers table
$query = "SELECT a.transid,a.areaid,a.areaname,a.areaname_eng,s.subdistrictid,s.subdistrictname,s.subdistrictname_eng,d.districtid,d.districtname,d.districtname_eng,t.stateid,t.statename,t.statename_eng 
FROM area a,subdistrict s,district d, state t
where a.subdistrictid=s.subdistrictid and s.districtid=d.districtid and d.stateid=t.stateid and a.generated<>1 order by areaname_eng";
$result = mysqli_query($connection,$query);
if (!$result) 
{
  die('Invalid query: ' . mysqli_error());
}


// Iterate through the rows, adding XML nodes for each

while ($row = @mysqli_fetch_assoc($result)){
  // ADD TO XML DOCUMENT NODE
  $areaid = $row['areaid']*2+10;  
  $stateid_en= fnEncrypt_old($row['stateid']);
  $districtid_en= fnEncrypt_old($row['districtid']);
  $subdistrictid_en= fnEncrypt_old($row['subdistrictid']);
  $areaid_en= fnEncrypt_old($row['areaid']);
  $filename='../village/'.$areaid.'.php';  
  $myfile = fopen($filename, "w") or die("Unable to open file!");
  $txt ='<?php'
        ."\n"
        .'header("location: ../site/newvillage.php?stateid='.$stateid_en.'&districtid='.$districtid_en.'&subdistrictid='.$subdistrictid_en.'&areaid='.$areaid_en.'");'
        ."\n"
        .'?>'
        ."\n";
  fwrite($myfile, $txt);      
  fclose($myfile);
  $query2 = "update area set generated=1 where areaid=".$row['areaid'];
  //echo $query2;
  $result2 = mysqli_query($connection,$query2);
  if (!$result2) 
  {
    die('Invalid query: ' . mysqli_error());
  }
  $connection->commit();
}*/
?>
