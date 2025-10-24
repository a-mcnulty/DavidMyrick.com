<article class="inner gridpage ajaxload" data-bodyclass="light_header menuOn" data-metatitle="<?= $metaTitle ?>">

    <div class="divider_line"></div>

    <h1 class="pageTitle"><?= $parent['name'] ?></h1>

    <div id="itemTitle"></div>

    <?php if ($parentID > 0) { ?>

        <div class="pageNav">
            <ul>
                <?php for ($count = 1; $pageSub = mysqli_fetch_array($pageSubs); ++$count) { ?>

                    <?php

                    if ($pageSub['pageType'] === "link") {
                        $theLink = $pageSub['link'];
                        $theTarget = $pageSub['target'];
                    } else {
                        $theLink = "/" . $pageSub['slug'];
                        $theTarget = "_parent";
                    }

                    ?>
                    <li>
                        <a href="<?= $theLink ?>" target="<?= $theTarget ?>" class="<?php if ($catID == $pageSub['id'] or $parentID == $pageSub['id']) { ?>pageOn<?php } ?>">
                            <?= $pageSub['subName'] ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>

    <?php } ?>

    <div class="masongrid">

        <div class="grid-sizer"></div>
        <div class="gutter-sizer"></div>

        <?php $itemCount = 1; ?>
        <?php for ($count = 1; $item = mysqli_fetch_array($images); ++$count) { ?>

            <?php

            $ext = pathinfo(getOption("physicalPath") . "images/pics/" . $item['img'], PATHINFO_EXTENSION);
            $itemRatio = $item['height'] / $item['width'];
            $itemPad = $itemRatio * 100;

            $itemVideo = getImageVideo($item['id']);

            $cellClass = "";

            $hasAutoVideo = false;
            $videoStr = "";
            if ($itemVideo['hoverFile'] != "" or $itemVideo['url_loop_desktop'] != "") {
                $hasAutoVideo = true;
                if ($itemVideo['bgVideoType'] === "view") {
                    $cellClass .= " autovideo";
                } else {
                    $cellClass .= " hovervideo";
                }
                if ($itemVideo['url_loop_desktop'] != "") {
                    $videoStr = '<video class="sizeload" playsinline loop muted data-hd="' . $itemVideo['url_loop_desktop'] . '" data-sd="' . $itemVideo['url_loop_mobile'] . '"><source src="" /></video>';
                } else {
                    $videoStr = '<video muted playsinline loop><source src="/videos/' . $itemVideo['hoverFile'] . '" /></video>';
                    if ($itemVideo['hoverFileMobile'] != "") {
                        $videoStr = '<video class="sizeload" playsinline loop muted data-hd="/videos/' . $itemVideo['hoverFile'] . '" data-sd="/videos/' . $itemVideo['hoverFileMobile'] . '"><source src="" /></video>';
                    }
                }
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

            <figure class="cell openOverlay hoverTitle <?= $cellClass ?>" <?php if ($hasAutoVideo) { ?>data-autovideo='<?= $videoStr ?>' <?php } ?> data-id="<?= $item['id'] ?>">
                <div class="mediawrap" style="padding-top: <?= $itemPad ?>%;">
                    <img src="<?= $loaderImg ?>" data-img="<?= $item['img'] ?>" class="photo loadmeview" alt="<?= $company ?>" />
                    <?php if ($hasAutoVideo) { ?>
                        <div class="vidhold"></div>
                    <?php } ?>
                </div>

                <figcaption class="info">
                    <?php if ($hasTitle) { ?>
                        <h2><?= $item['title'] ?></h2>
                    <?php } ?>
                    <?php if ($hasCaption) { ?>
                        <h3><?= $item['caption'] ?></h3>
                    <?php } ?>
                </figcaption>

            </figure>

            <?php
            ++$itemCount;
            if ($itemCount == 3) {
                $itemCount == 1;
            }
            ?>

        <?php } ?>

    </div>

    <div class="pager">
        <ul>
            <?php if ($prevCat) { ?>
                <?php
                $theLink = "/" . $prevCat['slug'];
                $catSubs = subcatList($prevCat['id']);
                if (mysqli_num_rows($catSubs) > 0) {
                    $firstSubId = getFirstSubID($prevCat['id']);
                    $firstSub = catDetails($firstSubId);
                    if ($firstSub['pageType'] === "link") {
                        $theLink = $firstSub['link'];
                        $theTarget = $firstSub['target'];
                    } else {
                        $theLink = "/" . $firstSub['slug'];
                        $theTarget = "_parent";
                    }
                }
                ?>
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="32.829" viewBox="0 0 32.414 32.829">
                        <g id="Group_4" data-name="Group 4" transform="translate(2245.914 -360.086) rotate(90)">
                            <line id="Line_12" data-name="Line 12" y2="30" transform="translate(376.5 2214.5)" fill="none" stroke="#000" stroke-linecap="round" stroke-width="2" />
                            <line id="Line_13" data-name="Line 13" x1="15" y2="15" transform="translate(376.5 2229.5)" fill="none" stroke="#000" stroke-linecap="round" stroke-width="2" />
                            <line id="Line_14" data-name="Line 14" x2="15" y2="15" transform="translate(361.5 2229.5)" fill="none" stroke="#000" stroke-linecap="round" stroke-width="2" />
                        </g>
                    </svg>
                    <a href="<?= $theLink ?>"><?= $prevCat['name'] ?></a>
                </li>
            <?php } else { ?>
                <li></li>
            <?php } ?>
            <?php if ($nextCat) { ?>
                <?php
                $theLink = "/" . $nextCat['slug'];
                $catSubs = subcatList($nextCat['id']);
                if (mysqli_num_rows($catSubs) > 0) {
                    $firstSubId = getFirstSubID($nextCat['id']);
                    $firstSub = catDetails($firstSubId);
                    if ($firstSub['pageType'] === "link") {
                        $theLink = $firstSub['link'];
                        $theTarget = $firstSub['target'];
                    } else {
                        $theLink = "/" . $firstSub['slug'];
                        $theTarget = "_parent";
                    }
                }
                ?>
                <li>
                    <a href="<?= $theLink ?>"><?= $nextCat['name'] ?></a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="32.829" viewBox="0 0 32.414 32.829">
                        <g id="Group_5" data-name="Group 5" transform="translate(-2213.5 392.914) rotate(-90)">
                            <line id="Line_12" data-name="Line 12" y2="30" transform="translate(376.5 2214.5)" fill="none" stroke="#000" stroke-linecap="round" stroke-width="2" />
                            <line id="Line_13" data-name="Line 13" x1="15" y2="15" transform="translate(376.5 2229.5)" fill="none" stroke="#000" stroke-linecap="round" stroke-width="2" />
                            <line id="Line_14" data-name="Line 14" x2="15" y2="15" transform="translate(361.5 2229.5)" fill="none" stroke="#000" stroke-linecap="round" stroke-width="2" />
                        </g>
                    </svg>

                </li>
            <?php } else { ?>
                <li></li>
            <?php } ?>
        </ul>
    </div>
</article>