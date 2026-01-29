<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); 
    if (isset($_GET['flag']))
    {
        $flag = $_GET['flag'];    
    }
    else
    {
        $flag = -1;
    }
    if (isset($_GET['userid']))
    {
        $userid_def = $_GET['userid'];    
    }
    else
    {
        $userid_def = 0;
    }
    //$userid_def =621754328954127;
    if (isset($_GET['paramid']))
    {
        $paramid_def = $_GET['paramid'];    
        //$paramid_def = 1243508662092296;
    }
    else
    {
        $paramid_def = 0;
    }

    if ($paramid_def != 0)
    {
        $paramid_def = $paramid_def/2;
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('dmY',$dt);
        date_default_timezone_set("UTC");
        $paramid_def = $paramid_def-$dt;
    }
    if (isset($_GET['yearcode']))
    {
        $yearcode_def = $_GET['yearcode'];    
    }
    else
    {
        $yearcode_def = 0;
    }

    


    /*require ("../info/ncryptdcrypt.php");
    $pwd="CMX0weHdKa7y4QxlpoYQ2A==";
    echo 'ur pwd : '.fnDecryptpass($pwd);*/
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="../css/login.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<img class="wave" src="../img/wave.png">
	<div class="container">
		<div class="img">
			<img src="../img/bg.svg">
		</div>
		<div class="login-content">
			<form  method="post" role="form" action="../sqlproc/validatelogin.php">
				<img src="../img/avatar.svg">
				<h2 class="title">nigadeERP</h2>
           		<div class="input-div one">
           		   
           		   		
                        <?php
                        if ($userid_def!=0 and $paramid_def!=$userid_def)
                        {
                            echo '<div class="i">';
                            echo '        <i class="fas fa-user"></i>';
                            echo '</div>';
                            echo '<div class="div">';
                            echo '<h5>Username</h5>';
                            echo '<input type="text" class="input" name="userid" id="userid" autofocus>';
                        }
                        else if ($paramid_def==0)
                        {
                            echo '<div class="i">';
                            echo '        <i class="fas fa-user"></i>';
                            echo '</div>';
                            echo '<div class="div">';
                            echo '<h5>Username</h5>';
                            echo '<input type="text" class="input" name="userid" id="userid">';
                        }
                        else if ($userid_def!=0 and $paramid_def==$userid_def)
                        {
                            echo '<div class="div">';
                            echo '<input type="hidden" name="userid" id="userid"  value="'.$userid_def.'">';
                        }
                        ?>
           		   </div>
           		</div>
           		<div class="input-div pass">
           		   
           		    	
           		    	
                           <?php
                        if ($userid_def!=0 and $paramid_def!=$userid_def)
                        {
                            echo '<div class="i">';
                            echo '      <i class="fas fa-lock"></i>';
                            echo '</div>';
                            echo '<div class="div">';
                            echo '<h5>Password</h5>';
           		   		    echo '<input type="password" class="input" name="users_pass" id="users_pass" autofocus>';
                        }
                        else if ($paramid_def==0)
                        {
                            echo '<div class="i">';
                            echo '      <i class="fas fa-lock"></i>';
                            echo '</div>';
                            echo '<div class="div">';
                            echo '<h5>Password</h5>';
                            echo '<input type="password" class="input" name="users_pass" id="users_pass">';
                        }
                        else if ($userid_def!=0 and $paramid_def==$userid_def)
                        {
                            echo '<div class="div">';
                            echo '<input type="hidden" name="users_pass" id="users_pass" value="'.$paramid_def.'">';
                            
                            
                        }
                        ?>
                    </div>
            	</div>
                <?php
                if ($paramid_def==0)
                {
                    echo '<div class="input-div pass">';
                    echo '<div class="i">'; 
                    echo '	<i class="fas fa-lock"></i>';
           		    echo '</div>';
           		    echo '<div class="div">';
           		    echo ' <h5>Captcha</h5>';
           	        echo ' <input type="text" autocomplete="off" class="input" name="capcode" id="capcode">';
                    echo '</div>';
                    
            	    echo '</div>';
                }
                else
                {
                   
                }
                if ($userid_def!=0 and $paramid_def==$userid_def)
                {
                    /* echo '<div class="div">';
                    echo '<input type="submit" class="btn" value="Select Module">';
                    echo '</div>'; */
                    echo '
                    <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        setTimeout(() => document.forms[0].submit(), 100);
                    });
                    </script>';
                }
                else
                {
                    echo '<input type="submit" class="btn" value="Login">';
                    echo '<div class="div">';
                    echo '        <img src="captcha.php" width="300" height="100">';
                    echo '</div>';
                }
                ?>
            	<!-- <a href="#">Forgot Password?</a> -->
            	
                
                <?php
                    echo '<td><input type="hidden" name="userid_def" id="userid_def" value="'.$userid_def.'"></input></td>';
                    echo '<td><input type="hidden" name="paramid_def" id="paramid_def" value="'.$paramid_def.'"></input></td>';
                    echo '<td><input type="hidden" name="yearcode_def" id="yearcode_def" value="'.$yearcode_def.'"></input></td>';
                ?>  
                <?php
                            if ($paramid_def==0)
                            {
                                /* echo '<tr>';
                                echo '    <td height="30px"><button type="submit">Login </button>';
                                echo '</tr>'; */
                            }
                            else if ($userid_def!=0 and $paramid_def==$userid_def)
                            {
                                /* echo '<tr>';
                                echo '    <td height="30px"><button type="submit">Select Module</button>';
                                echo '</tr>'; */

                                
                            }
                        ?>
                <?php
                            if ($flag == 0)
                            {
                                echo '<label for="message">Successfully, Logged out!</label>';
                            }
                            elseif ($flag == 2)
                            {
                                echo '<label for="message">Timed out! Login Again</label>';
                            }
                            elseif ($flag == 3)
                            {
                                echo '<label for="message">Login IP Changed! Login Again</label>';
                            }
                            elseif ($flag == 4)
                            {
                                echo '<label for="message">Incomplete Login Information!</label>';
                            }
                            elseif ($flag == 5)
                            {
                                echo '<label for="message">Invalid Credentials</label>';
                            }
                            elseif ($flag == 6)
                            {
                                echo '<label for="message">Invalid User Id</label>';
                            }
                            elseif ($flag == 7)
                            {
                                echo '<label for="message">Invalid Captcha Code</label>';
                            }
                        ?>  
            </form>
        </div>
    </div>
    <script type="text/javascript" src="../js/main.js"></script>
    <body>
<?php
if ($yearcode_def != 0) {
    echo '
    <div id="busyOverlay" style="
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255,255,255,0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        z-index: 9999;
    ">
        <img src="../img/busy.gif" alt="Please wait..." style="width:100px;height:100px;">
        <p style="font-family:Poppins, sans-serif; font-size:18px; color:#333;">Please wait...</p>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Keep busy overlay visible briefly, then auto-hide or redirect
        setTimeout(() => {
            document.getElementById("busyOverlay").style.display = "none";
            // Optionally auto-submit
            // document.forms[0].submit();
        }, 6000);
    });
    </script>
    ';
}
    ?>
</body>
</html>
