<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
require("../info/ncryptdcrypt.php");
require("../info/ncryptdcrypt_old.php");
require("../info/phpsqlajax_dbinfo.php");
//require('../site/visitcounter.php');
$factoryid =(int) fnDecrypt($_GET['factoryid']);
if (!isset($factoryid) or $factoryid==0)
    {
      header("location: ../site/maharashtradistrictlist.php");
      exit;
    }
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
if ($factoryid != 0)
{
$query = "SELECT * FROM markers WHERE id =".$factoryid;
}
$result = mysqli_query($connection,$query);
if (!$result) 
{
  die('Invalid query: ' . mysqli_error());
}


// Iterate through the rows, adding XML nodes for each

while ($row = @mysqli_fetch_assoc($result)){
  // ADD TO XML DOCUMENT NODE
  $factoryid_en = fnEncrypt('00000'.$row['id']);  
  $filename='../map/'.$row['id'].'.php';  
  $myfile = fopen($filename, "w") or die("Unable to open file!");
  $txt ='<?php'
        ."\n"
        .'header("location: ../site/factorynewmap.php?factoryid='.$factoryid_en.'");'
        ."\n"
        .'header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.'
        ."\n"
        .'header("Pragma: no-cache"); // HTTP 1.0.'
        ."\n"
        .'header("Expires: 0"); // Proxies.'
        ."\n"
        .'?>'
        ."\n";
  fwrite($myfile, $txt);      
  $txt ='<!DOCTYPE html>'
        ."\n"
        .'<html>'
        ."\n"
        .'<head>'
        ."\n"
        .'<title>Swapp Software Application</title>'
        ."\n"
        .'<meta charset="utf-8"></meta>'
        ."\n"
        .'<meta name="viewport" content="width=device-width, initial-scale=1">'
        ."\n"
        .'<link rel="stylesheet" href="../css/w3.css">'
        ."\n"
        .'<script async src="http://maps.googleapis.com/maps/api/js"></script>'
        ."\n"
        .'<script async>'
        ."\n"
        .'function initialize() {'
        ."\n"
        .'  var mapProp = {'
        ."\n";
  fwrite($myfile, $txt);
  $txt ='center:new google.maps.LatLng('
        .($row['lat'])
        .','
        .($row['lng'])
        .'),'
        ."\n";
  fwrite($myfile, $txt);
  if ($row['type'] == 'Private Sugar Factory')
  {
    $factname = "<p>Sugar Factory Name</Br>साखर कारखाना नाव</br>".$row['name']."<br/> Sugar Factory".$row['namelocal']." साखर कारखाना<br/>"
        ."Location</br>स्थळ</br>".$row['address']."</p>";
    $factname_eng = $row['name']." Sakhar Karkhana";    
    $factname_local = $row['namelocal']." साखर कारखाना";
  }
  else
  {
    $factname = "<p>Sugar Factory Name</Br>साखर कारखाना नाव</br>".$row['name']." Sahakari Sakhar Karkhana<br/>".$row['namelocal']." सहकारी साखर कारखाना<br/>"
        ."Location</br>स्थळ</br>".$row['address']."</p>";
    $factname_eng = $row['name']." Sahakari Sakhar Karkhana";    
    $factname_local = $row['namelocal']." सहकारी साखर कारखाना";
  }
  
  $txt ='zoom:13,'
        ."\n"
        .'mapTypeId:google.maps.MapTypeId.ROADMAP'
        ."\n"
        .'};'
        ."\n"
        .'var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);'
        ."\n"
        .'}'
        ."\n"
        ."google.maps.event.addDomListener(window, 'load', initialize);"
        ."\n"
        .'</script>'
        ."\n"
        .'<style type="text/css">'
        ."\n"
        .'body'
        ."\n"
        .'  {'
        ."\n"
        .'    background-color: #fff;'
        ."\n"
        .'  }'
        ."\n"
        .'header'
        ."\n"
        .'{'
        ."\n"
        .'  background-color: #333;'
        ."\n"
        .' min-height: 50px;'
        ."\n"
        .'  color: #070;'
        ."\n"
        .'  font-family: Arial;'
        ."\n"
        .'  font-size: 19px;'
        ."\n"
        .'}'
        ."\n"
        .'nav'
        ."\n"
        .'{'
        ."\n"
        .'  width: 300px;'
        ."\n"
        .'  float: left;'
        ."\n"
        .'  list-style-type: none;'
        ."\n"
        .'  font-family: verdana;'
        ."\n"
        .'  font-size: 15px;'
        ."\n"
        .'  color: #070;'
        ."\n"
        .'  line-height: 30px;'
        ."\n"
        .'}'
        ."\n"
        .'article'
        ."\n"
        .'{'
        ."\n"
        .'  background-color: #fff;'
        ."\n"
        .'  display: table;'
        ."\n"
        .'  margin-left: 0px;'
        ."\n"
        .'  padding-left: 10px;'
        ."\n"
        .'  font-family: Verdana;'
        ."\n"
        .'  font-size: 15px;'
        ."\n"
        .'}'
        ."\n"
        .'section'
        ."\n"
        .'{'
        ."\n"
        .'  margin-left: 0px;'
        ."\n"
        .'  margin-right: 15px;'
        ."\n"
        .'  float: left;'
        ."\n"
        .'  text-align: justify;'
        ."\n"
        .'  color: #111;'
        ."\n"
        .'  line-height: 23px;'
        ."\n"
        .'}'
        ."\n"
        .'footer'
        ."\n"
        .'{'
        ."\n"
        .'  float: bottom;'
        ."\n"
        .'  color: #eee;'
        ."\n"
        .'  font-family: verdana;'
        ."\n"
        .'  font-size: 12px;'
        ."\n"
        .'}'
        ."\n"
        .'div'
        ."\n"
        .'{'
        ."\n"
        .'  float:left;'
        ."\n"
        .'}'
        ."\n"
    .'</style>'
    ."\n"
  .'<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58db35533dec30001259e677&product=sticky-share-buttons"></script>
</head>'
  ."\n"
  .'<body>'
  ."\n"
        /*
      .'<header class="w3-container">'
      ."\n"
        .'<div><img src="/img/swapp_small_logo.png" width="50px" height="50px">'
        ."\n"
        .'</div>'
        ."\n"
        .'<div>Swapp Software <br/>Application'
        ."\n"
        .'</div>'
        ."\n"
      .'</header>'
      ."\n"
        */
      .'<nav "w3-container">'
      ."\n"
        .'<meta name="description" content="'.$factname_eng.'" />'
        .'<ul class="navbar">'
        ."\n"
          .'<li><a class="navbar" href="../site/factory_list.php">Sugar Factory List</Br>साखर कारखाना यादी</a>'
          .'<li><a class="navbar">'.$factname_eng.' Information</Br>'.$factname_local.' माहिती</a>'
          ."\n"
        .'</ul>'
        ."\n"
      .'</nav>'
      ."\n"
      .'<article>'
      ."\n"
        .'<div id="factory" style="width:300px;height:100px;">'
        ."\n"
        .$factname_eng.'</br> Location Map</br>'.$factname_local.' स्थळ नकाशा'
        ."\n"
        .'<div id="googleMap" style="width:275px;height:300px;"></div>'
        ."\n"
        .'</div>'
      .'</article>'
      ."\n"
  .'</body>'
  ."\n"
.'</html> '
."\n";
  fwrite($myfile, $txt);
  fclose($myfile);
}
//header("Location: ".$filename);
?>
