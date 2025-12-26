<?php
  require("../info/phpgetloginview.php");
	echo '<!DOCTYPE html>';
	echo '<html>';
    	echo '<head>';
	        echo '<meta charset="utf-8"></meta>';
	        echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
	        echo '<link rel="stylesheet" href="../css/w3.css">';
	    echo '</head';
	    echo '<body>';
	    	echo '<nav "w3-container">';
       			echo '<ul class="navbar" >';
       				echo '<li><a  class="navbar" href="../index.php">MIS Home</a>';
       				echo '<li><a  class="navbar" href="../mis/mismenu.php">MIS Menu</a>';
       				if ($_SESSION["responsibilitycode"] == 621478512368915 or $_SESSION["responsibilitycode"] == 785236954125917)
       				{
       				if (isset($_SESSION['selecteduserid']))
       				{
                    	$qstr='?misuserid='.$_SESSION['selecteduserid'].'&misusername='.$_SESSION['selecteduser'];
                    	echo '<li><a class="navbar" href="../ip_view/userdatatransaction.php'.$qstr.'">Select Option</a>';
       				}
       				}
       				echo '<li><a  class="navbar" href="../sqlproc/logout.php">Log Out</a><br/>';
        		echo '</ul>';
    		echo '</nav>';
	    echo '</body>';
	echo '</html>';
?>