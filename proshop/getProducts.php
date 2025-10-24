<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	
	$picID = $_GET['picID']; // this is the parent product id
	$catID = $_GET['category']; // this is the category the user chose to see products

	$query2 = "SELECT pics.* FROM pics JOIN cat_pics ON pics.id = cat_pics.picid WHERE cat_pics.catid = '$catID' ORDER BY pics.sortBy ASC";

	$result2 = mysql_query($query2);

	if (!$result2) {
			die('Could not query:' . mysql_error());
			exit;
	}

	echo "Select Product(s):<br /><br />";
	echo "<input type='hidden' id='picID' name='picID' value='" . $picID . "'>";
	
	for ($count = 1; $pic = mysql_fetch_array($result2); ++$count) {
	
?>
	
		<input type="checkbox" class="related" name="relatedID" value="<?=$pic['id']?>"> <?=$pic['title']?> - $<?=$pic['price']?><br />
		
<?php
	
	}	
	
?>

<button id="btn" class="saveRelated" style="margin-bottom: 15px; margin-right: 5px; float: left;">Save Selected</button><br /><br />

<script>

	$(".saveRelated").click(function() {
	
		var parentID = $("#picID").val();
		
		var relatedArray = [];
		$('.related').each(function() {
			if ($(this).is(':checked')) {
				var theID = $(this).val();
				relatedArray.push(theID);
			}
		});
		
		 var data = {"related" : relatedArray, "parentID" : parentID};
		 var data_encoded = JSON.stringify(data);

		 $.ajax({
		   type: "POST",
		   url: "saveRelated.php",
		   data: {
			 "myContent" : data_encoded
		   },
		   success: function(response) {
			 //reload currentRelated.php to show addition
			$.ajax({
			  url: 'currentRelated.php?picID='+parentID,
			  cache: false,
			  success: function(data) {
				$('.currentRelated').html(data);
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