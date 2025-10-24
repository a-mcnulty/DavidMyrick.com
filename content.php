<?php

session_start();
include('includes/connect.php');
include('includes/functions.php');

$page = $_GET['page'];
$slug = str_replace("/", "", $page);

$catID = getCatID($slug);
$catDetails = catDetails($catID);
$parentID = $catDetails['parentID'];

$pageName = $catDetails['name'];

if ($catDetails['metaTitle'] != "") {
    $metaTitle = $catDetails['metaTitle'];
} else {
    $metaTitle = getOption("company") . " | " . ucwords(str_replace("-", " ", $slug));
}

if ($catDetails['pageType'] === "grid - masonry") {

    $pagerID = $catID;

    $parentID = $catDetails['parentID'];
    if ($parentID > 0) {
        $parent = catDetails($parentID);
        $pageSubs = subcatList($parentID);
        $pagerID = $parentID;
    }

    $images = getImages($catID, $catDetails['sorter']);
    $firstImage = getFirstImage($catID, $catDetails['sorter']);

    $metaTitle = getOption("company") . " | " . ucwords(str_replace("-", " ", $slug));

    // next / prev category

    $catArray = array();
    $allCats = catList();

    for ($count = 1; $row = mysqli_fetch_array($allCats); ++$count) {
        array_push($catArray, $row['id']);
    }

    $currentIndex = array_search($pagerID, $catArray);
    $nextIndex = $currentIndex + 1;
    $prevIndex = $currentIndex - 1;

    if ($nextIndex >= 0) {
        $nextCat = catDetails($catArray[$nextIndex]);
    }
    if ($prevIndex >= 0) {
        $prevCat = catDetails($catArray[$prevIndex]);
    }
    include('ajax/grid-masonry.php');
} elseif ($catDetails['pageType'] === "grid - justified") {

    $pagerID = $catID;
    $parentID = $catDetails['parentID'];
    if ($parentID > 0) {
        $parent = catDetails($parentID);
        $pageSubs = subcatList($parentID);
        $pageName = $parent['name'];
        $pagerID = $parentID;
    }

    $images = getImages($catID, $catDetails['sorter']);
    $firstImage = getFirstImage($catID, $catDetails['sorter']);

    $metaTitle = getOption("company") . " | " . ucwords(str_replace("-", " ", $slug));

    // next / prev category

    $catArray = array();
    $allCats = catList();

    for ($count = 1; $row = mysqli_fetch_array($allCats); ++$count) {
        array_push($catArray, $row['id']);
    }

    $currentIndex = array_search($pagerID, $catArray);
    $nextIndex = $currentIndex + 1;
    $prevIndex = $currentIndex - 1;

    if ($nextIndex >= 0) {
        $nextCat = catDetails($catArray[$nextIndex]);
    }
    if ($prevIndex >= 0) {
        $prevCat = catDetails($catArray[$prevIndex]);
    }

    include('ajax/grid-justified.php');
} elseif ($catDetails['pageType'] === "text") {

    $pageText = getPageTextID($catID);

    $bgId = getFirstSubID($catID, "preview");
    $bg = getFirstImage($bgId);
    $hasBgVideo = false;
    $bgVideo = getImageVideo($bg['id']);
    if ($bgVideo['hoverFile'] != "") {
        $hasBgVideo = true;
    }

    include("ajax/textpage.php");
}
