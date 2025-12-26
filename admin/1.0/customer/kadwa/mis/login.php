<?php
    if (isset($_GET['flag']))
    {
        $flag = $_GET['flag'];    
    }
    else
    {
        $flag = -1;
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
        <link rel="stylesheet" href="../css/w3.css">
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
<script>
// multiplication table d
var d = [
    [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
    [1, 2, 3, 4, 0, 6, 7, 8, 9, 5],
    [2, 3, 4, 0, 1, 7, 8, 9, 5, 6],
    [3, 4, 0, 1, 2, 8, 9, 5, 6, 7],
    [4, 0, 1, 2, 3, 9, 5, 6, 7, 8],
    [5, 9, 8, 7, 6, 0, 4, 3, 2, 1],
    [6, 5, 9, 8, 7, 1, 0, 4, 3, 2],
    [7, 6, 5, 9, 8, 2, 1, 0, 4, 3],
    [8, 7, 6, 5, 9, 3, 2, 1, 0, 4],
    [9, 8, 7, 6, 5, 4, 3, 2, 1, 0]
];

// permutation table p
var p = [
    [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
    [1, 5, 7, 6, 2, 8, 3, 0, 9, 4],
    [5, 8, 0, 3, 7, 9, 6, 1, 4, 2],
    [8, 9, 1, 6, 0, 4, 3, 5, 2, 7],
    [9, 4, 5, 3, 1, 2, 6, 8, 7, 0],
    [4, 2, 8, 6, 5, 7, 3, 9, 0, 1],
    [2, 7, 9, 3, 8, 0, 6, 4, 1, 5],
    [7, 0, 4, 6, 9, 1, 3, 2, 5, 8]
];

// inverse table inv
var inv = [0, 4, 3, 2, 1, 5, 6, 7, 8, 9];

// converts string or number to an array and inverts it
function invArray(array) {

    if (Object.prototype.toString.call(array) === "[object Number]") {
        array = String(array);
    }

    if (Object.prototype.toString.call(array) === "[object String]") {
        array = array.split("").map(Number);
    }

    return array.reverse();

}

// generates checksum
function generate(array) {

    var c = 0;
    var invertedArray = invArray(array);

    for (var i = 0; i < invertedArray.length; i++) {
        c = d[c][p[((i + 1) % 8)][invertedArray[i]]];
    }

    return inv[c];
}

// validates checksum
function validate(array) {

    var c = 0;
    var invertedArray = invArray(array);

    for (var i = 0; i < invertedArray.length; i++) {
        c = d[c][p[(i % 8)][invertedArray[i]]];
    }

    return (c === 0);
}

 $(document).ready(function () {
  $('#UserForm').formValidation({
                    message: 'This value is not valid',
                    feedbackIcons: {
                        valid: 'glyphicon glyphicon-ok',
                        invalid: 'glyphicon glyphicon-remove',
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    fields: {aadhaar_no: {
                            validators: {
                                digits: {
                                    message: 'Please use numeric characters only.'
                                },
                                stringLength: {
                                    min: 12,
                                    max: 12,
                                    message: 'The aadhaar number must be 12 characters long'
                                }, identical: {
                                    field: 'c_aadhaar_number',
                                    message: 'The aadhaar number and its confirm field are not the same'
                                }, callback: {
                                    message: 'The input string is not a valid Aadhaar number.',
                                    callback: function (value, validator, $field) {
                                        return validate(value);
                                    }
                                }
                            }
                        }
 });
            });
</script>
    </head>
    <body>
        <header class="w3-container">
            <div><img src="../img/swapp_namelogo.png" width="93px" height="31px">
                </div>
        </header>
        <nav "w3-container">
            <ul class="navbar">
                <li><a class="navbar" href="../index.php">MIS Home</a>
            </ul>
        </nav>
        <article class="w3-container">
            <div><img src="../img/userlogin.png" width="146px" height="33px">
            </div>
            <section>
                <form method="post" role="form" action="../sqlproc/validatelogin.php">
                    <table style="border=1px solid black; border-radius:10px; padding:0px; padding-top:6px;margin: 0px;" align="left" width=200px>
                       <div class="form-group">
                        <tr>
                            <td><label for="aadharnumber">Aadhar Number</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="aadharnumber" id="aadharnumber"></td>
                        </tr>
                        </div>
                        <div class="form-group">
                        <tr>
                            <td><label for="users_pass">Password</label></td>
                        </tr>
                        <tr>
                            <td><input type="password" name="users_pass" id="users_pass"></input></td>
                        </tr>
                        </div>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td height="30px"><button type="submit">Login </button>
                        </tr>
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
                        ?>
                    </table>
                </form>
            </section>
        </article>
        <footer>
        </footer>
    </body>
</html>