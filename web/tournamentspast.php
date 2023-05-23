<?php
session_start();
require_once("inc/dbcon.php");
include("inc/header.php");
include("inc/printbracket.php"); 
$title="Past Tournaments";

$sql = "SELECT id, name, date, region FROM tournaments";
$resT = pg_query($sql);

include("inc/pageHeader.php");
?>

<div class="container">

<?php 
while($rowT = pg_fetch_object($resT))
{
	if(strtotime($rowT->date) + 86400 < strtotime($curDateTime))
	{		
	?>

<div class="row">
	<div class="col-lg-1">
	</div>
	<div class="col-lg-10">
		
		<ul class="breadcrumb" id="tournamentLabel">
				<div class="row">
					<div class="col-lg-12">
						<div class="page-header"  style="margin-left:2%;">
							<h3><?php echo $rowT->name; ?></h3>
						</div>
					</div>
				</div>

				<div class="row" style="margin-left:2%;margin-bottom:1%;">
					<div class="col-lg-4">			
						<h3><p class="text-default"><?php echo $rowT->date; ?></p></h3>					
					</div>
					<div class="col-lg-4">					
						<h3><p class="text-default"><?php echo $rowT->region; ?></p></h3>
					</div>
					<div class="col-lg-4">
						<a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $rowT->id;?>" class="btn btn-default btn-lg btn-block">Show Bracket</a>
					</div>
				</div>
		</ul>	
	</div>	
  
	<div class="col-lg-1">
	</div>
</div>

<div style="margin-left:6%;">
	<div id="<?php echo $rowT->id; ?>" class="panel-collapse collapse">
		<div class="" style= "padding-left: 6%;">
			<?php printBracket($rowT->id);?>
		</div>
	</div>
</div>	
	
	<?php
	}
}
?>


</div>




<?php
include("inc/footer.php");
?>
