<?php
	
	session_start();
	include 'includes/secure.php';
	include 'includes/connect.php';
	$picID = $_GET['picID'];
	
	function getOption2($name) {

		$query = "SELECT value FROM options WHERE name = '$name'";

		$result=mysql_query($query);

		if (!$result) {
				die('Could not query:' . mysql_error());
				exit;
		}

		$row = mysql_fetch_array($result);

		return $row['value'];

	}
	
	function getRelated2($picID) {
	
		$sql1 = "SELECT related FROM pics WHERE id = '$picID'";

		$result1 = mysql_query($sql1);

		if (!$result1) {
				die('Could not query:' . mysql_error());
				exit;
		}
		
		$row1 = mysql_fetch_array($result1);
		
		$related = $row1['related'];
		$relatedArray = explode(",", $related);
		
		foreach($relatedArray as $relatedID) {
		
			if ($relatedID === "") {
				continue;
			}
			
			$sql2 = "SELECT * FROM pics WHERE id = '$relatedID'";
				
			$result2 = mysql_query($sql2);

			if (!$result2) {
					die('Could not query:' . mysql_error());
					exit;
			}
			
			$row2 = mysql_fetch_array($result2);
			
			echo '<img src="' . getOption2("imagePath") . 'thumbs/' . $row2['img'] . '" class="relatedThumb" id="' . $relatedID . '" width="50" style="display: inline-block; margin: 0 3px; cursor: pointer;">';
		
		}
	
	}

?>
<script>

	$(".relatedThumb").click(function() {
	
		var parentID = $("#parentID").val();
		var theID = $(this).attr("id");
		
		 var data = {"relatedID" : theID, "parentID" : parentID};
		 var data_encoded = JSON.stringify(data);

		 $.ajax({
		   type: "POST",
		   url: "deleteRelated.php",
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

<input type="hidden" name="parentID" id="parentID" value="<?=$picID?>">
<?php echo getRelated2($picID); ?>