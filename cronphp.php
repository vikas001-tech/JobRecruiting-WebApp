#!/usr/bin/php
<?php
 include('/www/groups/n/nx_comp353_1/db_credentials.php');

$conn = OpenCon();

$sql1 = "SELECT * FROM Bank_Account WHERE user_id = 2;";

$result = $conn -> query($sql1)or die(mysqli_error($conn));

if ($result->num_rows > 0)
{
  $row = $result->fetch_assoc();
  $balance = $row["balance"];

}
mysqli_free_result($result);

$new_balance = $balance + 10;

$sql = "UPDATE Bank_Account SET balance = '$new_balance' WHERE user_id = 2;";
mysqli_query($conn, $sql) or die(mysqli_error($conn));


closeCon($conn);
exit();
?>
