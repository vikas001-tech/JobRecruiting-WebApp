<?php
	require("header.php");
?>

	<div class="py-5 text-center">
        <h2>Password Recovery</h2>
    </div>


	<!-- Form  #1 -->
	<form style="width:900px; margin:auto;" class="form-signin" action="passwordRecoveryProcess.php" method="post">

		<div class="mb-3">
			<label><strong>Enter Your E-mail</strong></label>
			<input class="form-control" type="email" name="email" required/>
   		</div>

			<!-- <div class="form-check form-check-inline"> -->
			<div class="form-inline md-form mr-auto mb-4 inline-block">	
				<label><strong>Please select the type of your account:      </strong></label>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" id= "radio_admin" name="user_type"  value="0"
					<label class="form-check-label" for="radio_admin">
						 Admin
					</label>
				</div>
				<div class="form-check form-check-inline disabled">
					<input class="form-check-input" type="radio" id= "radio_employer" name="user_type"  value="1" required/>
					<label class="form-check-label" for="radio_employer">
						Employer Account
					</label>
				</div>
				<div class="form-check form-check-inline disabled">
					<input class="form-check-input" type="radio" id= "radio_user" name="user_type"  value="2" required/>
					<label class="form-check-label" for="radio_user">
						User Account
					</label>
				</div>
			</div>

		<button class="btn btn-primary btn-lg btn-block" type="submit">Request Confirmation Code</button>
	</form>

</body>
</html>
