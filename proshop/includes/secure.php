<?php

	if ($_COOKIE['admin'] != "yes") {
		header("Location: index.php?status=session_has_timed_out");
	}

?>