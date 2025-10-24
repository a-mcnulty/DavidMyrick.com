<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	
	$picID = $_GET['picID']; // this is the parent product id
	$catID = $_GET['category']; // this is the category the user chose to see products

	$query2 = "SELECT * FROM pics WHERE catid = '$catID' ORDER BY sortBy ASC";

	$result2 = mysql_query($query2);

	if (!$result2) {
			die('Could not query:' . mysql_error());
			exit;
	}

	echo "Select Product(s):<br /><br />";
	echo "<input type='hidden' id='picID' name='picID' value='" . $picID . "'>";
	
	for ($count = 1; $pic = mysql_fetch_array($result2); ++$count) {
	
?>
	
		<input type="checkbox" class="relatedColor" name="relatedID" value="<?=$pic['id']?>"> <?=$pic['title']?> - $<?=$pic['price']?><br />
		
<?php
	
	}	
	
?>

<button id="btn" class="saveRelatedColor" style="margin-bottom: 15px; margin-right: 5px; float: left;">Save Selected</button><br /><br />

<script>

	$(".saveRelatedColor").click(function() {
	
		var parentID = $("#picID").val();
		
		var relatedArray = [];
		$('.relatedColor').each(function() {
			if ($(this).is(':checked')) {
				var theID = $(this).val();
				relatedArray.push(theID);
			}
		});
		
		 var data = {"related" : relatedArray, "parentID" : parentID};
		 var data_encoded = JSON.stringify(data);

		 $.ajax({
		   type: "POST",
		   url: "saveRelatedColors.php",
		   data: {
			 "myContent" : data_encoded
		   },
		   success: function(response) {
			 //reload currentRelated.php to show addition
			$.ajax({
			  url: 'currentColors.php?picID='+parentID,
			  cache: false,
			  success: function(data) {
				$('.currentRelatedColors').html(data);
			  }
			});
		   },
		   error: function(){
			 alert('failure');
		   }
		});
		return false;
	
	});
	
</script>