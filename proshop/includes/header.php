<?php

	date_default_timezone_set('America/Los_Angeles');
	
?>

<div class="inner">

	<div id="headerContent">

		<img id="logo" data-href="/" src="/images/logo.png" alt="SKINGRAFT" />
		
		<?php if ($shopPage === "yes") { ?>
			<div id="storeTop" class="fadeMeOn">
				<div data-href="skip" class="cell search">
					<form name="search" action="/search-results.php" method="get">
						<img src="/images/_magnify_search_.png" />
						<label for="search">SEARCH</label>
						<input name="search" type="text" id="search_site" value="" />
					</form>
				</div>
				<!--<div data-href="/comingSoon.html" class="cell help">LIVE HELP</div>-->
				<!-- BEGIN OLARK CHAT LINK -->
				<div data-href="skip" class="cell">
					<a href="javascript:void(0);" onclick="olark('api.box.expand')" data-href="skip">
						LIVE HELP
					</a>
				</div>
				<!-- END OLARK CHAT LINK -->
				<div data-href="/wishlist" class="cell wishlist <?php if ($slug === "wishlist") { ?>pageOn<?php } ?>">MY WISHLIST</div>
				<div data-href="https://www.skingraftdesigns.com/cart" class="cell bag <?php if ($slug === "cart") { ?>pageOn<?php } ?>">MY SHOPPING BAG (<span class="cartCount"><?php echo countCartItems($sid); ?></span>)</div>
				
				<?php if (isset($_SESSION['customer_id'])) { ?>
					<div data-href="/account" class="cell account <?php if ($slug === "account") { ?>pageOn<?php } ?>">MY ACCOUNT</div>
					<div data-href="/logout.php" class="cell logout">LOGOUT</div>
				<?php } else { ?>
					<div data-href="skip" class="cell account openLogin">MY ACCOUNT</div>
				<?php } ?>
			</div>
		<?php } ?>

		<ul class="nav <?php if ($shopPage === "yes") { ?>store<?php } ?>">
		
			<?php
			
				$menSub = getFirstSubID(483);
				$menSubSlug = getSlug($menSub);
			
			?>
			
			<li data-href="/shop/<?=$menSubSlug?>" <?php if ($shopSlug === "mens" OR $parentID == 483) { ?>class="pageOn"<?php } ?>>MEN</li>
			
			<?php
			
				$womenSub = getFirstSubID(484);
				$womenSubSlug = getSlug($womenSub);
			
			?>
			
			<li data-href="/shop/<?=$womenSubSlug?>" <?php if ($shopSlug === "womens"  OR $parentID == 484) { ?>class="pageOn"<?php } ?>>WOMEN</li>
			
			<?php
			
				$cats = catList("488");
				for ($catcount = 1; $cat = mysql_fetch_array($cats); ++$catcount) {
				
					if ($cat['pageType'] === "link") {
						$link = $cat['link'];
						$target = "_blank";
					} else {
						$link = "/" . $cat['slug'];
						$target = "_parent";					
					}					
			
			?>			

				<li data-href="<?=$link?>" <?php if ($slug === $cat['slug'] OR $parentID == $cat['id']) { ?>class="pageOn"<?php } ?>><?=$cat['name']?></li>
				
			<?php } ?>

			<span id="hiddenMOB">
				<span id="hiddenMOBTrigger">...</span>
				<ul>
					<li class="rowQty3" data-href="/info/#returns-and-exchanges">RETURNS EXCHANGES</li>
					<li class="rowQty3" data-href="/info/#terms-and-conditions">TERMS CONDITIONS</li>
					<li class="rowQty3" data-href="/info/#privacy-policy">PRIVACY POLICY</li>
					<li class="rowQty2" data-href="comingSoon.html">WHOLESALE LOGIN</li>
					<li class="rowQty2" data-href="/info/#contact-us">CONTACT</li>
				</ul>
			</span>
		</ul>

		<div id="openMenuBtn">
			<div></div>
			<div></div>
		</div>

	</div>

	<img class="logo" src="/images/SW_Logo.png" />

</div>


<div id="LOGINoverlay" class="">				

	<div class="inner login">						
		<img class="QVclose" src="/images/QVClose.png" />
		
		<form name="logIn" id="logIn" class="col">

			<div class="column">
				<h2 class="headline" style="padding-bottom: 14px;">LOG IN:</h2>
		
				<input type="text" value="EMAIL" name="login_email" onfocus="if (this.value == 'EMAIL') this.value='';" onblur="if (this.value == '') this.value='EMAIL';" />
				<input type="text" value="PASSWORD" class="login_password" name="login_password" onfocus="if (this.value == 'PASSWORD') this.value='';" onblur="if (this.value == '') this.value='PASSWORD';" />
				<a class="buttonBlk loginBtn" href="#"><h1>SUBMIT</h1></a>

				<p style="padding-top: 14px; font-size: 14px;"><a href="#">Forgot your password?</a></p>
			</div>
			
		</form>
			
		<form name="createAccount" id="createAccount" class="col">
			
			<div class="column">
				<h2 class="headline" style="padding-bottom: 14px;">OR CREATE AN ACCOUNT:</h2>
				
				<input class="halfsize" style="width: 174px; margin-right: 6px;" type="text" value="FIRST NAME" name="fname" onfocus="if (this.value == 'FIRST NAME') this.value='';" onblur="if (this.value == '') this.value='FIRST NAME';" />
				<input class="halfsize" style="width: 175px;" type="text" value="LAST NAME" name="lname" onfocus="if (this.value == 'LAST NAME') this.value='';" onblur="if (this.value == '') this.value='LAST NAME';" />
				
				<input type="text" value="EMAIL" name="login_email" onfocus="if (this.value == 'EMAIL') this.value='';" onblur="if (this.value == '') this.value='EMAIL';" />
				<input type="text" value="PASSWORD" class="login_password" name="login_password" onfocus="if (this.value == 'PASSWORD') this.value='';" onblur="if (this.value == '') this.value='PASSWORD';" />
				
				<a class="buttonBlk step2" href="#"><h1>CREATE ACCOUNT</h1></a>
			</div>

		</form>

		
	</div>	
</div>
<?/* temo off...-ae

*/?>