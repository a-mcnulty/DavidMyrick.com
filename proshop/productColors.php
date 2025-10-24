<script>

// BASED ON THE OPTION SET THEY CHOOSE WE NEED TO DISPLAY AVAILABLE VARIANTS
$('.setSelect').change(function() {
	$('.saveOptions').show();
	
	var setID = $(this).val();
	//$(this).parent().find('.add').show();
	$(this).parent().find('.optionVariants').load('getVariants.php?id='+setID, function() {
		// auto-save selections
		$(this).parent().find('.add').click();
	});
	return false;
});

// IF ANY VARIANT CHOICES ARE CHECKED OR UN-CHECKED SAME OPTIONS
$('.optionVariants .variant').live('change', function(){
	
	$('.saveOptions').show();
	
	var setID = $(this).parent().parent().find('#optionID').val();
	$(this).parent().parent().find('.add').click();
	return false;
});

// WHEN USER SAVES AN OPTION SET NEED TO UPDATE EC_PRODUCT_OPTIONS TABLE, UNIQUE ROW FOR EACH VARIANT
$('.add').click(function() {
	
	var productID = $(this).parent().find('#productID').val();
	var setID = $(this).parent().find('#optionID').val();
	var optionArray = [];
	$(this).parent().find('.variant').each(function() {
		if ( $(this).is(':checked') ) {
			var opID = $(this).val();
			optionArray.push(opID);
		}
	});
	
	 var data = {"productID" : productID, "setID" : setID, "optionArray" : optionArray};
	 var data_encoded = JSON.stringify(data);

	 $.ajax({
	   type: "POST",
	   url: "saveOptions.php",
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

$('.newSet').click(function() {

	var theRel = $(this).attr("rel");
	var relInt = parseInt(theRel);
	var nextRel = relInt + 1;
	
	$('#optionSet'+theRel).show();
	$(this).attr("rel", nextRel);
	
	return false;

});

// ONCE ALL SETS ARE ASSIGNED WE NEED TO CREATE THE INVENTORY TABLE
$('.save').click(function() {

	var productID = $(this).attr("rel");

	var data = {"productID" : productID};
	var data_encoded = JSON.stringify(data);

	$.ajax({
	   type: "POST",
	   url: "createInventory.php",
	   data: {
		 "myContent" : data_encoded
	   },
	   success: function(response) {
	   
			// reload productOptions to show inventory table
			loadPage('detail.php?picID='+productID+'&category=<?=$catID?>&type=product&showInventory=yes');
			
	   },
	   error: function(){
		 alert('failure');
	   }
	});
	return false;

});

// SAVE PRODUCT INVENTORY
$('.saveInv').click(function() {

	var productID = $(this).attr("rel");
	
	// store each price and qty number in array
	var idArray = [];
	var priceArray = [];
	var qtyArray = [];
	
	 $('.invID').each(function() {
		var thisID = $(this).val();
		idArray.push(thisID);
	 });
	
	 $('.invPrice').each(function() {
		var thisPrice = $(this).val();
		priceArray.push(thisPrice);
	 });
	 
	 $('.invQty').each(function() {
		var thisQty = $(this).val();
		qtyArray.push(thisQty);
	 });

	var data = {"productID" : productID, "idArray" : idArray, "priceArray" : priceArray, "qtyArray" : qtyArray};
	var data_encoded = JSON.stringify(data);

	$.ajax({
	   type: "POST",
	   url: "saveInventory.php",
	   data: {
		 "myContent" : data_encoded
	   },
	   success: function(response) {
	   
			// reload productOptions to show inventory table
			loadPage('detail.php?picID='+productID+'&category=<?=$catID?>&type=product&showInventory=yes');
			
	   },
	   error: function(){
		 alert('failure');
	   }
	});
	return false;

});

</script>

<div id="productOptions" style="margin-top: 5px;">

	<?php
	
		$optionCount = checkProductOptions($picID);
		
		if ($optionCount > 0) {
		
			$productSets = getProductOptionSets($picID);
			$setCount = mysql_numrows($productSets);
			$colCount = $setCount + 2;
			$colWidth = floor(85 / $colCount);
			
			$setArray = array();
			for ($count = 1; $row = mysql_fetch_array($productSets); ++$count) {
				$setArray[] = strtolower(subName($row['optionsetid']));
			}
	?>
	
		<div class="invRow">
		
			<?php foreach($setArray as $set) { ?>
			
				<div style="width: <?=$colWidth?>%;">
					<?=ucwords($set)?>
				</div>
				
			<?php } ?>
			
			<div style="width: <?=$colWidth?>%;">
				Price (+/-)
			</div>
			<div style="width: <?=$colWidth?>%;">
				Qty
			</div>
			<div style="width: 10%; text-align: right;"></div>
			
		</div>
		
		<?php
		
			$productVariants = getProductInventory($picID);
			for ($count = 1; $row = mysql_fetch_array($productVariants); ++$count) {
			
				$optionStr = $row['optionstr'];
				$optionArray = explode(",", $optionStr);
				
				if ($count % 2) {
					$bgColor = "background: #ededed;";
				} else {
					$bgColor = "background: #fff;";
				}
		
		?>
		
				<div class="invRow" style="<?=$bgColor?>">
	
					<?php 
					
						foreach($optionArray as $option) {
						
							$optionDetail = getProductDetail($option);
						
					?>
					
						<div style="width: <?=$colWidth?>%;">
							<?=strtolower($optionDetail['title'])?>
						</div>
						
					<?php } ?>
					<div style="width: <?=$colWidth?>%;">
						<input type="hidden" name="invID" class="invID" value="<?=$row['id']?>">
						<input type="text" name="price" class="invPrice" value="<?=$row['price']?>">
					</div>
					<div style="width: <?=$colWidth?>%;">
						<input type="text" name="qty" class="invQty" value="<?=$row['qty']?>">
					</div>
					<div style="width: 10%; text-align: right;">
						<img src="images/icons/edit.png" border="0" class="editIcon" style="position: relative; top: 1px;">
						<img src="images/icons/delete.png" border="0" class="deleteIcon">
					</div>
				
				</div>
			
		<?php } ?>
		
		<button id="btn" class="saveInv" rel="<?=$picID?>" style="float: left; height: 25px; margin-top: 10px; margin-right: 10px; display: block;">save inventory</button>
	
	
	<?php } else { ?>
	
		<?php for($i=1; $i<=5; ++$i) { ?>
		
			<div class="optionSet" id="optionSet<?=$i?>" style="margin-bottom: 15px; width: 100%; <?php if ($i > 1) { ?>display: none;<?php } ?>">
				<?php $optionSets = getOptionSets(); ?>
				Choose an option set:<br />
				<input type="hidden" name="productID" id="productID" value="<?=$picID?>">
				<select name="optionID" id="optionID" class="setSelect" style="margin-top: 5px; float: left;">

					<option value="">--SELECT--</option>
					<?php for ($count = 1; $option = mysql_fetch_array($optionSets); ++$count) { ?>
						<option value="<?=$option['id']?>"><?=$option['subName']?></option>
					<?php } ?>

				</select>
				
				<button id="btn" class="add" style="float: left; height: 25px; margin-top: 5px; margin-left: 10px; display: none;">add</button>
				
				<div style="clear: both;"></div>
				
				<div class="optionVariants"></div>

			</div>
			
		<?php } ?>
		
		<div class="saveOptions" style="display: none;">
		
			<button id="btn" class="save" rel="<?=$picID?>" style="float: left; height: 25px; margin-top: 5px; margin-right: 10px; display: block;">save options</button> <span style="float: left; margin-top: 10px;"> - OR - <a href="#" class="newSet" rel="2">Add Another Set</a></span>
			
		</div>
		
	<?php } ?>
		
	<div style="clear: both;"></div>
	
</div>

<hr><br />