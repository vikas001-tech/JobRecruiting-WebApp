<?php
	require("header.php");
?>
	<style>
		#pad_left {
			padding-left: 15px;
			padding-right: 15px;
		}
		#center_logo {
		  margin: auto;
		  width: 50%;
		  position: center;
		  text-align: center;
		}
		#center_text {
		  margin: auto;
		  width: 50%;
		  position: center;
		  text-align: center;
		}
		#center_grey {
		  margin: auto;
		  width: 50%;
		  position: center;
		  text-align: center;
		  color: #909090;
		}
	</style>

	<br><br>

	<div id="center_logo">
	 <img src="images/logo.png" height="350" width="350"> <!-- Logo Picture -->
	</div>

	<br><br>

	<div id="center_text">
	 	<i> nxc_353_1 </i> is one of the most significant Canadian Job Site with over <i> 1000 </i> employers and <i> 50000 </i> unique job seekers actively looking for jobs every month. We connect thousands of people to new opportunities. Our goal is to create a network where talent is valued and easily acquired.
	</div>

	<br><br><br>
	<div id="center_grey">
	 	Our supporters
	 	<br><br>
	 	<img src="images/logo_banner.png" height=75% width=75%>
	</div>



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
