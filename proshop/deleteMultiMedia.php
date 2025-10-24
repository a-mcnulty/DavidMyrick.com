<?php

    session_start();
    include 'includes/secure.php';
    include '../includes/connect.php';
    include 'includes/functions.php';

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];
		$data_array = json_decode($data_json, true);

		if($data_array !== null){
            $itemArray = $data_array["itemArray"];
        }
    }

    foreach($itemArray as $itemID) {

        $sql = "DELETE FROM media WHERE id = '$itemID'";

        $result = mysqli_query(Database::$conn, $sql);

    }

?>