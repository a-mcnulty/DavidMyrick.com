<?php

	

	$rows = getTaxAndShipping($dayRange);

	

?>



	<div id="summary" style="margin-top: 10px; margin-bottom: 20px; float: left; display: none;">

	

		TOTAL ORDERS: <span class="showOrders" style="font-weight: bold;"></span> | TOTAL TAX COLLECTED: <span class="showTax" style="font-weight: bold;"></span> | TOTAL SHIPPING COLLECTED: <span class="showShipping" style="font-weight: bold;"></span>

	

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

					# Orders

				</td>

				<td align="left" valign="top" class="dataHeader cufon">

					Tax $

				</td>

				<td align="left" valign="top" class="dataHeader cufon">

					Shipping $

				</td>
				
				<td align="left" valign="top" class="dataHeader cufon">

					Product $

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

						<?=$row['orderCount']?>

					</td>
					
					<td align="left" valign="top" class="<?=$class?> cufon">

						 <?=$row['taxTotal']?>

					</td>
					
					<td align="left" valign="top" class="<?=$class?> cufon">

						 <?=$row['shipTotal']?>

					</td>

					<td align="left" valign="top" class="<?=$class?> cufon">

						<?=$row['prodTotal']?>

					</td>

				</tr>



			<?php 

			

				} 

				

			?>


		</table>



	</div>