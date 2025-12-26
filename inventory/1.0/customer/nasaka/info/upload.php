<!DOCTYPE html>
<html>
<body>

<form action="uploadtextfile.php" method="post" enctype="multipart/form-data">
    Select Text File to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <?php
    session_start();
    $_SESSION['lastrecord']=0;
    ?>
    <input type="submit" value="Upload File" name="submit">
</form>

</body>
</html>