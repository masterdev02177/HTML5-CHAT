<?php
if (isset($_FILES["uploadImageFile"]) && $_FILES["uploadImageFile"] != null) {
    $target_dir = "../backgrounds/";
    $target_file = $target_dir . basename($_FILES["uploadImageFile"]["name"]);
    $target_file_thumb = $target_dir . "thumbs/" . basename($_FILES["uploadImageFile"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // var_dump($_FILES["uploadImageFile"]["tmp_name"]);
    // Check if image file is a actual image or fake image

    $check = getimagesize($_FILES["uploadImageFile"]["tmp_name"]);
    if ($check !== false) {
        // echo "<script>alert('File is an image - " . $check["mime"] . ".');</script>";
        $uploadOk = 1;
    } else {
        // echo "<script>alert('File is not an image.');</script>";
        $uploadOk = 0;
    }
    // // Check if file already exists
    if (file_exists($target_file)) {
        echo "<script>alert('Sorry, file already exists.');</script>";
        
        $target_file = null;
        $uploadOk = 0;
    }

    //   // Check file size
    //   if ($_FILES["uploadImageFile"]["size"] > 500000) {
    //     echo "Sorry, your file is too large.";
    //     $uploadOk = 0;
    //   }

    // Allow certain file formats
    if ($imageFileType != "jpg") {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
        // echo "<script>alert('Sorry, your file was not uploaded.');</script>";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["uploadImageFile"]["tmp_name"], $target_file)) {
            echo "<script>alert('The file " . htmlspecialchars(basename($_FILES["uploadImageFile"]["name"])) . " has been uploaded.');</script>";
            copy($target_file, $target_file_thumb);
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
    }
}
echo '<script>window.location.href="http://localhost/chatmap/chatadmin/background.php";</script>';
?>