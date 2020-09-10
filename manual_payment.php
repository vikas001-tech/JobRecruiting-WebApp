<?php
include('header.php');
session_start();
error_reporting(E_ALL|E_STRICT);

// optionally can be provided an amount to charge 'charge' or a bank account number 'BankAccount'
//if not provided, it determines the chargefrom the subscription the userhas, and the bank account
//from the database

//$bankAccount :
//$charge :
//$balance :
//Flocation :
//Slocation :
//Fdescription :
//Sdescription :

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$bankAccount = null;
$charge = null;
$Flocation = null;
$conn = OpenCon();
$balance = null;
$subscription = null;
$email = null;

//determining the bank account number
if(isset($_POST['account_number'])){
  $bankAccount = $_POST['account_number'];
  $balance = getBalance($user_id);
}else{

  if(isset($_SESSION['account_number'])){
    $bankAccount = $_SESSION['account_number'];
    $balance = getBalance($user_id);
  }else{

    $query = null;
    //the query will vary depending on the user type
    if(1 == $_SESSION['user_type']){
      $query = "SELECT * FROM Bank_Account WHERE employer_id = '$user_id' ";
    }
    if (2 == $_SESSION['user_type']){
      $query = "SELECT * FROM Bank_Account WHERE user_id = '$user_id' ";
    }

    $result = $conn -> query($query)or die(mysqli_error($conn));


    if ($result->num_rows > 0)
    {
      $row = $result->fetch_assoc();
      $bankAccount = $row["account_number"];
      $balance = $row["balance"];

    }
    mysqli_free_result($result);
  }

}

//finiding the email
if(isset($_POST['email'])){
  $email= $_POST['email'];
}else{

  if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
  }else{

    $query = null;
    //the query will vary depending on the user type
    if(1 == $_SESSION['user_type']){
      $query = "SELECT * FROM Bank_Account WHERE employer_id = '$user_id' ";
    }
    if (2 == $_SESSION['user_type']){
      $query = "SELECT * FROM Bank_Account WHERE user_id = '$user_id' ";
    }

    $result = $conn -> query($query)or die(mysqli_error($conn));


    if ($result->num_rows > 0)
    {
      $row = $result->fetch_assoc();
      $email = $row["email"];

    }
    mysqli_free_result($result);
  }

}
//determining the charge
if(isset($_POST['charge'])){
  $charge = $_POST['charge'];
}else{
  //if it doesnt exist in post,look for it in the session
    if(isset($_SESSION['charge'])){
      $charge = $_SESSION['charge'];
    }else{    //if in neither nor session, calculate it according to users subscription
      $subscription = getSubscriptionType($user_id, $user_type);

      //employer
      if(1 == $user_type){
        switch ($subscription) {
           case 'Gold':
            $charge = 100;
           break;
           case 'Prime':
            $charge = 50;
           break;
         }
       }
      // user
      if(2 == $user_type){
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

  }
}

//determining the subscription
if(isset($_POST['subscription'])){
  $subscription = $_POST['subscription'];
}else{
  //if it doesnt exist in post,look for it in the session
    if(isset($_SESSION['newSubscription'])){
      $subscription = $_SESSION['newSubscription'];
      //echo 'isset here ' . $subscription;
    }else{    //if in neither nor session, calculate it according to users subscription
      $subscription = getSubscriptionType($user_id, $user_type);

  }
}
//echo ' <br><br>user id : ' . $user_id . ' user_type : ' . $user_type . ' BankAccount : ' . $bankAccount .' charge : ' . $charge . ' balance : ' . $balance . ' subscription: ' . $subscription;

//determining failed redirect location
if(isset($_POST['Flocation'])){
  $Flocation = $_POST['Flocation'];
}else{
  $Flocation = 'failed_payment.php';
}

//determining success redirect location
if(isset($_POST['Slocation'])){
  $Slocation = $_POST['Slocation'];
}else{
  $Slocation = 'success_payment.php';
}
  //if insuffiecient balance
if($balance < $charge){

  // determining the faled description
  if(isset($_Post['Fdescription'])){
    $description = $_Post['Fdescription'];
  }else{
    $description = 'Insuficient Funds';
  }

  //recording the failed transaction
  $today = $today = date("Y-m-d");
  $sql = "INSERT INTO Transaction (account_number, date, amount, description, status_of_transaction)
  VALUES ('$bankAccount', '$today', '$charge', '$description', 'Failed');";
  mysqli_query($conn, $sql) or die(mysqli_error($conn));
  //header('Location: '. $Flocation);
  echo "<script type='text/javascript'>
        window.location.href = '" . $Flocation ."';
        </script>";
  exit();

}else{

//determining the succes description
  if(isset($_Post['Sdescription'])){
    $description = $_Post['Sdescription'];
  }else{
    $description = 'Successful payment';
  }

  // reduce balance in his bank account and update it in the table
  $new_balance = $balance - $charge;
  $sql2 = "UPDATE Bank_Account SET balance='$new_balance' WHERE account_number='$bankAccount';";
  mysqli_query($conn, $sql2) or die(mysqli_error($conn));

  //updating the user info (new subscription and frozen status)
  if(1 == $_SESSION['user_type']){

    $sql3 = "UPDATE Employer SET frozen_user ='0', membership = '$subscription' WHERE employer_id ='$user_id';";
    mysqli_query($conn, $sql3) or die(mysqli_error($conn));
  }
  if (2 == $_SESSION['user_type']){
    $sql3 = "UPDATE User SET frozen_user ='0', subscription = '$subscription' WHERE user_id ='$user_id';";
    mysqli_query($conn, $sql3) or die(mysqli_error($conn));
  }



  //recording the transaction
  $today = $today = date("Y-m-d");
  $sql = "INSERT INTO Transaction (account_number, date, amount, description, status_of_transaction)
  VALUES ('$bankAccount', '$today', '$charge', '$description', 'success');";
  mysqli_query($conn, $sql) or die(mysqli_error($conn));

  if(isset($email)) mail($email,'Charge','You have been charged ' . $charge.'$ for your subscription.','From: nxc353.encs.concordia.ca' . "\r\n");

  //header('Location: '. $Slocation);
  echo "<script type='text/javascript'>
        window.location.href = '" . $Slocation ."';
        </script>";
  exit();
}

?>
