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

  //redirecting to dashboard
  header("location: dashboard.php");
  exit();

}else{
  // destroy session, redirect back to login page
    mysqli_free_result($result);
    unset($_SESSION);
    session_destroy();
    require("login.php");
}
#unset($_SESSION);
#ession_destroy();
?>
