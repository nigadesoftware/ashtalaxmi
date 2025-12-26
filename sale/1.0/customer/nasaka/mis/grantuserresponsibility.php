<?php
    require("../info/phpgetlogin.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/w3.css">
        <title>Grant User Responsibility</title>
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
            <div><img src="../img/grantrole.png" width="146px" height="33px">
            </div>
          <section>
                <form method="post" action="../sqlproc/insertuserresponsibility.php">
                    <table border="0" >
                        
                        <?php
                            echo '<tr>';
                            echo '<td><label for="users_mobile">युजर मोबाईल (User Mobile)</label></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>';
                            echo '<select name="users_mobile" style="height:35px;font-size:14px;">';
                            // Opens a connection to a MySQL server
                            $connection=mysqli_connect($hostname, $username, $password, $database);
                            // Check connection
                            if (mysqli_connect_errno())
                            {
                              echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication error</span>';
                              exit;
                            }
                            mysqli_query($connection,'SET NAMES UTF8');
                            $result1 = mysqli_query($connection, "select * from misuser m where m.active=1 and (miscustomerid=".$_SESSION["factorycode"]." or miscustomerid=0) order by misusername");
                            
                            while ($row1 = mysqli_fetch_assoc($result1))
                            {
                                echo '<option value="'.$row1['misuserid'].'">'.$row1['misusername'].'</option>';   
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '</tr>';

                            echo '<tr>';
                            echo '<td><label for="users_responsibility">युजर जबाबदारी (User Responsibility)</label></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>';
                            echo '<select name="users_responsibility" style="height:35px;font-size:14px;">';
                            // Opens a connection to a MySQL server
                            $connection=mysqli_connect($hostname, $username, $password, $database);
                            // Check connection
                            if (mysqli_connect_errno())
                            {
                              echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication error</span>';
                              exit;
                            }
                            mysqli_query($connection,'SET NAMES UTF8');
                            $result1 = mysqli_query($connection, "select * from misresponsibility m where m.active=1 order by misresponsibilityname");
                            
                            while ($row1 = mysqli_fetch_assoc($result1))
                            {
                                echo '<option value="'.$row1['misresponsibilityid'].'">'.$row1['misresponsibilityname'].'</option>';   
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '</tr>';
                        ?>
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
