<?php

	$layouts = getLayouts();

?>

<script type="text/javascript">

$(document).ready(function() {

	$(".add_new").click(function() {

		var catName = $("input#catNameAdd_new").val();
		var pageType = $("#pageTypeSelAdd_new option:selected").val();
		var link = $("input#linkAdd_new").val();
		var status = $("#statusAdd_new option:selected").val();

		var alertMsg = "The following REQUIRED fields\nhave been left empty:\n";
		if (catName === "" || catName === "Category/Page Name") alertMsg += "\nName of Category";
		if (pageType === "") alertMsg += "\nType of Page";
		if (status === "") alertMsg += "\nPage Status";
		if (alertMsg != "The following REQUIRED fields\nhave been left empty:\n") {
			alert(alertMsg);
			return false;
		} else {

			// handle ajax post here
			 var data = {"catName" : catName, "pageType" : pageType, "link" : link, "status" : status, "contentType" : "page", "parentID" : 0, "parentParentID" : 0};
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
					loadLeft("includes/leftCol.php?showMenu=yes");
					*/

			   },
			   error: function(){
				 alert('failure');
			   }
			});
			return false;
		}

	});

});

</script>

<form name="catFormnew" method="post">

<div style="float: left; margin-right: 10px;">
	<input type="text" name="catName" id="catNameAdd_new" value="Category/Page Name" onClick="this.value=''" class="smallField" style="width: 165px; height: 17px;">
</div>

<div style="float: left; margin-right: 10px;">
	<select name="pageType" id="pageTypeSelAdd_new" class="smallField" style="width: 165px; height: 25px;">
		<option value="">Type of Page</option>
		
		<?php for ($count = 1; $row = mysqli_fetch_array($layouts); ++$count) { ?>
		
			<option id="<?=$row['layout']?>" value="<?=$row['layout']?>"><?=$row['layout']?></option>
			
		<?php } ?>
		
	</select>
</div>

<div id="LinkBoxnew" style="display: none; float: left; margin-right: 10px;">
	<input type="text" id="linkAdd_new" name="link" placeholder="Add Link" class="smallField" style="width: 165px; height: 17px;">
</div>

<div style="float: left; margin-right: 10px;">
	<select name="status" id="statusAdd_new" class="smallField" style="width: 165px; height: 24px;">
		<option value="">Status</option>
		<option value="live">Live</option>
		<option value="preview">Preview</option>
	</select>
</div>

<div style="float: left;">
	<button id="btn" class="add_new">Add</button>
</div>
</form>

<script>
	$('#pageTypeSelAdd_new').change(function() {
          var str = "";
          $("#pageTypeSelAdd_new option:selected").each(function () {
                str += $(this).text();
              });
          	  $("#"+str+"Boxnew").show();
        })
        .change();
</script>