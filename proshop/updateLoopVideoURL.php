<?php

session_start();
include 'includes/secure.php';
include '../includes/connect.php';
include 'includes/functions.php';

$picID = mysqli_real_escape_string(Database::$conn, $_POST["picID"]);
$catID = mysqli_real_escape_string(Database::$conn, $_POST["catID"]);
$url_loop_desktop = mysqli_real_escape_string(Database::$conn, $_POST["url_loop_desktop"]);
$url_loop_mobile = mysqli_real_escape_string(Database::$conn, $_POST["url_loop_mobile"]);
$bgVideoType = mysqli_real_escape_string(Database::$conn, $_POST['bgVideoType']);

$sql1 = "SELECT id FROM videos WHERE picid = '$picID'";

$result1 = mysqli_query(Database::$conn, $sql1);
$num = mysqli_num_rows($result1);

if ($num == 0) {

    $sql2 = "INSERT INTO videos (picid, url_loop_desktop, url_loop_mobile, bgVideoType) VALUES ('$picID', '$url_loop_desktop', '$url_loop_mobile', '$bgVideoType')";
} else {

    $sql2 = "UPDATE videos SET url_loop_desktop = '$url_loop_desktop', url_loop_mobile = '$url_loop_mobile', bgVideoType = '$bgVideoType' WHERE picid = '$picID'";
}

$result2 = mysqli_query(Database::$conn, $sql2);

if (!$result2) {
    die('Could not query:' . mysqli_error(Database::$conn));
    exit;
}

header("Location: detail.php?picID=" . $picID . "&category=" . $catID . "&status=video_links_have_been_updated");
