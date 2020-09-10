<?php
//start the session
session_start();

include("db_credentials.php");

$conn = OpenCon();

//retrieving info and storing them in session variables
$_SESSION['email'] = $_POST['email'];
$_SESSION['password'] = $_POST['password'];


//creating local php variables for the session vars
$email = mysqli_real_escape_string($conn,$_POST['email']);
$userPass = mysqli_real_escape_string($conn,$_POST['password']);

//checking in the Admin database
$query = "SELECT * FROM Admin WHERE email = '$email' AND password = '$userPass'";
$result = $conn -> query($query);

if ($result->num_rows > 0) {
  // if login credentials were found, set session variables and redirect to dashboard
  $row = $result->fetch_assoc();
  $_SESSION["user_type"] = $row["user_type"];
  $_SESSION["user_id"] = $row["admin_id"];
  $_SESSION["admin_id"] = $row["admin_id"];

  // needed for header
  $_SESSION["first_name"] = $row["first_name"];
  $_SESSION["last_name"] = $row["last_name"];
  $_SESSION["user_type"] = $row["user_type"];


  //redirecting to dashboard
  header("location: dashboard.php");
  exit();
}

//clearing the result
mysqli_free_result($result);

//checking in the Employer database
$query = "SELECT * FROM Employer WHERE email = '$email' AND password = '$userPass'";
$result = $conn -> query($query);

if ($result->num_rows > 0) {
  // if login credentials were found, set session variables and redirect to dashboard
  $row = $result->fetch_assoc();
  $_SESSION["user_type"] = $row["user_type"];
  $_SESSION["user_id"] = $row["employer_id"];
  $_SESSION["employer_id"] = $row["employer_id"];

  // needed for header
  $_SESSION["first_name"] = $row["first_name"];
  $_SESSION["last_name"] = $row["last_name"];
  $_SESSION["user_type"] = $row["user_type"];

//echo "id: " . $_SESSION["user_id"] . " ";
  //redirecting to dashboard
  header("location: dashboard.php");
  exit();
}

//clearing the result
mysqli_free_result($result);

//checking in the user database
$query = "SELECT * FROM User WHERE email = '$email' AND password = '$userPass'";
$result = $conn -> query($query);

if ($result->num_rows > 0) {
  // if login credentials were found, set session variables and redirect to dashboard
  $row = $result->fetch_assoc();
  $_SESSION["user_type"] = $row["user_type"];
  $_SESSION["user_id"] = $row["user_id"];
  // needed for header
  $_SESSION["first_name"] = $row["first_name"];
  $_SESSION["last_name"] = $row["last_name"];
  $_SESSION["user_type"] = $row["user_type"];


  //redirecting to dashboard
  header("location: dashboard.php");
  exit();

}else{
  // destroy session, redirect back to login page
    mysqli_free_result($result);
    unset($_SESSION);
    session_destroy();
    header("location: login.php");
}
#unset($_SESSION);
#ession_destroy();
?>
