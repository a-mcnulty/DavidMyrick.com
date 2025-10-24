<?php

    session_start();
    include 'includes/secure.php';
    include '../includes/connect.php';
    include 'includes/functions.php';
  
    $picID = mysqli_real_escape_string(Database::$conn, $_POST['picID']);
    $catID = mysqli_real_escape_string(Database::$conn, $_POST['catID']);
    $fileurl = mysqli_real_escape_string(Database::$conn, $_POST['fileurl']);
    $filewidth = mysqli_real_escape_string(Database::$conn, $_POST['filewidth']);
    $fileheight = mysqli_real_escape_string(Database::$conn, $_POST['fileheight']);

    $sql1 = "SELECT id FROM videos WHERE picid = '$picID'";

    $result1 = mysqli_query(Database::$conn, $sql1);

	if (!$result1) {
			die('Could not query:' . mysqli_error(Database::$conn));
			exit;
    }

    $num = mysqli_num_rows($result1);

    if ($num == 0) {
        
        $sql2 = "INSERT INTO videos (file, width, height, picid) VALUES ('$fileurl','$filewidth','$fileheight','$picID')";

    } else {

        $sql2 = "UPDATE videos SET file = '$fileurl', width = '$filewidth', height = '$fileheight' WHERE picid = '$picID'";

    }

    $result2 = mysqli_query(Database::$conn, $sql2);

	if (!$result2) {
			die('Could not query:' . mysqli_error(Database::$conn));
			exit;
    }
    
    header("Location: detail.php?picID=" . $picID . "&category=" . $catID . "&status=video_has_been_updated");

?>