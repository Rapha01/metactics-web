<?php 
session_start();

include("inc/header.php");
$title = "Hots Guides";
include("inc/pageHeader.php");
?>

<style>
	.list-group {margin-left:5%;}
	a.guidelink {text-decoration: none;color:red;}
	.aaa {color:red;}
</style>

<div class="container"><br>

    	
		<ul class="nav nav-tabs" style="width:45%;">
			  <li class="active"><a href="#basicguides" data-toggle="tab" aria-expanded="true"><h4>Basic</h4></a></li>
			  <li class=""><a href="#heroguides" data-toggle="tab" aria-expanded="false"><h4>Hero</h4></a></li>
			  <li class=""><a href="#mapguides" data-toggle="tab" aria-expanded="false"><h4>Map</h4></a></li>
		</ul>
		<br />
		<div id="myTabContent" class="tab-content" style="padding-left:2%;">
			<div class="tab-pane fade active in" id="basicguides">
				<a href="#" class="guidelink"><h2><p class="text-primary">Basic Tactics</p></h2></a>
				<a href="#" class="guidelink"><h2><p class="text-primary">Advanced Information</p></h2></a>
				<a href="#" class="guidelink"><h2><p class="text-primary">Pro Knowledge</p></h2></a>
			</div>
		
			<div class="tab-pane fade" id="heroguides">	
			</div>
			
			<div class="tab-pane fade" id="mapguides">			
			</div>
		</div>

</div>	
<?php	
include("inc/footer.php");
?>