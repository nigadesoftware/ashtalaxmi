<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/w3.css">
        <title>Contract</title>
        <link rel="stylesheet" href="../css/swapp123.css">
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
        <link rel="stylesheet" href="../css/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script>
        var data = {};
        $(document).ready(function() {
        $('input[type="submit"]').on('click', function() {
            resetErrors();
            var url = 'process.php';
            $.each($('form input, form select'), function(i, v) {
                if (v.type !== 'submit') {
                    data[v.name] = v.value;
                }
            }); //end each
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: url,
                data: data,
                success: function(resp) {
                    if (resp === true) {
                            //successful validation
                            $('form').submit();
                            return false;
                    } else {
                        $.each(resp, function(i, v) {
                    console.log(i + " => " + v); // view in console for error messages
                            var msg = '<label class="error" for="'+i+'">'+v+'</label>';
                            $('input[name="' + i + '"], select[name="' + i + '"]').addClass('inputTxtError').after(msg);
                        });
                        var keys = Object.keys(resp);
                        $('input[name="'+keys[0]+'"]').focus();
                    }
                    return false;
                },
                error: function() {
                    console.log('there was a problem checking the fields');
                }
            });
            return false;
        });
        });
        function resetErrors() {
            $('form input, form select').removeClass('inputTxtError');
            $('label.error').remove();
        }
    </script>

    </head>
    <body>
        <nav "w3-container">
        </nav>
        <article class="w3-container">


<?php
    echo '<section>';
    echo '<form method="post" action="process.php">';
    echo '<label>First Name</label>';
    echo '<input name="first_name" type="text"  />';
    echo '<label>Email</label>';
    echo '<input name="email" type="text"  />';
    echo '<input type="submit" value="Submit" />';
    echo '</form>';
    echo '</section>';
?>
    </article>
    <footer>
    </footer>
    </body>
</html>
