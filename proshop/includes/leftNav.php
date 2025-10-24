<ul id="menu">

<?php

	$sql = "SELECT * FROM cat_list WHERE sectionID <> '0' AND contentType = 'category' AND status = 'live' ORDER BY sortBy ASC";

	$result = mysql_query($sql);

	for ($count = 1; $shop = mysql_fetch_array($result); ++$count) {

		$shopID = $shop['id'];

?>

		<li class="menuCat">

			<h4><a href="browse.php?slug=<?=$shop['slug']?>" id="<?=$shop['id']?>"><?=$shop['name']?></a></h4>

			<div class="section section_<?=$shop['id']?>" <?php if ($parentID == $shopID OR $catID == $shopID) { ?>style="display: block;"<?php } else { ?>style="display: none;"<?php } ?>>

				<?php

					$subcategories = subcatList($shopID);

					for ($count = 1; $sub = mysql_fetch_array($subcategories); ++$count) {

				?>

						<a href="browse.php?slug=<?=$sub['slug']?>" class="talent" id="talent_<?=$sub['id']?>"><h3 id="title_talent_<?=$sub['id']?>"><?=$sub['subName']?></h3></a>

				<?php } ?>

			</div>

		</li>

<?php } ?>

</ul>