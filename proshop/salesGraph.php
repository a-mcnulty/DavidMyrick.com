<?php
	
	// get the last x days of sales data
	$days = array();
	
	if ($custom === "no") {
	
		for($i = 0; $i < $showdays; $i++) {
			$days[] = date('Y-m-d', strtotime('-'. $i .' days'));
		}	
		$days = array_reverse($days, true);
		
	} else {
		
		$days = createDateRangeArray($startDate,$endDate);
	
	}
	
	$js_array = "[";	$totalSales = 0;
	
	foreach ($days as $date) {				
		$sales = getSales($date);		
		if ($sales['dayTotal'] > 0) {			
			$theTotal = $sales['dayTotal'];		
		} else {			
			$theTotal = 0;		
		}
		
		$totalSales = $totalSales + $sales['dayTotal'];		
		/*		echo $day . "<br />";		echo strtotime($day) . "<br />";		echo $sales['dayTotal'] . "<br /><br />";		*/				/* $js_array .= '[' . strtotime($day) . ', ' . $sales['dayTotal'] . '],'; */		
		
		$js_array .= '[' . (mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4))*1000) . ', ' . $theTotal . '],';
		
	}	
	
	$js_array = rtrim($js_array);		$js_array .= "]";
?>