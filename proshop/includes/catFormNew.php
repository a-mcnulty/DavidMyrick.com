<?php

	$layouts = getLayouts();

?>

<script type="text/javascript"><!--

function validateFormNew() {
with (document.catFormNew) {
var alertMsg = "The following REQUIRED fields\nhave been left empty:\n";
if (catName.value == "" || catName.value == "Category/Page Name") alertMsg += "\nName of Category/Page";
if (pageType.options[pageType.selectedIndex].value == "") alertMsg += "\nType of Page";
if (status.options[status.selectedIndex].value == "") alertMsg += "\nPage Status";
if (alertMsg != "The following REQUIRED fields\nhave been left empty:\n") {
alert(alertMsg);
return false;
} else {
return true;
} } }

// --></script>

<form name="catFormNew" action="addCategory.php" method="post" onsubmit="return validateFormNew()" enctype="multipart/form-data">
<input type="text" name="catName" value="Category/Page Name" onClick="this.value=''" class="smallField"><br />
<select name="pageType" id="pageTypeSel" class="smallField">
	<option value="">Type of Page</option>
	<?php for ($count = 1; $row = mysql_fetch_array($layouts); ++$count) { ?>
	
		<option id="<?=$row['layout']?>" value="<?=$row['layout']?>"><?=$row['layout']?></option>
		
	<?php } ?>
</select><br />

<div id="LinkBox" style="display: none;">
	<input type="text" name="link" value="Add Link" class="smallField"><br />
</div>

<select name="section" class="smallField">
	<option value="">Select Section</option>

	<?php $sections = catList(); ?>

	<?php

		for ($sectionCount = 1; $section = mysqli_fetch_array($sections); ++$sectionCount) {

			$sectionID = $section['id'];

	?>

			<option value="<?=$sectionID?>"><?=$section['name']?></option>

	<?php } ?>

</select><br />

<select name="status" class="smallField">
	<option value="">Status</option>
	<option value="live">Live</option>
	<option value="preview">Preview</option>
</select><br />

<?php if (getOption("navType") === "images") { ?>

	<span class="smallWhite">Add Menu Images:</span><br />
	<input type="text" value="Menu Image" id="fileName" class="file_input_textbox" style="font-size: 11px; color: #636363; width: 85px;" readonly="readonly">

	<div class="file_input_div">
	  <input type="button" value="Select" class="file_input_button" />
	  <input type="file" name="navOFF" class="file_input_hidden" onchange="javascript: document.getElementById('fileName').value = this.value" />
	</div>

	<div style="clear: both;"></div>

	<input type="text" value="Rollover Image" id="fileName2" class="file_input_textbox" style="font-size: 11px; color: #636363; width: 85px;" readonly="readonly">

	<div class="file_input_div">
	  <input type="button" value="Select" class="file_input_button" />
	  <input type="file" name="navON" class="file_input_hidden" onchange="javascript: document.getElementById('fileName2').value = this.value" />
	</div>

<?php } ?>

<input type="submit" value="Add" id="btn"><br />
</form>

<script>
	$('#pageTypeSel').change(function() {
          var str = "";
          $("#pageTypeSel option:selected").each(function () {
                str += $(this).text();
              });
          	  $("#"+str+"Box").show();
        })
        .change();
</script>