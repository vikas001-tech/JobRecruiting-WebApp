<?php  
	require("header.php");
?>

	<div class="py-5 text-center">
        <h2>Employer Sign up</h2>
        <h4>Please enter the following information.</h4>
    </div>


	<!-- Form 1 -->
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

			<h4>Please enter Employer's Contact-Info.</h4>
			<label><strong>Contact Email</strong></label>
			<input class="form-control" type="email" name="contact_email" required/>

			<label><strong>Phone</strong></label>
			<input class="form-control" type="text" name="contact_phone" required/>

			<label><strong>Street</strong></label>
			<input class="form-control" type="text" name="street" required/>

			<label><strong>City</strong></label>
			<input class="form-control" type="text" name="city" required/>

			<label><strong>Province</strong></label>
			<input class="form-control" type="text" name="province" required/>

			<label><strong>Country</strong></label>
			<input class="form-control" type="text" name="country" required/>

			<br>

			<h4>Payment Information</h4>
			<label><strong>Select a Membership Plan </strong></label> <br>
			<input type="radio" id="Prime" name="membership" value="Prime">
			<label for="Prime">Prime ($10/month): You can apply up to 5 jobs per month. </label><br>
			<input type="radio" id="Gold" name="membership" value="Gold">
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

			<!-- Make sure that we record the user_type 1: is for Employer -->
			<input type="hidden" name="user_type" value="1"/>

   		</div>

		<p class="description"><small>
			A username can contain letters (both upper and lower case) and digits only. <br/>
			A password must be at least 6 characters long (characters are to be letters and digits only), <br/>
			have at least one letter and at least one digit
		</small></p>

		<button class="btn btn-primary btn-lg btn-block" type="submit">Sign Up</button>

	</form>


<!-- don't need the other footer to browse between pages -->
</body>
</html>


