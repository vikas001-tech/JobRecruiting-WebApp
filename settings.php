<?php
	session_start();
	require("header.php");
	include("computeRating.php");
?>
	<style>
		#pad_left {
			padding-left: 15px;
			padding-right: 15px;
		}
		#center_title {
		  margin: auto;
		  width: 75%;
		  position: center;
		  text-align: center;
		}
		#center_text {
		  margin: auto;
		  width: 75%;
		  position: center;
		  text-align: center;
		}
		#center_form {
		  margin: 0 auto;
    	  width:50%
		}
		#div-inline{
			display: inline-block;
		}
		#right {
		  position: absolute;
		  right: 0px;
		  width: 300px;
		  border: 3px solid #73AD21;
		  padding: 10px;
		}
		#left {
		  position: absolute;
		  left: 0px;
		  padding: 10px;
		}
		#left_psw {
		  position: absolute;
		  left: 0px;
		  padding: 10px;
		}
	</style>

	<br/><br/>

	<!-- Main content -->

	<div id="center_text">
		 <!-- Settings title should change dynamically depending on the type of user -->
		 <?php
		 	switch ($_SESSION['user_type']) {
		 		case 0: //admin
		 			echo "<h2><b> Admin Settings </b></h2> <br>";
		 			break;

		 		case 1: //Employer
		 			echo "<h2><b> Employer Settings </b></h2> <br>";
		 			break;

		 		case 2: //User
		 			echo "<h2><b> User Settings </b></h2> <br>";
		 			break;

		 	}
		 	//<h2><b> *User/Admin/Employer* Dashboard </b></h2> <br>
		 ?>
	</div>

	<br/><br/>

	<div id="center_text">
		<!-- <h4> Account Status: Active / Frozen </h4> -->
		<?php
			switch (getAccountStatus($_SESSION["user_id"],$_SESSION["user_type"])) {
		 		case 0: // ACTIVE
		 			echo "<h4> Account Status </h4> <br> <h5> Active &#9989; </h5> <br>";
		 			break;

		 		case 1: // FROZEN
		 			echo "<h4> Account Status </h4> <br> <h5> Frozen &#10060; </h5> <br>";
		 			break;
		 	}
			//echo "got status = " . getAccountStatus($_SESSION["user_id"],$_SESSION["user_type"]);
		?>
	</div>

	<hr style="height:1px;border-width:0;color:#CCD1D1;background-color:#CCD1D1">

	<br/>

	<div id="center_text">
		<!-- <h4> Subscription Category: ... </h4> -->
		<?php
			switch (getSubscriptionType($_SESSION["user_id"],$_SESSION["user_type"])) {
		 		case "Gold":
		 			echo "<h4> Subscription Category </h4> <br> <h5> Gold &#127942 </h5> <br>";
		 			break;

		 		case "Prime":
		 			echo "<h4> Subscription Category </h4> <br> <h5> Prime &#127894 </h5> <br>";
		 			break;

	 			case "Basic":
	 				echo "<h4> Subscription Category </h4> <br> <h5> Basic &#128119 </h5> <br>";
	 			break;
		 	}
		?>
	</div>

	<div id="center_text">
			<br>
			<h5> Edit Subscription </h5><br>


			<?php
				switch (getSubscriptionType($_SESSION["user_id"],$_SESSION["user_type"])) {
			 		case "Gold":

			 			echo '
				 			  <form class="form-inline md-form mr-auto mb-4 inline-block row justify-content-center" action="upgradeSubscription.php">
						 			<div class="form-check form-check-inline disabled">
											  <input class="form-check-input" type="radio" name="subscription_radio" id="radio_gold" value="Gold" checked disabled>
											  <label class="form-check-label" for="radio_gold">
											    Gold &#127942
											  </label>
											</div>
											<div class="form-check form-check-inline">
											  <input class="form-check-input" type="radio" name="subscription_radio" id="radio_prime" value="Prime">
											  <label class="form-check-label" for="radio_prime">
											    Prime &#127894
											  </label>
											</div>';
											//if the user is not an employer
											if(1 != $_SESSION["user_type"]){
												echo '	<div class="form-check form-check-inline">
													  <input class="form-check-input" type="radio" name="subscription_radio" id="radio_basic" value="Basic">
													  <label class="form-check-label" for="radio_basic">
													    Basic &#128119
													  </label>
											</div><br>
										<button class="btn btn-primary" type="submit">Submit</button>
										</form>';
									}else{
										echo '<br><button class="btn btn-primary" type="submit">Submit</button>
										</form>';
									}


			 			break;

			 		case "Prime":

			 			echo '
			 				<form class="form-inline md-form mr-auto mb-4 inline-block row justify-content-center" action="upgradeSubscription.php">
				 				<div class="form-check form-check-inline">
									  <input class="form-check-input" type="radio" name="subscription_radio" id="radio_gold" value="Gold">
									  <label class="form-check-label" for="radio_gold">
									    Gold &#127942
									  </label>
									</div>
									<div class="form-check form-check-inline disabled">
									  <input class="form-check-input" type="radio" name="subscription_radio" id="radio_prime" value="Prime" checked disabled>
									  <label class="form-check-label" for="radio_prime">
									    Prime &#127894
									  </label>
									</div>';
									//if the user is not an emplyer
									if(1 != $_SESSION["user_type"]){
										echo '<div class="form-check form-check-inline">
										  <input class="form-check-input" type="radio" name="subscription_radio" id="radio_basic" value="Basic">
										  <label class="form-check-label" for="radio_basic">
										    Basic &#128119
										  </label>
									</div><br>
									<button class="btn btn-primary" type="submit">Submit</button>
								</form>';
									}else{
										echo '<br>
										<button class="btn btn-primary" type="submit">Submit</button>
									</form>';
									}

			 			break;

			 			case "Basic":

		 				echo '
			 				<form class="form-inline md-form mr-auto mb-4 inline-block row justify-content-center" action="upgradeSubscription.php">
				 				<div class="form-check form-check-inline">
										  <input class="form-check-input" type="radio" name="subscription_radio" id="radio_gold" value="Gold">
										  <label class="form-check-label" for="radio_gold">
										    Gold &#127942
										  </label>
										</div>
										<div class="form-check form-check-inline">
										  <input class="form-check-input" type="radio" name="subscription_radio" id="radio_prime" value="Prime">
										  <label class="form-check-label" for="radio_prime">
										    Prime &#127894
										  </label>
										</div>
										<div class="form-check disabled form-check-inline">
										  <input class="form-check-input" type="radio" name="subscription_radio" id="radio_basic" value="Basic" checked disabled>
										  <label class="form-check-label" for="radio_basic">
										    Basic &#128119
										  </label>
									</div><br>
								<button class="btn btn-primary" type="submit">Submit</button>
							</form>';

		 				break;

			 	}

			?>
	</div>

	<br>

	<hr style="height:1px;border-width:0;color:#CCD1D1;background-color:#CCD1D1">

	<br/>

	<div id="center_text">
		<h4> Methods of Payment </h4>

		<br/>
		<div>
			<!-- <h6> Automatic Payment: true / false </h6> -->
			<?php
			switch (getAutomaticPayment($_SESSION["user_id"],$_SESSION["user_type"])) {
		 		case 0: // disabled
		 			echo "<h6> Automatic Payment: Disabled &#10060; </h6> <br>";
		 			break;

		 		case 1: // enabled
		 			echo "<h6> Automatic Payment: Enabled &#9989; </h6>";
		 			break;
		 	}
			//echo "got status = " . getAccountStatus($_SESSION["user_id"],$_SESSION["user_type"]);
			?>
		</div>

		<br/>

		<!-- <div>
			<h6> Edit methods of Payment ... </h6>
		</div> -->

	</div>

	<br/>

	<hr style="height:1px;border-width:0;color:#CCD1D1;background-color:#CCD1D1">

	<br/>

	<div id="center_text">
	    <h4> Transaction History </h4><br>

	    <?php echo "Bank Account #" . getBankAccountNum($_SESSION["user_id"]);?>
	    <br><br>
		<!-- <?php echo print_r(getTransactions($_SESSION["user_id"]));?> -->

		<div>
			<?php

				$transactions = getTransactions($_SESSION["user_id"]);

				echo "<table style=\"width:100%\">";

				// Get Headers
					echo '<tr>';
						echo '<th>' . "Transaction ID" . '</th>';
						echo '<th>' . "Account #" . '</th>';
						echo '<th>' . "Date" . '</th>';
						echo '<th>' . "Amount" . '</th>';
						echo '<th>' . "Description" . '</th>';
						echo '<th>' . "Status of Transaction" . '</th>';
					echo '</tr>';

				foreach($transactions as $key=>$val){

					// Populate Table
					echo '<tr>';
					foreach($val as $k=>$v){
						//get data
						echo '<td>' . $v . '</td>';
					}
					echo '</tr>';
				}

				echo "</table>";
			?>
		</div>

	</div>


	<?php
		// PROFILE DETAILS EDIT AVAILABLE TO ALL USERS
				echo'<br/>
				<hr style="height:1px;border-width:0;color:#CCD1D1;background-color:#CCD1D1">
				<br/>
				<div id="center_text"><h4>Update Profile Details</h4></div>

				  <form style="width:50%; margin:auto;" class="form-signin">
					<div class="mb-3">
						<label><strong>First Name</strong></label>
						<input class="form-control" type="text" name="change_first_name" placeholder="First Name" required/>

						<label><strong>Last Name</strong></label>
						<input class="form-control" type="text" name="change_last_name" placeholder="Last Name" required/>

						<label><strong>Email</strong></label>
						<input class="form-control" type="email" name="change_email" placeholder="Email" required/>

						</div><br>

						<button class="btn btn-primary btn-lg btn-block" type="submit">Submit</button>
					</form><br><br>';

		//checking for url

		// Data gathered. Sending Request to Functions.php

		$edit_id = $_SESSION['user_id'];
		$edit_first = $_REQUEST['change_first_name'];
		$edit_last = $_REQUEST['change_last_name'];
		$edit_email = $_REQUEST['change_email'];

		//echo $_SESSION['user_type'];

		if(! is_null($edit_first)){

			switch ($_SESSION['user_type']) {
				case '0':
					include_once 'db_credentials.php';
					$conn = OpenCon();

					$query_edit = "UPDATE Admin SET first_name = '$edit_first', last_name = '$edit_last', email = '$edit_email' WHERE admin_id = '$user_id';";

					mysqli_query($conn, $query_edit) or die(mysqli_error($conn));
					break;

				case '1':
					include_once 'db_credentials.php';
					$conn = OpenCon();

					$query_edit = "UPDATE Employer SET first_name = '$edit_first', last_name = '$edit_last', email = '$edit_email' WHERE employer_id = '$user_id';";

					mysqli_query($conn, $query_edit) or die(mysqli_error($conn));
					break;

				case '2':
					include_once 'db_credentials.php';
					$conn = OpenCon();

					$query_edit = "UPDATE User SET first_name = '$edit_first', last_name = '$edit_last', email = '$edit_email' WHERE user_id = '$user_id';";

					mysqli_query($conn, $query_edit) or die(mysqli_error($conn));
					break;

				default:
					echo 'Error!';
					break;
			}

			echo '

			<div class="alert alert-success" role="alert">
			 Profile Updated Successfully!
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			  </button>
			</div>

			';
		}

	?>


	<br/>

	<hr style="height:1px;border-width:0;color:#CCD1D1;background-color:#CCD1D1">

	<br/>

	<div id="center_text">

		<!-- password recovery -->
		<!-- <h4> Password Reset </h4> -->
		<h4><a href=\passwordRecovery.php>Password Reset</a></h4>

	</div>

	<?php
		if($_SESSION['user_type'] == 2){
			echo'<br/>
				<hr style="height:1px;border-width:0;color:#CCD1D1;background-color:#CCD1D1">
				<br/>
				<form style="width:50%; margin:auto;" action="deleteUserAccount.php">
					<div class="mb-3 text-center">
					<h4>Delete User Account</h4><br>
					<label><strong>Are you sure you want to delete your account?</strong></label>
	   				</div>

	   				<div class="form-check text-center">
						  <input class="form-check-input" type="radio" name="delete_user_id" id="delete_true" value="' .$_SESSION["user_id"].'">
						  <label class="form-check-label" for="radio_tr">
						    True
						  </label>
					</div><br>

					<button class="btn btn-danger btn-lg btn-block" type="submit">Delete Account</button>
				</form>
				';

		}

		// <div id="center_text">
		// 		<h4><a href=\deleteUserAccount.php?delete_user_id=' . $_SESSION['user_id'] . '> Delete User Account</a></h4>
		// 		</div>';
	?>

	<!-- new lines -->
	<br/><br/>
	<br/><br/>



	<!-- Pagination -->
	<ul class="pagination fixed-bottom justify-content-center">
		<?php
		if($current_page > 1) {
		echo "<li class=\"page-item\"> <a class='page-link' href=\"?".$get_url."current_page=1\"> &lsaquo;&lsaquo; First </a> </li>";
		echo "<li class=\"page-item\"> <a class='page-link' href=\"?".$get_url."current_page=$previous_page\"> Previous </a> </li>"; }
		?>

		<?php
 		for ($counter = 1; $counter <= $num_page; $counter++) {
 			if ($counter == $current_page) {
 				echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
         	}
         	else {
        		echo "<li class='page-item'><a class='page-link' href='?".$get_url."current_page=$counter'>$counter</a></li>";
            }
		} ?>

		<?php
		if($current_page < $num_page) { echo "<li class=\"page-item\"> <a class='page-link' href=\"?".$get_url."current_page=$next_page\"> Next </a> </li>"; }
		?>

		<?php if($current_page < $num_page){ echo "<li class=\"page-item\"><a class='page-link' href='?".$get_url."current_page=$num_page'>Last &rsaquo;&rsaquo;</a></li>"; } ?>
	</ul>


<?php
    require("footer.php");
?>
