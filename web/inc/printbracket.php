<?php 


function printBracket($tId)
{
	
	//print_r($tId);
	$bracketRes = pg_query_params('SELECT * FROM matches WHERE tournamentid = $1 ORDER BY id',array(intval($tId)));


	if(pg_num_rows($bracketRes) == 0)
	{
		echo "No Matches online";
		return;
	}
	
	if(pg_num_rows($bracketRes) != 7 && pg_num_rows($bracketRes) != 15 && pg_num_rows($bracketRes) != 31 && pg_num_rows($bracketRes) != 63 && pg_num_rows($bracketRes) != 127)
	{
		echo "tournament has unproper matchnumber";
		return;
	}

 	$bracketLines = array();
    while($row = pg_fetch_object($bracketRes)){
    	$bracketLines[] = $row;
    }
	
	
	
	$tFormat = (sizeof($bracketLines) +1);
	$bracketCount = $tFormat/2;
	$fontSize= "110%";
	if($tFormat == 16) $fontSize="98%";
	if($tFormat == 32) $fontSize="83%";
	if($tFormat == 64) $fontSize="69%";
	if($tFormat == 128) $fontSize="57%";
	
	$j=0;
	$i=1;
	$jSave=0;
	
	?>
	<link rel="stylesheet" href="css/bracket.css">
	<main id="tournament">
		<?php
		while($bracketCount != 1)
		{
		?>
			<ul class="round round-<?php echo $i;?>">
				<li class="spacer">&nbsp;</li>	
								
					<?php
					$jSave = $jSave + $j ;						 
					for($j = 0; $j <= $bracketCount-1; $j += 1)
					{
						$winner = "";
						if($bracketLines[$jSave+$j]->score1 > $bracketLines[$jSave+$j]->score2 ){ $winner= "1";}
						if($bracketLines[$jSave+$j]->score1 < $bracketLines[$jSave+$j]->score2 ){ $winner= "2";}
					?>
						
						<li style="padding-bottom:2%;" class="game game-<?php if($j % 2 == 0){echo "top-top";}else{echo "top-bottom";}?> winner">
							<?php
							if($bracketLines[$jSave+$j]->teamid1 != 0)
							{	
							?>
								<div style="font-size:<?php echo $fontSize; ?>;" class="label label-<?php if($winner=="1"){ echo "success";}else{echo "default";}?>">
									<?php echo getTeamNameById($bracketLines[$jSave+$j]->teamid1);?>
								</div>
							<?php
							}	
							?>
							<span><?php echo $bracketLines[$jSave+$j]->score1; ?></span>							
						</li>
						
						
						<li style="padding-top:2%;" class="game game-<?php if($j % 2 == 1){echo "bottom-bottom";}else{echo "bottom-top";}?>">
							<?php
							if($bracketLines[$jSave+$j]->teamid2 != 0)
							{	
							?>
								<div style="font-size:<?php echo $fontSize; ?>;" class="label label-<?php if($winner=="2"){ echo "success";}else{echo "default";}?>">
									<?php echo getTeamNameById($bracketLines[$jSave+$j]->teamid2);?>
								</div>
							<?php
							}	
							?> 
							<span><?php echo $bracketLines[$jSave+$j]->score2;?></span>
						</li>
					
	
						<?php if($j % 2 == 0){?><li class="game game-spacer">&nbsp;</li><?php } ?>
						<?php if($j % 2 == 1){?><li class="spacer">&nbsp;</li><?php } ?>
						
					<?php 
					$i++;
					}
					$bracketCount= $bracketCount/2;
					?>
			</ul>
			
		<?php 
		}

		$winner = "";
		if($bracketLines[$jSave+$j]->score1 > $bracketLines[$jSave+$j]->score2 ){ $winner= "1";}
		if($bracketLines[$jSave+$j]->score1 < $bracketLines[$jSave+$j]->score2 ){ $winner= "2";}
		?>	
		<ul class="round round-<?php echo $i;?>">
			<li class="spacer">&nbsp;</li>
			
			<li style="padding-bottom:2%;" class="game game-top-top winner">
				<?php
				if($bracketLines[$jSave+$j]->teamid1 != 0)
				{	
				?>
					<div style="font-size:small;" class="label label-<?php if($winner=="1"){ echo "success";}else{echo "default";}?>">
						<?php echo getTeamNameById($bracketLines[$jSave+$j]->teamid1);?>
					</div>
				<?php
				}	
				?>
				<span><?php echo $bracketLines[$jSave+$j]->score1; ?></span>
			</li>
			<li style="padding-top:2%;" class="game game-bottom-bottom">
				<?php
				if($bracketLines[$jSave+$j]->teamid2 != 0)
				{	
				?>
				<div style="font-size:small;" class="label label-<?php if($winner=="2"){ echo "success";}else{echo "default";}?>">
					<?php echo getTeamNameById($bracketLines[$jSave+$j]->teamid2);?>
				</div>
				<?php
				}	
				?>
				<span><?php echo $bracketLines[$jSave+$j]->score2; ?></span>
			</li>
			
			<li class="spacer">&nbsp;</li>
		</ul>
		
		<ul class="round round-<?php echo $i+1;?>">
			<li class="spacer">&nbsp;</li>		
			<li class="game game-top winner"><?php if($winner==1){echo getTeamNameById($bracketLines[$jSave+$j]->teamid1);}if($winner=="2"){echo getTeamNameById($bracketLines[$jSave+$j]->teamid2);}?>
			</li>
			
			<li class="spacer">&nbsp;</li>
		</ul>
	</main>
<?php	
}


function editBracket($tId)
{
	
	$bracketRes = pg_query_params('SELECT * FROM matches WHERE tournamentid = $1 ORDER BY id', [$tId]);
	
	if(pg_num_rows($bracketRes) == 0)
	{
		echo "No Matches online";
		return;
	}
	
	if(pg_num_rows($bracketRes) != 7 && pg_num_rows($bracketRes) != 15 && pg_num_rows($bracketRes) != 31 && pg_num_rows($bracketRes) != 63 && pg_num_rows($bracketRes) != 127)
	{
		echo "tournament has unproper matchnumber";
		return;
	}

 	$bracketLines = array();
    while($row = pg_fetch_object($bracketRes)){
    	$bracketLines[] = $row;
    }
	
	
	
	$tFormat = (sizeof($bracketLines) +1) /2;
	$bracketCount = $tFormat;
	$j=0;
	$i=1;
	$jSave=0;
	
	//get participants
	$participants=array();
	$resP = pg_query_params('SELECT * FROM tournamentregistrations WHERE tournamentid = $1',[$tId]);

	while($row = pg_fetch_object($resP))
	{
		$participants[] = $row->teamid;
	}
	
	?>
	<link rel="stylesheet" href="css/bracket.css">
	
	<form action="admin.php" method="POST">
			<main id="tournament">
		<?php
		while($bracketCount != 1)
		{
		?>
			<ul class="round round-<?php echo $i;?>">
				<li class="spacer">&nbsp;</li>	
								
					<?php
					$jSave = $jSave + $j ;						 
					for($j = 0;$j <= $bracketCount -1; $j++)
					{
					?>
					
						<li class="game game-<?php if($j % 2 == 0){echo "top-top";}else{echo "top-bottom";}?>"><!--<span>Erg</span>-->
							<!--input style="width:7em;background: transparent;color: white;" value="<?php echo $bracketLines[$jSave+$j]->teamId1;?>" name="editMatchTeamTop<?php echo $bracketLines[$jSave+$j]->id;?>"></input-->
							<select style="color:black;" name="editMatchTeamTop<?php echo $bracketLines[$jSave+$j]->id;?>">
								<option <?php if($bracketLines[$jSave+$j]->teamid1 == 0){echo "selected";}?> value="0">Empty</option>
								<option <?php if($bracketLines[$jSave+$j]->teamid1 == -1){echo "selected";}?> value="-1">No Team</option>
								<?php
								foreach($participants as $part)
								{
									?>
									<option <?php if($part == $bracketLines[$jSave+$j]->teamid1){echo "selected";}?> value="<?php echo $part;?>"><?php echo getTeamNameById($part);?></option>
									<?php
								}
								?>	
							</select>
							<span>
								<select style="color:black;" class="form-control-small" name="editMatchScoreTop<?php echo $bracketLines[$jSave+$j]->id;?>">
									<option value="" <?php if($bracketLines[$jSave+$j]->score1 == ""){echo "selected";} ?>>/</option>
									<option value="0" <?php if($bracketLines[$jSave+$j]->score1 == "0"){echo "selected";} ?>>0</option>
									<option value="1" <?php if($bracketLines[$jSave+$j]->score1 == "1"){echo "selected";} ?>>1</option>
									<option value="2" <?php if($bracketLines[$jSave+$j]->score1 == "2"){echo "selected";} ?>>2</option>
									<option value="3" <?php if($bracketLines[$jSave+$j]->score1 == "3"){echo "selected";} ?>>3</option>
									<option value="4" <?php if($bracketLines[$jSave+$j]->score1 == "4"){echo "selected";} ?>>4</option>
								</select>
							</span>
						</li>
						
						<li class="game game-<?php if(($j) % 2 == 1){echo "bottom-bottom";}else{echo "bottom-top";}?>"><!--<span>Erg</span>-->
							<!--input style="width:7em;background: transparent;color: white;" value="<?php echo $bracketLines[$jSave+$j]->teamId2;?>" name="editMatchTeamBot<?php echo $bracketLines[$jSave+$j]->id;?>"></input-->
							<select style="color:black;" name="editMatchTeamBot<?php echo $bracketLines[$jSave+$j]->id;?>">
								<option <?php if($bracketLines[$jSave+$j]->teamid2 == 0){echo "selected";}?> value="0">Empty</option>
								<option <?php if($bracketLines[$jSave+$j]->teamid2 == -1){echo "selected";}?> value="-1">No Team</option>
								<?php
								foreach($participants as $part)
								{
									?>
									<option style="" <?php if($part == $bracketLines[$jSave+$j]->teamid2){echo "selected";}?> value="<?php echo $part;?>"><?php echo getTeamNameById($part);?></option>
									<?php
								}
								?>	
							</select>
							<span>
								<select style="color:black;" class="form-control-small" name="editMatchScoreBot<?php echo $bracketLines[$jSave+$j]->id;?>">
									<option value="" <?php if($bracketLines[$jSave+$j]->score2 == ""){echo "selected";} ?>>/</option>
									<option value="0" <?php if($bracketLines[$jSave+$j]->score2 == "0"){echo "selected";} ?>>0</option>
									<option value="1" <?php if($bracketLines[$jSave+$j]->score2 == "1"){echo "selected";} ?>>1</option>
									<option value="2" <?php if($bracketLines[$jSave+$j]->score2 == "2"){echo "selected";} ?>>2</option>
									<option value="3" <?php if($bracketLines[$jSave+$j]->score2 == "3"){echo "selected";} ?>>3</option>
									<option value="4" <?php if($bracketLines[$jSave+$j]->score2 == "4"){echo "selected";} ?>>4</option>
								</select>
							</span>
						</li>
						
						<?php if($j % 2 == 0){?><li class="game game-spacer">&nbsp;</li><?php } ?>
						<?php if($j % 2 == 1){?><li class="spacer">&nbsp;</li><?php } ?>
					
					<?php	
					$i++;
					}
					$bracketCount= $bracketCount/2;
					?>
			</ul>
			
		<?php 
		}
		?>
		
		<ul class="round round-<?php echo $i;?>">
			<li class="spacer">&nbsp;</li>
			
			<li class="game game-top-top"><!--<span>Erg</span>-->
				<!--input style="width:10em;background: transparent;color: white;" value="<?php echo $bracketLines[$jSave+$j]->teamId1;?>" name="editMatchTeamTop<?php echo $bracketLines[$jSave+$j]->id;?>"></input-->
				<select style="color:black;" name="editMatchTeamTop<?php echo $bracketLines[$jSave+$j]->id;?>">
					<option <?php if($bracketLines[$jSave+$j]->teamid1 == 0){echo "selected";}?> value="0">Empty</option>
					<option <?php if($bracketLines[$jSave+$j]->teamid1 == -1){echo "selected";}?> value="-1">No Team</option>
					<?php
					foreach($participants as $part)
					{
						?>
						<option style="" <?php if($part == $bracketLines[$jSave+$j]->teamid1){echo "selected";}?> value="<?php echo $part;?>"><?php echo getTeamNameById($part);?></option>
						<?php
					}
					?>	
				</select>
				<span>
					<select style="color:black;" class="form-control-small" name="editMatchScoreTop<?php echo $bracketLines[$jSave+$j]->id;?>">
						<option value="" <?php if($bracketLines[$jSave+$j]->score1 == ""){echo "selected";} ?>>/</option>
						<option value="0" <?php if($bracketLines[$jSave+$j]->score1 == "0"){echo "selected";} ?>>0</option>
						<option value="1" <?php if($bracketLines[$jSave+$j]->score1 == "1"){echo "selected";} ?>>1</option>
						<option value="2" <?php if($bracketLines[$jSave+$j]->score1 == "2"){echo "selected";} ?>>2</option>
						<option value="3" <?php if($bracketLines[$jSave+$j]->score1 == "3"){echo "selected";} ?>>3</option>
						<option value="4" <?php if($bracketLines[$jSave+$j]->score1 == "4"){echo "selected";} ?>>4</option>
					</select>
				</span>
				
			</li>
			<li class="game game-bottom-bottom"><!--<span>Erg</span>-->
				<!--input style="width:10em;background: transparent;color: white;" value="<?php echo $bracketLines[$jSave+$j]->teamId2;?>" name="editMatchTeamBot<?php echo $bracketLines[$jSave+$j]->id;?>"></input-->
				<select style="color:black;" name="editMatchTeamBot<?php echo $bracketLines[$jSave+$j]->id;?>">
					<option <?php if($bracketLines[$jSave+$j]->teamid2 == 0){echo "selected";}?> value="0">Empty</option>
					<option <?php if($bracketLines[$jSave+$j]->teamid2 == -1){echo "selected";}?> value="-1">No Team</option>
					<?php
					foreach($participants as $part)
					{
						?>
						<option style="" <?php if($part == $bracketLines[$jSave+$j]->teamid2){echo "selected";}?> value="<?php echo $part;?>"><?php echo getTeamNameById($part);?></option>
						<?php
					}
					?>	
				</select>
				<span>
					<select style="color:black;" class="form-control-small" name="editMatchScoreBot<?php echo $bracketLines[$jSave+$j]->id;?>">
						<option value="" <?php if($bracketLines[$jSave+$j]->score2 == ""){echo "selected";} ?>>/</option>
						<option value="0" <?php if($bracketLines[$jSave+$j]->score2 == "0"){echo "selected";} ?>>0</option>
						<option value="1" <?php if($bracketLines[$jSave+$j]->score2 == "1"){echo "selected";} ?>>1</option>
						<option value="2" <?php if($bracketLines[$jSave+$j]->score2 == "2"){echo "selected";} ?>>2</option>
						<option value="3" <?php if($bracketLines[$jSave+$j]->score2 == "3"){echo "selected";} ?>>3</option>
						<option value="4" <?php if($bracketLines[$jSave+$j]->score2 == "4"){echo "selected";} ?>>4</option>
					</select>
				</span>
			</li>
			<li class="spacer">&nbsp;</li>
		</ul>
		
	</main>
		
	
	
	<input type="submit" class="btn btn-success" name="editMatches" value="editMatches"/>
	</form>
	<?php	
	}	
?>