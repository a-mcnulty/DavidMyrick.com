<?php

	// get the last 30 days of sales data by default, check user selected date range as well
	$xsqldata = array();
	for($i = 0; $i < $dayRange; $i++) {
		$xsqldata[] = date("Y-m-d", strtotime('-'. $i .' days'));
	}
	//$xsqldata = array_reverse($xsqldata);
	
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
					Date
				</td>
				<td align="left" valign="top" class="dataHeader cufon">
					# Orders
				</td>
				<td align="left" valign="top" width="135" class="dataHeader cufon">
					Product $
				</td>
			</tr>

			<?php 

				$totOrders = 0;
				$totSales = 0;
				$count = 1;
				foreach($xsqldata as $day) {
				
					$sales = getSales($day);
					
					if ($count % 2) {
						$class = "oddRow";
					} else {
						$class = "evenRow";
					}

			?>

				<tr>
					<td align="left" valign="top" class="<?=$class?> cufon">
						<?php echo displayDate($day); ?>
					</td>
					<td align="left" valign="top" class="<?=$class?> cufon">
						<?=$sales['orderCount']?>
						<?php
						
							$totOrders = $totOrders + $sales['orderCount'];
						
						?>
					</td>
					<td align="left" valign="top" class="<?=$class?> cufon">
						<?=$sales['dayTotal']?>
						
						<?php
						
							$totSales = $totSales + $sales['dayTotal'];
						
						?>
					</td>
				</tr>

			<?php 
			
					++$count;
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