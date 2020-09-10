<?php
	require("header.php");
	//include("computeRating.php");
	//include("https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js");
?>

    <?php

		// Getting URI
		$url = $_SERVER['REQUEST_URI'];
		// Getting Components
		$url_components = parse_url($url);
		// Parsing Params
		parse_str($url_components['query'], $url_params);

		if(array_key_exists("delete_user_id", $url_params)){
			//echo 'delete ' . $_REQUEST["delete_user_id"];
			$del_id = $_REQUEST["delete_user_id"];

		// Actually delete user from database
		  include_once 'db_credentials.php';
		  $conn = OpenCon();

		  $query = "DELETE FROM User WHERE user_id = '$del_id';";

		  mysqli_query($conn, $query) or die(mysqli_error($conn));

		  $conn->close();
		}

		echo '

			<div class="alert alert-success" role="alert">
			  Account Deletion Successfull! You will be redirected to the home page.
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			  </button>
			</div>

			<meta http-equiv="refresh" content="2;url=processLogout.php" />
			';


	?>

	

	

</body>
</html>
