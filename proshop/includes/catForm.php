<?php

	$layouts = getLayouts();

?>

<script type="text/javascript"><!--

$('body').on( "click", ".add_<?=$thisID?>", function(event) {

	var parentID = $("input#parentIDAdd_<?=$thisID?>").val();
	var parentParentID = $("input#parentParentID_<?=$thisID?>").val();
	var catName = $("input#catNameAdd_<?=$thisID?>").val();
	var offsetTop = $("input#offsetTopAdd_<?=$thisID?>").val();
	var pageType = $("#pageTypeSelAdd_<?=$thisID?> option:selected").val();
	var link = $("input#linkAdd_<?=$thisID?>").val();
	var status = $("#statusAdd_<?=$thisID?> option:selected").val();

	var alertMsg = "The following REQUIRED fields\nhave been left empty:\n";
	if (catName === "" || catName === "Category/Page Name") alertMsg += "\nName of Category";
	if (pageType === "") alertMsg += "\nType of Page";
	if (status === "") alertMsg += "\nPage Status";
	if (alertMsg != "The following REQUIRED fields\nhave been left empty:\n") {
		alert(alertMsg);
		return false;
	} else {

		// handle ajax post here
		 var data = {"parentID" : parentID, "parentParentID" : parentParentID, "catName" : catName, offsetTop: "offsetTop", "pageType" : pageType, "link" : link, "status" : status};
		 var data_encoded = JSON.stringify(data);

		 $.ajax({
		   type: "POST",
		   url: "addCategory.php",
		   data: {
			 "myContent" : data_encoded
		   },
		   success: function(response) {
				
				window.location.href = response;
				
				/*
				loadPage(response);
				loadLeft("includes/leftCol.php?showMenu=yes&showSub="+parentID);
				*/

		   },
		   error: function(){
			 alert('failure');
		   }
		});
		return false;
	}
});

// --></script>

<form name="catForm<?=$thisID?>" method="post">
<input type="hidden" name="parentID" id="parentIDAdd_<?=$thisID?>" value="<?=$thisID?>">
<input type="hidden" name="parentParentID" id="parentParentID_<?=$thisID?>" value="<?=$thisParentID?>">
<input type="text" name="catName" id="catNameAdd_<?=$thisID?>" value="Category/Page Name" onClick="this.value=''" class="smallField"><br />
<select name="pageType" id="pageTypeSelAdd_<?=$thisID?>" class="smallField">
	<option value="">Type of Page</option>
	<?php for ($count = 1; $row = mysqli_fetch_array($layouts); ++$count) { ?>
	
		<option id="<?=$row['layout']?>" value="<?=$row['layout']?>"><?=$row['layout']?></option>
		
	<?php } ?>
</select><br />

<div id="LinkBox<?=$thisID?>" style="display: none;">
	<input type="text" id="linkAdd_<?=$thisID?>" name="link" value="Add Link" class="smallField"><br />
</div>

<?php /* <input type="text" name="offsetTop" id="offsetTopAdd_<?=$thisID?>" value="Top Margin" onClick="this.value=''" class="smallField"><br /> */ ?>

<select name="status" id="statusAdd_<?=$thisID?>" class="smallField">
	<option value="">Status</option>
	<option value="live">Live</option>
	<option value="preview">Preview</option>
</select><br />

<button id="btn" class="add_<?=$thisID?>">Add</button>
<!--<input type="submit" value="Add" id="btn"><br />-->
</form>

<script>
	$('#pageTypeSelAdd_<?=$thisID?>').change(function() {
          var str = "";
          $("#pageTypeSel<?=$thisID?> option:selected").each(function () {
                str += $(this).text();
              });
          	  $("#"+str+"Box<?=$thisID?>").show();
        })
        .change();
</script>