<article class="inner textpage ajaxload" data-bodyclass="no_footer fullbleed light_header menuOn" data-metatitle="<?= $metaTitle ?>">

    <div class="divider_line"></div>

    <h1 class="pageTitle"><?= $catDetails['name'] ?></h1>

    <div class="text-block">
        <div class="inner">
            <?= $pageText['content'] ?>
        </div>
    </div>

    <div class="text-block bottom">
        <div class="inner">
            <?= $pageText['content2'] ?>
        </div>
    </div>

    <div class="page_bg">
        <?php if ($hasBgVideo) { ?>
            <video autoplay muted playsinline loop>
                <source src="/videos/<?= $bgVideo['hoverFile'] ?>" />
            </video>
        <?php } else { ?>
            <img class="photo loadmeview" src="<?= $loaderImg ?>" data-img="<?= $bg['img'] ?>" alt="<?= $catDetails['name'] ?> Background Image" />
        <?php } ?>
    </div>

</article>