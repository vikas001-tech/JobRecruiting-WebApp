<?php
	require("header.php");
?>
	<style>
		#pad_left {
			padding-left: 15px;
			padding-right: 15px;
		}
		#center_logo {
		  margin: auto;
		  width: 50%;
		  position: center;
		  text-align: center;
		}
		#center_text {
		  margin: auto;
		  width: 50%;
		  position: center;
		  text-align: center;
		}
		#center_grey {
		  margin: auto;
		  width: 50%;
		  position: center;
		  text-align: center;
		  color: #909090;
		}
	</style>

	<br><br>

	<div id="center_logo">
	 <img src="images/logo.png" height="350" width="350"> <!-- Logo Picture -->
	</div>

	<br><br>

<?php
    $subscription = $_SESSION['subscription'];
?>

	<div id="center_text">
	 	<h3>If you'd like to pay for your monthly subscription: <?php echo $subscription; ?> </h3> <br>
	 	<h3>Please press the button below. </h3> <br>

	 	<!-- Main Form -->
		<form style="width:900px; margin:auto;" class="form-signin" action="process_subscription_payment.php" method="post">
			<button class="btn btn-primary btn-lg btn-block" type="submit">Make Payment</button>
		</form>

	</div>



<?php
    require("footer.php");
?>
