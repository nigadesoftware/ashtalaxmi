<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    include("../info/ncryptdcrypt.php");
    require("../info/rawmaterialroutine.php");
    //Raw Material Transaction Addition or Alteration
    if (isaccessible(451230287895415)==0 and isaccessible(475124562358965)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $contractid_de = fnDecrypt($_GET['contractid']);
    $contractreferencecategoryid_de = fnDecrypt($_GET['contractreferencecategoryid']);
    $contractreferencedetailid_de = fnDecrypt($_GET['contractreferencedetailid']);
    if (isset($_GET['contractphotodetailid']))
    {
        $contractphotodetailid_de = fnDecrypt($_GET['contractphotodetailid']);    
    }
    $flag = $_GET['flag'];
    // Opens a connection to a MySQL server
    $connection=rawmaterial_connection();
?>
<!doctype html>
 
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Capture Web camera image</title>
	<style type="text/css">
		body { font-family: Helvetica, sans-serif; }
		h2, h3 { margin-top:0; }
		form { margin-top: 15px; }
		form > input { margin-right: 15px; }
		#results { float:right; margin:20px; padding:20px; border:1px solid; background:#ccc; }
	</style>
</head>
<body>
	<div id="results">Your captured image will appear here...</div>
	
	<h1>Capture Web camera image</h1>
	<h3>Demonstrates simple 600x460 capture &amp; display</h3>
	
	<div id="my_camera"></div>
	
	<!-- First, include the Webcam.js JavaScript Library -->
	<script type="text/javascript" src="../js/webcam.js"></script>
	
	<!-- Configure a few settings and attach camera -->
	<script language="JavaScript">
		Webcam.set({
			width: 600,
			height: 460,
			image_format: 'jpeg',
			jpeg_quality: 90
		});
		Webcam.attach( '#my_camera' );
	</script>
	
    <nav "w3-container">
            <ul class="navbar">
                <li><a class="navbar" href="../data/entitymenu.php">Entity Menu</a><br/>
                <?php
                    /*$personnamedetailtypeid_en = fnEncrypt($personnamedetailtypeid_de);
                    $personnamedetailid_en = fnEncrypt($personnamedetailid_de);
                    echo '<li><a style="color:#f48;text-align:left;" href="../data/personnamedetail_find.php?personnamedetailtypeid='.$personnamedetailtypeid_en.'">personnamedetail Find</a><br/>';*/
                    $contractreferencecategoryid_en = fnEncrypt($contractreferencecategoryid_de);
                    $contractreferencedetailid_en = fnEncrypt($contractreferencedetailid_de);
                    echo '<li><a class="navbar" href="../data/contract.php?contractid='.fnEncrypt($contractid_de).'&flag='.fnEncrypt('Display').'">Add/Display Contract</a></br>';
                    if ($contractreferencecategoryid_de == 254156358)
                    {
                        echo '<li><a class="navbar" href="../data/contractharvestdetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractreferencecategoryid='.$_GET['contractreferencecategoryid'].'&flag='.fnEncrypt('Display').'">Add/Display Contract Harvest Detail List</a></br>';    
                    }
                    elseif ($contractreferencecategoryid_de == 584251658)
                    {
                        echo '<li><a class="navbar" href="../data/contracttransportdetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractreferencecategoryid='.$_GET['contractreferencecategoryid'].'&flag='.fnEncrypt('Display').'">Add/Display Contract Transport Detail List</a></br>';    
                    }
                    echo '<li><a class="navbar" href="../data/contractphotodetail_list.php?contractid='.fnEncrypt($contractid_de).'&contractreferencecategoryid='.$contractreferencecategoryid_en.'&contractreferencedetailid='.$contractreferencedetailid_en.'&flag='.fnEncrypt('Display').'">Add/Display Contract Aadhar Detail List</a></br>';
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>

	<!-- A button for taking snaps -->
	<form>
		
        <input type=button value="Take Snapshot" onClick="take_snapshot()">
	</form>
	
    <article class="w3-container">
            <div><img src="../img/contract.png" width="201" height="41px"></div>
            <?php
                $query = "select f.* from contractphotodetail f where f.active=1 and f.contractid = ".$contractid_de." and f.contractreferencecategoryid=".$contractreferencecategoryid_de." and f.contractreferencedetailid=".$contractreferencedetailid_de;
                //echo $query;
                $result = oci_parse($connection, $query); $r = oci_execute($result);
                if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    echo '<section>';
                    //if ($flag_de == 'Display')
                    //{
                        echo '<form enctype="multipart/form-data" action="../api_action/contractphotodetail_action.php" method="post" name="changer">';
                    //}
                        echo '<table border="0" >';

                        echo '<tr>';  
                        echo '<td></td>';  
                        echo '</tr>';

                        echo '<input name="MAX_FILE_SIZE" value="102400" type="hidden">';
                        echo '<img src="data:image/jpeg;base64,'.base64_encode( $row['FINGERPRINT'] ).'"/>';
                        echo '<input name="fingerprint" accept="image/jpeg" type="file">';
                        
                        echo '<tr>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractid" id="contractid" style="width:300px" value ="'.$row['CONTRACTID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractreferencecategoryid" id="contractreferencecategoryid" style="width:300px" value ="'.$row['CONTRACTREFERENCECATEGORYID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractreferencedetailid" id="contractreferencedetailid" style="width:300px" value ="'.$row['CONTRACTREFERENCEDETAILID'].'"></td>';
                        echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractphotodetailid" id="contractphotodetailid" style="width:300px" value ="'.$row['CONTRACTPHOTODETAILID'].'"></td>';
                        echo '</tr>';

                        if ($flag=='change')
                        {
                            echo '<tr>';
                            echo '<td><input type="submit" name="btn" value="Change" style="width:100px"></td>';
                            echo '</tr>';
                        }
                        if ($flag=='delete')
                        {
                            echo '<tr>';
                            echo '<td><input type="submit" name="btn" value="Delete" style="width:100px"></td>';
                            echo '</tr>';
                        }
                        echo '<tr>';
                        echo '<td><input type="submit" name="btn" value="Reset" style="width:100px"></td>';
                        echo '</tr>';
                        
                    echo '</table>';
                    echo '</form>';
                    echo '</section>';
                }
                else
                {
                    echo '<section>';
                    //echo '<form method="post" action="../api_action/contractphotodetail_action.php">';
                    echo '<form enctype="multipart/form-data" action="../api_action/contractphotodetail_action.php" method="post" name="changer">';
                    echo '<table border="0" >';

                    echo '<tr>';
                    echo '<td<input name="MAX_FILE_SIZE" value="102400" type="hidden"></td>';
                    //echo '<input type=button value="Take Snapshot" onClick="take_snapshot()">';
                    echo '<td><input type=button value="Take Snapshot" onClick="take_snapshot()"></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><img id="blah" src="#" alt="your image" /></td>';
                    echo '</tr>';
                    //echo<input value="Submit" type="submit">

                    /*echo '<tr>';
                    echo '<td><label for="aadharnumber">Aadhar</label></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="aadharnumber" id="aadharnumber" ></td>';
                    echo '</tr>';*/

                    echo '<tr>';
                    echo '<td></td>';  
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractid" id="contractid" style="width:300px" value ="'.$contractid_de.'"></td>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractreferencecategoryid" id="contractreferencecategoryid" style="width:300px" value ="'.$contractreferencecategoryid_de.'"></td>';
                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="contractreferencedetailid" id="contractreferencedetailid" style="width:300px" value ="'.$contractreferencedetailid_de.'"></td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td><input type="submit" name="btn" value="Add" style="width:100px"</button>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="submit" name="btn" value="Display" style="width:100px"</button>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="submit" name="btn" value="Reset" style="width:100px"</button>';
                    echo '</tr>';
                    echo '</table>';
                    echo '</form>';
                    echo '</section>';
                }
            ?>
        </article>

	<!-- Code to handle taking the snapshot and displaying it locally -->
	<script language="JavaScript">
		function take_snapshot() {
			// take snapshot and get image data
			Webcam.snap( function(data_uri) {
				// display results in page
				
					
				Webcam.upload( data_uri, 'saveimage.php', function(code, text) {
					document.getElementById('results').innerHTML = 
					'<h2>Here is your image:</h2>' + 
					'<img src="'+text+'"/>';
				} );	
			} );
		}
	</script>
	
</body>
</html>