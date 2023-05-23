<?php 


function printTeamProfile($teamId)
{
	$meTeamRes = pg_fetch_object(pg_query("SELECT * FROM account WHERE id = ".$teamId." LIMIT 1"));
	
	?>

			<div class="jumbotron">
				
				<div class="page-header">
					<div class="row">
						<div class="col-lg-6" style="word-wrap: break-word;">
							<h1><?php echo $meTeamRes->teamname;?></h1>
						</div>
						<div class="col-lg-6">
							<div class="">
								<?php
								if($meTeamRes->avatarpic != "")
								{
								?>					                                                     
									<img src="uploads/avatars/<?php echo $meTeamRes->avatarpic;?>" alt="" style="width:100%;">	
								<?php
								}
								?>
							</div>
						</div>
					</div>
				</div>
		
				<h2>Roster</h2>
				
				<div class="row" style="margin-left:1%;">
					<div class="col-lg-6">
						
						<div class="row">
							<div class="col-lg-10">
								<h1><p class="text-warning">Teamleader</p></h1>		
						
								<ul class="breadcrumb" style="margin-bottom:1%; margin-left:5%;">
									<li id="roasterLi"  style=""><?php echo $meTeamRes->btag1;?></li>
								</ul>
			
								<?php
								if($meTeamRes->btag6 != "" || $meTeamRes->btag7 != "") {
								?>
									<h1><p class="text-warning">Substitutes</p></h1>
								<?php
								}
								
								
								if($meTeamRes->btag6 != "") {
								?>
									<ul class="breadcrumb" style="margin-bottom:1%; margin-left:5%;">
										<li id="roasterLi"><?php echo $meTeamRes->btag6;?></li>
									</ul>	
								<?php
								}
								if($meTeamRes->btag7 != "") {
								?>
									<ul class="breadcrumb" style="margin-left:5%;">
										<li id="roasterLi"><?php echo $meTeamRes->btag7;?></li>
									</ul>
								<?php
								}
								?>
							</div>
						</div>	
					</div>
			
			    	<div class="col-lg-6">
			    		
			    		<h1><p class="text-warning">Core Member</p></h1>
			    		
			    		<div class="row">
							<div class="col-lg-10">
						
								<ul class="breadcrumb" style="margin-bottom:1%;margin-left:5%;">
									<li id="roasterLi"><?php echo $meTeamRes->btag2;?></li>
								</ul>
								<ul class="breadcrumb" style="margin-bottom:1%;margin-left:5%;">
									<li id="roasterLi"><?php echo $meTeamRes->btag3;?></li>
								</ul>
								<ul class="breadcrumb" style="margin-bottom:1%;margin-left:5%;">
									<li id="roasterLi"><?php echo $meTeamRes->btag4;?></li>
								</ul>
								<ul class="breadcrumb" style="margin-left:5%;">
									<li id="roasterLi"><?php echo $meTeamRes->btag5;?></li>
								</ul>
							</div>
			 			</div>			
					</div>
			 	</div>
		 	
				<br />	
				
				<?php
				if($meTeamRes->aboutus != ""){
					?>
					<h2>About us</h2>	
					<div class="row" style="margin-left:2%;">
						<p class="text-primary"><?php echo nl2br($meTeamRes->aboutus);?></p>
					</div>		
					<?php									
				}
				?>
				
				<br />			
				<h2>Stats</h2>
				<div class="row" style="margin-left:2%;">
					<h4><p class="text-primary">Yet to come</p></h4>
				</div>			
				<h2>Recent Activities</h2>
				<div class="row" style="margin-left:2%;">
					<h4><p class="text-primary">Yet to come</p></h4>
				</div>
				
			</div>


<?php	
}	
?>