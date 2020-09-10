<?php
include("db_credentials.php");

//submits the query to the sql and returns the output in an array form
//use implode to convert the array into string (or other types) ex: implode(', ', query( string) )
function query($query){
  $conn = OpenCon();

  $result = $conn -> query($query);

//// NOTE: error checking does not work as of now
  if($result === FALSE){
    return "Null";
  }

  if($result === TRUE){
    return "True";
  }

  if ($result->num_rows > 0) {
    // if login credentials were found, set session variables and redirect to dashboard
    $row = $result->fetch_assoc();
      return $row;
}
$conn->close();
}

// returns if account is frozen or not
function getAccountStatus(int $user_id, int $user_type){
  $conn = OpenCon();

  switch ($user_type) {
   case 0: //admin
   $query = "SELECT * FROM Admin WHERE admin_id = '$user_id'";
   $result = $conn -> query($query);
     break;

   case 1: //Employer
   $query = "SELECT * FROM Employer WHERE employer_id = '$user_id'";
   $result = $conn -> query($query);
     break;

   case 2: //User
   $query = "SELECT * FROM User WHERE user_id = '$user_id'";
   $result = $conn -> query($query);
     break;
 }

 //checking that the returned query is not empty
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  return $row["frozen_user"];
}else {
  return NULL;
}
$conn->close();
}

function getBalance(int $user_id){

  $conn = OpenCon();

  $query = null;
  $balance = null;
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
    $balance = $row["balance"];

  }
  mysqli_free_result($result);
  CloseCon($conn);
  return $balance;
}

function getSubscriptionType(int $user_id, int $user_type){
  $conn = OpenCon();

  switch ($user_type) {
     case 0: //admin to fix!!
     echo 'admin';
     // $query = "SELECT * FROM Admin WHERE admin_id = '$user_id'";
     // $result = $conn -> query($query);
     //   break;

     case 1: //Employer
     $query = "SELECT * FROM Employer WHERE employer_id = '$user_id'";
     $result = $conn -> query($query);
        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          return $row["membership"];
        }else {
          return NULL;
        }
     break;

     case 2: //User
     $query = "SELECT * FROM User WHERE user_id = '$user_id'";
     $result = $conn -> query($query);
      if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          return $row["subscription"];
      }else {
          return NULL;
      }
 }

$conn->close();
}

function getAutomaticPayment(int $user_id, int $user_type){
  $conn = OpenCon();

  switch ($user_type) {
   case 0: //admin to fix!!
   echo 'admin';
   // $query = "SELECT * FROM Admin WHERE admin_id = '$user_id'";
   // $result = $conn -> query($query);
   //   break;

   case 1: //Employer
   $query = "SELECT * FROM Bank_Account WHERE employer_id = '$user_id'";
   $result = $conn -> query($query);
     break;

   case 2: //User
   $query = "SELECT * FROM Bank_Account WHERE user_id = '$user_id'";
   $result = $conn -> query($query);
     break;
 }

 //checking that the returned query is not empty
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  return $row["automatic_payment"];
}else {
  return NULL;
}
$conn->close();
}

function getName(int $user_id, int $user_type){
  $conn = OpenCon();

  switch ($user_type) {
   case 0: //admin
   $query = "SELECT * FROM Admin WHERE admin_id = '$user_id'";
   $result = $conn -> query($query);
     break;

   case 1: //Employer
   $query = "SELECT * FROM Employer WHERE employer_id = '$user_id'";
   $result = $conn -> query($query);
     break;

   case 2: //User
   $query = "SELECT * FROM User WHERE user_id = '$user_id'";
   $result = $conn -> query($query);
     break;
 }

 //checking that the returned query is not empty
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  return $row["first_name"] . " " . $row["last_name"];
}else {
  return NULL;
}
$conn->close();
}


function getJobsAppliedTo(int $user_id){
  $conn = OpenCon();

  $query = "SELECT * FROM Jobs_Applied_To WHERE user_id = '$user_id'";
  $result = $conn -> query($query);

  $jobs_stack = array();

  while ($row = $result->fetch_assoc()){
      $jobs_stack[] = $row;
  }

  return $jobs_stack;

$conn->close();
}


function getBankAccountNum(int $user_id){
  $conn = OpenCon();

  switch ($user_id) {
   case 0: //admin to fix!!
   echo 'admin';
   // $query = "SELECT * FROM Admin WHERE admin_id = '$user_id'";
   // $result = $conn -> query($query);
   //   break;

   case 1: //Employer
   $query = "SELECT * FROM Bank_Account WHERE employer_id = '$user_id'";
   $result = $conn -> query($query);
     break;

   case 2: //User
   $query = "SELECT * FROM Bank_Account WHERE user_id = '$user_id'";
   $result = $conn -> query($query);
     break;
 }

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return $row["account_number"];
  }else {
    echo 'null!';
    return NULL;
  }
$conn->close();
}

function getTransactions(int $user_id){
  $conn = OpenCon();

  $bank_num = getBankAccountNum($user_id);

  $query = "SELECT * FROM Transaction WHERE account_number = '$bank_num'";
  $result = $conn -> query($query);

  $transaction_stack = array();

  while ($row = $result->fetch_assoc()){
      $transaction_stack[] = $row;
  }

  return $transaction_stack;

$conn->close();
}

function getJobsPosted(){
  $conn = OpenCon();

  $query = "SELECT * FROM Jobs_Posted";
  $result = $conn -> query($query);

  $jobs_stack_posted = array();

  while ($row = $result->fetch_assoc()){
      $jobs_stack_posted[] = $row;
  }

  return $jobs_stack_posted;

$conn->close();
}

function getJobsPostedFiltered(string $url_filter){
  $conn = OpenCon();

  $query = "SELECT * FROM Jobs_Posted WHERE category = '$url_filter'";
  $result = $conn -> query($query);

  $jobs_stack_posted = array();

  while ($row = $result->fetch_assoc()){
      $jobs_stack_posted[] = $row;
  }

  return $jobs_stack_posted;

$conn->close();
}

function getJobsPostedKeyWord(string $url_keyword){
  $conn = OpenCon();

  $query = "SELECT * FROM Jobs_Posted WHERE job_name LIKE '%$url_keyword%' UNION SELECT * FROM Jobs_Posted WHERE description LIKE '%$url_keyword%' UNION SELECT * FROM Jobs_Posted WHERE category LIKE '%$url_keyword%' UNION SELECT * FROM Jobs_Posted WHERE field LIKE '%$url_keyword%'";

  $result = $conn -> query($query);

  $jobs_stack_posted = array();

  while ($row = $result->fetch_assoc()){
      $jobs_stack_posted[] = $row;
  }

  return $jobs_stack_posted;

$conn->close();
}


function getJobsPostedFilteredAndKeyWord(string $url_filter, string $url_keyword){
  $conn = OpenCon();

  $query = "SELECT * FROM Jobs_Posted WHERE job_name LIKE '%$url_keyword%' UNION SELECT * FROM Jobs_Posted WHERE description LIKE '%$url_keyword%' UNION SELECT * FROM Jobs_Posted WHERE category LIKE '%$url_filter%' UNION SELECT * FROM Jobs_Posted WHERE field LIKE '%$url_keyword%'";

  $result = $conn -> query($query);

  $jobs_stack_posted = array();

  while ($row = $result->fetch_assoc()){
      $jobs_stack_posted[] = $row;
  }

  return $jobs_stack_posted;

$conn->close();
}

function getAllUsers(){
  $conn = OpenCon();

  $query = "SELECT * FROM User";
  $result = $conn -> query($query);

  $users_stack = array();

  while ($row = $result->fetch_assoc()){
      $users_stack[] = $row;
  }

  return $users_stack;

$conn->close();
}

function getCountJobsApplied(int $user_id){
  $conn = OpenCon();

  $query = "SELECT COUNT(*) FROM Jobs_Applied_To WHERE user_id = '$user_id'";
  $result = $conn -> query($query);


  // return $result;

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return $row["COUNT(*)"];
  }else {
    return NULL;
  }


$conn->close();
}

function getCountJobsPosted(int $user_id){
  $conn = OpenCon();

  $query = "SELECT COUNT(*) FROM Jobs_Posted WHERE employer_id = '$user_id'";
  $result = $conn -> query($query);


  // return $result;

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return $row["COUNT(*)"];
  }else {
    return NULL;
  }


$conn->close();
}


function freezeUser(int $user_id){
  $conn = OpenCon();

  $query = "UPDATE User SET frozen_user = '1' WHERE user_id = '$user_id';";

  mysqli_query($conn, $query) or die(mysqli_error($conn));

  $conn->close();
}

function unfreezeUser(int $user_id){
  $conn = OpenCon();

  $query = "UPDATE User SET frozen_user = '0' WHERE user_id = '$user_id';";

  mysqli_query($conn, $query) or die(mysqli_error($conn));

  $conn->close();
}

function deleteUser(int $user_id){
  $conn = OpenCon();

  $query = "DELETE FROM User WHERE user_id = '$user_id';";

  mysqli_query($conn, $query) or die(mysqli_error($conn));

  $conn->close();
}

function getJobsCategories(){
  $conn = OpenCon();

  $query = "SELECT * FROM Jobs_Posted";
  $result = $conn -> query($query);

  $categories_stack = array();

  while ($row = $result->fetch_assoc()){
      $categories_stack[] = $row["category"];
  }
  // // Returns array of all Job Categories
  // print_r($categories_stack);

  // // Returns array of all UNIQUE Job Categories
  // return array_unique($categories_stack);

  // // Returns array of all UNIQUE AND NOT NULL AND REINDEXED Job Categories
  return array_values(array_filter(array_unique($categories_stack)));

$conn->close();
}

// TO DEBUG NOT WORKING HERE FOR SOME REASON
// function postNewJob(int $emp_id, string $name, date $date, int $num, string $category, string $description, string $field){
//   $conn = OpenCon();

//     $query = "INSERT INTO Jobs_Posted (employer_id, job_name, date_posted, needed_number, category, description, field) VALUES ('$emp_id', '$name', '$date', '$num', '$category', '$description', '$field');";

//     // if ($conn->query($sql) === TRUE) {
//     //   return "New Job Posting created successfully";
//     // } else {
//     //   return "Error: " . $sql . "<br>" . $conn->error;
//     // }

//    mysqli_query($conn, $query_post) or die(mysqli_error($conn));

//    return "New Job Posting created successfully";

//   $conn->close();
// }


// ex: updateVal(User, first_name,user_id, 11, newName)
function updateVal($tableName, $column, $identifier, $identifierValue, $newValue){
  $conn = OpenCon();

  $sql = "UPDATE $tableName SET $column = '$newValue' WHERE $identifier = '$identifierValue'";

  if ($conn->query($sql) === TRUE) {
  return "true";
} else {
  return "false";
}
$conn->close();
}



 ?>
