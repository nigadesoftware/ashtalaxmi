<?php
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
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./js/w3.css">
        <title>Login</title>
        <style type="text/css">
            body
            {
                background-color: #fff;
            }
            header
            {
                background-color: #fff;
                min-height: 38px;
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
                color: #f48;
                line-height: 30px;
            }
            article
            {
                background-color: #fff;
                display: table;
                margin-left: 0px;
                padding-left: 3px;
                font-family: Arial Unicode Ms;
                font-size: 15px;
            }
            section
            {
                margin-left: 0px;
                margin-right: 3px;
                float: left;
                text-align: left;
                color: #000;
                line-height: 23px;
            }
            a.navbar
            {
                color: #f48;
            }
            a.servicebar
            {
                color: #b00;
            }
            footer
            {
                float: bottom;
                color: #000;
                font-family: verdana;
                font-size: 12px;
            }
            div
            {
                float:left;
            }
            ul
            {
                line-height: 30px;
            }
        </style>
        <script src="./js/1.11.0/jquery.min.js">
         </script>
         <script>
            $(document).ready(function(){
             setInterval(function(){cache_clear()},3600000);
             });
             function cache_clear()
            {
             window.location.reload(true);
            }
        </script>
    </head>
    <body>
        <header class="w3-container">
        <div style="background-color:#fff;padding-left:20px">
        <?php
        echo '<div><img src="../img/nigadesoftwaretechnologies_logo_3.png" width="35px" height="35px"></div>';
        echo '<div><img src="../img/nigadesoftwaretechnologies_logo.png" width="150px" height="33px"></div>';
        //echo '<div><img src="../img/erp.png" width="49px" height="26px"></div>';
        echo '<div><img src="../img/kadwa.jpg" width="35px" height="35px"></div>';
        echo '<div><label style="font-family:siddhanta;font-size:17px">Nashik Sahakari Sakhar Karkhana</label></div>';
        ?>

                                        </div>
                    <div>
                    <img width="100px" height="100px" src="../img/login.png" alt="">
                    </br>
                     </div>
        </header>
        <nav "w3-container">
            <!-- <ul class="navbar">
                <li><a class="navbar" href="../index.php">MIS Home</a>
            </ul> -->
        </nav>
        <article class="w3-container">
            <!-- <div><img src="../img/userlogin.png" width="50px" height="50px">
            </div> -->
            <section>
                <form method="post" role="form" action="../sqlproc/validatelogin.php">
                    <table style="border=1px solid black; border-radius:10px; padding:0px; padding-top:6px;margin: 2px;background-color:#efd469;color:#373d3f;" align="left" width=300px>
                       <div class="form-group">
                        <tr>
                            <td><h4 for="">User Login Entry</h4></td>
                        </tr>
                        <tr>
                            <?php
                            if ($userid_def==0)
                            {
                                echo '<td><label for="userid">User Id</label></td>';
                            }
                            ?>
                        </tr>
                        <tr>
                            <?php
                                if ($userid_def!=0)
                                {
                                    //echo '<td><input readonly="readonly" tabindex="-1" type="text" name="userid" id="userid" value='.$userid_def.' ></td>';
                                    echo '<td><input type="hidden" name="userid" id="userid" value="'.$userid_def.'"></input></td>';
                                }
                                else
                                {
                                    echo '<td><input type="text" name="userid" id="userid" autofocus></td>';
                                    echo '<td><label for="userid">*</label></td>';
                                }
                            ?>
                            
                        </tr>
                        </div>
                        <div class="form-group">
                        <tr>
                            <td><label for="users_pass">Password</label></td>
                        </tr>
                        <tr>
                            <?php
                                if ($userid_def!=0)
                                {
                                    echo '<td><input type="password" name="users_pass" id="users_pass" autofocus></input></td>';
                                }
                                else
                                {
                                    echo '<td><input type="password" name="users_pass" id="users_pass"></input></td>';
                                }
                            ?>
                            <td><label for="password">*</label></td>
                            </tr>
                            <tr>
                            <td>   <img src="captcha.php" ></td>
                                </tr>
                                <tr>
                                <td><label for="capcode">Enter Captcha</label></td>
                                </tr>
                                <tr>
                                <td><input autocomplete="off" type="text" name="capcode" id="capcode"></input></td>
                        </tr>
                        </div>
                        <tr>
                            <?php
                                echo '<td><input type="hidden" name="userid_def" id="userid_def" value="'.$userid_def.'"></input></td>';
                                echo '<td><input type="hidden" name="yearcode_def" id="yearcode_def" value="'.$yearcode_def.'"></input></td>';
                            ?>
                        </tr>
                        <tr>
                            <td height="30px"><button type="submit">Login </button>
                        </tr>
                        <!-- <tr>
                            <td><a ><input type="checkbox" name="usedefaultusersettings" id="usedefaultusersettings">Use Default User Settings</a></td>
                        </tr>
                        <tr>
                            <td><a ><input type="checkbox" name="changedefaultusersettings" id="changedefaultusersettings">Change Default User Settings</a></td>
                        </tr> -->
                        
                        <?php
                            if ($flag == 0)
                            {
                                echo '<tr>';
                                echo '<td style = "color: #b00"><label for="message">Successfully, Logged out!</label></td>';
                                echo '</tr>';
                            }
                            elseif ($flag == 2)
                            {
                                echo '<tr>';
                                echo '<td style = "color: #b00"><label for="message">Timed out! Login Again</label></td>';
                                echo '</tr>';
                            }
                            elseif ($flag == 3)
                            {
                                echo '<tr>';
                                echo '<td style = "color: #b00"><label for="message">Login IP Changed! Login Again</label></td>';
                                echo '</tr>';
                            }
                            elseif ($flag == 4)
                            {
                                echo '<tr>';
                                echo '<td style = "color: #b00"><label for="message">Incomplete Login Information!</label></td>';
                                echo '</tr>';
                            }
                            elseif ($flag == 5)
                            {
                                echo '<tr>';
                                echo '<td style = "color: #b00"><label for="message">Invalid Credentials</label></td>';
                                echo '</tr>';
                            }
                            elseif ($flag == 6)
                            {
                                echo '<tr>';
                                echo '<td style = "color: #b00"><label for="message">Invalid User Id</label></td>';
                                echo '</tr>';
                            }
                            elseif ($flag == 7)
                            {
                                echo '<tr>';
                                echo '<td style = "color: #b00"><label for="message">Invalid Captcha Code</label></td>';
                                echo '</tr>';
                            }
                        ?>
                    </table>
                </form>
            </section>
        </article>
        <footer>
        </footer>
    </body>
</html>