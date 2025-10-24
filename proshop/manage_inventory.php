<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	
	// GET THE TOP LEVEL CATEGORIES
	$query1 = "SELECT * FROM cat_list WHERE contentType = 'category' AND parentID = '0' AND status = 'live' ORDER BY sortBy ASC";
	
	$result1 = mysql_query($query1);

	if (!$result1) {
			die('Could not query:' . mysql_error());
			exit;
	}

?>

<script type="text/javascript">
$(document).ready(function(){

	$(".inventory .invQty").change(function(){
		var invID = $(this).attr("rel");
		var qty = $(this).val();
		
		 var data = {"invID" : invID, "qty" : qty};
		 var data_encoded = JSON.stringify(data);

		 $.ajax({
		   type: "POST",
		   url: "updateInventory.php",
		   data: {
			 "myContent" : data_encoded
		   },
		   success: function(response) {
				
		   },
		   error: function(){
			 alert('failure');
		   }
		});
		return false;
		
	});
	
	// in page ajax links

	$("A.ajax").click(function() {

		var thePage = $(this).attr("id");

		loadPage(thePage);

	});

});
</script>

<div class="infoPage">

	<div id="alertDivPic" style="float: right; margin: 9px 5px 0 0;"></div>
	<p style="float: left;"><span class="head">Manage Inventory:</span></p>

	<div id="breadcrumb">
		<a href="main.php">DASHBOARD</a> > <span class="pageOn">MANAGE INVENTORY</span>
	</div>

	<div id="line" style="width: 90%; margin-bottom: 2px; clear: both;"></div>
	<div id="line" style="width: 90%;"></div><br />

	<div id="manageCustomers" class="inventory">

		<table border="0" cellpadding="0" cellspacing="0" style="width: 90%;">

			<?php for ($count = 1; $row1 = mysql_fetch_array($result1); ++$count) { ?>
			
				<tr>
					<td align="left" valign="top" class="dataHeader cufon" style="background: #181818; color: #fff;">
						<?php echo $row1['name']; ?><br />
					</td>
					<td align="left" valign="top" class="dataHeader cufon" style="background: #181818; color: #fff;">
					
						<table>
							<tr>
						
							<?php
							
								$variants = getOptions(489, "result");
								for ($count = 1; $size = mysql_fetch_array($variants); ++$count) {							
								
							?>
							
								<td style="width: 100px;">
									<?=$size['title']?>
								</td>							
							
							<?php
							
								}
								
							?>
						
							</tr>
						</table>
					
					</td>
				</tr>
				
				<?php		
				
					// NOW WE GRAB ALL PRODUCTS AND CATEGORIES BASED ON PARENT CATEGORY
					
					$parentID = $row1['id'];
				
					$query2 = "SELECT pics.title, pics.id, cat_pics.catid FROM cat_pics JOIN cat_list ON cat_list.id = cat_pics.catid JOIN pics ON pics.id = cat_pics.picid WHERE cat_list.parentID = '$parentID' GROUP BY pics.id ORDER BY cat_list.sortBy ASC, pics.title ASC";
					
					$result2 = mysql_query($query2);

					if (!$result2) {
							die('Could not query:' . mysql_error());
							exit;
					}
					
					for ($count2 = 1; $row2 = mysql_fetch_array($result2); ++$count2) {

						if ($count2 % 2) {
							$class = "oddRow";
						} else {
							$class = "evenRow";
						}

				?>

						<tr>
							<td align="left" valign="top" class="<?=$class?> cufon">
								<a class="ajax" id="detail.php?picID=<?=$row2['id']?>&category=<?=$row2['catid']?>&type=product"><?php echo $row2['title']; ?></a>
							</td>
							<td align="left" valign="top" class="<?=$class?> cufon">
								
								<table>
									<tr>
							
										<?php
											$variants = getOptions(489, "result");
											for ($count = 1; $variant = mysql_fetch_array($variants); ++$count) {
											
												// check for specific qty based on size and product
												$thisInv = getProductVariantID($row2['id'], $variant['id']);
										?>
										
												
												<td style="width: 100px;">
													<?php if ($thisInv === "N/A") { ?>
														<?=$thisInv?>
													<?php } else { ?>
														<input type="text" name="qty" class="invQty" rel="<?=$thisInv[0]?>" value="<?=$thisInv[1]?>" style="width: 40%;">
													<?php } ?>
												</td>
												
										<?php
										
											}
											
										?>
										
									</tr>
								</table>

								
							</td>
						</tr>
						
				<?php } ?>

			<?php } ?>

		</table>

	</div>

</div>