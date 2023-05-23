<?php 


function showParticipants($tId)
{
	?>			
	
	<?php
	
	$resP = pg_query_params("SELECT * FROM tournamentregistrations WHERE tournamentid = $1 ORDER BY date",array($tId));
	
	?>
	<div class="row">
		<div class="col-lg-4">
			
				
			<?php
			$playersPerColumn = ceil(pg_num_rows($resP) / 3);
			$i=1;
			while($rowP = pg_fetch_object($resP))
			{				
				?>
				<ul class="breadcrumb" style="margin: 5% 0; width:100%;">	
						<div class="row">
							<div class="col-lg-10">
								<a href="teamprofiles.php?getTeamProfile=<?php echo $rowP->teamid; ?>"><?php echo getTeamNameById($rowP->teamid); ?></a>
							</div>
							<div class="col-lg-2">
							</div>
						</div>
				</ul>
				
				<?php
				if($i % $playersPerColumn == 0){
					?>
					</div>
					<div class="col-lg-4">
					<?php
				}
									
				$i++;
			}
			
			?>	
			
		</div>
	</div>
		

	<?php
}
?>

<?php
function editParticipants($tId)
{
	
	$resP = pg_query_params("SELECT * FROM tournamentregistrations WHERE tournamentid = $1 ORDER BY date",array($tId));
	
	?>
	<div class="container" style="margin-top:3%;">
	<form class="form-horizontal" action="admin.php" method="POST">
	<div class="row">
		
		<div class="row">
			<div class="col-lg-4">
				<div class="row">
					<div class="col-lg-1"></div>
					<div class="col-lg-8">Teamname</div>
					<div class="col-lg-2">Delete</div>
					<div class="col-lg-1"></div>
				</div>
			</div>	
		</div>
				
		<div class="col-lg-4">
		
			<?php
			$playersPerColumn = ceil(pg_num_rows($resP) / 3);
			$i=1;
			while($rowP = pg_fetch_object($resP))
			{				
				?>
				<ul class="breadcrumb" style="margin: 5% 0; width:100%;">	
						<div class="row">
							<div class="col-lg-10">
								<?php echo getTeamNameById($rowP->teamid); ?>
							</div>
							<div class="col-lg-2">
								<input type="checkbox"  name="delParticipant<?php echo $rowP->teamid; ?>" value="<?php echo $rowP->teamid; ?>" />
							</div>
						</div>
				</ul>
				
				<?php
				if($i % $playersPerColumn == 0){
					?>
					</div>
					<div class="col-lg-4">
					<?php
				}
									
				$i++;
			}
			?>
				
		</div>
	</div>
	
	
	<div class="form-group">
		<label for="select" class="col-lg-2 control-label">Add Participant (Id or Teamname)</label>
		<div class="col-lg-6">
			<input type="text" class="form-control" name="addParticipant" value="">
		</div>
		<div class="form-group" style="margin-top:5%;">
			<div class="col-lg-10 col-lg-offset-2">
				<button type="submit" name="editParticipantsSubmit" class="btn btn-success">Update</button>
			</div>
		</div>
	</div>
	
	</form>
	</div>

	<?php
}	
?>