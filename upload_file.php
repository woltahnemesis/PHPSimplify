<?php require_once 'DBUtility.php'; ?>

<?php

$db = new DBUtility();

$db->checkConn();

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Rey Magbanua">
        <meta name="description" content="Making an upload system">
        <meta name="keywords" content="HTML, CSS, JavaScript, PHP">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Upload A Photo</title>
    </head>
    <body>
        <!-- Needs enctype to upload a file -->
        <form action="" method="post" enctype="multipart/form-data">
            <label for="photo">Choose a photo</label>
            <br />
            <input type="file" name="photo" id="photo" accept="image/*" required>
            <br />
            <input type="submit" name="submit" value="Upload">
        </form>

        <?php


        // Check if form is submitted
        if(isset($_POST['submit'])) {

          $sql = 'INSERT INTO photos_version2(name) VALUES(:name)';

          // The first index of the dictionary must always be "name" => "default"
          // when uploading a file
          $data = [
            'name' => 'default'
          ];

          $fileData = [
            // Name of the input file
            'inputName' => 'photo',

            // Maximum size for the file
            // This is 2000000 bytes which is 2 megabytes
            'maxSize' => 2000000,

            // Acceptable formats
            'accept' => array('jpeg', 'jpg', 'png', 'gif'),

            // True: Creates a unique name for your file
            // False: Default name will remain
            'bool' => true,

            // The directory/folder where you're gonna store the file
            'fileLoc' => 'Uploads/'
          ];

          // Upload file
          if($db->uploadFile($sql, $data, $fileData)) {
            echo 'File successfully uploaded!';

            $db->closeConn();
          }
        }


        ?>
    </body>
</html>
