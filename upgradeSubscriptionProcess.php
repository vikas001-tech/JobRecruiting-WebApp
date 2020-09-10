<?php
session_start();
require('db_credentials.php');

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$subscription = $_POST['subscription'];
$account_number = $_POST['account_number'];
$card_number = $_POST['card_number'];
$type_of_account = $_POST['type_of_account'];
$automatic_payment = $_POST['automatic_payment'];

//echo ' subscribtion: ' . $subscription . ' acc num: ' . $account_number . ' card no: ' . $card_number . ' type of acc: ' . $type_of_account . ' automatic pay: ' . $automatic_payment . '<br>';

$conn = OpenCon();
$balance = rand(5,1000000);
$charge = null;

//checking if the bank info given already exidt in the table
$sql11 = "SELECT * From Bank_Account WHERE account_number = '$account_number' OR card_number = '$card_number'";
$result = mysqli_query($conn, $sql11) or die(mysqli_error($conn));

if ($result->num_rows > 0)
{
  header("location: failed_payment.php");
}

//determining the right query to use and the charge
if(1 == $user_type){

  //inserting bankinfo into the db
  $sql = "INSERT INTO Bank_Account (account_number, user_id, employer_id, balance, card_number, type_of_account, automatic_payment)
  VALUES ('$account_number', NULL, '$user_id', '$balance', '$card_number', '$type_of_account', '$automatic_payment');";

  //determining the charge
  switch ($subscription) {
     case 'Gold':
      $charge = 100;
     break;
     case 'Prime':
      $charge = 50;
     break;
   }

}
if(2 == $user_type){
  $sql = "INSERT INTO Bank_Account (account_number, user_id, employer_id, balance, card_number, type_of_account, automatic_payment)
  VALUES ('$account_number', '$user_id', NULL, '$balance', '$card_number', '$type_of_account', '$automatic_payment');";

  //determining the charge
  switch ($subscription) {
     case 'Gold':
      $charge = 20;
     break;
     case 'Prime':
      $charge = 10;
     break;
     case 'Basic':
      $charge = 0;
     break;
}
}
mysqli_query($conn, $sql) or die(mysqli_error($conn));

$_SESSION['charge'] = $charge;
$_SESSION['newSubscription'] = $subscription;
$_SESSION['account_number'] =$account_number;

header("location: manual_payment.php");

CloseCon($conn);
?>
