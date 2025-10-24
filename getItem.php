<?php

session_start();
include('includes/connect.php');
include('includes/functions.php');

$itemID = $_GET['id'];
$item = getMainImage($itemID);
$itemText = getImageText($itemID);
$itemVideo = getImageVideo($itemID);

?>

<div class="wrap">
    <div class="title">
        <?php if ($item['title'] != "title:") { ?>
            <h2><?= $item['title'] ?></h2>
        <?php } ?>
        <div class="summary">
            <?= strip_tags(str_replace("<br>", "&nbsp;", $itemText['content'])) ?>
        </div>
    </div>
    <?php if ($itemVideo['embed'] != "") { ?>
        <?= $itemVideo['embed'] ?>
    <?php } elseif ($itemVideo['file'] != "") { ?>
        <video playsinline controls>
            <source src="/videos/<?= $itemVideo['file'] ?>" />
        </video>
    <?php } elseif ($itemVideo['url_desktop'] != "") { ?>
        <div class="videoplayer" data-id="<?= $itemID ?>">
            <video class="sizeload" id="fullvideo" data-hd="<?= $itemVideo['url_desktop'] ?>" data-sd="<?= $itemVideo['url_mobile'] ?>" playsinline x-webkit-airplay="allow">
                <source src="" />
            </video>
            <svg class="playIcon" xmlns="http://www.w3.org/2000/svg" width="80.44" height="80.44" viewBox="0 0 80.44 80.44">
                <path d="M42.72,3A39.72,39.72,0,1,0,82.44,42.72,39.735,39.735,0,0,0,42.72,3ZM34.776,60.594V24.846L58.608,42.72Z" transform="translate(-2.5 -2.5)" fill="#fff" stroke="#000" stroke-width="1" />
            </svg>
            <div class="controls hide">
                <div class="scrubber">
                    <input type="range" id="seek-bar" value="0">
                    <div class="bg"></div>
                </div>
                <div class="bottom">
                    <div class="leftcontrols">
                        <div class="playpause" id="playpause">
                            <svg class="play" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 8.21 12.45">
                                <g id="Layer_2" data-name="Layer 2">
                                    <g id="Layer_1-2" data-name="Layer 1">
                                        <path class="cls-1" d="M.14.41V12c0,.24.36.36.58.19L8,6.47a.23.23,0,0,0,0-.38L.72.22C.51.05.14.17.14.41Z" />
                                    </g>
                                </g>
                            </svg>
                            <svg class="pause" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7.18 11.74">
                                <g id="Layer_2" data-name="Layer 2">
                                    <g id="Layer_1-2" data-name="Layer 1">
                                        <line class="cls-1" x1="1" y1="1" x2="1" y2="10.74" />
                                        <line class="cls-1" x1="6.18" y1="1" x2="6.18" y2="10.74" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <div class="timestamp">
                            <span class="progress">0:00</span>&nbsp;|&nbsp;
                            <span class="duration">0:00</span>
                        </div>
                    </div>
                    <div class="rightcontrols">
                        <div class="volume">
                            <svg id="mute" class="mute" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10.15 13.11">
                                <g id="Layer_2" data-name="Layer 2">
                                    <g id="Layer_1-2" data-name="Layer 1">
                                        <rect class="cls-1" y="3.45" width="8.72" height="6.22" rx="0.74" />
                                        <path class="cls-1" d="M10.15,12.72V.39A.4.4,0,0,0,9.46.12L3,6.36a.38.38,0,0,0,0,.56L9.47,13A.4.4,0,0,0,10.15,12.72Z" />
                                    </g>
                                </g>
                            </svg>
                            <div class="volumewrap">
                                <input type="range" id="volume-bar" min="0" max="1" step="0.1" value="1">
                                <div class="bg"></div>
                            </div>
                        </div>
                        <div class="fullscreen">
                            <svg class="gofull" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11.64 11.63">
                                <g id="Layer_2" data-name="Layer 2">
                                    <g id="Layer_1-2" data-name="Layer 1">
                                        <line class="cls-1" x1="10.87" y1="10.87" x2="6.59" y2="6.59" />
                                        <polygon class="cls-2" points="11.42 5.63 10.42 5.67 10.6 10.6 5.67 10.42 5.64 11.42 11.64 11.63 11.42 5.63" />
                                        <line class="cls-1" x1="0.77" y1="0.77" x2="5.05" y2="5.05" />
                                        <polygon class="cls-2" points="0.22 6 1.22 5.97 1.04 1.04 5.97 1.22 6 0.22 0 0 0.22 6" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <img src="<?= getOption("imagePathFront") ?>1920/<?= $item['img'] ?>" alt="" />
    <?php } ?>
</div>