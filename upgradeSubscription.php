<?php
session_start();
require_once('header.php');
require_once("db_credentials.php");
//require_once('functions.php');
//ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);

$conn = OpenCon();
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

//the query will vary depending on the user type
if(1 == $_SESSION['user_type']){
  $query = "SELECT * FROM Bank_Account WHERE employer_id = '$user_id' ";
//  echo "employer " . $user_id . " : ". $user_type;
}
if (2 == $_SESSION['user_type']){
  $query = "SELECT * FROM Bank_Account WHERE user_id = '$user_id' ";
//  echo "user";
}

$result = $conn -> query($query)or die(mysqli_error($conn));


if ($result->num_rows > 0)
{
  $row = $result->fetch_assoc();
  $account = $row["account_number"];
}



// Getting URI
$url = $_SERVER['REQUEST_URI'];

// Getting Components
$url_components = parse_url($url);

// Parsing Params
parse_str($url_components['query'], $url_params);

// radio pre-selection found in URL coming from settings.php
if(array_key_exists("subscription_radio", $url_params)){

  //determiningn how much will the user be charged
    //placing the pre-seelction into a variable
    $subscription = $url_params['subscription_radio'];
    $charge = null;
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

    //if we already have the users bank info, redirect to payment page
  if(!empty($account)){
    $_SESSION['charge'] = $charge;
    $_SESSION['newSubscription'] = $subscription;
    //header("location: manual_payment.php");
      echo "<script type='text/javascript'>
          window.location.href = 'manual_payment.php';
          </script>";
          //echo 'here: ' . $_SESSION['subscription'] . ' ' . $_SESSION['charge'];
    exit();
  }

?>
<hmtl>
<form style="width:900px; margin:auto;" class="form-signin" action="upgradeSubscriptionProcess.php" method="post">
  <div class="mb-3">

    <label><strong>Select a Subscription Plan </strong></label> <br>
    <!-- <input type="radio" id="Prime" name="subscription" value="Prime">
    <label for="Prime">Prime ($10/month): You can apply up to 5 jobs per month. </label><br>
    <input type="radio" id="Gold" name="subscription" value="Gold">
    <label for="Gold">Gold ($20/month): You can apply to as many jobs. </label><br> -->

    <?php


            switch ($url_params['subscription_radio']) {
              case "Prime":
                echo'<input type="radio" id="Prime" name="subscription" value="Prime" checked>
                     <label for="Prime">Prime &#127894 ($10/month): You can apply up to 5 jobs per month. </label><br>
                     <input type="radio" id="Gold" name="subscription" value="Gold">
                     <label for="Gold">Gold &#127942 ($20/month): You can apply to as many jobs. </label><br>
                     <input type="radio" id="Basic" name="subscription" value="Basic">
                     <label for="Basic">Basic &#128119 (Free): Can see jobs but cannot apply to them. </label><br>';
                break;
              case "Gold":
                echo'<input type="radio" id="Prime" name="subscription" value="Prime">
                     <label for="Prime">Prime &#127894 ($10/month): You can apply up to 5 jobs per month. </label><br>
                     <input type="radio" id="Gold" name="subscription" value="Gold" checked>
                     <label for="Gold">Gold &#127942 ($20/month): You can apply to as many jobs. </label><br>
                     <input type="radio" id="Basic" name="subscription" value="Basic">
                     <label for="Basic">Basic &#128119 (Free): Can see jobs but cannot apply to them. </label><br>';
                break;
              case "Basic":
                echo'<input type="radio" id="Prime" name="subscription" value="Prime">
                     <label for="Prime">Prime &#127894 ($10/month): You can apply up to 5 jobs per month. </label><br>
                     <input type="radio" id="Gold" name="subscription" value="Gold">
                     <label for="Gold">Gold &#127942 ($20/month): You can apply to as many jobs. </label><br>
                     <input type="radio" id="Basic" name="subscription" value="Basic" checked>
                     <label for="Basic">Basic &#128119 (Free): Can see jobs but cannot apply to them. </label><br>';
                break;
            }

        } else {
            // If no option found in the url, form is displayed in default mode
            echo'<input type="radio" id="Prime" name="subscription" value="Prime">
                <label for="Prime">Prime &#127894 ($10/month): You can apply up to 5 jobs per month. </label><br>
                <input type="radio" id="Gold" name="subscription" value="Gold">
                <label for="Gold">Gold &#127942 ($20/month): You can apply to as many jobs. </label><br>
                <input type="radio" id="Basic" name="subscription" value="Basic">
                     <label for="Basic">Basic &#128119 (Free): Can see jobs but cannot apply to them. </label><br>';
        }

    ?>

    <label><strong>Account Number</strong></label>
    <input class="form-control" type="text" name="account_number" required/>

    <label><strong>Card Number</strong></label>
    <input class="form-control" type="text" name="card_number" required/>

    <label><strong>Type of Account</strong></label> <br>
    <input type="radio" id="1" name="type_of_account" value="1">
    <label for="1">Debit</label><br>
    <input type="radio" id="2" name="type_of_account" value="2">
    <label for="2">Credit</label><br>
    <input type="radio" id="3" name="type_of_account" value="3">
    <label for="3">Savings</label><br>

    <!-- in the DB, this is a BOOLEAN attribute -->
    <label><strong>Automatic Payment (we charge you automatically at the due date of the month)</strong></label> <br>
    <input type="radio" id="yes" name="automatic_payment" value="1">
    <label for="yes">Yes</label><br>
    <input type="radio" id="no" name="automatic_payment" value="0">
    <label for="no">No</label><br>


    <!-- this is sent like this to checkout_process.php-->
    <input type="hidden" name="pay_now" value="yes"/>


    <!-- Make sure that we record the user_type: User is 2 -->
    <input type="hidden" name="user_type" value="2"/>

    </div>


  <button class="btn btn-primary btn-lg btn-block" type="submit">Submit</button>
</form>
</html>
