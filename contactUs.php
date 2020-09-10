<?php  
	require("header.php");
	include("computeRating.php");
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
		  width: 50%;
		  position: center;
		  text-align: center;
		}
	</style>


	<br/><br/>

	<div id="center_text">
		 <h2><b> Let's Connect </b></h2> <br>
		 <h3>We'd love to help you start exceeding your expectations</h3>

		<br/><br/>

		<img src="images/map.png" height=60% width=60%>

		<br/><br/>

		Pavillion Henry F.Hall Bldg, <br>
		Boulevard de Maisonneuve O 13th floor, <br>
		Montreal, Québec <br>
		H3G 1M8 <br>
		Canada <br>

		<br/><br/>

		+1 5148482424

		<br/><br/>

		<a href = "mailto: nxc353_1@encs.concordia.ca">nxc353_1@encs.concordia.ca</a>



	</div>

	<!-- new lines -->
	<br/><br/>
	<br/><br/>



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
