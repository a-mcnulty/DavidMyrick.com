<?php

session_start();
include("includes/connect.php");
include("includes/functions.php");

date_default_timezone_set('America/Los_Angeles');

if (!isset($_SERVER['QUERY_STRING'])) {
	$_SERVER['QUERY_STRING'] = '';
}

if (strpos($_SERVER['QUERY_STRING'], '/index.html') === 0) {
	if (strpos($_SERVER['PATH_INFO'], '/index.html') !== 0) {
		$_SERVER['QUERY_STRING'] = '/index.html' . $_SERVER['QUERY_STRING'];
	}
}

$slug = $_SERVER['QUERY_STRING']; //get what was sent

if (strpos($slug, '../') === 0) {
	exit('Big Security Threat!');
} //dont allow higher level directories!!

// CHECK FOR ERROR MESSAGE IN URL
$fullURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$parts = parse_url($fullURL);
if (isset($parts['query'])) {
	parse_str($parts['query'], $query);
	$errorMessage = $query['errorMessage'];
	$video = $query['video'];
}

$projectCheck = stristr($slug, "/project/");
$projectSlug = str_replace("/project/", "", $slug);

$pitchCheck = stristr($slug, "/pitch/");
$pitchSlug = str_replace("/pitch/", "", $slug);

$slideshowCheck = stristr($slug, "/slideshow/");
$slideshowSlug = str_replace("/slideshow/", "", $slug);

$slug = str_replace("/", "", $slug);
$dataSlug = str_replace("-and-", "-/-", $slug);

$sql = "SELECT * FROM cat_list WHERE slug = '$slug'";

$result = mysqli_query(Database::$conn, $sql);
$row = mysqli_fetch_array($result);

if ($slug === "") {

	header("Location: /");
	exit;
} else {

	if ($projectCheck) {

		include('project.php');
	} elseif ($slideshowCheck) {

		include('slideshow.php');
	} elseif ($pitchCheck) {

		include('pitch.php');
	} elseif ($row['pageType'] === "grid - masonry") {

		include('gridpage-masonry.php');
	} elseif ($row['pageType'] === "grid - justified") {

		include('gridpage-justified.php');
	} elseif ($row['pageType'] === "scroll") {

		include('scroll.php');
	} elseif ($row['pageType'] === "text") {

		include('textpage.php');
	} elseif ($row['pageType'] === "modules") {

		include('modulespage.php');
	} else {

		include('404.php');
	}
}
