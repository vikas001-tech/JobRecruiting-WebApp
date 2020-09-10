<?php
	session_start();
	require("header.php");
	include("computeRating.php");
	include("https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js");
?>
	<style>
		#pad_left {
			padding-left: 15px;
			padding-right: 15px;
		}
		#center_title {
		  margin: auto;
		  width: 75%;
		  position: center;
		  text-align: center;
		}
		#center_text {
		  margin: auto;
		  width: 97%;
		  position: center;
		  text-align: center;
		}
		#center_text_smaller {
		  margin: auto;
		  width: 65%;
		  position: center;
		  text-align: center;
		}
		#center_form {
		  margin: 0 auto;
    	  width: 30%;
		}
		#div-inline{
			display: inline-block;
		}
		#applications{
			padding-left: 50px;
		}
		#big_table_jobs{
			width: 100%;
		}
	</style>

	<br/><br/>

	<!-- Main content -->

	<div id="center_text">
		 <!-- Dashboard title should change dynamically depending on the type of user -->
		 <?php
		 	switch ($_SESSION['user_type']) {
		 		case 0: //admin
		 			echo "<h2><b> Admin Dashboard </b></h2> <br>";
		 			break;

		 		case 1: //Employer
		 			echo "<h2><b> Employer Dashboard </b></h2> <br>";
		 			break;

		 		case 2: //User
		 			echo "<h2><b> User Dashboard </b></h2> <br>";
		 			break;

		 	}
		 	//<h2><b> *User/Admin/Employer* Dashboard </b></h2> <br>
		 ?>
		<div id="center_form">
			<br><br>
			<h4> Job Availabilities </h4>
			<br>

			<!-- Populate from DB -->

			 <!-- Main Product Search Bar -->
			<form class="form-inline md-form mr-auto mb-4 inline-block">
			  <input class="form-control mr-sm-2" type="text" name="search" placeholder="Search"aria-label="Search">
			  <button class="btn btn-primary" type="submit">Search</button>
			</form>

			<!-- Category search button -->
				<div class="btn-group">
				  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    Category Filter
				  </button>
				  <div class="dropdown-menu inline-block" name="category_filter">
					<!-- Dropdown populated via Database -->
				    <?php
				    	$job_cat_arr = getJobsCategories();
					    // Generating dropdown
						for ($i = 0; $i < count($job_cat_arr); $i++){
							if(! is_null($job_cat_arr[$i])){
								echo "<a class=\"dropdown-item\" href=\"?filter=". $job_cat_arr[$i] ."\">" . $job_cat_arr[$i] . "</a>";
							} else {
								echo "<a class=\"dropdown-item\" href=\"#\">" . "null" . "</a>";
							}
						}
				    ?>

				  </div>
				</div>
			<br/><br/>

		</div>

			<!-- TEST categories from DB -->
			<!-- <?php echo print_r(getJobsCategories()); ?> -->

			<!-- <?php echo $_REQUEST['search_keyword'];	?> -->

			<br><br>

			<!-- Gets all Jobs available from DB -->
			<!-- <?php echo getJobsPosted(); ?> -->
		<div id="big_table_jobs">
			<?php 

				// Parsing URL for potential options #example

				// Getting URI
				$url = $_SERVER['REQUEST_URI'];

				// Getting Components
				$url_components = parse_url($url);

				// Parsing Params
				parse_str($url_components['query'], $url_params);

				// Base URI: '/dashboard.php' has no params
				if (! array_key_exists("filter", $url_params) and ! array_key_exists("search", $url_params)){
					//echo "nothing" . '<br><br>';;

					//gets ALL JOBS since no filter present
					echo '<b>Current Category</b> <br> All' . '<br><br>';
					$jobs_posted = getJobsPosted();

				// ONLY Filter Present '/dashboard.php?filter=Programming' has 1 param
				} else if (array_key_exists("filter", $url_params) and ! array_key_exists("search", $url_params)) {
					//echo "FILTER present" . '<br><br>'; ;
 
					//gets FILTERED JOBS since dropdown filter present
					$jobs_posted = getJobsPostedFiltered($url_params['filter']);
					echo '<b>Current Category</b><br> ' . $url_params['filter'] . '<br><br>';

				// ONLY Search Present '/dashboard.php?search=ciao' has 1 param
				} else if (! array_key_exists("filter", $url_params) and array_key_exists("search", $url_params)) {
					//echo "SEARCH present" . '<br><br>'; ;

					//gets Keyword FILTERED JOBS since dropdown filter present
					$jobs_posted = getJobsPostedKeyWord($url_params['search']);
					echo '<b>Current Keyword</b><br> ' . $url_params['search'] . '<br><br>';

				// Filter & Search BOTH Present '/dashboard.php?filter=Programming&search=ciao' has 2 params
				} else if (array_key_exists("filter", $url_params) and array_key_exists("search", $url_params)){
					//echo "FILTER & SEARCH present" . '<br><br>';;

					echo '<b>Current Category</b><br> ' . $url_params['filter'] . '<br><br>' . '<b>Current Keyword</b><br>' . $url_params['search'] . '<br><br>';

					$jobs_posted = getJobsPostedFilteredAndKeyWord($url_params['filter'], $url_params['search']);
				}

			
				// Making Jobs Table

				echo "<table style=\"width:100%\">";

				// Get Headers
					echo '<tr>';
						echo '<th>' . "Job ID" . '</th>';
						echo '<th>' . "Employer ID" . '</th>';
						echo '<th>' . "Job Name" . '</th>';
						echo '<th>' . "Date Posted" . '</th>';
						echo '<th>' . "Needed Number" . '</th>';
						echo '<th>' . "Number of Applicants" . '</th>';
						echo '<th>' . "Number of Accepted Applicants" . '</th>';
						echo '<th>' . "Category" . '</th>';
						echo '<th>' . "Description" . '</th>';
						echo '<th>' . "Field" . '</th>';
						echo '<th>' . "Status" . '</th>';
						echo '<th>' . "Number of Rejected Applicants" . '</th>';

						// Header: Apply to Jobs: Only visible to USER
						if($_SESSION['user_type'] == 2){
							echo '<th>' . "Apply Now" . '</th>';
						}

					echo '</tr>';

				foreach($jobs_posted as $key=>$val){ 

					// Populate Table
					echo '<tr>';
					foreach($val as $k=>$v){ 
						//get data
						echo '<td>' . $v . '</td>';

						//Storing job_id because needed for apply button below
						if ($k == "job_id"){
							$current_job_id = $v;
						}
					}
					
					// Button: Apply Now Button Only visible to USER that is ACTIVE (0) and NOT BASIC and MAX APPLICATIONS < 5
					if($_SESSION['user_type'] == 2){
						//echo "STATUS" . getAccountStatus($_SESSION["user_id"],$_SESSION["user_type"]));

						if(getSubscriptionType($_SESSION["user_id"],$_SESSION["user_type"]) == 'Gold'){
							echo '<th>' . '<a type="button" class="btn btn-primary text-white" href="?apply_job_id=' . $current_job_id . '"> Apply</a>' . '</th>'; 
						}else{
							if(getSubscriptionType($_SESSION["user_id"],$_SESSION["user_type"]) == 'Prime' and getCountJobsApplied($_SESSION["user_id"]) < 5) {

								if(getAccountStatus($_SESSION["user_id"],$_SESSION["user_type"]) == 0 and getSubscriptionType($_SESSION["user_id"],$_SESSION["user_type"]) != 'Basic'){
									echo '<th>' . '<a type="button" class="btn btn-primary text-white" href="?apply_job_id=' . $current_job_id . '"> Apply</a>' . '</th>'; 
								} else {
									echo '<td>' . 'Account Frozen / Basic !' . '</td>';  
								}
							} else {
								echo '<td>' . 'Limit Reached!' . '</td>'; 
							}
						}
					}
					echo '</tr>';
				}

				echo "</table>";

				// If Apply job query filter in URL found update jobs in DB

				// Getting URI
				$url_apply = $_SERVER['REQUEST_URI'];
				// Getting Components
				$url_components_apply = parse_url($url);
				// Parsing Params
				parse_str($url_components_apply['query'], $url_params_apply);

				if(array_key_exists("apply_job_id", $url_params_apply)){
					//echo "Job ID FOUND! " . $_REQUEST['apply_job_id'] . '<br>';

					//Gathering variables
					$apply_user_id = $_SESSION['user_id'];
					$apply_job_id = $_REQUEST['apply_job_id'];
					$apply_job_date = date("Y-m-d");
					$acceptance_status = "pending";

					if(! is_null($apply_job_id)){
						include_once 'db_credentials.php';
						$conn = OpenCon();

						$query_post = "INSERT INTO Jobs_Applied_To (user_id, job_id, application_date, acceptance_status) VALUES ('$apply_user_id', '$apply_job_id', '$apply_job_date', '$acceptance_status');";

						mysqli_query($conn, $query_post) or die(mysqli_error($conn));

						echo '

						<div class="alert alert-success" role="alert">
						  Applied to Job Successfully!
						  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						    <span aria-hidden="true">&times;</span>
						  </button>
						</div>

						';
					}

				}
			?>
		</div>	
	</div>
		

	<br/><br/>
	<hr style="height:1px;border-width:0;color:#CCD1D1;background-color:#CCD1D1">
	<br/><br/>

	<?php
	 // Only visible to USER
		if($_SESSION['user_type'] == 2){
			echo	'

					<div id="center_text_smaller">
					<!-- Application History -->
					<h4> Application History </h4>
					<br/>

					<h6> Jobs Applied To </h6><br/>
						<!-- Populate from DB -->
					';

						 //echo 'num jobs applied to = ' . getCountJobsApplied($_SESSION["user_id"]) . '<br>';
						
							$jobs_applied_arr = getJobsAppliedTo($_SESSION["user_id"]);

							echo "<table style=\"width:100%\">";

							// Get Headers
								echo '<tr>';
									echo '<th>' . "User ID" . '</th>';
									echo '<th>' . "Job ID" . '</th>';
									echo '<th>' . "Application Date" . '</th>';
									echo '<th>' . "Acceptance Status" . '</th>';
									echo '<th>' . "Response to Employer's Offer" . '</th>';
									echo '<th>' . "Accept Offer" . '</th>';
									echo '<th>' . "Reject Offer" . '</th>';
								echo '</tr>';

							foreach($jobs_applied_arr as $key=>$val){ 

								// Populate Table
								echo '<tr>';
								foreach($val as $k=>$v){ 
									//get data

									//Storing job_id because needed for apply button below
									if ($k == "job_id"){
										$resp_offer_job_id = $v;
									} 
									if ($k == "user_id") {
										$resp_offer_user_id = $v;
									}
									if ($k == "response_to_employer_offer") {
										$resp_emp_offer_status = $v;
									}

									switch ($v) {
									  case "accepted":
									    echo '<td>' . 'Accepted  &#9989' . '</td>';
									    break;
									  case "pending":
									    echo '<td>' . 'Pending  &#9203' . '</td>';
									    break;
									  case "rejected":
									    echo '<td>' . 'Rejected  &#10060' . '</td>';
									    break;
									  case "accept":
									    echo '<td>' . 'Accepted  &#9989' . '</td>';
									    break;
									  case "waiting":
									    echo '<td>' . 'Pending  &#9203' . '</td>';
									    break;
									  case "reject":
									    echo '<td>' . 'Rejected  &#10060' . '</td>';
									    break;
									  default:
									    echo '<td>' . $v . '</td>';
									}

								}

								// Check if already responded
								if ($resp_emp_offer_status != 'accept' and $resp_emp_offer_status != 'reject'){

								// Buttons to Respond
								echo '<th>' . '<a type="button" class="btn btn-success text-white" href="?respond_offer_job_id=' . $resp_offer_job_id . '&respond_user_id=' . $resp_offer_user_id .'&respond_job_offer=accept"> Accept </a>' . '</th>';
								echo '<th>' . '<a type="button" class="btn btn-danger text-white" href="?respond_offer_job_id=' . $resp_offer_job_id . '&respond_user_id=' . $resp_offer_user_id . '&respond_job_offer=reject"> Reject </a>' . '</th>';

								} else {
									echo '<td>' . 'Already Replied!' . '</td>';
									echo '<td>' . 'Already Replied!' . '</td>';
								}

								echo '</tr>';
							}
								echo "</table>";


				// Processing URL to see if request present to accept/reject job offer
				// Getting URI
				$url_apply = $_SERVER['REQUEST_URI'];
				// Getting Components
				$url_components_respond = parse_url($url);
				// Parsing Params
				parse_str($url_components_respond['query'], $url_params_respond);

				if(array_key_exists("respond_job_offer", $url_params_respond)){

					//Gathering variables

					$resp_user_id_off = $_REQUEST['respond_user_id'];

					$var_u_id = $_REQUEST['respond_user_id'];
					$var_j_id = $_REQUEST['respond_offer_job_id'];

					// echo 'resp user id: ' . $var_u_id . '<br>';
					// echo 'resp job id: ' . $var_j_id . '<br>';
					// echo 'offer :' . $_REQUEST['respond_job_offer'] . '<br>';

					// $resp_offer_job_id
					// $resp_offer_user_id

					switch ($_REQUEST['respond_job_offer']) {
						case 'accept':

							$offer_resp_emp = 'accept';

							include_once 'db_credentials.php';
							$conn = OpenCon();

							 // getting job ids matching to logged in employer
							 $respond_query = "UPDATE Jobs_Applied_To SET response_to_employer_offer = '$offer_resp_emp' WHERE job_id = '$var_j_id' AND user_id = '$var_u_id';";

		   					 mysqli_query($conn, $respond_query) or die(mysqli_error($conn));


		   					 // if also update jobs_posted table (number_accepted_applications)


							break;

						case 'reject':

							$offer_resp_emp = 'reject';

							include_once 'db_credentials.php';
							$conn = OpenCon();

							 // getting job ids matching to logged in employer
							 $respond_query = "UPDATE Jobs_Applied_To SET response_to_employer_offer = '$offer_resp_emp' WHERE job_id = '$var_j_id' AND user_id = '$var_u_id';";

		   					 mysqli_query($conn, $respond_query) or die(mysqli_error($conn));


		   					// if also update jobs_posted table (number_rejected_applications)

							break;
						default:
							//clearing variables !!!
							$resp_offer_job_id = '';
							$resp_offer_user_id = '';
							break;
					}

					echo '

						<div class="alert alert-success" role="alert">
						  Responded to Job Successfully!
						  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						    <span aria-hidden="true">&times;</span>
						  </button>
						</div>

						';
				}
					
					echo '		
							<br/><br/>
							</div>
						 ';
			
			// Contact Us Employer Helpline - User Side
			echo'
				<br/>
				<hr style="height:1px;border-width:0;color:#CCD1D1;background-color:#CCD1D1">
				<br/><br/>
				<div id="center_text">
						<h4> Contact an Employer Helpline </h4> <br/><br>
				</div>
					<form style="width:900px; margin:auto;" class="form-signin">
						<div class="mb-3">
						<label><strong>Employer Name</strong></label>
						<input class="form-control" type="text" name="emp_name_contact" placeholder="Employer Full Name" required/><br>
						<label><strong>Message</strong></label>
								<textarea class="form-control" type="text" name="user_message_contact" required/></textarea><br>
						<button class="btn btn-primary btn-lg btn-block" type="submit">Send Message</button>
					</form>
				
			';
		
		}
	?>


	<?php
		// Only visible to EMPLOYER
			if($_SESSION['user_type'] == 1){

				// ACCEPT / DENY USERS 

				echo '
					  <div id="center_text">
							<h4> Review User Applications </h4> <br/><br>
					  </div>';

				// Get job applications that correspond to the emp_id

				$app_emp_id = $_SESSION['user_id'];

				if(! is_null($app_emp_id)){

					include_once 'db_credentials.php';
					$conn = OpenCon();

					 // getting job ids matching to logged in employer
					 $app_query = "SELECT user_id, job_id, application_date FROM Jobs_Applied_To WHERE job_id IN (SELECT job_id FROM Jobs_Posted WHERE employer_id = '$app_emp_id') and acceptance_status = 'pending';";
   					 $result = $conn -> query($app_query);

   					 $emp_jobs_respond = array();

					  while ($row = $result->fetch_assoc()){     
					      $emp_jobs_respond[] = $row;
					  }
   					 
					$conn->close();

				}

				echo "<table id=\"center_text\" style=\"width:50%\">";

							// Get Headers
								echo '<tr>';
									echo '<th>' . "User ID" . '</th>';
									echo '<th>' . "Job ID" . '</th>';
									echo '<th>' . "Application Date" . '</th>';
									echo '<th>' . "Accept" . '</th>';
									echo '<th>' . "Reject" . '</th>';
								echo '</tr>';

							foreach($emp_jobs_respond as $key=>$val){ 

								// Populate Table

								echo '<tr>';
								foreach($val as $k=>$v){ 
									//get data
									echo '<td>' . $v . '</td>';

									//Storing job_id because needed for apply button below
									if ($k == "job_id"){
										$emp_job_id_respond = $v;
									} 
									if ($k == "user_id") {
										$emp_user_id_respond = $v;
									}
								}
								
								echo '<th>' . '<a type="button" class="btn btn-success text-white" href="?respond_job_id=' . $emp_job_id_respond . '&respond_user_id=' . $emp_user_id_respond .'&respond_job_status=accepted"> Accept </a>' . '</th>';
								echo '<th>' . '<a type="button" class="btn btn-danger text-white" href="?respond_job_id=' . $emp_job_id_respond . '&respond_user_id=' . $emp_user_id_respond . '&respond_job_status=rejected"> Reject </a>' . '</th>';
								
								echo '</tr>';
							}
								echo "</table>";

				// If review user applications query filter in URL found update jobs in DB

				// Getting URI
				$url_apply = $_SERVER['REQUEST_URI'];
				// Getting Components
				$url_components_respond = parse_url($url);
				// Parsing Params
				parse_str($url_components_respond['query'], $url_params_respond);

				if(array_key_exists("respond_job_status", $url_params_respond)){

					//Gathering variables

					$respond_user_id = $_REQUEST['respond_user_id'];
					// $emp_job_id_respond
					// $app_emp_id = $_SESSION['user_id'];


					// echo '$respond_user_id ' . $respond_user_id . '<br>';
					// echo '$emp_job_id_respond ' . $emp_job_id_respond . '<br>';
					// echo '$app_emp_id ' . $app_emp_id . '<br>';

					switch ($_REQUEST['respond_job_status']) {
						case 'accepted':

							$resp_acceptance_status = "accepted";

							include_once 'db_credentials.php';
							$conn = OpenCon();

							 // getting job ids matching to logged in employer
							 $respond_query = "UPDATE Jobs_Applied_To SET acceptance_status = '$resp_acceptance_status' WHERE job_id = '$emp_job_id_respond' AND user_id = '$respond_user_id';";

		   					 mysqli_query($conn, $respond_query) or die(mysqli_error($conn));


		   					 // if also update jobs_posted table (number_accepted_applications)


							break;

						case 'rejected':

							$resp_acceptance_status = "rejected";

							include_once 'db_credentials.php';
							$conn = OpenCon();

							 // getting job ids matching to logged in employer
							 $respond_query = "UPDATE Jobs_Applied_To SET acceptance_status = '$resp_acceptance_status' WHERE job_id = '$emp_job_id_respond' AND user_id = '$respond_user_id';";

		   					 mysqli_query($conn, $respond_query) or die(mysqli_error($conn));


		   					// if also update jobs_posted table (number_rejected_applications)

							break;
					}

					echo '

						<div class="alert alert-success" role="alert">
						  Responded to Job Successfully!
						  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						    <span aria-hidden="true">&times;</span>
						  </button>
						</div>

						';
				}

				echo '<br/>
					  <hr style="height:1px;border-width:0;color:#CCD1D1;background-color:#CCD1D1">
					  <br/><br/>';


				// POST JOBS
				// $post_emp_id = $_SESSION['user_id'];
				// echo $post_emp_id . '<br>';
				// $emp_sub_type = getSubscriptionType($_SESSION["user_id"],$_SESSION["user_type"]);

				// echo 'type ' . $emp_sub_type . '<br>';

				// echo 'count ' . getCountJobsPosted($post_emp_id); 

				// $emp_job_count = getCountJobsPosted($post_emp_id); 

				echo '<div id="center_text">
							<h4> Post a new Job Offer </h4> <br/><br>
					  </div>';
				
				// if(($emp_sub_type == 'Gold') or ($emp_job_count < 5)) {

						echo '<form style="width:900px; margin:auto;" class="form-signin">
								<div class="mb-3">
									<label><strong>Job Name</strong></label>
									<input class="form-control" type="text" name="post_job_name" placeholder="Job Name" required/>

									<label><strong>Number of applicants needed</strong></label>
									<input class="form-control" type="number" name="post_job_num_app_needed" placeholder="Number of applicants" required/>

									<label><strong>Job field</strong></label>
									<input class="form-control" type="text" name="post_job_field" placeholder="Job Field" required/>	

									<label><strong>Category</strong></label>
									<input class="form-control" type="text" name="post_job_category" placeholder="Please write one of the already present categories below or create a new one" required/>	
									  <div btn-group id="center_text"><br>';

										    $job_cat_arr = getJobsCategories();
										    // Generating dropdown
											for ($i = 0; $i < count($job_cat_arr); $i++){
												if(! is_null($job_cat_arr[$i])){
													echo "<a class=\"dropdown-item\">" . $job_cat_arr[$i] . "</a>";
												} else {
													echo "<a class=\"dropdown-item\">" . "null" . "</a>";
												}
											}

									  echo '</div><br>';

						echo '				    		

									<label><strong>Job Description</strong></label>
									<textarea class="form-control" type="text" name="post_job_description" required/></textarea>
								</div>

								<button class="btn btn-primary btn-lg btn-block" type="submit">Submit Job</button>
							</form><br><br>';
				// } else {
				// 	echo 'Job Limit Reached! Upgrade Account to Gold in Settings';
				// }
			}


			// Data gathered. Sending Request to Functions.php

			$user_id = $_SESSION['user_id'];
			$post_job_name = $_REQUEST['post_job_name'];
			$post_job_date = date("Y-m-d");
			$post_job_num = $_REQUEST['post_job_num_app_needed'];
			$post_job_cat = $_REQUEST['post_job_category'];
			$post_job_desc = $_REQUEST['post_job_description'];
			$post_job_field = $_REQUEST['post_job_field'];

			if(! is_null($post_job_num)){
				include_once 'db_credentials.php';
				$conn = OpenCon();

				$query_post = "INSERT INTO Jobs_Posted (employer_id, job_name, date_posted, needed_number, category, description, field) VALUES ('$user_id', '$post_job_name', '$post_job_date', '$post_job_num', '$post_job_cat', '$post_job_desc', '$post_job_field');";

				mysqli_query($conn, $query_post) or die(mysqli_error($conn));

				// To fix does not work there
				//postNewJob($user_id, $post_job_name, $post_job_date, $post_job_num, $post_job_cat, $post_job_desc, $post_job_field);

				echo '

				<div class="alert alert-success" role="alert">
				  Job Post added successfully!
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				    <span aria-hidden="true">&times;</span>
				  </button>
				</div>

				';
			}

		if($_SESSION['user_type'] == 1){
			// Contact Us Employer Helpline
			echo'
				<br/>
				<hr style="height:1px;border-width:0;color:#CCD1D1;background-color:#CCD1D1">
				<br/><br/>
				<div id="center_text">
						<h4> Contact User Helpline </h4> <br/><br>
				</div>
					<form style="width:900px; margin:auto;" class="form-signin">
						<div class="mb-3">
						<label><strong>User Full Name</strong></label>
						<input class="form-control" type="text" name="user_name_contact" placeholder="User Full Name" required/><br>
						<label><strong>Message</strong></label>
								<textarea class="form-control" type="text" name="user_message_contact" required/></textarea><br>
						<button class="btn btn-primary btn-lg btn-block" type="submit">Send Message</button>
					</form>
				
			';
		}

	?>

	<?php
	 // Only visible to ADMIN
		if($_SESSION['user_type'] == 0){
			echo	'
			         <div id="center_text">
						<h4> User Maintenance </h4> <br/>
					 </div>';

			// Getting all user table and  
			$adm_all_users = getAllUsers();

			echo "<table id=\"center_text\" style=\"width:90%\">";

							// Get Headers
								echo '<tr>';
									echo '<th>' . "User ID" . '</th>';
									echo '<th>' . "User Type" . '</th>';
									echo '<th>' . "Fisrt Name" . '</th>';
									echo '<th>' . "Last Name" . '</th>';
									echo '<th>' . "Email" . '</th>';
									echo '<th>' . "Password" . '</th>';
									echo '<th>' . "Subscription" . '</th>';
									echo '<th>' . "Frozen User" . '</th>';
									echo '<th>' . "Unfreeze User" . '</th>';
									echo '<th>' . "Freeze User" . '</th>';
									echo '<th>' . "Delete User" . '</th>';
								echo '</tr>';

							foreach($adm_all_users as $key=>$val){ 

								// Populate Table

								echo '<tr>';
								foreach($val as $k=>$v){ 
									//get data
									echo '<td>' . $v . '</td>';

									//Storing job_id because needed for apply button below
									if ($k == "user_id") {
										$adm_user_id_maintenance = $v;
									}
								}
								echo '<th>' . '<a type="button" class="btn btn-success text-white" href="?adm_usr_unfreeze=' . $adm_user_id_maintenance . '"> Unfreeze </a>' . '</th>';
								echo '<th>' . '<a type="button" class="btn btn-danger text-white" href="?adm_usr_freeze=' . $adm_user_id_maintenance . '"> Freeze </a>' . '</th>';
								echo '<th>' . '<a type="button" class="btn btn-danger text-white" href="?adm_usr_delete=' . $adm_user_id_maintenance . '"> Delete </a>' . '</th>';
								
								echo '</tr>';
							}
			echo "</table>";

			// If user maintenance query filter in URL found update jobs in DB

				// Getting URI
				$url_maint = $_SERVER['REQUEST_URI'];
				// Getting Components
				$url_components_maint = parse_url($url);
				// Parsing Params
				parse_str($url_components_maint['query'], $url_params_maint);

				if(array_key_exists("adm_usr_freeze", $url_params_maint)){

					//Gathering variables

					$maint_user_id = $_REQUEST['adm_usr_freeze'];

					freezeUser($maint_user_id);


				} else if (array_key_exists("adm_usr_unfreeze", $url_params_maint)) {

					$maint_user_id = $_REQUEST['adm_usr_unfreeze'];

					unfreezeUser($maint_user_id);

				} else if (array_key_exists("adm_usr_delete", $url_params_maint)) {

					$maint_user_id = $_REQUEST['adm_usr_delete'];

					deleteUser($maint_user_id);

				} 

					echo '

						<div class="alert alert-success" role="alert">
						  User Maintenance Successfull!
						  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						    <span aria-hidden="true">&times;</span>
						  </button>
						</div>

						';

		}
	?>


	<!-- Pagination -->
	<ul class="pagination fixed-bottom justify-content-center">
		<?php
		if($current_page > 1) {
		echo "<li class=\"page-item\"> <a class='page-link' href=\"?".$get_url."current_page=1\"> &lsaquo;&lsaquo; First </a> </li>";
		echo "<li class=\"page-item\"> <a class='page-link' href=\"?".$get_url."current_page=$previous_page\"> Previous </a> </li>"; }
		?>

		<?php
 		for ($counter = 1; $counter <= $num_page; $counter++) {
 			if ($counter == $current_page) {
 				echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
         	}
         	else {
        		echo "<li class='page-item'><a class='page-link' href='?".$get_url."current_page=$counter'>$counter</a></li>";
            }
		} ?>

		<?php
		if($current_page < $num_page) { echo "<li class=\"page-item\"> <a class='page-link' href=\"?".$get_url."current_page=$next_page\"> Next </a> </li>"; }
		?>

		<?php if($current_page < $num_page){ echo "<li class=\"page-item\"><a class='page-link' href='?".$get_url."current_page=$num_page'>Last &rsaquo;&rsaquo;</a></li>"; } ?>
	</ul>


<?php
    require("footer.php");
?>
