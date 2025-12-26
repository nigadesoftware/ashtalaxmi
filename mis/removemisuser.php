<?php
    require("../info/phpgetlogin.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./js/w3.css">
        <title>Remove MIS User</title>
        <style type="text/css">
            body
            {
                background-color: #c44;
            }
            header
            {
                background-color: #fff;
                min-height: 50px;
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
                color: #fc8;
                line-height: 30px;
            }
            a
            {
                color: #fc8;
            }
            article
            {
                background-color: #fc8;
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
                color: #eee;
                font-family: verdana;
                font-size: 12px;
            }
            div
            {
                float:left;
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
                <li><a class="navbar" href="../mis/mismenu.php">MIS Menu</a>
            </ul>
        </nav>
        <article class="w3-container">
            <section>
                <form method="post" action="../sqlproc/deletemisuser.php">
                    <table border="0" >
                        <tr>
                            <td><label for="users_mobile">User Mobile</label></td>
                        </tr>
                            <td><input type="text" name="users_mobile" id="users_mobile"></td>
                        </tr>
                        <tr>
                            <td><label for="remove_user">Remove MIS User?</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="remove_user" id="remove_user"></td>
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