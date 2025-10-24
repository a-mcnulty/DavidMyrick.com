<?php
	
	if (isset($metaType) AND $metaType === "product") {
	
		$theCat = getMainImage($picID);
	
	} else {
	
		$theCat = catDetails($catID);
		
	}

?>

<script>

	$(document).ready(function() {
	
		// HANDLE FORM SUBMISSION
		$(".updateMeta").click(function() {
			
			var catID = $("input#catID").val();
			var picID = $("input#picID").val();
			var metaTitle = $("#metaTitle").val();
			var metaDesc = $('#metaDesc').val();
			var metaKeywords = $("#metaKeywords").val();

			var data = {"catID" : catID, "picID" : picID, "metaTitle" : metaTitle, "metaDesc" : metaDesc, "metaKeywords" : metaKeywords};
			var data_encoded = JSON.stringify(data);
			
			if (typeof picID === "undefined") {
				var url = "updateMeta.php";
			} else {
				var url = "updateMetaProduct.php";
			}

			$.ajax({
			  type: "POST",
			  url: url,
			  data: {
				"myContent" : data_encoded
			  },
			  success: function(response) {
				$('.metaResponse').html(response);
			  },
			  error: function(){
				alert('failure');
			  }
			});

			return false;

		});
		
		$(".openerTitle").click(function() {

			$(".contents").slideToggle();

		});
		
	});

</script>

<div class="opener" style="width: 98%; margin-top: 20px;">
	<span class="openerTitle" style="cursor: pointer;">META TAG EDITOR</span>
	<span class="dirArrow"><img src="images/icons/openArrow.png" style="border: 0px;"></span>
</div>
<div class="contents" style="display: none; padding: 25px; width: 93%;">

	Title:<br />
	<input type="text" name="metaTitle" id="metaTitle" value="<?=$theCat['metaTitle']?>" style="width: 450px;"><br /><br />

	Description:<br />
	<textarea name="metaDesc" id="metaDesc" class="mceNoEditor" style="width: 450px; height: 35px; font-family: Arial;"><?=$theCat['metaDesc']?></textarea><br /><br />

	Keywords:<br />
	<textarea name="metaKeywords" id="metaKeywords" class="mceNoEditor" style="width: 450px; height: 35px; font-family: Arial;"><?=$theCat['metaKeywords']?></textarea><br /><br />

	<button id="btn" class="updateMeta">Update Meta Tags</button><br>

	<div class="metaResponse" style="margin-top: 10px;"></div>
</div>

<div id="line" style="width: 99%; margin-bottom: 25px;"></div><br />