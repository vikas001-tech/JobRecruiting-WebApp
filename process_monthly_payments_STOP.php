<?php
// database and connection
include_once 'db_credentials.php';
$conn = OpenCon();

// must check also for manual payments, if they weren't done, then user will be frozen !!
// 1. for non-automatic payments, check if the last time they paid was more than 1 month ago (if he missed a payment, freeze the user account)
// 2. for the automatic payments, process the payment every 1 month (check bank balance, if not enough balance freeze them)

// execute code every day, and charge the people that owe money for that particular day:
// 1) automatic payments: if the user has not enough funds, then we freeze his account
// 2) for manual payments: if the last payment was 1 month ago (meaning the user didnt pay), then I freeze the account
// If the user was frozen before and he pays, we must change to non-frozen

echo "outside loop";
// we are gonna allow manual payments up to 2 days before the 30 day mark, and we will charge automatic_payment every 30 days.
while(true)
{
	// PART #1: check the User and Employer that has automatic_payment OFF
	$query = "SELECT account_number, user_id, employer_id FROM Bank_Account WHERE automatic_payment = '0';";
	$result = $conn -> query($query);
	$rows = resultToArray($result);
	
	echo var_dump($rows);
	$result->free();
	// rows has users and employers that have automatic_payment off



	// PART #2: 
	// to-do
	// automatic_payments = '1'

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
