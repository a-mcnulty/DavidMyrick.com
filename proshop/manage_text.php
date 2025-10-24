<?php

session_start();
include 'includes/secure.php';
include '../includes/connect.php';
include 'includes/functions.php';

if (isset($_GET['category'])) {
	$catID = $_GET['category'];
}
if (isset($_GET['parentID'])) {
	$parentID = $_GET['parentID'];
}
if (isset($_GET['parentParentID'])) {
	$parentParentID = $_GET['parentParentID'];
}

$theCat = catDetails($catID);
if ($theCat['name'] != "") {
	$pageName = $theCat['name'];
} else {
	$pageName = $theCat['subName'];
}

$metaType = "category";
$myText = getPageTextAll($catID);

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

		<div id="pageContent">

			<div class="imagePage">

				<div id="alertDivPic" style="float: right; margin: 9px 5px 0 0;"></div>
				<p style="float: left;"><span class="head"><?= $pageName ?></span> (<a href="/<?= $theCat['slug'] ?>" target="_blank">VIEW PAGE</a>)</p>

				<div id="breadcrumb">
					<a href="main.php">DASHBOARD</a> > <span class="pageOn"><?= $pageName ?></span>
				</div>

				<div id="line" style="width: 99%; margin-bottom: 2px; clear: both;"></div>
				<div id="line" style="width: 99%; margin-bottom: 25px;"></div>

				<?php include('metaEditor.php'); ?>

				<div style="width: 98%;">

					<b>Editor Tip</b> - To create a single line break hold down the SHIFT + ENTER Key.<br /><br />
					<form name="textEditor" method="post">
						<input type="hidden" name="catID" id="catID" value="<?= $catID ?>">

						<span class="head">MAIN TEXT</span>
						<textarea name="content" id="content" class="mceEditor" style="width: 800px; height: 400px;"><?= stripslashes($myText['content']) ?></textarea><br>

						<span class="head">SECONDARY TEXT</span>
						<textarea name="content2" id="content2" class="mceEditor" style="width: 800px; height: 200px;"><?= stripslashes($myText['content2']) ?></textarea><br>

						<button id="btn" class="update">Update Page</button><br>

						<div class="theResponse" style="margin-top: 10px;"></div>

					</form>

				</div>

			</div>

		</div>

	</div>

</body>

</html>


<script type="text/javascript">
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
</script>

<script type="text/javascript">
	$(document).ready(function() {

		/* JS OVERRIDES TO SHOW CORRECT DEFAULT MENU */

		$("#contentTopMenu").css('display', 'block');
		$("#catList").css('display', 'block');

		// HANDLE FORM SUBMISSION
		$(".update").click(function() {

			var ed = tinymce.get('content'); // get editor instance
			var myText = ed.save(); // retrieve the content save to variable

			var ed2 = tinymce.get('content2'); // get editor instance
			var myText2 = ed2.save(); // retrieve the content save to variable

			var catID = $("input#catID").val();

			var data = {
				"content": myText,
				"content2": myText2,
				"catID": catID
			};
			var data_encoded = JSON.stringify(data);

			$.ajax({
				type: "POST",
				url: "updateText.php",
				data: {
					"myContent": data_encoded
				},
				success: function(response) {
					$('.theResponse').html(response);
				},
				error: function() {
					alert('failure');
				}
			});

			return false;

		});

	});
</script>