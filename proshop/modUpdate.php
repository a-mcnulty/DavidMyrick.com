<?php

session_start();
include 'includes/secure.php';
include '../includes/connect.php';
include 'includes/functions.php';

$sql = "SELECT id, moduleid, rowBreak, sortBy FROM pics WHERE moduleid > 0";

$result = mysqli_query(Database::$conn, $sql);

if (!$result) {
    die('Could not query:' . mysqli_error(Database::$conn));
    exit;
}

for ($count = 1; $row = mysqli_fetch_array($result); ++$count) {

    $picID = $row['id'];
    $modID = $row['moduleid'];
    $rowBreak = $row['rowBreak'];
    $sortBy = $row['sortBy'];

    if (!$sortBy) {
        $sortBy = 0;
    }

    $sql2 = "INSERT INTO module_pics (picid, modid, rowBreak, sortBy) VALUES ('$picID', '$modID', '$rowBreak', '$sortBy')";

    $result2 = mysqli_query(Database::$conn, $sql2);

    if (!$result2) {
        die('Could not query:' . mysqli_error(Database::$conn));
        exit;
    }
}

echo "done";
