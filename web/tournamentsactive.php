<?php 
session_start();
require_once("inc/dbcon.php");
include("inc/header.php");
include("inc/printbracket.php");
$activeTlog = array();
$title="Active Tournament";

$activeT = getTournamentById($resAdmin->actualtid);
//If no Tournament in Progress show nothing

if($resAdmin->tinprogress == 'f')
{
	include("inc/pageHeader.php");
	?>
		<div class="container">
			<h4><p class="text-primary">No Tournament in progress</p></h4>
		</div>
<?php
}
//Else Show Tournament in Progress
else 
{
	$title = "Active Tournament ".$activeT->name;
	include("inc/pageHeader.php");
	?>
	<script type="text/javascript" src="inc/countDown.js"></script>

	<?php
		if($resAdmin->activetshout != "")
		{
			$activeTlog[$resAdmin->activetshoutcolor][] = $resAdmin->activetshout;
			logEcho($activeTlog);
		}
	?>
	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<h1><p class="text-primary">State</p></h1>
				<br />
					<div class="row" style="margin-left:5%;">
						<ul class="breadcrumb text-center" style="width:80%">			
								<h2 style="margin-top:4%;"><?php echo $resAdmin->activetstate;?></h2>
								<h4><?php echo $resAdmin->activetstateinfo;?></h4>
						</ul>
					</div>
				
			</div>  
			
			<?php
			if($resAdmin->activettimerdate != "0000-00-00 00:00:00")
			{
			?>
				<div class="col-lg-6">
					<h1><p class="text-primary">Countdown</p></h1>
					<br />
					<div class="row" style="margin-left:5%;">					
						<ul class="breadcrumb text-center" style="width:80%;">
							<li>
								<?php 
								$countdownTime = strtotime($resAdmin->activettimerdate)-strtotime($curDateTime);
								?>
								<div id="cID">  Init<script>countdown(<?php echo $countdownTime ?>,'cID');</script></div>
							</li>
						</ul>
					</div>
				</div>
			<?php
			}
			?>	
		</div>
	
	</div>

	<?php
	if($resAdmin->activetshowbracket == 't')
	{
	?>
		<div class="container">
			<h1><p class="text-primary">Bracket</p></h1>
			<br>
			<div class="" style="margin-left:5%;">
				
				<?php 
				printBracket($resAdmin->actualtid);
				?>
			</div>
		</div>
	<?php
	}
}
include("inc/footer.php");
?> 
