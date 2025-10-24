<?php

session_start();
include 'includes/secure.php';
include '../includes/connect.php';
include 'includes/functions.php';

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

		<div id="pageContent" class="manageimages media_library">

			<div class="heading">
				<div class="leftside">
					<div class="titles">
						<p><span class="head uploaderLink">MEDIA LIBRARY</span> (<?= $countMedia ?> Items)</p>
					</div>
					<div class="pager">

						<p>
							Page
							<?php for ($i = 1; $i <= $totalPages; ++$i) { ?>
								<a href="manage_media.php?page=<?= $i ?>" class="<?php if ($page == $i) { ?>disabled<?php } ?>"><?= $i ?></a>
							<?php } ?>
						</p>

					</div>
				</div>
				<div class="rightside">
					<form action="manage_media.php" method="get" name="search">
						<input type="text" name="keyword" value="<?= $keyword ?>" class="media_search" placeholder="Search by Filename / Title" />
						<input type="submit" value="SEARCH" id="btn" /><br />
						<a href="manage_media.php">X CLEAR</a>
					</form>
				</div>

			</div>


			<div id="line" style="width: 99%; margin-bottom: 2px;"></div>
			<div id="line" style="width: 99%;"></div><br /><br />

			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td align="left" valign="top">

						<div id="managePics">

							<form name="content" action="updateImages.php" method="post">
								<input type="hidden" id="category" name="category" value="<?= $catID ?>">
								<input type="hidden" id="parentID" name="parentID" value="<?= $parentID ?>">
								<input type="hidden" id="parentParentID" name="parentParentID" value="<?= $parentParentID ?>">

								<div id="alertDivPic" style="float: left;">

								</div>

								<div style="clear: both;"></div>

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

											<div class="imagetools">
												<a href="detail.php?picID=<?= $pic['id'] ?>&fromMedia=yes">

													<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
														<path d="M63.8,18L46.1,0.2l-8.3,8.3L0,46.2V64h17.8l39.1-39l0,0L63.8,18z M58.2,18L54,22.2L41.9,10l4.1-4.2L58.2,18z M4,51.9l8.3,8.1H4V51.9z M17,59.1l-12.3-12l34.3-34.2L51.2,25L17,59.1z" />
													</svg>
													<span class="label">details</span>

												</a>

											</div>

										</li>

									<?php

									}

									?>

								</ul>

						</div>

						<div id="clear" style="height: 10px;"></div>

						</form>

					</td>
				</tr>
			</table>

		</div>

	</div>

</body>

</html>

<script type="text/javascript">
	$(document).ready(function() {

		$('#contentArea').scroll(function(event) {
			var scroll = $('#contentArea').scrollTop();
			if (scroll > 445) {
				$('body').addClass('fixed');
			} else {
				$('body').removeClass('fixed');
			}
		});

		/* JS OVERRIDES TO SHOW CORRECT DEFAULT MENU */
		$('.label').removeClass('labelOn');
		$('#media-library').addClass('labelOn');

		$('a.filedetails').click(function(e) {
			e.preventDefault();
			var link = 'Media Link: <a href="' + $(this).data('link') + '" target="_blank">' + $(this).data('link') + '</a>';

			if ($('#notice').length) {
				$('#notice').html(link);
			} else {
				$('<div id="notice">' + link + '</div>').appendTo($('body'));
			}

		});

		$("A.ajax").click(function() {

			var thePage = $(this).attr("id");

			loadPage(thePage);

		});

		// ADDITION OF MULTI DELETE
		$(".selectCheck").click(function() {

			var total = $('input[name="selectCheck"]:checked').length;
			$('.heading .count').html(total);

		});

		$('.heading .deleteAll').click(function(e) {

			e.preventDefault();

			var deleteArray = [];
			var itemArray = [];

			$('.selectCheck').each(function() {

				var picid = $(this).data('id');
				if ($(this).is(":checked")) {

					itemArray.push(picid);

				}

			});

			var data = {
				"itemArray": itemArray
			};
			var data_encoded = JSON.stringify(data);

			$.ajax({
				type: "POST",
				url: "deleteMultiMedia.php",
				data: {
					"myContent": data_encoded
				},
				success: function(response) {
					window.location.href = "manage_media.php?status=items_have_been_deleted";
					//console.log(response);
				},
				error: function() {
					alert('failure');
				}
			});

		});

		$(".delete").colorbox({
			width: "402px",
			height: "227px",
			iframe: true,
			opacity: ".95"
		});

	});
</script>