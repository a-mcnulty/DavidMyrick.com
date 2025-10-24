<script>

$(document).ready(function() {

	// top menu functions

	$("A.topMenu.ajax").click(function() {

		// remove any current "topLabelOn" classes
		$("div").removeClass("toplabelOn");
		$(this).parent().addClass("toplabelOn");

		// close any open submenus
		$(".topSub").hide();

		// get the lang attr for submenu to open
		var theSub = $(this).attr("lang");
		$("#topSub"+theSub).show();

		var thePage = $(this).attr("id");

		if (thePage != undefined) {

			loadPage(thePage);

		}

	});

	// product sub-menu

	$(".catFilter").change(function() {

		var selCatID = $(this).val();
		thePage = "manage_products.php?catID="+selCatID;

		loadPage(thePage);

	});

	$("A.topSubLink.ajax").click(function() {

		$(".subLabel").removeClass("subLabelOn");

		$(this).parent().addClass("subLabelOn");

		var thePage = $(this).attr("id");
		loadPage(thePage);

	});
	
	// product search
	
	$(".search").click(function() {
	
		var theKeyword = $("#keyword").val();
		loadPage("manage_products.php?keyword="+theKeyword);
	
	});

});

</script>

	<div class="holder">
		<a href="main.php"><img src="images/icons/home.png" style="float: left; margin-right: 54px;"></a>

		<div id="contentTopMenu" class="topMenuDetail">
			<div class="toplabel">
				<a href="#" onclick="return false" class="topMenu ajax" lang="Content">ADD PAGE</a>
			</div>
			<?php if (getOption("tags") === "yes") { ?>
				<div class="toplabel">
					<a href="manage_tags.php" class="topMenu">TAGS</a>
				</div>
			<?php } ?>
		</div>

		<div class="topSub" id="topSubContent" style="display: none;">
			<div class="topSubPad" style="margin-left: 250px; margin-top: 21px;">
				<?php include('catFormTop.php'); ?>
			</div>
		</div>

		<div id="storeTopMenu" class="topMenuDetail" <?php if (isset($_GET['showTop']) AND $_GET['showTop'] === "store") { ?>style="opacity: 1; display: block;"<?php } ?>>
			<div class="toplabel">
				<a href="#" id="manage_orders.php" onclick="return false" class="topMenu ajax" lang="Orders">ORDERS</a>
			</div>
			<div class="toplabel">
				<a href="manage_categories.php" id="manage_categories.php" class="topMenu" lang="Products">PRODUCTS</a>
			</div>
			<div class="toplabel">
				<a href="#" id="manage_customers.php" onclick="return false" class="topMenu ajax" lang="Customers">CUSTOMERS</a>
			</div>
		</div>

		<div class="topSub" id="topSubProducts" style="<?php if (isset($_GET['showSub']) AND $_GET['showSub'] === "products") { ?>display: block;<?php } else { ?>display: none;<?php } ?>">
			<div class="topSubPad">
				<div class="subLabel <?php if (isset($_GET['showSub']) AND $_GET['showSub'] === "products") { ?>toplabelOn<?php } ?>">
					<a href="#" id="add_products.php" onclick="return false" class="topSubLink ajax">+ ADD PRODUCTS</a>
				</div>
				<div class="subLabel">
					<a href="manage_categories.php" class="topSubLink ajax">CATEGORIES</a>
				</div>
				<div class="subLabel">
					<select name="catid" class="catFilter" style="margin-top: -6px;">
						<option value="">CATEGORY FILTER</option>

						<?php getShopCategories(); ?>

					</select>
				</div>
				<div class="subLabel" style="width: 190px;">
					<input style="margin-top: -6px; float: left; border: 1px solid #abadb3;" type="text" name="keyword" id="keyword" value="FIND A PRODUCT" onfocus="if (this.value == 'FIND A PRODUCT') this.value='';" onblur="if (this.value == '') this.value='FIND A PRODUCT';">
					<img src="images/icons/search.png" class="search" style="float: left; margin-left: 6px; margin-top: -4px; cursor: pointer;">
				</div>
			</div>
		</div>

	</div>