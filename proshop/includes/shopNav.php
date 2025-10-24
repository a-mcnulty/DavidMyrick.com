<?php

	$shopCats = getShopCats();
	for ($shopcount = 1; $shop = mysql_fetch_array($shopCats); ++$shopcount) {
	
		$firstSub = getFirstSubID($shop['id']);
		$firstSubSlug = getSlug($firstSub);

?>

		<?php /* <div class="cell transitionThis <?php if ($catID == $shop['id'] OR $parentID == $shop['id']) { ?>open<?php } ?>" data-href="/shop/<?=$firstSubSlug?>" title="<?=$shop['name']?>"> */ ?>

		<div class="cell <?php if ($catID == $shop['id'] OR $parentID == $shop['id']) { ?>open<?php } ?>" title="<?=$shop['name']?>">
		
			<a data-href="/shop/<?=$firstSubSlug?>" class="transitionThis" style="display: block; position: absolute; top: -15px; left: 0px; height: 30px; width: 100%;"></a>
		
			<?php

				$subcategories = subcatList($shop['id']);

				for ($subcount = 1; $shopsub = mysql_fetch_array($subcategories); ++$subcount) {

			?>

					<span data-href="/shop/<?=$shopsub['slug']?>" <?php if ($catID == $shopsub['id']) { ?>class="pageOn"<?php } ?>><?=strtoupper($shopsub['subName'])?></span>
					
			<?php } ?>

		</div>
		
<?php } ?>			

<div id="newsletterSignup">

	<h1>NEWSLETTER SIGNUP</h1>
	
	<div class="inner">
	
		<!-- Start MyNewsletterBuilder Subscription Form -->
		<form id="joinNewsletter" name="subscriber_form" action="http://www.mynewsletterbuilder.com/tools/ext_subscribe" method="post" target="_blank">
			<input type="hidden" name="mnb_success_url" value="" />
			<input type="hidden" name="mnb_error_url" value="" />
			<input type="hidden" name="mnb_uid" value="293234" />
			<input type="hidden" name="mnb_cat_override" value="add" />
			<input type="hidden" name="mnb_notify" value="" />
			<input type="hidden" name="mnb_nlid" value="" />
			
				<label for="email">ENTER EMAIL HERE</label>
				<input type="text" name="mnb_fields[email]" />
				<input type="hidden" name="mnb_req_fields[email]" value="Email" />
				<div style="display: none;">
					<input type="checkbox" name="mnb_cats[]" value="297213" id="cat-297213" checked="checked" /> <label for="cat-297213">General
				</div>
				<img src="/images/_arw_lrg_.png" class="newsletter_side_submit" />
				<!--<input type="image" src="/images/_arw_lrg_.png" style="width: 15px; height: 13px;" value="Subscribe" />-->
		</form>
		<!-- End MyNewsletterBuilder Subscription Form -->
	
		<!--
	
		<form>
			<label for="email">Enter Email</label>
			<input name="email" type="text" id="newsletter_enail" value="" />
			<img src="/images/prod_arwNext.png" />
		</form>

		-->
	
	</div>					
	
</div>