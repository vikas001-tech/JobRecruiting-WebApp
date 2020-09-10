<?php
require('header.php');
session_start();

//require('db_credentials.php');
$email =null;
$subject = 'Password Recovery';
$txt = 'Your password recovery code for nxc353.encs.concordia.ca is : ';
$headers = 'From: nxc353.encs.concordia.ca' . "\r\n" ;

$email = $_POST['email'];
$user_type = $_POST['user_type'];
$conn = OpenCon();

$sql = null;

switch($user_type){
  case 0:
    $sql = "SELECT * FROM Admin WHERE email = '$email'";
  break;
  case 1:
    $sql = "SELECT * FROM Employer WHERE email = '$email'";
  break;
  case 2:
    $sql = "SELECT * FROM User WHERE email = '$email'";
  break;
}

$result = $conn -> query($sql)or die(mysqli_error($conn));

//if it returns a result
if ($result->num_rows > 0)
{
  //$row = $result->fetch_assoc();
//  $account = $row["account_number"];
  $code = rand(10000,99999);
  $_SESSION['code'] = $code;
  mail($email,$subject,$txt . $code ,$headers);
  //header("location: resetPassword.php");
  /*echo "<script type='text/javascript'>
        window.location.href = 'resetPassword.php';
        </script>";*/
        //echo ' ' .$_SESSION['code'];
  //exit();
}else{
  $_SESSION['errmsg'] = 'Email not found';
  header("location: passwordRecovery.php");
  /*echo "<script type='text/javascript'>
        window.location.href = 'passwordRecovery.php';
        </script>";*/
  exit();
}

?>

	<div class="py-5 text-center">
        <h2>Password Recovery</h2>
    </div>


	<!-- Form  #1 -->
	<form style="width:900px; margin:auto;" class="form-signin" action="resetPassword.php" method="post">

		<div class="mb-3">
			<label><strong>Please enter the code we sent to you </strong></label>
			<input class="form-control" type="number" name="code" required/>

      <label><strong>Please enter the new password </strong></label>
			<input class="form-control" type="password" name="password" required/>

      	<<?php
          echo '	<input type="hidden" name="email" value="' .$email .'"/> ';
          echo '	<input type="hidden" name="user_type" value="' .$user_type .'"/> ';
          echo '	<input type="hidden" name="generatedcode" value="' .$code.'"/> ';
         ?>
   		</div>



		<button class="btn btn-primary btn-lg btn-block" type="submit">Reset Password</button>
	</form>

</body>
</html>
