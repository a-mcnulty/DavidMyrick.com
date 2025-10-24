<?php

session_start();
include 'includes/secure.php';
include '../includes/connect.php';
include 'includes/functions.php';

$modId = $_GET['modId'];
$picID = $_GET['picId'];
$catID = $_GET['catId'];
$modType = $_GET['modType'];
$module = getModule($modId);
$images = getModuleImages($modId, $module['sorter']);

?>

<?php

for ($count = 1; $pic = mysqli_fetch_array($images); ++$count) {

    $ext = pathinfo(getOption("physicalPath") . "/images/pics/" . $pic['img'], PATHINFO_EXTENSION);

    if (file_exists(getOption("physicalPath") . "/images/pics/500/" . $pic['img'])) {
        $theImg = getOption("imagePath") . "500/" . $pic['img'];
    } else {
        $theImg = getOption("imagePath") . $pic['img'];
    }

    if ($ext === "gif") {
        $theImg = getOption("imagePath") . $pic['img'];
    }

    if ($pic['imgSrc'] != "upload") {
        $theImg = $pic['img'];
    }

?>

    <li class="picHolder" id="recordsArray_<?php echo $pic['id']; ?>">
        <img src="<?= $theImg ?>" border="0"><br />
        <input type="hidden" class="picID" name="picID[]" value="<?= $pic['id'] ?>">
        <input type="hidden" class="img" name="img[]" value="<?= $pic['img'] ?>">

        <div class="picCover" style="display: none;"></div>

        <div class="picInfo" style="display: none;">

            <?php if (getOption("title") === "yes") { ?>
                <input type="text" name="title[]" class="titleField" <?php if ($pic['title'] != "") { ?>value="<?= $pic['title'] ?>" <?php } else { ?>value="title:" <?php } ?> onfocus="if (this.value == 'title:') this.value='';" onblur="if (this.value == '') this.value='title:';"><br />
            <?php } ?>

            <?php if (getOption("caption") === "yes") { ?>
                <input type="text" name="captions[]" class="captions" <?php if ($pic['caption'] != "") { ?>value="<?= $pic['caption'] ?>" <?php } else { ?>value="caption:" <?php } ?> onfocus="if (this.value == 'caption:') this.value='';" onblur="if (this.value == '') this.value='caption:';"><br />
            <?php } ?>

            <?php if ($module['pageType'] === "grid - justified") { ?>

                <input type="checkbox" name="rowBreak[]" class="rowBreak" value="no" style="width: 20px;" <?php if ($pic['modRowBreak'] === "yes") { ?>checked<?php } ?>> <span style="font-size: 10px; color: #fff; position: relative; top: -3px;">end row</span><br />

            <?php } ?>

            <?php if ($module['pageType'] === "grid - loose" or $module['pageType'] === "grid") { ?>

                <span class="smallGray">image size:</span>
                <select name="imgSize[]" class="imgSize" style="font-size: 10px; width: 122px;">
                    <option value="" selected>--select--</option>

                    <option value="large" <?php if ($pic['imgSize'] === "large") { ?>selected<?php } ?>>large</option>

                </select><br />

            <?php } ?>



            <?php /*

                <input type="checkbox" name="rowBreak[]" class="rowBreak" value="no" style="width: 20px;" <?php if ($pic['rowBreak'] === "yes") { ?>checked<?php } ?>> <span style="font-size: 10px; color: #fff; position: relative; top: -3px;">end row</span><br />
                
                <?php if (getOption("title") === "yes") { ?>
                    <input type="text" name="title[]" class="titleField" <?php if ($pic['title'] != "") { ?>value="<?=$pic['title']?>"<?php } else { ?>value="title:"<?php } ?> onfocus="if (this.value == 'title:') this.value='';" onblur="if (this.value == '') this.value='title:';"><br />
                <?php } ?>

                <?php if (getOption("caption") === "yes") { ?>
                    <input type="text" name="captions[]" class="captions" <?php if ($pic['caption'] != "") { ?>value="<?=$pic['caption']?>"<?php } else { ?>value="caption:"<?php } ?> onfocus="if (this.value == 'caption:') this.value='';" onblur="if (this.value == '') this.value='caption:';"><br />
                <?php } ?>

                <?php if (getOption("picURL") === "yes") { ?>
                    <input type="text" name="picURL[]" class="picURL" <?php if ($pic['picURL'] != "") { ?>value="<?=$pic['picURL']?>"<?php } else { ?>value="url:"<?php } ?> onfocus="if (this.value == 'url:') this.value='';" onblur="if (this.value == '') this.value='url:';"><br />
                <?php } ?>

                <?php if (getOption("shortDesc") === "yes") { ?>
                    <textarea name="shortDesc[]" class="shortDesc mceNoEditor" style="width: 170px; height: 60px; font-size: 11px; font-family: Helvetica, Arial;"><?php if ($pic['shortDesc'] != "") {?><?=$pic['shortDesc']?><?php } else { ?>short description:<?php } ?></textarea><br />
                <?php } ?>
                
                <input type="checkbox" name="hover[]" class="hover" value="yes" style="width: 20px;" <?php if ($pic['hover'] === "yes") { ?>checked<?php } ?>> <span style="font-size: 10px; color: #fff; position: relative; top: -3px;">hover image</span></br />
                
                */ ?>

        </div>
        <div class="imagetools"> <a href="detail.php?picID=<?= $pic['id'] ?>&category=<?= $catID ?>&imgName=<?= $pic['img'] ?>&parentPic=<?= $picID ?>&fromModule=yes"> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
                    <path d="M63.8,18L46.1,0.2l-8.3,8.3L0,46.2V64h17.8l39.1-39l0,0L63.8,18z M58.2,18L54,22.2L41.9,10l4.1-4.2L58.2,18z M4,51.9l8.3,8.1H4V51.9z M17,59.1l-12.3-12l34.3-34.2L51.2,25L17,59.1z" />
                </svg> <span class="label">edit</span> </a> <a href="delete_image.php?id=<?= $pic['id'] ?>&category=<?= $catID ?>&imgName=<?= $pic['img'] ?>&parentID=<?= $picID ?>&modID=<?= $module['id'] ?>&modType=<?= $modType ?>" class="delete"> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
                    <g>
                        <path d="M54,8H42V4c0-2.2-1.8-4-4-4H26c-2.2,0-4,1.8-4,4v4H10c-2.2,0-4,1.8-4,4v8h4.1l2.6,38.4c0.2,3.1,2.8,5.6,6,5.6h26.5c3.1,0,5.8-2.5,6-5.6L53.9,20H58v-8C58,9.8,56.2,8,54,8z M26,4h12v4H26V4z M47.3,58.1c-0.1,1-0.9,1.9-2,1.9H18.7c-1,0-1.9-0.8-2-1.9L14.1,20h35.7L47.3,58.1z M54,16H10v-4h44V16z" />
                        <rect x="9" y="37" transform="matrix(7.669654e-02 0.9971 -0.9971 7.669654e-02 59.1978 14.0736)" width="26.1" height="4" />
                        <rect x="30" y="26" width="4" height="26" />
                        <rect x="40" y="26" transform="matrix(0.9971 7.665917e-02 -7.665917e-02 0.9971 3.1133 -3.1049)" width="4" height="26.1" />
                    </g>
                </svg> <span class="label">delete</span> </a> </div>

    </li>

<?php

}

?>