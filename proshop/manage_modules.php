<?php

session_start();
include 'includes/secure.php';
include '../includes/connect.php';
include 'includes/functions.php';

$picID = 0;
$modType = "category";
$catID = $_GET['category'];
$theCat = catDetails($catID);
if (isset($_GET['parentID'])) {
    $parentID = $_GET['parentID'];
}
$theParent = catDetails($parentID);
if (isset($_GET['parentParentID'])) {
    $parentParentID = $_GET['parentParentID'];
}
if (isset($_GET['modedit'])) {
    $modedit = $_GET['modedit'];
} else {
    $modedit = "";
}
$allmods = getModLayouts();
$modules = getCatModules($catID);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML>

<HEAD>

    <?php include 'includes/pageHead.php'; ?>

</HEAD>

<BODY>

    <?php

    if (isset($_REQUEST['status'])) {
        echo showStatus($_REQUEST['status']);
    }

    ?>

    <div id="topMenu">
        <?php include('includes/topMenu.php'); ?>
    </div>

    <div id="leftCol">
        <div class="innerLoad">

            <?php include('includes/leftColNew.php'); ?>

        </div>
    </div>

    <div id="contentArea">

        <div id="pageContent" class="manageimages">

            <form>
                <input type="hidden" name="picID" id="picID" value="<?= $picID ?>">
                <input type="hidden" name="catID" id="catID" value="<?= $catID ?>">
            </form>

            <p><span class="head uploaderLink"><?php if ($parentID == 0) { ?><?= $theCat['name'] ?><?php } else { ?><?= $theCat['subName'] ?><?php } ?></span> (<a href="/<?= $theCat['slug'] ?>" target="_blank">VIEW PAGE</a>)</p>

            <div id="line" style="width: 99%; margin-bottom: 2px;"></div>
            <div id="line" style="width: 99%;"></div><br />

            <input type="hidden" id="catID" name="catID" value="<?= $catID ?>">
            <input type="hidden" id="parentID" name="parentID" value="<?= $parentID ?>">

            <?php include('metaEditor.php'); ?>

            <div class="addMod">

                <span class="head">ADD A MODULE</span><br />

                <div id="line" style="width: 99%; margin-bottom: 2px; clear: both;"></div>
                <div id="line" style="width: 99%;"></div><br />

                <ul id="draggable" class="draggable layouts">

                    <?php for ($count = 1; $mod = mysqli_fetch_array($allmods); ++$count) { ?>

                        <li id="1" data-catid="<?= $catID ?>" data-layout="<?= $mod['layout'] ?>">
                            <?php if ($mod['icon'] != "") { ?>
                                <span class="icon">
                                    <?= $mod['icon'] ?>
                                </span>
                            <?php } ?>
                            <span class="title">
                                <?= $mod['layout'] ?>
                            </span>
                        </li>

                    <?php } ?>

                </ul>

                <div id="droppable" class="droparea">
                    <span class="label">Drop new module type here</span>
                </div>

            </div>

            <div class="currentMods">

                <span class="head">CURRENT MODULES</span><br />

                <div id="line" style="width: 99%; margin-bottom: 2px; clear: both;"></div>
                <div id="line" style="width: 99%;"></div><br />

                <div id="moduleHolder">

                    <ul id="modules" class="toplevel">

                        <?php for ($count = 1; $module = mysqli_fetch_array($modules); ++$count) { ?>

                            <li id="recordsArray_<?php echo $module['id']; ?>">
                                <?php include('loadModule.php'); ?>
                                <div id="line" style="width: 99%; color: #dadada;"></div><br />
                            </li>

                        <?php } ?>

                    </ul>

                </div>

            </div>

        </div>

    </div>

    <?php include('includes/overlay.php'); ?>

    <script type="text/javascript">
        $(document).ready(function() {

            $("#contentTopMenu").css('display', 'block');
            $("#catList").css('display', 'block');

            <?php if ($modedit != "") { ?>
                $("#<?= $modedit ?>").addClass('moduleOpen');
                $("#<?= $modedit ?>").find('.moduleEdit').show();

                $('#contentArea').animate({
                    scrollTop: $("#<?= $modedit ?>").offset().top
                }, 500);

            <?php } ?>

            tinymce.init({
                license_key: 'gpl',
                promotion: false,
                mode: "specific_textareas",
                selector: ".mceEditor",
                images_upload_url: 'postAcceptor.php',
                plugins: "image link media lists code",
                toolbar: "styles bold italic forecolor alignleft aligncenter alignright alignjustify bullist numlist outdent indent link image media code",
                preview_styles: false,
                style_formats: [{
                        title: 'Heading 1',
                        block: 'h1'
                    },
                    {
                        title: 'Heading 2',
                        block: 'h2'
                    },
                    {
                        title: 'Heading 3',
                        block: 'h3'
                    },
                    {
                        title: 'Paragraph',
                        block: 'p'
                    },
                ],
                image_class_list: [{
                        title: 'None',
                        value: ''
                    },
                    {
                        title: 'Half Width',
                        value: 'half'
                    },
                    {
                        title: 'Full Width',
                        value: 'full'
                    }
                ],
                link_class_list: [{
                        title: 'None',
                        value: ''
                    },
                    {
                        title: 'Button',
                        value: 'btn'
                    }
                ],
                content_css: '/css/admin.css',
                relative_urls: false,
                /*
                setup: function(ed) {
                    ed.addButton('column', {
                        title: 'Make Column',
                        text: 'Column',
                        icon: false,
                        onclick: function() {
                            var text = ed.selection.getContent({
                                'format': 'html'
                            });
                            if (text && text.length > 0) {
                                ed.execCommand('mceInsertContent', false,
                                    '<div class="col">' + text + '</div>');
                            }
                        }
                    });
                }
                    */
            });

            /* JS OVERRIDES TO SHOW CORRECT DEFAULT MENU */

            $("#contentTopMenu").css('display', 'block');
            $("#catList").css('display', 'block');

            /* DRAG/DROP EXISTING MODULES */
            $("#moduleHolder ul.toplevel").sortable({
                opacity: 0.6,
                cursor: 'move',
                handle: '.moduleHandle',
                update: function() {
                    var order = $(this).sortable("serialize") + '&action=updateRecordsListings';
                    $.post("updateModuleOrder.php", order, function(theResponse) {



                    });
                }
            });

            /* OPEN/CLOSE EXISTING MODULES */
            $('#modules li .moduleLabel').click(function() {

                if ($(this).parent().hasClass('moduleOpen')) {
                    $(this).parent().removeClass('moduleOpen');
                    $(this).parent().find('.moduleEdit').hide();
                } else {

                    $('#modules li').removeClass('moduleOpen');
                    $('#modules li .moduleEdit').hide();

                    $(this).parent().addClass('moduleOpen');
                    $(this).parent().find('.moduleEdit').show();
                }

            });

            /* ADD NEW MODULE DRAG/DROP */
            $("li", "#draggable").draggable({
                helper: "clone"
            });
            $("#droppable").droppable({
                drop: function(event, ui) {
                    var catid = ui.draggable.data('catid');
                    var layout = ui.draggable.data('layout');

                    $.post("createModuleCat.php", {
                            catID: catid,
                            picID: 0,
                            title: layout,
                            pageType: layout
                        })
                        .done(function(data) {
                            if (data != "error") {
                                var modId = data;
                                var thePage = "manage_modules.php?category=<?= $catID ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&modedit=recordsArray_" + data;
                                window.location.href = thePage;
                            } else {
                                alert("There was an error adding the module");
                            }
                        });

                }
            });

            // show/hide image info
            $(".picHolder").hoverIntent(
                function() {
                    $(this).children(".picInfo").fadeTo(200, 1);
                    $(this).children(".picCover").fadeTo(200, .5);
                },
                function() {
                    $(this).children(".picInfo").fadeTo(200, 0);
                    $(this).children(".picCover").fadeTo(200, 0);
                }
            );

            // edit image info
            $(".updateAlt").click(function() {

                var parent = $(this).closest('#managePics');

                if ($(this).hasClass('module')) {
                    var module = "yes";
                }

                var category = $("input#catID").val();
                var detailID = $("input#detailID").val();
                var moduleID = $(this).data('modid');

                var picIDArray = [];
                parent.find('.picID').each(function() {
                    var picID = $(this).val();
                    picIDArray.push(picID);
                });

                var imgArray = [];
                parent.find('.img').each(function() {
                    var img = $(this).val();
                    imgArray.push(img);
                });

                var titleArray = [];
                parent.find('.titleField').each(function() {
                    var title = $(this).val();
                    titleArray.push(title);
                });

                var captionsArray = [];
                parent.find('.captions').each(function() {
                    var captions = $(this).val();
                    captionsArray.push(captions);
                });

                var picURLArray = [];
                parent.find('.picURL').each(function() {
                    var picURL = $(this).val();
                    picURLArray.push(picURL);
                });

                var shortDescArray = [];
                parent.find('.shortDesc').each(function() {
                    var shortDesc = $(this).val();
                    shortDescArray.push(shortDesc);
                });

                var hoverArray = [];
                parent.find('.hover').each(function() {
                    if ($(this).is(':checked')) {
                        var hover = "yes";
                    } else {
                        var hover = "";
                    }
                    hoverArray.push(hover);
                });

                var moveCatArray = [];
                parent.find('.moveCat').each(function() {
                    var moveCat = $(this).val();
                    moveCatArray.push(moveCat);
                });

                var copyCatArray = [];
                parent.find('.copyCat').each(function() {
                    var copyCat = $(this).val();
                    copyCatArray.push(copyCat);
                });

                var deleteArray = [];
                parent.find('.deleteCheck').each(function() {
                    if ($(this).is(':checked')) {
                        var deleteThis = "yes";
                    } else {
                        var deleteThis = "no";
                    }
                    deleteArray.push(deleteThis);
                });

                var rowBreakArray = [];
                parent.find('.rowBreak').each(function() {
                    if ($(this).is(':checked')) {
                        var rowBreak = "yes";
                    } else {
                        var rowBreak = "";
                    }
                    rowBreakArray.push(rowBreak);
                });

                var imgSizeArray = [];
                parent.find('.imgSize').each(function() {
                    var imgSize = $(this).val();
                    imgSizeArray.push(imgSize);
                });

                var data = {
                    "category": category,
                    "detailID": detailID,
                    "moduleID": moduleID,
                    "picID": picIDArray,
                    "img": imgArray,
                    "title": titleArray,
                    "captions": captionsArray,
                    "picURL": picURLArray,
                    "shortDesc": shortDescArray,
                    "hover": hoverArray,
                    "module": module,
                    "moveCat": moveCatArray,
                    "copyCat": copyCatArray,
                    "deleteArray": deleteArray,
                    "rowBreakArray": rowBreakArray,
                    "imgSizeArray": imgSizeArray
                };
                var data_encoded = JSON.stringify(data);

                $.ajax({
                    type: "POST",
                    url: "updateImages.php",
                    data: {
                        "myContent": data_encoded
                    },
                    success: function(response) {
                        $('<div id="alert">ITEM(S) HAVE BEEN UPDATED</div>').appendTo('body');
                        setTimeout(function() {
                            $("#alert").fadeOut("slow");
                        }, 3000);

                    },
                    error: function() {
                        alert('failure');
                    }
                });
                return false;

            });

        });
    </script>

</body>

</html>