<?php 
session_start();
require_once("inc/dbcon.php");
include("inc/header.php");
include("inc/printTeamProfile.php");
$teamprofileLog = array();
$validTeamId = null;
$title = "Team Profiles";


if(isset($_GET["getTeamProfile"]))
{
	$getTeamProfile = pg_escape_string($_GET["getTeamProfile"]);

	if(is_numeric($getTeamProfile))
	{		
		$sql = "SELECT id FROM account WHERE id = ".$getTeamProfile." LIMIT 1";
		$res = pg_query($sql);
		if(pg_num_rows($res) == 1)
		{				
			$validTeamId = $getTeamProfile;
		}
		else
		{
			$teamprofileLog["warning"][] = "No such id found";
		}
	}
		
	if(!is_numeric($getTeamProfile))
	{
		$sql = "SELECT id FROM account WHERE LOWER(teamname) = LOWER('".$getTeamProfile."') LIMIT 1";
		$res = pg_query($sql);
		if(pg_num_rows($res) == 1)
		{				
			$validTeamId = pg_fetch_object($res)->id;
		}
		else
		{
			$teamprofileLog["warning"][] = "No such teamname found";
		}
	}
		
}



?>

<!--JETZT bei Infoupdate in jquery.datatables.js script>
	jQuery(document).ready(function($) {
	    $(".clickable-row").click(function() {
	        window.document.location = $(this).data("href");
	    });
	});
</script-->

	
<?php
include("inc/pageHeader.php");
logEcho($teamprofileLog);
?>

<div class="container"><br>
	<div class="row">
		<div class="col-lg-4">

			
			<h2 class="text-center" style="margin-bottom:5%;">Search for Teamprofile</h2>
					
			<table id="myTable" class="table table-hover">
				 <thead>
					<tr>		
						<th>Teamname</th>
						<th>Leader</th>										
					</tr>
				</thead>
				<tbody>
					
					<?php
					$res = pg_query("SELECT id, teamname, btag1 FROM account");
					while($row = pg_fetch_object($res))
					{
						?>
						<a href="#">						
			 				<tr class="clickable-row" data-href="teamprofiles.php?getTeamProfile=<?php echo $row->id;?>">						 					
								<td><?php echo $row->teamname; ?></td>
								<td><small><?php echo $row->btag1; ?></small></td>	
							</tr>
						</a>
					<?php
					}
					?>
			
				</tbody>
			</table> 
										
			
		</div>
		<div class="col-lg-8">
				<?php
				if($validTeamId != null){
					printTeamProfile($validTeamId);
				}
				?>
			</div>
	</div>
</div>
<?php
include("inc/footer.php");
?> 
