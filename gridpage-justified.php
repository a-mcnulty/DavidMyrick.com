<?php

$catID = getCatID($slug);
$catDetails = catDetails($catID);
$pageName = $catDetails['name'];

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

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <?php if ($catDetails['metaTitle'] != "") { ?>
        <title><?= $catDetails['metaTitle'] ?></title>
        <meta property="og:title" name="og:title" content="<?= $catDetails['metaTitle'] ?>" />
    <?php } else { ?>
        <title><?= $metaTitle ?></title>
        <meta property="og:title" name="og:title" content="<?= $metaTitle ?>" />
    <?php } ?>
    <meta name="description" content="<?= $catDetails['metaDesc'] ?>" />
    <meta name="keywords" content="<?= $catDetails['metaKeywords'] ?>" />

    <meta property="og:description" name="og:description" content="<?= $catDetails['metaDesc'] ?>" />
    <meta property="og:image" name="og:image" content="<?= getOption("url") ?><?= getOption("imagePathFront") ?><?= $firstImage['img'] ?>" />
    <meta property="og:image:width" content="<?= $firstImage['width'] ?>" />
    <meta property="og:image:height" content="<?= $firstImage['height'] ?>" />
    <meta property="og:url" name="og:url" content="<?= getOption("url") ?><?= $slug ?>" />
    <meta property="og:site_name" name="og:site_name" content="<?= getOption("company") ?>" />

    <?php include('includes/topScripts.php'); ?>

</head>

<body class="no_divider light_nav">

    <?php include('includes/header.php'); ?>

    <main>

        <section id="content">

            <?php include("ajax/grid-justified.php"); ?>

        </section>

    </main>

    <?php include('includes/footer.php'); ?>
    <?php include('includes/overlays.php'); ?>
    <?php include('includes/scripts.php'); ?>

</body>

</html>