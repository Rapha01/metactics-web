<?php
session_start();
include("inc/header.php");
$title="Welcome to Heroes of the Storm Tournaments";


include("inc/pageHeader.php");




?>


<div class="container">
	<div class="row" >
		<div class="col-lg-1">	
		</div>
		<div class="col-lg-5">	
			<a class="twitter-timeline" href="https://twitter.com/Metactic_Adm" data-widget-id="607957212460404737">Tweets von @Metactic_Adm </a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
		<div class="col-lg-5">		
		</div>
		
	</div>
</div>

<?php
include("inc/footer.php");
?>