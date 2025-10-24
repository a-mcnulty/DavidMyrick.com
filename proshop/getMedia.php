<?php

session_start();
include 'includes/secure.php';
include '../includes/connect.php';
include 'includes/functions.php';

// vars below define if items go to category or module
$type = $_GET['type'];
$id = $_GET['id'];
$src = $_GET['src'];
$field = $_GET['field'];

// setup pagination
$limit = 100;
$start = 0;
$page = 1;
$keyword = "";

if (isset($_GET['page'])) {
    $page = mysqli_real_escape_string(Database::$conn, $_GET['page']);
    $start = ($page - 1) * $limit;
}

$nextPage = $page + 1;
$prevPage = $page - 1;

$countMedia = countMedia();
$totalPages = ceil($countMedia / $limit);

if (isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string(Database::$conn, $_GET['keyword']);
    $items = searchMedia($keyword);
} else {
    $items = getMedia($start, $limit);
}

?>

<div class="manageimages media_library">

    <div class="heading">
        <div class="leftside">
            <div class="titles">
                <p><span class="head uploaderLink">MEDIA LIBRARY</span> (<?= $countMedia ?> Items)</p>
            </div>
            <div class="pager">

                <p>
                    Page
                    <?php for ($i = 1; $i <= $totalPages; ++$i) { ?>
                        <a href="getMedia.php?page=<?= $i ?>" class="modal <?php if ($page == $i) { ?>disabled<?php } ?>"><?= $i ?></a>
                    <?php } ?>
                </p>

            </div>
        </div>
        <div class="rightside">
            <form action="manage_media.php" method="get" name="search" class="modal">
                <input type="hidden" name="type" id="type" value="<?= $type ?>" />
                <input type="hidden" name="id" id="id" value="<?= $id ?>" />
                <input type="hidden" name="src" id="src" value="<?= $src ?>" />
                <input type="hidden" name="field" id="field" value="<?= $field ?>" />
                <input type="text" name="keyword" value="<?= $keyword ?>" class="media_search" placeholder="Search by Filename / Title" />
                <input type="submit" value="SEARCH" id="btn" /><br />
                <a href="getMedia.php?type=<?= $type ?>&id=<?= $id ?>&src=<?= $src ?>&field=<?= $field ?>" class="modal">X CLEAR</a>
            </form>
        </div>

    </div>

    <div id="line" style="width: 99%; margin-bottom: 2px;"></div>
    <div id="line" style="width: 99%;"></div><br />

    <form name="selectMedia" class="assign" method="get" action="assignMedia.php">
        <input type="hidden" name="type" id="type" value="<?= $type ?>" />
        <input type="hidden" name="id" id="id" value="<?= $id ?>" />
        <input type="hidden" name="src" id="src" value="<?= $src ?>" />
        <input type="hidden" name="field" id="field" value="<?= $field ?>" />
        <input type="submit" value="SAVE ITEMS" id="btn" /><br /><br />

        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left" valign="top">

                    <div id="managePics">

                        <ul class="sortme">

                            <?php

                            for ($count = 1; $pic = mysqli_fetch_array($items); ++$count) {

                                $ext = pathinfo(getOption("physicalPath") . "/images/pics/" . $pic['img'], PATHINFO_EXTENSION);

                                if (file_exists(getOption("physicalPath") . "/images/pics/500/" . $pic['img'])) {
                                    $theImg = getOption("imagePath") . "500/" . $pic['img'];
                                } else {
                                    $theImg = getOption("imagePath") . $pic['img'];
                                }

                                if ($ext === "gif") {
                                    $theImg = getOption("imagePath") . $pic['img'];
                                }

                            ?>

                                <li class="picHolder media" id="recordsArray_<?php echo $pic['id']; ?>">
                                    <a href="detail.php?picID=<?= $pic['id'] ?>&fromMedia=yes">
                                        <?php if ($pic['img'] != "no-image.jpg") { ?>

                                            <img src="<?= $theImg ?>" border="0"><br />

                                        <?php } else { ?>
                                            <div style="width: 150px; height: 150px; position: absolute; top: 0px; left: 0px; text-align: center; border: 1px solid #ccc;">
                                                <div style="padding-top: 44%;"><?= $pic['title'] ?></div>
                                            </div>
                                        <?php } ?>
                                    </a>

                                    <div class="imagetools" style="bottom: -40px; margin-bottom: 20px; display: flex; flex-wrap: wrap; align-items: center; justify-content: flex-start; color: #627dad;">
                                        <input type="checkbox" name="additem" class="additem" data-id="<?= $pic['id'] ?>" style="margin-bottom: 0; border: 1px solid #627dad;">
                                        <span style="padding-left: 5px;">Select</span>
                                    </div>

                                </li>

                            <?php

                            }

                            ?>

                        </ul>

                    </div>

                </td>
            </tr>
        </table>

    </form>

</div>