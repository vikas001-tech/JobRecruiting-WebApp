<?php
// database and connection
include_once 'db_credentials.php';
$conn = OpenCon();

// must check also for manual payments, if they weren't done, then user will be frozen !!
// 1. for non-automatic payments, check if the last time they paid was more than 1 month ago (if he missed a payment, freeze the user account)
// 2. for the automatic payments, process the payment every 1 month (check bank balance, if not enough balance freeze them)

// we are gonna allow manual payments up to 2 days before the 30 day mark, and we will charge automatic_payment every 30 days.
while(true)
{
	// PART #1: check the User and Employer that has automatic_payment OFF
	$query = "SELECT account_number, user_id, employer_id FROM Bank_Account WHERE automatic_payment = '0';";
	$result = $conn -> query($query);
	$rows = resultToArray($result); // each entry has an entire row
	//echo var_dump($rows);
	$result->free();
	// rows has users and employers that have automatic_payment off
	$size = count($rows);

	for($i=0; $i < $size; $i++)
	{
		// users with automatic_payment OFF
		if( $rows[$i]['user_id']!= NULL &&  $rows[$i]['employer_id']== NULL)
		{
			//echo $rows[$i]['user_id'];
			//echo $rows[$i]['account_number'];

			$user_id = $rows[$i]['user_id']; 
			$account_number = $rows[$i]['account_number'];

			// check his last transaction, and see if it has been more than 1 month ago (PASSED DUE)
			$today = date("Y-m-d");
			$query2 = "SELECT * FROM Transaction WHERE account_number='$account_number' AND date < now() - interval 30 DAY;"; 
			$result = $conn -> query($query2);

			$account_number_passed_due = NULL;
			if ($result->num_rows > 0)
			{
				// if login credentials were found, set session variables and redirect to dashboard
				$row = $result->fetch_assoc();
				$account_number_passed_due = $row["account_number"];

				//echo $account_number_passed_due . " is passed due. ";
				//echo $user_id . " is user id. ";

				// freeze these accounts since they haven't made their payments
				$sql3 = "UPDATE User SET frozen_user='1' WHERE user_id = '$user_id';";
				mysqli_query($conn, $sql3) or die(mysqli_error($conn));
			}
			//mysqli_free_result($result);
			
		}

		// employers with automatic_payment OFF
		if( $rows[$i]['employer_id']!= NULL && $rows[$i]['user_id']== NULL)
		{
			//echo $rows[$i]['employer_id'];
			//echo $rows[$i]['account_number'];

			$employer_id = $rows[$i]['employer_id'];
			$account_number = $rows[$i]['account_number'];

			// check his last transaction
			// check his last transaction, and see if it has been more than 1 month ago (PASSED DUE)
			$today = date("Y-m-d");
			$query2 = "SELECT * FROM Transaction WHERE account_number='$account_number' AND date < now() - interval 30 DAY;"; 
			$result = $conn -> query($query2);

			$account_number_passed_due = NULL;
			if ($result->num_rows > 0)
			{
				// if login credentials were found, set session variables and redirect to dashboard
				$row = $result->fetch_assoc();
				$account_number_passed_due = $row["account_number"];

				// echo $account_number_passed_due . " is passed due. ";
				// echo $employer_id . " is employer id. ";

				// freeze these accounts since they haven't made their payments
				$sql3 = "UPDATE Employer SET frozen_user='1' WHERE employer_id = '$employer_id ';";
				mysqli_query($conn, $sql3) or die(mysqli_error($conn));
			}
			//mysqli_free_result($result);
		}
	}



	// PART #2: check user that has automatic_payment on, and charge them after 30 days (basically check that last time was exactly 30 days ago)
	// to-do
	// automatic_payments = '1'
	$query = "SELECT account_number, user_id, employer_id, balance FROM Bank_Account WHERE automatic_payment = '1';";
	$result = $conn -> query($query);
	$rows = resultToArray($result); // each entry has an entire row
	//echo var_dump($rows);
	$result->free();
	// rows has users and employers that have automatic_payment off
	$size = count($rows);

	for($i=0; $i < $size; $i++)
	{
		// users with automatic_payment ON
		if( $rows[$i]['user_id']!= NULL &&  $rows[$i]['employer_id']== NULL)
		{
			$user_id = $rows[$i]['user_id']; 
			$account_number = $rows[$i]['account_number'];
			$balance = $rows[$i]["balance"];

			//echo $account_number . " is account. ";
			//echo $user_id . " is automatic_payment user. ";

			// charge him if it has been 30 days
			$today = date("Y-m-d");
			$query2 = "SELECT * FROM Transaction WHERE account_number='$account_number' AND date = CURDATE() - interval 30 DAY;"; 
			$result = $conn -> query($query2);

			$account_number_pay_now = NULL;
			if ($result->num_rows > 0)
			{
				// if login credentials were found, set session variables and redirect to dashboard
				$row = $result->fetch_assoc();
				$account_number_pay_now = $row["account_number"];

				echo $account_number_pay_now . " must pay today";

				// get his subscription
				// checking in the user database
				$query = "SELECT * FROM User WHERE user_id = '$user_id'";
				$result = $conn -> query($query);
				if ($result->num_rows > 0)
				{
					// if login credentials were found, set session variables and redirect to dashboard
					$row = $result->fetch_assoc();
					$subscription = $row["subscription"];
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
				echo $amount . " for user. ";
				echo $balance . " is the balance in his account.";

				
				//If he has enough money,I deduct the money, and I create a Transaction and mark it as paid.
				if($balance >= $amount)
				{
					// if he has enough balance to pay the subscription, create a transaction entry
					$today = $today = date("Y-m-d");
					$sql1 = "INSERT INTO Transaction (account_number, date, amount, description, status_of_transaction)
					VALUES ('$account_number_pay_now', '$today', '$amount', 'User Automatic Payment Monthly Subscription', 'Paid');";
					mysqli_query($conn, $sql1) or die(mysqli_error($conn));

					// reduce balance in his bank account
					$new_balance = $balance - $amount;
					$sql2 = "UPDATE Bank_Account SET balance='$new_balance' WHERE account_number='$account_number_pay_now';";
					mysqli_query($conn, $sql2) or die(mysqli_error($conn));

					// if he was frozen, make him unfrozen
					$sql3 = "UPDATE User SET frozen_user='0' WHERE user_id = '$user_id';";
					mysqli_query($conn, $sql3) or die(mysqli_error($conn));

					// header("location: success_payment.php");
					// exit();
				}
				else
				{
					$sql3 = "UPDATE User SET frozen_user='1' WHERE user_id = '$user_id';";
					mysqli_query($conn, $sql3) or die(mysqli_error($conn));
				}
			}
		}

		// employers with automatic_payment ON
		if( $rows[$i]['employer_id']!= NULL && $rows[$i]['user_id']== NULL)
		{
			$employer_id = $rows[$i]['employer_id']; 
			$account_number = $rows[$i]['account_number'];
			$balance = $rows[$i]["balance"];

			//echo $account_number . " is account. ";
			//echo $user_id . " is automatic_payment user. ";

			// charge him if it has been 30 days
			$today = date("Y-m-d");
			$query2 = "SELECT * FROM Transaction WHERE account_number='$account_number' AND date = CURDATE() - interval 30 DAY;"; 
			$result = $conn -> query($query2);

			$account_number_pay_now = NULL;
			if ($result->num_rows > 0)
			{
				// if login credentials were found, set session variables and redirect to dashboard
				$row = $result->fetch_assoc();
				$account_number_pay_now = $row["account_number"];

				echo $account_number_pay_now . " must pay today";

				// get his subscription
				// checking in the user database
				$query = "SELECT * FROM Employer WHERE employer_id = '$employer_id'";
				$result = $conn -> query($query);
				if ($result->num_rows > 0)
				{
					// if login credentials were found, set session variables and redirect to dashboard
					$row = $result->fetch_assoc();
					$subscription = $row["membership"];
				}
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
				echo $amount . " for Employer. ";
				echo $balance . " is the balance in his account.";

				
				//If he has enough money,I deduct the money, and I create a Transaction and mark it as paid.
				if($balance >= $amount)
				{
					// if he has enough balance to pay the subscription, create a transaction entry
					$today = $today = date("Y-m-d");
					$sql1 = "INSERT INTO Transaction (account_number, date, amount, description, status_of_transaction)
					VALUES ('$account_number_pay_now', '$today', '$amount', 'Employer Automatic Payment Monthly Subscription', 'Paid');";
					mysqli_query($conn, $sql1) or die(mysqli_error($conn));

					// reduce balance in his bank account
					$new_balance = $balance - $amount;
					$sql2 = "UPDATE Bank_Account SET balance='$new_balance' WHERE account_number='$account_number_pay_now';";
					mysqli_query($conn, $sql2) or die(mysqli_error($conn));

					// if he was frozen, make him unfrozen
					$sql3 = "UPDATE Employer SET frozen_user='0' WHERE employer_id = '$employer_id';";
					mysqli_query($conn, $sql3) or die(mysqli_error($conn));

					// header("location: success_payment.php");
					// exit();
				}
				else
				{
					$sql3 = "UPDATE Employer SET frozen_user='1' WHERE employer_id = '$employer_id';";
					mysqli_query($conn, $sql3) or die(mysqli_error($conn));
				}
			}
		}
	
	} //FOR

    // Check every day
    //sleep(60 * 60 * 24 * 1);
    exit();
	sleep(20);
}

function resultToArray($result) 
{
    $rows = array();
    while($row = $result->fetch_assoc()) 
    {
        $rows[] = $row;
    }
    return $rows;
}

?>