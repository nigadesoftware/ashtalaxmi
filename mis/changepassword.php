<?php
    require("../info/phpgetlogin.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./js/w3.css">
        <title>Change Password</title>
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
        <nav "w3-container">
            <ul class="navbar">
                <li><a class="navbar" href="../mis/login.php">Login</a>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/userlogin.png" width="50px" height="50px">
            </div>
            <section>
                <form method="post" action="../sqlproc/updatepassword.php">
                    <table border="0" >
                        <tr>
                            <td><label for="existing_password">Existing Password</label></td>
                        </tr>
                        <tr>
                            <td><input type="password" name="existing_password" id="existing_password"></td>
                            <td>*</td>
                        </tr>
                        <tr>
                            <td><label for="new_password">New Password</label></td>
                        </tr>
                        <tr>
                            <td><input type="password" name="new_password" id="new_password"></td>
                            <td>*</td>
                        </tr>
                        <tr>
                            <td><label for="retype_new_password">Retype New Password</label></td>
                        </tr>
                        <tr> 
                            <td><input type="password" name="retype_new_password" id="retype_new_password"></td>
                            <td>*</td>
                        </tr>
                        <tr>
                            <td><input type="submit" value="Submit"/>
                        </tr>
                        <tr>
                            <td><input type="reset" value="Reset"/>
                        </tr>
                    </table>
                </form>
            </section>
        </article>
        <footer>
            Copyright @2017 Swapp Software Application.<br/>
            All Rights Reserved.
        </footer>
    </body>
</html>
