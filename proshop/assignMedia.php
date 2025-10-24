<?php

session_start();
include 'includes/secure.php';
include '../includes/connect.php';
include 'includes/functions.php';

if (isset($_POST["myContent"])) {
    $data_json = $_POST["myContent"];

    $data_array = json_decode($data_json, true);

    if ($data_array !== null) {
        $type = $data_array["type"];
        $id = $data_array["id"];
        $field = $data_array["field"];
        $itemArray = $data_array["itemArray"];
    }

    foreach ($itemArray as $itemID) {

        if ($type === "category") {

            // first make sure item doesn't already live in category
            $sql1 = "SELECT id FROM cat_pics WHERE picid = '$itemID' AND catid = '$id'";

            $result1 = mysqli_query(Database::$conn, $sql1);

            if (!$result1) {
                die('Could not query 1:' . mysqli_error(Database::$conn));
                exit;
            }

            $num = mysqli_num_rows($result1);

            if ($num == 0) {

                // add new content

                $sql2 = "INSERT INTO cat_pics (picid, catid, sortBy) VALUES ('$itemID', '$id', 0)";

                $result2 = mysqli_query(Database::$conn, $sql2);

                if (!$result2) {
                    die('Could not query 2:' . mysqli_error(Database::$conn));
                    exit;
                }
            }
        } else if ($type === "detail") {

            // just swap the main or poster image with new filename
            $newItem = getMainImage($itemID);
            $filename = $newItem['img'];

            $sql1 = "UPDATE pics SET $field = '$filename' WHERE id = '$id'";

            $result1 = mysqli_query(Database::$conn, $sql1);

            if (!$result1) {
                die('Could not query 1:' . mysqli_error(Database::$conn));
                exit;
            }
        } else {

            // first make sure item doesn't already live in module
            $sql1 = "SELECT id FROM module_pics WHERE picid = '$itemID' AND modid = '$id'";

            $result1 = mysqli_query(Database::$conn, $sql1);

            if (!$result1) {
                die('Could not query 1:' . mysqli_error(Database::$conn));
                exit;
            }

            $num = mysqli_num_rows($result1);

            if ($num == 0) {

                // add new content

                $sql2 = "INSERT INTO module_pics (picid, modid, sortBy) VALUES ('$itemID', '$id', 0)";

                $result2 = mysqli_query(Database::$conn, $sql2);

                if (!$result2) {
                    die('Could not query 2:' . mysqli_error(Database::$conn));
                    exit;
                }
            }
        }
    }
}
