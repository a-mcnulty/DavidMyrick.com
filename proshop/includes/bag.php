<?php

	session_start();
	include("connect.php");
	include("functions.php");

?>

<a href="cart.php"><span class="cartCount"><?php echo countCartItems($sid); ?></span><img src="images/bag.png" />shopping bag</a>