<!DOCTYPE html>
<html>
<head>
	<title>Map</title>
	<meta charset="utf-8"></meta>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../css/w3.css">
	<script async src="http://maps.googleapis.com/maps/api/js"></script>
	<script async>
	function initialize() {
	  var mapProp = {
	    center:new google.maps.LatLng(18.1,74.28),
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
					background-color: #c44;
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
					color: #fc8;
					line-height: 30px;
				}
				article
				{
					background-color: #fc8;
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
	<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58db35533dec30001259e677&product=sticky-share-buttons"></script>
</head>
	<body>
			<header class="w3-container">
				<div><img src="../img/swapp_namelogo.png" width="150px" height="50px">
				</div>
			</header>
			<nav "w3-container">
				<ul class="navbar">
					<li><a class="navbar" href="/index.php">Home</a>
				</ul>
			</nav>
			<article>
				<div id="factory" style="width:300px;height:100px;">
					
				</div>
				<div id="googleMap" style="width:300px;height:300px;"></div>
			</article>
			<footer>
						Copyright @2015 Swapp Software Application.<br/>
						All Rights Reserved.
			</footer>
	</body>
</html> 