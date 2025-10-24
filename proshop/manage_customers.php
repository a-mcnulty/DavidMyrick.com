<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$catID = $_GET['catID'];
	$keyword = $_GET['keyword'];
	$sort = $_GET['sort'];
	if (!isset($sort)) {
		$sort = "dateAdded DESC";
	}
	$customers = getCustomers($keyword, $sort);

?>

<script type="text/javascript">
$(document).ready(function(){

	$(".search").click(function() {

		var keyword = $("input#keyword").val();

		loadPage("manage_customers.php?keyword="+keyword);

		return false;

	});

	$(".sortBy").click(function() {

		var sort = $(this).attr("id");

		loadPage("manage_customers.php?sort="+sort);

		return false;

	});

});
</script>

<div class="infoPage">

	<div id="alertDivPic" style="float: right; margin: 9px 5px 0 0;"></div>
	<p style="float: left;"><span class="head">Manage Customers:</span></p>

	<div id="breadcrumb">
		<a href="main.php">DASHBOARD</a> > <span class="pageOn">CUSTOMERS</span>
	</div>

	<div id="line" style="width: 90%; margin-bottom: 2px; clear: both;"></div>
	<div id="line" style="width: 90%;"></div><br />

		<div id="search" style="margin-top: 20px; margin-bottom: 20px; float: right; margin-right: 10%;">

			<form name="search" method="post">
			<input type="text" name="keyword" id="keyword" <?php if ($_GET['keyword'] != "") { ?>value="<?=$_GET['keyword']?>"<?php } else { ?>value="SEARCH BY NAME"<?php } ?> style="float: left; margin-right: 5px; height: 13px;" onfocus="if (this.value == 'SEARCH BY NAME') this.value='';" onblur="if (this.value == '') this.value='SEARCH BY NAME';">
			<button id="btn" class="search" style="float: left">SEARCH</button>

			</form>

		</div>

	<div id="manageCustomers">

		<table border="0" cellpadding="0" cellspacing="0" style="width: 90%;">
			<tr>
				<td align="left" valign="top" class="dataHeader cufon">
					<a href="#" class="sortBy" id="fname ASC">First Name</a>
				</td>
				<td align="left" valign="top" class="dataHeader cufon">
					<a href="#" class="sortBy" id="lname ASC">Last Name</a>
				</td>
				<td align="left" valign="top" class="dataHeader cufon">
					<a href="#" class="sortBy" id="dateAdded ASC">Date Created</a>
				</td>
				<td align="left" valign="top" class="dataHeader cufon">
					<a href="#" class="sortBy" id="type ASC">Type</a>
				</td>
			</tr>

			<?php for ($count = 1; $cust = mysql_fetch_array($customers); ++$count) { ?>

				<?php

					if ($count % 2) {
						$class = "oddRow";
					} else {
						$class = "evenRow";
					}

				?>

				<tr>
					<td align="left" valign="top" class="<?=$class?> cufon">
						<a href="#" id="customer_detail.php?id=<?=$cust['id']?>" class="breadcrumb"><?php echo $cust['fname']; ?></a>
					</td>
					<td align="left" valign="top" class="<?=$class?> cufon">
						<a href="#" id="customer_detail.php?id=<?=$cust['id']?>" class="breadcrumb"><?php echo $cust['lname']; ?></a>
					</td>
					<td align="left" valign="top" class="<?=$class?> cufon">
						<?php echo displayDate($cust['dateAdded']); ?>
					</td>
					<td align="left" valign="top" class="<?=$class?> cufon">
						<?php echo $cust['type']; ?>
					</td>
				</tr>

			<?php } ?>

		</table>

	</div>

</div>