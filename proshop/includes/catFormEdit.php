<?php

	$layouts = getLayouts();

?>

<script type="text/javascript"><!--

$('body').on( "click", ".update_<?=$thisID?>", function(event) {

	var thisCatID = $("input#thisCatID_<?=$thisID?>").val();
	var catID = $("input#catID_<?=$thisID?>").val();
	var parentID = $("input#parentID_<?=$thisID?>").val();
	var parentParentID = $("input#parentParentID_<?=$thisID?>").val();
	var catName = $("input#catName_<?=$thisID?>").val();
	var offsetTop = $("input#offsetTop_<?=$thisID?>").val();
	var pageType = $("#pageTypeSel_<?=$thisID?> option:selected").val();
	var link = $("input#link_<?=$thisID?>").val();
	var status = $("#status_<?=$thisID?> option:selected").text();

	var alertMsg = "The following REQUIRED fields\nhave been left empty:\n";
	if (catName === "" || catName === "Category/Page Name") alertMsg += "\nName of Category";
	if (pageType === "") alertMsg += "\nType of Page";
	if (status === "") alertMsg += "\nPage Status";
	if (alertMsg != "The following REQUIRED fields\nhave been left empty:\n") {
		alert(alertMsg);
		return false;
	} else {

		// handle ajax post here
		 var data = {"thisCatID" : thisCatID, "catID" : catID, "parentID" : parentID, "parentParentID" : parentParentID, "catName" : catName, "offsetTop" : offsetTop, "pageType" : pageType, "link" : link, "status" : status};
		 var data_encoded = JSON.stringify(data);
		 
		 console.log(data_encoded);

		 $.ajax({
		   type: "POST",
		   url: "editCategory.php",
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
<input type="hidden" name="thisCatID" id="thisCatID_<?=$thisID?>" value="<?=$thisID?>">
<input type="hidden" name="catID" id="catID_<?=$thisID?>" value="<?=$thisID?>">
<input type="hidden" name="parentID" id="parentID_<?=$thisID?>" value="<?=$thisParentID?>">
<input type="hidden" name="parentParentID" id="parentParentID_<?=$thisID?>" value="<?=$parentDetails['parentID']?>">
<input type="text" name="catName" id="catName_<?=$thisID?>" value="<?=$name?>" class="smallField"><br />
<select name="pageType" id="pageTypeSel_<?=$thisID?>" class="smallField">
	<option value="<?=$pageType?>"><?=$pageType?></option>
	<?php for ($count = 1; $row = mysqli_fetch_array($layouts); ++$count) { ?>
	
		<option id="<?=$row['layout']?>" value="<?=$row['layout']?>"><?=$row['layout']?></option>
		
	<?php } ?>
</select><br />

<div id="LinkBox<?=$thisID?>" style="display: none;">
	<input type="text" name="link" id="link_<?=$thisID?>" value="<?=$linkURL?>" class="smallField"><br />
</div>

<!--<input type="text" name="offsetTop" id="offsetTop_<?=$thisID?>" value="<?=$offsetTop?>" class="smallField"><br />-->

<select name="status" id="status_<?=$thisID?>" class="smallField">
	<option value="<?=$status?>"><?=$status?></option>
	<option value="live">Live</option>
	<option value="preview">Preview</option>
</select><br />

<button id="btn" class="update_<?=$thisID?>">Update</button>
</form>

<script>
	$('#pageTypeSel_<?=$thisID?>').change(function() {
          var str = "";
          $("#pageTypeSel<?=$thisCatID?> option:selected").each(function () {
                str += $(this).text();
              });
          	  $("#"+str+"Box<?=$thisCatID?>").show();
        })
        .change();
</script>