<?php
// start a session
session_start();
error_reporting(E_ALL);

// database and connection
include_once 'db_credentials.php';
$conn = OpenCon();

// instantiate session variables that are needed in the session
$_SESSION['user_type'] = $_POST['user_type'];
$_SESSION['pay_now'] = $_POST['pay_now'];
$_SESSION['email'] = $_POST['email'];
$_SESSION['first_name'] = $_POST['first_name'];
$_SESSION['last_name'] = $_POST['last_name'];

// variables needed for SQL statement
$user_type = $_SESSION['user_type'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$email = $_SESSION['email'];
$password = $_POST['password'];
$pay_now = $_SESSION['pay_now']; // works well either for longer form, or for the shortest form

$_SESSION['user_id'] = NULL;
$_SESSION['balance'] = NULL;

// extra variables if the user decided to pay (longer form)
if($pay_now == "yes")
{
	// $_SESSION['subscription'] = $_POST['subscription']; // may need to put it inside: if($user_type == "2")
	$_SESSION['account_number'] = $_POST['account_number'];
	$_SESSION['card_number'] = $_POST['card_number'];
	$_SESSION['type_of_account'] = $_POST['type_of_account'];
	$_SESSION['automatic_payment'] = $_POST['automatic_payment'];

	//$subscription = $_SESSION['subscription'];
	$account_number = $_SESSION['account_number'];
	$card_number = $_SESSION['card_number'];
	$type_of_account = $_SESSION['type_of_account'];
	$automatic_payment = $_SESSION['automatic_payment']; // NOTE: this is a BOOLEAN (must be 0 or 1 into sql DB)
}



// lookup in database: if email already exists, don't allow to signup
$already_exists = false;

if($user_type == "2")
{
	// clearing the result.
	//mysqli_free_result($result);
	// checking in the user email already exists in the database
	$query = "SELECT * FROM User WHERE email = '$email'";
	$result = $conn -> query($query);

	if ($result->num_rows > 0)
	{
		$already_exists = true;

		session_destroy();

		header("location: signup_fail_email.php");
		exit();
	}
}
if($user_type == "1")
{
	// clearing the result.
	//mysqli_free_result($result);
	// checking in the user email already exists in the database
	$query = "SELECT * FROM Employer WHERE email = '$email'";
	$result = $conn -> query($query);

	if ($result->num_rows > 0)
	{
		$already_exists = true;

		session_destroy();

		header("location: signup_fail_email.php");
		exit();
	}
}



// User Signup (could chose not to pay right away)
if($user_type == "2")
{
	// $_SESSION['subscription'] = $_POST['subscription']; // because we named the attribute different for Employer ...
	// $subscription = $_SESSION['subscription'];

	// subscription is set to Basic
	if($pay_now == "no")
	{
		// then we write the user's data into the database
		$sql = "INSERT INTO User (user_type, first_name, last_name, email, password, subscription, frozen_user)
		VALUES ('2', '$first_name', '$last_name', '$email', '$password', 'Basic', '0');";

		mysqli_query($conn, $sql);

		// clearing the result. I need to get the user_id
		mysqli_free_result($result);
		// checking in the user database
		$query = "SELECT * FROM User WHERE email = '$email' AND password = '$password'";
		$result = $conn -> query($query);

		if ($result->num_rows > 0)
		{
		  // if login credentials were found, set session variables and redirect to dashboard
		  $row = $result->fetch_assoc();
		  $_SESSION["user_id"] = $row["user_id"];

		  // redirecting to dashboard
		  header("location: dashboard.php");
		  exit();
		}
	}

	// subscription could be Prime or Gold, as chosen in the previous form
	if($pay_now == "yes")
	{
		$_SESSION['subscription'] = $_POST['subscription']; // because we named the attribute different for Employer ...
		$subscription = $_SESSION['subscription'];
		
		// 1. CREATE USER
		$sql1 = "INSERT INTO User (user_type, first_name, last_name, email, password, subscription, frozen_user)
		VALUES ('2', '$first_name', '$last_name', '$email', '$password', '$subscription', '0');";
		mysqli_query($conn, $sql1);

		// checking in the user database
		$query = "SELECT * FROM User WHERE email = '$email' AND password = '$password'";
		$result = $conn -> query($query);

		$user_id = NULL;
		if ($result->num_rows > 0)
		{
		  // if login credentials were found, set session variables
		  $row = $result->fetch_assoc();
		  $_SESSION["user_id"] = $row["user_id"];
		  // initialize user_id
		  $user_id = $_SESSION["user_id"];
		}

		// 2. CREATE HIS BANK ACCOUNT
		$balance = rand(5,1000000);
		$sql2 = "INSERT INTO Bank_Account (account_number, user_id, employer_id, balance, card_number, type_of_account, automatic_payment)
		VALUES ('$account_number', '$user_id', NULL, '$balance', '$card_number', '$type_of_account', '$automatic_payment');";
		mysqli_query($conn, $sql2) or die(mysqli_error($conn));


		// 3. Both, manual and automatic_payment must go through process_payment.php
		if($automatic_payment == "1")
		{
			header("location: process_payment.php");
			exit();
		} 
		else if ($automatic_payment == "0")
		{
			header("location: process_payment.php");
			exit();
		}
		
	}
}


// Employer Signup (must choose a membership and pay right away)
else if ($user_type == "1")
{
	$_SESSION['membership'] = $_POST['membership']; // because we named the attribute different for User: User has subscription rather than membership
	$membership = $_SESSION['membership'];

	// only missing to instantiate is contact-info variables
	$_SESSION['contact_email'] = $_POST['contact_email'];
	$_SESSION['contact_phone'] = $_POST['contact_phone'];
	$_SESSION['street'] = $_POST['street'];
	$_SESSION['city'] = $_POST['city'];
	$_SESSION['province'] = $_POST['province'];
	$_SESSION['country'] = $_POST['country'];
	

	// 1. CREATE EMPLOYER
	$sql1 = "INSERT INTO Employer (user_type, first_name, last_name, email, password, membership, frozen_user)
	VALUES ('1', '$first_name', '$last_name', '$email', '$password', '$membership', '0');";
	mysqli_query($conn, $sql1);

	// checking in the Employer database
	$query = "SELECT * FROM Employer WHERE email = '$email' AND password = '$password'";
	$result = $conn -> query($query);

	$employer_id = NULL;
	if ($result->num_rows > 0)
	{
	  // if login credentials were found, set session variables
	  $row = $result->fetch_assoc();
	  $_SESSION["employer_id"] = $row["employer_id"];
	  // initialize user_id
	  $employer_id = $_SESSION["employer_id"];
	}

	// fix of a bug ...
	$_SESSION['user_id'] = $employer_id;

	// 2. CREATE HIS BANK ACCOUNT
	$balance = rand(5,1000000);
	$sql2 = "INSERT INTO Bank_Account (account_number, user_id, employer_id, balance, card_number, type_of_account, automatic_payment)
	VALUES ('$account_number', NULL, '$employer_id', '$balance', '$card_number', '$type_of_account', '$automatic_payment');";
	mysqli_query($conn, $sql2) or die(mysqli_error($conn));


	// 3. Both, manual and automatic_payment must go through process_payment.php
	if($automatic_payment == "1")
	{
		header("location: process_payment.php");
		exit();
	} 
	else if ($automatic_payment == "0")
	{
		header("location: process_payment.php");
		exit();
	}

}



// TEST DESTROY SESSION
//session_destroy();
?>
