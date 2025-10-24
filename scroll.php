<?php

$catID = getCatID($slug);
$catDetails = catDetails($catID);

$images = getImages($catID, $catDetails['sorter']);
$firstImage = getFirstImage($catID, $catDetails['sorter']);

$metaTitle = getOption("company") . " | " . ucwords(str_replace("-", " ", $slug));

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

<body class="no_divider">

    <?php include('includes/header.php'); ?>

    <main>

        <section id="content">

            <article class="inner gridpage">

                <h1 class="pageTitle"><?= $catDetails['name'] ?></h1>

                <div class="vert_scroll">

                    <?php $itemCount = 1; ?>
                    <?php for ($count = 1; $item = mysqli_fetch_array($images); ++$count) { ?>

                        <?php

                        $ext = pathinfo(getOption("physicalPath") . "images/pics/" . $item['img'], PATHINFO_EXTENSION);
                        $itemRatio = $item['height'] / $item['width'];
                        $itemPad = $itemRatio * 100;

                        $itemVideo = getImageVideo($item['id']);

                        $cellClass = "";

                        if ($itemCount % 2 == 0) {
                            $cellClass .= " right";
                        }

                        $hasAutoVideo = false;
                        if ($itemVideo['hoverFile'] != "") {
                            $hasAutoVideo = true;
                            $cellClass .= " autovideo";
                        }

                        if ($item['catSize'] === "large") {
                            $cellClass .= " large";
                            $itemCount = 0;
                        }

                        $hasTitle = false;
                        $hasCaption = false;

                        if ($item['title'] != "title:" and $item['title'] != "") {
                            $hasTitle = true;
                        }

                        if ($item['caption'] != "caption:" and $item['caption'] != "") {
                            $hasCaption = true;
                        }

                        ?>

                        <figure class="cell <?= $cellClass ?>" <?php if ($hasAutoVideo) { ?>data-autovideo='<video muted playsinline loop><source src="/videos/<?= $itemVideo['hoverFile'] ?>" /></video>' <?php } ?>>
                            <div class="mediawrap" style="padding-top: <?= $itemPad ?>%;">
                                <img src="<?= $loaderImg ?>" data-img="<?= $item['img'] ?>" class="photo loadmeview" alt="<?= $company ?>" />
                                <?php if ($hasAutoVideo) { ?>
                                    <div class="vidhold"></div>
                                <?php } ?>
                                <?php if ($hasTitle or $hasCaption) { ?>
                                    <figcaption class="info">
                                        <?php if ($hasCaption) { ?>
                                            <h3><?= $item['caption'] ?></h3>
                                        <?php } ?>
                                        <?php if ($hasTitle) { ?>
                                            <h2><?= $item['title'] ?></h2>
                                        <?php } ?>
                                    </figcaption>
                                <?php } ?>
                            </div>
                        </figure>

                        <?php
                        ++$itemCount;
                        if ($itemCount == 3) {
                            $itemCount == 1;
                        }
                        ?>

                    <?php } ?>

                </div>

            </article>

        </section>

    </main>

    <?php include('includes/footer.php'); ?>
    <?php include('includes/overlays.php'); ?>
    <?php include('includes/scripts.php'); ?>

</body>

</html>