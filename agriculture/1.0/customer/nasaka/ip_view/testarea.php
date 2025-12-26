<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/w3.css">
        <title>plantationplotareadetail</title>
        <link rel="stylesheet" href="../css/swapp123.css">
        <script src="../js/2.1.3/jquery.min.js">
         </script>
         
        <script>
            // Set global variable
            var watchID;

            function showPosition() {
                if(navigator.geolocation) {
                    watchID = navigator.geolocation.watchPosition(successCallback);
                } else {
                    alert("Sorry, your browser does not support HTML5 geolocation.");
                }
            }
            function successCallback(position) {
                
                // Check position has been changed or not before doing anything
                if(prevLat != position.coords.latitude || prevLong != position.coords.longitude) {
                    
                    // Set previous location
                    var prevLat = position.coords.latitude;
                    var prevLong = position.coords.longitude;
                    
                    // Get current position
                    var positionInfo = "Your current position is (" + "Latitude: " + position.coords.latitude + ", " + "Longitude: " + position.coords.longitude + ")";
                    //document.getElementById("result").innerHTML = positionInfo;
                    $('#latitude').val(prevLat);
                    $('#longitude').val(prevLong);
                }
                
            }
        </script>

    </head>
    <body onload="showPosition()">
        <article class="w3-container">


<?php
    echo '<section>';
    echo '<form method="post">';
    echo '<div style="width:250px;float:left">';
    echo '<td><input type="text"  style="font-size:12pt;height:30px" name="latitude" id="latitude" style="width:300px"></td>';
    echo '<td><input type="text"  style="font-size:12pt;height:30px" name="longitude" id="longitude" style="width:300px"></td>';
    echo '</div>';
        echo '</form>';
        echo '</section>';
    ?>
    </article>
    <footer>
    </footer>
    </body>
</html>

