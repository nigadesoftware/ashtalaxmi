<?php
    require("../info/phpgetlogin.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/w3.css">
        <title>Change Password</title>
        <style type="text/css">
            body
            {
                background-color: #fff;
            }
            header
            {
                background-color: #fff;
                min-height: 70px;
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
                color: #fff;
                line-height: 30px;
            }
            a
            {
                color: #f48;
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
                color: #f48;
                line-height: 23px;
            }
            footer
            {
                float: bottom;
                color: #fff;
                font-family: verdana;
                font-size: 12px;
            }
            div
            {
                float:left;
            }
        </style>
        <script src="../js/1.11.0/jquery.min.js">
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
        <nav "w3-container">
            <ul class="navbar">
                <li><a class="navbar" href="../mis/usermenu.php">Menu</a>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/changepassword.png" width="216px" height="33px">
            </div>
            <section>
                <form method="post" action="../sqlproc/updatepassword.php">
                    <table border="0" >
                        <tr>
                            <td><label for="existing_password">Existing Password *</label></td>
                        </tr>
                        <tr>
                            <td><input type="password" name="existing_password" id="existing_password"></td>
                        </tr>
                        <tr>
                            <td><label for="new_password">New Password *</label></td>
                        </tr>
                        <tr>
                            <td><input type="password" name="new_password" id="new_password"></td>
                        </tr>
                        <tr>
                            <td><label for="retype_new_password">Retype New Password *</label></td>
                        </tr>
                        <tr>
                            <td><input type="password" name="retype_new_password" id="retype_new_password"></td>
                        </tr>
                        <tr>
                            <td><span style="background-color:#ddd;color:#000;text-align:left;">Password Policy</span></td>
                        </tr>
                        <tr>
                            <td><span style="background-color:#ddd;color:#000;text-align:left;">1. Password Length Minimum 8</span></td>
                        </tr>
                        <tr>
                            <td><span style="background-color:#ddd;color:#000;text-align:left;">2. Password Length Maximum 20</span></td>
                        </tr>
                        <tr>
                            <td><span style="background-color:#ddd;color:#000;text-align:left;">3. Atleast One Letter[A-Z] or [a-z] should be present</span></td>
                        </tr>
                        <tr>
                            <td><span style="background-color:#ddd;color:#000;text-align:left;">4. Atleast One Digit (0-9) should be present</span></td>
                        </tr>
                        <tr>
                            <td><input type="submit" value="Submit"/>
                        </tr>
                    </table>
                </form>
            </section>
        </article>
        <footer>
            Copyright @2015 Swapp Software Application.<br/>
            All Rights Reserved.
        </footer>
    </body>
</html>