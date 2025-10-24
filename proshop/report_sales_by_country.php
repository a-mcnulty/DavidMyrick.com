<?php
	
	$sort = $_GET['sort'];	

	if (stristr($sort, "ASC")) {
		$theDir = "DESC";
	} else {
		$theDir = "ASC";
	}
	
	$rows = getSalesByCountry($dayRange, $sort);
	
?>

	<div id="summary" style="margin-top: 10px; margin-bottom: 20px; float: left;">
	
		TOTAL ORDERS: <span class="showOrders" style="font-weight: bold;"></span> | TOTAL SALES: <span class="showSales" style="font-weight: bold;"></span>
	
	</div>

	<div id="search" style="margin-top: 10px; margin-bottom: 20px; float: right; margin-right: 10%;">

		<form name="filter">
		<select name="days" id="days">
			<option value="<?=$dayRange?>"><?=$dayRange?> Days</option>
			<option value="7">7 Days</option>
			<option value="14">14 Days</option>
			<option value="30">30 Days</option>
			<option value="60">60 Days</option>
			<option value="90">90 Days</option>
		</select>

		</form>

	</div>

	<div id="manageOrders" style="margin-top: 20px; margin-bottom: 20px;">

		<table border="0" cellpadding="0" cellspacing="0" style="width: 90%;">
			<tr>
				<td align="left" valign="top" class="dataHeader cufon">
					<a class="ajax" rel="manage_reports.php?report=report_sales_by_country.php&days=<?=$dayRange?>&sort=billlcountry <?=$theDir?>" onclick="return false">Country</a>
				</td>
				<td align="left" valign="top" class="dataHeader cufon">
					<a class="ajax" rel="manage_reports.php?report=report_sales_by_country.php&days=<?=$dayRange?>&sort=orderCount <?=$theDir?>" onclick="return false"># Orders</a>
				</td>
				<td align="left" valign="top" width="135" class="dataHeader cufon">
					<a class="ajax" rel="manage_reports.php?report=report_sales_by_country.php&days=<?=$dayRange?>&sort=dayTotal <?=$theDir?>" onclick="return false">Product $</a>
				</td>
			</tr>

			<?php 

				$totOrders = 0;
				$totSales = 0;
				for ($count = 1; $row = mysql_fetch_array($rows); ++$count) {
					
					if ($count % 2) {
						$class = "oddRow";
					} else {
						$class = "evenRow";
					}

			?>

				<tr>
					<td align="left" valign="top" class="<?=$class?> cufon">
						<?php echo $row['billcountry']; ?>
					</td>
					<td align="left" valign="top" class="<?=$class?> cufon">
						<?=$row['orderCount']?>
						<?php
						
							$totOrders = $totOrders + $row['orderCount'];
						
						?>
					</td>
					<td align="left" valign="top" class="<?=$class?> cufon">
						<?=$row['dayTotal']?>
						
						<?php
						
							$totSales = $totSales + $row['dayTotal'];
						
						?>
					</td>
				</tr>

			<?php 
			
				} 
				
			?>
			
			<tr>
				<td align="left" valign="top" class="summaryRow cufon"></td>
				<td align="left" valign="top" class="summaryRow cufon totOrders">
					<?=$totOrders?>
				</td>
				<td align="left" valign="top" class="summaryRow cufon totSales">
					$<?=$totSales?>
				</td>
			</tr>

		</table>

	</div>