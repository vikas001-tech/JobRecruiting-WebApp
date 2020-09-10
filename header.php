<?php
session_start();
error_reporting(0);
include("functions.php");
?>
<!DOCTYPE html>
<html>

	<head>
		<title> nxc_353_1 Career Finder </title>

		<!-- Bootstrap css ***needs to go before other css files*** -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

		<link rel="stylesheet" type="text/css" href="generalStyle.css">
		<link rel="stylesheet" type="text/css" href="albumLayout.css">
		<link rel="stylesheet" type="text/css" href="header.css">

		<meta charset="utf-8">

		<script type="text/javascript" src="someJavaScriptFile.js">
		</script>

	</head>


	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation">


		  	<a class="navbar-brand" href="homePage.php">
		  		<img src="images/logo.png" height="100" width="100"> <!-- Logo Picture -->
		  	</a>

    		<!-- Important: show the name of the user name that is logged in (all the time, so must be on the header) -->
    		<!-- Conditions: showing Login and Signup buttons, or Logout button, depending on user logged in or not logged in -->
    		<?php
       			//session_start();
       			// we can't use user_id, since for employer we have employer_id
    			if($_SESSION['email'] != NULL)
    			{
    				//echo "Logged in as \"" . $_SESSION['username'] . "\"";

    				echo "<form class=\"form-inline my-2 my-lg-0\">
    						<a class=\"nav-link\" href=\"homePage.php\">Home</a>
    						<a class=\"nav-link\" href=\"contactUs.php\">Contact Us</a>
							<a class=\"nav-link\" href=\"dashboard.php\">Dashboard</a>
			    			</form>";

	  				// if logged in, user will be able to go to his/her profile page
	  				echo "<form class=\"form-inline my-2 my-lg-0\">
							<a class=\"nav-link float-right\" href=\"settings.php\">" . $_SESSION["first_name"] ." ".$_SESSION["last_name"] . "'s Profile Settings</a>
	  						</form> ";

	  				// database and connection
					include_once 'db_credentials.php';
					$conn = OpenCon();
	  				$email = $_SESSION['email'];
	  				$user_id = $_SESSION['user_id'];

	  				// if subscription!=basic, and automatic_payment is false, show make_payment.php
	  				if($_SESSION['user_type']=="2")
	  				{
		  				// subscription
		  				$query = "SELECT * FROM User WHERE email = '$email';";
						$result = $conn -> query($query);

						$subscription = NULL;
						if ($result->num_rows > 0)
						{
							// if login credentials were found, set session variables and redirect to dashboard
							$row = $result->fetch_assoc();
							$subscription = $row["subscription"];
							$_SESSION['subscription'] = $subscription;
						}
						//echo $subscription;

						mysqli_free_result($result);
						
						// automatic_payment
		  				$query = "SELECT * FROM Bank_Account WHERE user_id = '$user_id ';";
						$result = $conn -> query($query);

						$automatic_payment = NULL;
						if ($result->num_rows > 0)
						{
							// if login credentials were found, set session variables and redirect to dashboard
							$row = $result->fetch_assoc();
							$automatic_payment = $row["automatic_payment"];
						}
						//echo $automatic_payment;

						// user chooses manual payment, and has Prime or Gold subscription
		  				if($automatic_payment == '0' && $subscription!= "Basic")
		  				{
		  					echo "<form class=\"form-inline my-2 my-lg-0\">
							<a class=\"nav-link float-right\" href=\"make_subscription_payment.php\">Subscription Payment</a>
	  						</form> ";
		  				}

		  			}
		  			if($_SESSION['user_type']=="1")
	  				{
		  				// subscription
		  				$query = "SELECT * FROM Employer WHERE email = '$email';";
						$result = $conn -> query($query);

						$subscription = NULL;
						if ($result->num_rows > 0)
						{
							// if login credentials were found, set session variables and redirect to dashboard
							$row = $result->fetch_assoc();
							$subscription = $row["membership"];
							$_SESSION['subscription'] = $subscription;
						}
						//echo $_SESSION['subscription'];

						mysqli_free_result($result);
						
						// automatic_payment
		  				$query = "SELECT * FROM Bank_Account WHERE employer_id = '$user_id';";
						$result = $conn -> query($query);

						$automatic_payment = NULL;
						if ($result->num_rows > 0)
						{
							// if login credentials were found, set session variables and redirect to dashboard
							$row = $result->fetch_assoc();
							$automatic_payment = $row["automatic_payment"];
						}

						// user chooses manual payment, and has Prime or Gold subscription
		  				if($automatic_payment == '0' && $subscription!= "Basic")
		  				{
		  					echo "<form class=\"form-inline my-2 my-lg-0\">
							<a class=\"nav-link float-right\" href=\"make_subscription_payment.php\">Subscription Payment</a>
	  						</form> ";
		  				}
		  			}

		  			// if logged in, user will be able to logout
    				echo "<form class=\"form-inline my-2 my-lg-0\">
							<a class=\"nav-link float-right\" href=\"processLogout.php\">Logout</a>
	  						</form> ";


    			}
    			else
    			{
    				echo "<form class=\"form-inline my-2 my-lg-0\">
	    						<a class=\"nav-link\" href=\"homePage.php\">Home</a>
	    						<a class=\"nav-link\" href=\"contactUs.php\">Contact Us</a>

		  	 	 				<a class=\"nav-link\" href=\"login.php\">Login</a>

								<div class=\"dropdown\">
									<button class=\"btn btn-primary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
									Signup
									</button>
									<div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
									<a class=\"dropdown-item\" href=\"signUpEmployer.php\">As Employer</a>
									<a class=\"dropdown-item\" href=\"signUpUser.php\">As User</a>
									</div>
								</div>
							</form>";

					//user is not logged in
					unset($_SESSION);
					session_destroy();
    			}

		    ?>

		</div>
		</form>

		</nav>

		<!-- Messages below the navigation bar -->
		<p>

		</p>
