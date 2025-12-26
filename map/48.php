<?php
header("location: ../site/factorynewmap.php?factoryid=Mgj8^kN`k<gjO^k:bkMgj8^kN`k<gjO^k@akMgj8^kN`k<gjN^k>akMgj9^kL`k");
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
?>
<!DOCTYPE html>
<html>
<head>
<title>Swapp Software Application</title>
<meta charset="utf-8"></meta>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../css/w3.css">
<script src="http://maps.googleapis.com/maps/api/js"></script>
<script>
function initialize() {
  var mapProp = {
center:new google.maps.LatLng(16.719999,74.580002),
zoom:13,
mapTypeId:google.maps.MapTypeId.ROADMAP
};
var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>
<style type="text/css">
body
  {
    background-color: #fff;
  }
header
{
  background-color: #333;
 min-height: 50px;
  color: #070;
  font-family: Arial;
  font-size: 19px;
}
nav
{
  width: 300px;
  float: left;
  list-style-type: none;
  font-family: verdana;
  font-size: 15px;
  color: #070;
  line-height: 30px;
}
article
{
  background-color: #fff;
  display: table;
  margin-left: 0px;
  padding-left: 10px;
  font-family: Verdana;
  font-size: 15px;
}
section
{
  margin-left: 0px;
  margin-right: 15px;
  float: left;
  text-align: justify;
  color: #111;
  line-height: 23px;
}
footer
{
  float: bottom;
  color: #eee;
  font-family: verdana;
  font-size: 12px;
}
div
{
  float:left;
}
</style>
</head>
<body>
<nav "w3-container">
<meta name="description" content="Datta Shetakari Sahakari Sakhar Karkhana" /><ul class="navbar">
<li><a class="navbar" href="../site/factory_list.php">Sugar Factory List</Br>साखर कारखाना यादी</a><li><a class="navbar">Datta Shetakari Sahakari Sakhar Karkhana Information</Br>दत्त शेतकरी सहकारी साखर कारखाना माहिती</a>
</ul>
</nav>
<article>
<div id="factory" style="width:300px;height:100px;">
Datta Shetakari Sahakari Sakhar Karkhana</br> Location Map</br>दत्त शेतकरी सहकारी साखर कारखाना स्थळ नकाशा
<div id="googleMap" style="width:275px;height:300px;"></div>
</div></article>
</body>
</html> 
