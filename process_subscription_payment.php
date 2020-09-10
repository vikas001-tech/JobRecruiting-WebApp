<?php
// start a session
session_start();
error_reporting(E_ALL);

// database and connection
include_once 'db_credentials.php';
$conn = OpenCon();

// IF enough funds in his account:
// 1. Create Transaction (success)
// 2. If he was frozen, make him no longer frozen: frozen_user = false
$user_type = $_SESSION['user_type'];
$email = $_SESSION['email'];
$account_number = NULL;

// USER
if($user_type == '2')
{
	$user_id = $_SESSION['user_id'];

	// subscription
	$query = "SELECT * FROM User WHERE user_id = '$user_id';";
	$result = $conn -> query($query);

	$subscription = NULL;
	if ($result->num_rows > 0)
	{
		// if login credentials were found, set session variables and redirect to dashboard
		$row = $result->fetch_assoc();
		$subscription = $row["subscription"];
	}

	mysqli_free_result($result);

	// balance
	$query = "SELECT * FROM Bank_Account WHERE user_id = '$user_id';";
	$result = $conn -> query($query);

	$balance = NULL;
	if ($result->num_rows > 0)
	{
		// if login credentials were found, set session variables and redirect to dashboard
		$row = $result->fetch_assoc();
		$balance = $row["balance"];
		$account_number = $row["account_number"];
	}
	//echo $subscription . $balance;

	// amount we will check he has in his account
	$amount = NULL;
	if($subscription == "Gold")
	{
		$amount = 20;
	}
	if($subscription == "Prime")
	{
		$amount = 10;
	}


	//If he has enough money,I deduct the money, and I create a Transaction and mark it as paid.
	if($balance >= $amount)
	{
		// if he has enough balance to pay the subscription, create a transaction entry
		$today = $today = date("Y-m-d");
		$sql1 = "INSERT INTO Transaction (account_number, date, amount, description, status_of_transaction)
		VALUES ('$account_number', '$today', '$amount', 'User Monthly Subscription Paid', 'Paid');";
		mysqli_query($conn, $sql1) or die(mysqli_error($conn));

		// reduce balance in his bank account
		$new_balance = $balance - $amount;
		$sql2 = "UPDATE Bank_Account SET balance='$new_balance' WHERE account_number='$account_number';";
		mysqli_query($conn, $sql2) or die(mysqli_error($conn));

		// if he was frozen, make him unfrozen
		$sql3 = "UPDATE User SET frozen_user='0' WHERE user_id = '$user_id';";
		mysqli_query($conn, $sql3) or die(mysqli_error($conn));


		header("location: success_payment.php");
		exit();
	}
	else
	{
		// 3.1 if he does not have enough balance, no transaction
		header("location: failed_payment.php");
		exit();
	}
}// USER


// EMPLOYER
if($user_type == '1')
{
	$employer_id = $_SESSION['employer_id'];

	// subscription
	$query = "SELECT * FROM Employer WHERE employer_id = '$employer_id';";
	$result = $conn -> query($query);

	$subscription = NULL;
	if ($result->num_rows > 0)
	{
		// if login credentials were found, set session variables and redirect to dashboard
		$row = $result->fetch_assoc();
		$subscription = $row["membership"]; // MEMBERSHIP
	}

	mysqli_free_result($result);

	// balance
	$query = "SELECT * FROM Bank_Account WHERE employer_id = '$employer_id';";
	$result = $conn -> query($query);

	$balance = NULL;
	if ($result->num_rows > 0)
	{
		// if login credentials were found, set session variables and redirect to dashboard
		$row = $result->fetch_assoc();
		$balance = $row["balance"];
		$account_number = $row["account_number"];
	}
	//echo $subscription . $balance;

	// amount we will check he has in his account
	$amount = NULL;
	if($subscription == "Gold")
	{
		$amount = 100;
	}
	if($subscription == "Prime")
	{
		$amount = 50;
	}


	//If he has enough money,I deduct the money, and I create a Transaction and mark it as paid.
	if($balance >= $amount)
	{
		// if he has enough balance to pay the subscription, create a transaction entry
		$today = $today = date("Y-m-d");
		$sql1 = "INSERT INTO Transaction (account_number, date, amount, description, status_of_transaction)
		VALUES ('$account_number', '$today', '$amount', 'Employer Monthly Subscription Paid', 'Paid');";
		mysqli_query($conn, $sql1) or die(mysqli_error($conn));

		// reduce balance in his bank account
		$new_balance = $balance - $amount;
		$sql2 = "UPDATE Bank_Account SET balance='$new_balance' WHERE account_number='$account_number';";
		mysqli_query($conn, $sql2) or die(mysqli_error($conn));

		// if he was frozen, make him unfrozen
		$sql3 = "UPDATE Employer SET frozen_user='0' WHERE employer_id = '$employer_id';";
		mysqli_query($conn, $sql3) or die(mysqli_error($conn));


		header("location: success_payment.php");
		exit();
	}
	else
	{
		// 3.1 if he does not have enough balance, no transaction
		header("location: failed_payment.php");
		exit();
	}
}// USER


?>
