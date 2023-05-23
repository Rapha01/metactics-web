<?php 
session_start();

include("inc/header.php");
$title = "Info";
include("inc/pageHeader.php");
?>

<style>
	.list-group {margin-left:5%;}
</style>

<div class="container"><br>

    	
		<ul class="nav nav-tabs" style="width:45%;">
			  <li class="active"><a href="#tournamentInfo" data-toggle="tab" aria-expanded="true"><h4>Tournament Info</h4></a></li>
			  <li class=""><a href="#siteInfo" data-toggle="tab" aria-expanded="false"><h4>About the Site</h4></a></li>
			  <li class=""><a href="#aboutUs" data-toggle="tab" aria-expanded="false"><h4>About Us</h4></a></li>
		</ul>
		<br />
		<div id="myTabContent" class="tab-content" style="padding-left:2%;">
			<div class="tab-pane fade active in" id="tournamentInfo">
				<?php include("inc/texts/tournamentInfo.php");?>
			</div>
		
			<div class="tab-pane fade" id="siteInfo">
				
			</div>
			<div class="tab-pane fade" id="aboutUs">
				
			</div>
		</div>

</div>	
<?php	
include("inc/footer.php");
?>