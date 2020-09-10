<?php  
	require("header.php");
	if( !isset($_POST['pay_now']) ) // because we don't want this form to show once we've been told if they want to pay now or not
	{
?>

	<div class="py-5 text-center">
        <h2>User Sign up</h2>
    </div>


	<!-- auxiliary Form for this SAME PAGE, for being able to Ask user more information -->
	<form style="width:900px; margin:auto;" class="form-signin text-center" action="signUpUser.php" method="post">
		<div class="mb-3 text-center">
			<h4>Would you like to select a Subscription and pay now?</h4><br>
					<h5>If you rather not pay now (<i>option no</i>), your subscription will be set to <i>Basic</i>. <br> You will only be able to view (and <i>not</i> apply to) job openings. </h5><br>
					<input type="radio" id="yes" name="pay_now" value="yes">
					<label for="yes">Yes</label><br>
					<input type="radio" id="no" name="pay_now" value="no">
					<label for="no">No</label><br>
		</div>	
		<div class="row justify-content-center">		
				<!-- <h5>Press Submit, then fill in the registration form that follows.</h5>
				<br><br> -->
				<button class="btn btn-primary btn-lg btn-block" style="width:350px;" type="submit">Submit</button>
		</div>
	</form>

	<br>

<?php  
	} // if( !isset($_POST['pay_now']) )

	// if the form above has been submited, then show the Main Form
	$pay_now = $_POST['pay_now'];
	if($pay_now == "yes")
	{
?>
	<!-- Main Form -->
	<form style="width:900px; margin:auto;" class="form-signin" action="signUp_process.php" method="post">
		<div class="mb-3">
			<label><strong>Email</strong></label>
			<input class="form-control" type="email" name="email" required/>

			<label><strong>Password</strong></label>
			<input class="form-control" type="password" name="password" required/>

			<label><strong>First name</strong></label>
			<input class="form-control" type="text" name="first_name" required/>	

			<label><strong>Last name</strong></label>
			<input class="form-control" type="text" name="last_name" required/>	

			<br>

			<!-- ADDED b/c of the yes -->
			<h4>Payment Information</h4>
			<label><strong>Select a Subscription Plan </strong></label> <br>
			<input type="radio" id="Prime" name="subscription" value="Prime">
			<label for="Prime">Prime ($10/month): You can apply up to 5 jobs per month. </label><br>
			<input type="radio" id="Gold" name="subscription" value="Gold">
			<label for="Gold">Gold ($20/month): You can apply to as many jobs. </label><br>

			<label><strong>Account Number (max 9 digit number)</strong></label>
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
			If you select 'No', you must pay manually at the due date. <br>
			<input type="radio" id="1" name="automatic_payment" value="1"> 
			<label for="1">Yes</label><br>
			<input type="radio" id="0" name="automatic_payment" value="0">
			<label for="0">No</label><br>


			<!-- this is sent like this to checkout_process.php-->
			<input type="hidden" name="pay_now" value="yes"/> 


			<!-- Make sure that we record the user_type: User is 2 -->
			<input type="hidden" name="user_type" value="2"/>

   		</div>

		<p class="description"><small>
			A username can contain letters (both upper and lower case) and digits only. <br/>
			A password must be at least 6 characters long (characters are to be letters and digits only), <br/>
			have at least one letter and at least one digit
		</small></p>

		<button class="btn btn-primary btn-lg btn-block" type="submit">Sign Up</button>
	</form>

<?php 
	} // end statement if($pay_now == "yes")
	else if ($pay_now == "no")
	{
?>
	<!-- Main Form -->
	<form style="width:900px; margin:auto;" class="form-signin" action="signUp_process.php" method="post">
		<div class="mb-3">
			<label><strong>Email</strong></label>
			<input class="form-control" type="email" name="email" required/>

			<label><strong>Password</strong></label>
			<input class="form-control" type="password" name="password" required/>

			<label><strong>First name</strong></label>
			<input class="form-control" type="text" name="first_name" required/>	

			<label><strong>Last name</strong></label>
			<input class="form-control" type="text" name="last_name" required/>	

			<br>


			<!-- this is sent like this to checkout_process.php-->
			<input type="hidden" name="pay_now" value="no"/> 

			<!-- Make sure that we record the user_type: User is 2 -->
			<input type="hidden" name="user_type" value="2"/>

		</div>

		<p class="description"><small>
			A username can contain letters (both upper and lower case) and digits only. <br/>
			A password must be at least 6 characters long (characters are to be letters and digits only), <br/>
			have at least one letter and at least one digit
		</small></p>

		<button class="btn btn-primary btn-lg btn-block" type="submit">Sign Up</button>
	</form>
<?php 
	} // end else if
?>
			
			

<!-- don't need the other footer to browse between pages -->
</body>
</html>


