<!doctype html>
<html manifest="index.appcache">
<head>
    <script type="text/javascript" src=
    "http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
</head>
<script>
    // Set global variable
var watchID;

function showPosition() 
{
    var geo_options = 
    {
        enableHighAccuracy: true, 
        maximumAge        : 30000, 
        timeout           : 27000
    };
    
    if(navigator.geolocation) 
    {
        watchID = navigator.geolocation.watchPosition(successCallback,geo_error,geo_options);
    } else 
    {
        alert("Sorry, your browser does not support HTML5 geolocation.");
    }
}
function successCallback(position) 
{
    toggleWatchBtn.innerHTML = "Stop Watching";
    
    // Check position has been changed or not before doing anything
    if(prevLat != position.coords.latitude || prevLong != position.coords.longitude) {
        
        // Set previous location
        var prevLat = position.coords.latitude;
        var prevLong = position.coords.longitude;
        // Get current position
        var positionInfo = "Your current position is (" + "Latitude: " + position.coords.latitude + ", " + "Longitude: " + position.coords.longitude + ")";
        //document.getElementById("results").innerHTML = positionInfo;
        /* $('#latitude').val(prevLat);
        $('#longitude').val(prevLong); */
        document.getElementById("latitude").value = prevLat.toString();
        document.getElementById("longitude").value = prevLong.toString();
    }
    
}
function geo_error() 
{
    alert("Sorry, no position available.");
}

function startWatch() 
{
    var result = document.getElementById("result");
    
    var toggleWatchBtn = document.getElementById("toggleWatchBtn");
    
    toggleWatchBtn.onclick = function() 
    {
        if(watchID) 
        {
            toggleWatchBtn.innerHTML = "Start Watching";
            navigator.geolocation.clearWatch(watchID);
            watchID = false;
        }
        else 
        {
            toggleWatchBtn.innerHTML = "Aquiring Geo Location...";
            showPosition();
        }
    }
}
    // Initialise the whole system (above)
    window.onload = startWatch;
</script>

<body>

<h2>Add Point</h2>
<button type="button" id="toggleWatchBtn">Start Watching</button><br/>
<input type="number" id="plotnumber" placeholder="Plot Number"><br/>
<input type="text"  name="latitude" id="latitude" style="width:300px">
<input type="text"  name="longitude" id="longitude" style="width:300px">
<input readonly="true" tabindex="-1" type="hidden" id="lastpointindex" placeholder="Last Index"><br/>
<button id="addPoint">Add Point</button>
<p/>

<h2>Search Point</h2>
<button id="search">Search</button>
<button id="delete">Delete Last Point</button>
<div id="results"></div>

<script>
function idbOK() {
    return "indexedDB" in window;
}

var db;

$(document).ready(function() {

    //No support? Go in the corner and pout.
    if(!idbOK()) return;

    var openRequest = indexedDB.open("loc_point",1);

    openRequest.onupgradeneeded = function(e) {
        var thisDB = e.target.result;
        console.log("running onupgradeneeded");

        if(!thisDB.objectStoreNames.contains("point")) {
            var pointOS = thisDB.createObjectStore("point",
                {keyPath: "id", autoIncrement: true });
                pointOS.createIndex("plotnumber", "plotnumber");
                pointOS.createIndex("id", "id",
                {unique:false, multiEntry: true});

        }

    }

    openRequest.onsuccess = function(e) {
        console.log("running onsuccess");
        db = e.target.result;

        //Start listening for button clicks
        $("#addPoint").on("click", addPoint);
        $("#search").on("click", searchPoint);
        $("#delete").on("click", deletePoint);
    }

    openRequest.onerror = function(e) {
        console.log("onerror!");
        console.dir(e);
    }

});

function addPoint(e) {
    var plotnumber = $("#plotnumber").val();
    var latitude = $("#latitude").val();;
    var longitude = $("#longitude").val();;

    //if(hobbies != "") hobbies = hobbies.split(",");

    console.log("About to add "+latitude+"/"+longitude);

    //Get a transaction
    //default for OS list is all, default for type is read
    var transaction = db.transaction(["point"],"readwrite");
    //Ask for the objectStore
    var store = transaction.objectStore("point");

    //Define a person
    var point = {
        plotnumber:plotnumber,
        latitude:latitude,
        longitude:longitude,
        created:new Date().getTime()
    }

    //Perform the add
    var request = store.add(point);

    request.onerror = function(e) {
        console.log("Error",e.target.error.name);
        //some type of error handler
    }

    request.onsuccess = function(e) {
        console.log("Woot! Did it");
    }
    searchPoint(e);
    $("#latitude").val('');
    $("#longitude").val('');
}

function searchPoint(e) 
{

    var plnumber = $("#plotnumber").val();

    if(plnumber == "") return;

    var range = IDBKeyRange.only(plnumber);

    var transaction = db.transaction(["point"],"readonly");
    var store = transaction.objectStore("point");
    var index = store.index("plotnumber");

    var s = "";
    var i = 1;
    index.openCursor(range).onsuccess = function(e) {
        var cursor = e.target.result;
        if(cursor) 
        {
            s += "<p>";
            for(var field in cursor.value) 
            {
                if ((field=="latitude") || (field=="longitude"))
                {
                    s+= i+"."+field+"="+cursor.value[field]+",";
                }
                if (field=="id")
                $("#lastpointindex").val(cursor.value[field]);
            }
            s+="</p>";
            cursor.continue();
        }
        i++;
    }

    transaction.oncomplete = function() {
        //no results?
        if(s === "") s = "<p>No results.</p>";
        $("#results").html(s);
    }

}

function deletePoint(e) {
    var key = $("#lastpointindex").val();
    if(key === "") return;

    var transaction = db.transaction(["point"],"readwrite");
    var store = transaction.objectStore("point");

    var request = store.delete(Number(key));

    request.onsuccess = function(e) {
        console.log("Point deleted");
        console.dir(e);
    }

    request.onerror = function(e) {
        console.log("Error");
        console.dir(e);
    }
    searchPoint(e);
    $("#latitude").val('');
    $("#longitude").val('');
}

</script>
</body>
</html>