<?php
    require("../info/phpgetlogin.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/w3.css">
        <title>Revoke User Responsibility</title>
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
                color: #f48;
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
                color: #111;
                line-height: 23px;
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
                <li><a class="navbar" href="../mis/mismenu.php">Menu</a>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/revokerole.png" width="160px" height="33px">
            </div>
            <section>
                <form method="post" action="../sqlproc/deleteuserresponsibility.php">
                    <table border="0" >
                        <tr>
                            <td><label for="users_mobile">User Mobile</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="users_mobile" id="users_mobile"></td>
                        </tr>
                        <tr>
                            <td><label for="users_responsibility">User Responsibility</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="users_responsibility" id="users_responsibility"></td>
                        </tr>
                        <tr>
                            <td><input type="submit" value="Submit"/>
                        </tr>
                    </table>
                </form>
            </section>
        </article>
        <footer>
        </footer>
    </body>
</html>
