<?php
	session_start();
	error_reporting(E_ALL);

	// database and connection
	include_once 'db_credentials.php';
	$conn = OpenCon();


	// To successfully finish your signup, we process a payment.
	// 1. Get the User's information, id, subscription, account number, etc
	$user_type = $_SESSION['user_type'];

	// USER
	if($user_type == 2)
	{
		$user_id = $_SESSION['user_id'];
		$subscription = $_SESSION['subscription'];
		$account_number = $_SESSION['account_number'];

		
		// 2. Query his  bank account and find if he has enough money in his account.
		// then we write the user's data into the database
		$query = "SELECT * FROM Bank_Account WHERE user_id = '$user_id';";
		$result = $conn -> query($query)or die(mysqli_error($conn));
		$balance = NULL;
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			$balance = $row["balance"];
		}

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

		// 3. If he has enough money,I deduct the money, and I create a Transaction and mark it as paid.
		if($balance >= $amount)
		{
			// if he has enough balance to pay the subscription, I will keep him in the system, and I will create an entry in Transaction table, and reduce his balance in his account
			$today = date("Y-m-d");
			$sql1 = "INSERT INTO Transaction (account_number, date, amount, description, status_of_transaction)
			VALUES ('$account_number', '$today', '$amount', 'User Signup Subscription Paid', 'Paid');";
			mysqli_query($conn, $sql1) or die(mysqli_error($conn));


			// reduce balance in his bank account
			$new_balance = $balance - $amount;
			$sql2 = "UPDATE Bank_Account SET balance='$new_balance' WHERE account_number='$account_number';";
			mysqli_query($conn, $sql2) or die(mysqli_error($conn));


			header("location: success_payment.php");
			exit();
		}
		else
		{
			// 3.1 if he does not have enough balance, I will delete the user, the bank information, and destroy the session
			$sql1 = "DELETE FROM User WHERE user_id = '$user_id';";
			mysqli_query($conn, $sql1) or die(mysqli_error($conn));

			$sql2 = "DELETE FROM Bank_Account WHERE account_number = '$account_number';";
			mysqli_query($conn, $sql2) or die(mysqli_error($conn));

			session_destroy();

			header("location: failed_payment.php");
			exit();
		}

	} //user_type == 2 

	// EMPLOYER
	if($user_type == 1)
	{
		$employer_id = $_SESSION['employer_id'];
		$membership = $_SESSION['membership'];
		$account_number = $_SESSION['account_number'];

		
		// 2. Query his  bank account and find if he has enough money in his account.
		// then we write the user's data into the database
		$query = "SELECT * FROM Bank_Account WHERE employer_id = '$employer_id';";
		$result = $conn -> query($query)or die(mysqli_error($conn));
		$balance = NULL;
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			$balance = $row["balance"];
		}

		// amount we will check he has in his account
		$amount = NULL;
		if($membership == "Gold")
		{
			$amount = 100;
		}
		if($membership == "Prime")
		{
			$amount = 50;
		}

		// 3. If he has enough money,I deduct the money, and I create a Transaction and mark it as paid.
		if($balance >= $amount)
		{
			// WRITE CONTACT-INFO OF EMPLOYER
			$contact_email = $_SESSION['contact_email'];
			$contact_phone = $_SESSION['contact_phone'];
			$street = $_SESSION['street'];
			$city = $_SESSION['city'];
			$province = $_SESSION['province'];
			$country = $_SESSION['country'];
			$first_name = $_SESSION['first_name'];
			$last_name = $_SESSION['last_name'];

			$sql0 = "INSERT INTO Contact_Info (employer_id, first_name, last_name, contact_email, street, city, province, country, phone)
			VALUES ('$employer_id', '$first_name', '$last_name', '$contact_email', '$street', '$city', '$province', '$country', '$contact_phone');";
			mysqli_query($conn, $sql0) or die(mysqli_error($conn));


			// if he has enough balance to pay the subscription, I will keep him in the system, and I will create an entry in Transaction table, and reduce his balance in his account
			$today = date("Y-m-d");
			$sql1 = "INSERT INTO Transaction (account_number, date, amount, description, status_of_transaction)
			VALUES ('$account_number', '$today', '$amount', 'Employer Signup Subscription Paid', 'Paid');";
			mysqli_query($conn, $sql1) or die(mysqli_error($conn));


			// reduce balance in his bank account
			$new_balance = $balance - $amount;
			$sql2 = "UPDATE Bank_Account SET balance='$new_balance' WHERE account_number='$account_number';";
			mysqli_query($conn, $sql2) or die(mysqli_error($conn));


			header("location: success_payment.php");
			exit();
		}
		else
		{
			// 3.1 if he does not have enough balance, I will delete the user, the bank information, and destroy the session
			$sql1 = "DELETE FROM Employer WHERE employer_id = '$employer_id';";
			mysqli_query($conn, $sql1) or die(mysqli_error($conn));

			$sql2 = "DELETE FROM Bank_Account WHERE account_number = '$account_number';";
			mysqli_query($conn, $sql2) or die(mysqli_error($conn));

			session_destroy();

			header("location: failed_payment.php");
			exit();
		}

	} //user_type == 2 

		
?>

