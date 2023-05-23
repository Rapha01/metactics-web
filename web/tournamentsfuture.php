<?php 
session_start();
require_once("inc/dbcon.php");
include("inc/header.php");
include("inc/showParticipants.php");
$title="Future Tournaments";

	
if(isset($_POST["joinT"]) && isset($_POST["tId"]))
{ 
	$resP = getRegistration($_SESSION["sid"], $_POST["tId"]);
	
	if($resP == null)
        addRegistration($_SESSION["sid"], $_POST["tId"]);
	else
		$errorlog["joinT"] = "Already enrolled in this Tournament";	

}

if(isset($_POST["leaveT"]) && isset($_POST["tId"]))
{
    $resP = getRegistration($_SESSION["sid"], $_POST["tId"]);
	
	if($resP != null)
        removeRegistration($_SESSION["sid"], $_POST["tId"]);
	else
		$errorlog["joinT"] = "Not enrolled in this Torunament";

}


$resT = pg_query("SELECT * FROM tournaments");

include("inc/pageHeader.php");
?>

<div class="container"><br>	
		
	<?php 
	while($rowT = pg_fetch_object($resT))
	{
		if(strtotime($rowT->date) > strtotime($curDateTime)+ 10800)//&& $actualTId != $rowT->id)
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
										<h2><?php echo $rowT->name; ?></h2>
									</div>
								</div>
							</div>
			
							<div class="row" style="margin-left:2%;">
								<div class="col-lg-4">			
									<h3><p class="text-default"><?php echo $rowT->date; ?></p></h3>					
								</div>
								<div class="col-lg-2">					
									<h3><p class="text-default"><?php echo $rowT->region; ?></p></h3>
								</div>
								<div class="col-lg-3">
									<?php
									if($loggedIn == true)
									{
										$resP = getRegistration($_SESSION["sid"],$rowT->id);
																
										if($resP == null)
										{
										?>	
												<form action="<?php echo $mee; ?>" method="post">			
													<td>
														<input value="<?php echo $rowT->id; ?>" type="hidden" name="tId"></input>
														<input value="Join Tournament" type="submit" class="btn btn-success" style="margin-top:5%;" name="joinT"></input>
													</td>
												</form>
										<?php
										}
										else
										{
										?>
											<form action="<?php echo $mee; ?>" method="post">
												<td>
													<input value="<?php echo $rowT->id; ?>" type="hidden" name="tId"></input>
													<p class="text-success">You are enrolled</p>
													<input value="Leave Tournament" type="submit" class="btn btn-danger" name="leaveT"></input>
												</td>
											</form>	
										<?php	
										}							
									}						
									?>		
								</div>
								<div class="col-lg-3">
								
										<?php 
										$registeredPlayersCount = pg_num_rows(pg_query_params("SELECT * FROM tournamentregistrations WHERE tournamentId = $1",array($rowT->id)));
										if($registeredPlayersCount > 2)
										{	
											?>
											<a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $rowT->id;?>" style="margin-top:5%;" class="btn btn-info">Show Participants</a>
											<?php			
											
										}									
										?>
										
									
								</div>
							</div>
					</ul>	
				</div>	
			  
				<div class="col-lg-1">		
				</div>
				
			</div>
				
			<div class="row">
				<div class="col-lg-2">
				</div>
				<div class="col-lg-8">
					<div id="<?php echo $rowT->id; ?>" class="panel-collapse collapse">			
							<?php showParticipants($rowT->id);?>
					</div>
				</div>
				<div class="col-lg-2">	
				</div>
			</div>
								
			<br />					
	
			
		<?php
		}
	}
//echo $time = date("H:i:s", strtotime($time));
	?>
 </div>  

 <?php
include("inc/footer.php");
?> 
