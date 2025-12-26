<!DOCTYPE html>
<html>
<head>
	<title>Map with Polygon and Marker</title>
	<meta name="description" content="A demo of google maps with polygon and marker" />
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
	<style>
		html, body, #map-canvas { height: 100%; margin: 0px; padding: 0px }
    </style>
    

	<script type="text/javascript">


var MarkerData = [20.227693557739,73.919532775879, "Elsie Roy Elementary"];
var PathData = [
[20.227693557739,73.919532775879], 
[20.227674484253,73.919532775879],
[20.227674484253,73.919525146484],
[20.227705001831,73.919494628906],
[20.227693557739,73.919532775879]
];

function initialize() {

	var map = 
	    new google.maps.Map(document.getElementById('map-canvas'));
	var infowindow = new google.maps.InfoWindow();
	var center = new google.maps.LatLng(MarkerData[0], MarkerData[1]);
	var marker = new google.maps.Marker({
		position: center,
		map: map,
		title: MarkerData[2]
	});

	google.maps.event.addListener(marker, 'click', function() {
		infowindow.setContent(this.title);
		infowindow.open(map, this);
	});

	var path = [];
	var bounds = new google.maps.LatLngBounds();
	bounds.extend(center);
	for (var i in PathData)
	{
		var p = PathData[i];
		var latlng = new google.maps.LatLng(p[0], p[1]);
		path.push(latlng);
		bounds.extend(latlng);
	}
	var poly = new google.maps.Polygon({
		paths: path,
		strokeColor: '#FF0000',
		strokeOpacity: 0.8,
		strokeWeight: 3,
		fillColor: '#FF0000',
		fillOpacity: 0.1
	});
	poly.setMap(map);
	
	
	map.fitBounds(bounds);
}

google.maps.event.addDomListener(window, 'load', initialize);


	</script>

</head>

<body>
	<div id="map-canvas"></div>
</body>
</html>