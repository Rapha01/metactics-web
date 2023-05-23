<?php 
session_start();

include("inc/header.php");
$title = "Info";
include("inc/pageHeader.php");
?>

<style>
.list-group {margin-left:6%;}
</style>

<div class="container"><br>

    <?php

    ?>
    
    
	<h2 class="text-warning">General</h2>

    <div class="row">
        <div class="col-lg-6">
        	<h3>Format</h3>       	
			<ul class="list-group">
				<li><p class="text-primary">Open Registration Tournament with 64(32) Slots for participating teams</p></li>
				<li><p class="text-primary">Single elimination Bracket</p></li>
				<li><p class="text-primary">All rounds except semifinals & finals will be best of 1 matches</p></li>
				<li><p class="text-primary">Semifinals & finals will be best of 3 matches</p></li>
			</ul>	
        </div>
        <div class="col-lg-6">       	
				<h3>Spectating and Casting</h3>
				<ul class="list-group">
					<li><p class="text-primary">All spectator's except official streamers and/or admins are prohibited</p></li>
					<li><p class="text-primary">Players are allowed to stream their own games, except the game will be casted by an affiliated streamer.</p></li>
					<li><p class="text-primary">Official streamer's and/or admins may not be rejected by players.</p></li>
				</ul>
			
			
        </div>
        
    </div>
	
	<div class="row">
        <div class="col-lg-6">   
        	<h3>Communication and Support</h3>
        	<ul class="list-group">
				<li><p class="text-primary">All team leaders are required to join the ingame chatroom Metactics (/join Metactics) to stay in direct contact with admins and other team leaders reagarding the matches</p></li>
				<li><p class="text-primary">Admins can be contacted via ingame chat or through opening a support ticket</p></li>
			</ul>
			
        </div>
    </div>

	<br>
	<h2 class="text-warning">Match Rules</h2>

    <div class="row">
        <div class="col-lg-6">
        	<h3>Pick, Ban and Map</h3> 
        	
        	<ul class="list-group">
				<li><p class="text-primary">Map draft and Heroes draft will be done on heroesdraft.com</p></li>
				<li><p class="text-primary">The one to create the draft has to select „Map draft (VETO)“ and „starting team random“</p></li>
				<li><p class="text-primary">In a Bo3 series the first pick&ban changes every games</p></li>
			</ul>
        	  
			<h3>Cheating and Exploits</h3> 
			
			<ul class="list-group">
				<li><p class="text-primary">Any team found to be using a known exploit will forfeit their game upon the first occurrence of the exploit</p></li>
				<li><p class="text-primary">If the team is found to use another known exploit for a second time or it is determined to have been done on purpose they will be removed from the event and banned from any future events</p></li>
			</ul>
			      	
			<h3>Match Results</h3>  
			
			<ul class="list-group">
				<li><p class="text-primary">Both participants are responsible to enter the correct result. If anything is unclear, participants should have screenshots available to verify the result and file a protest via the support ticket option</p></li>
			</ul>
			     
        </div>
        <div class="col-lg-6">
        	<h3>Disconnects</h3> 
        	
        	<ul class="list-group">
				<li><p class="text-primary">After a disconnect the game has to be paused. A team can pause a game for up to 10 minutes</p></li>
				<li><p class="text-primary">If the player isn't able to reconnect after this time, the game has to be finished without him. In every case a screenshot of the issue has to be taken</p></li>
				<li><p class="text-primary">In every case a screenshot of the issue has to be taken</p></li>
			</ul>
        	      	
			<h3>No Shows</h3> 

			<ul class="list-group">
				<li><p class="text-primary">If your opponent isn't present within 15 minutes after the match was announced contact an admin to get your match checked</p></li>
			</ul>     	
			     	
			<h3>Game Data</h3>  
			<ul class="list-group">
				<li><p class="text-primary">All screenshots and replays need to be retained up to 1 week</p></li>
			</ul>     	
        </div>  
    </div>
    
    <br /> <br /><br />
    <div class="text-center">        	
		<p><cite title="Source Title">The rules stated above are to be seen as a guideline to promise a clean tournament, but they can be updated by the admin team at any time to react to unexpected problems or add last minute clarifications</cite></p>
		<small></small>	
	</div>
	
</div>

	
<?php	
include("inc/footer.php");
?>