<?php 
session_start();
require_once("inc/dbcon.php");
$loginRequired = true;
include("inc/header.php");
include("inc/upload.php");
$meTeamLog = array();
$title="meTeam";






//Update of Teammembers, if submit was pressed

if (isset($_FILES["fileUpload"]["name"]) && $_FILES["fileUpload"]["name"] != "") 
{
	
	$uploadSuccess = avatarUpload($_FILES["fileUpload"]);
	
	if($uploadSuccess != 1)
	{
		$meTeamLog["info"][] = $uploadSuccess;
	}
	else
	{
		pg_query_params("UPDATE account SET avatarpic = $1 WHERE id = $2",array($_FILES["fileUpload"]["name"],$_SESSION["sid"]));
		$meTeamLog["success"][] = "Avatar Uploaded";
		$fileUploadName = $_FILES["fileUpload"]["name"];
	}
}
	
if(isset($_POST["update"]))
{
	$btag1 = pg_escape_string($_POST["btag1"]);
	$btag2 = pg_escape_string($_POST["btag2"]);
	$btag3 = pg_escape_string($_POST["btag3"]);
	$btag4 = pg_escape_string($_POST["btag4"]);
	$btag5 = pg_escape_string($_POST["btag5"]);
	$btag6 = pg_escape_string($_POST["btag6"]);
	$btag7 = pg_escape_string($_POST["btag7"]);
	$aboutUs = pg_escape_string($_POST["aboutUs"]);	
	
	if($resAdmin->tinprogress == 't'){
		$meTeamLog["warning"][] = "Changing of teammprofiles currently not allowed";
	}
	if(strlen($btag1) > 24 || strlen($btag2) > 24 || strlen($btag3) > 24 || strlen($btag4) > 24 || strlen($btag5) > 24 || strlen($btag6) > 24 || strlen($btag7) > 24 || strlen($aboutUs) > 600){
		$meTeamLog["warning"][] = "Membernames must not contain more than 24 characters, AboutUs not more than 600";
	}
	
	if (empty($meTeamLog["warning"])) {
		pg_query_params("UPDATE account SET btag1 = $1,	btag2 = $2, btag3 = $3, btag4 = $4, btag5 = $5, btag6 = $6, btag7 = $7, aboutus= $9 WHERE id = $8",
            array($btag1,$btag2,$btag3,$btag4,$btag5,$btag6,$btag7,$_SESSION["sid"],$aboutUs));
    }
}



//Pulling of the Team and -member names
$meTeamRes = getAccountById($_SESSION["sid"]);

?>


<?php
include("inc/pageHeader.php");

if($resAdmin->tinprogress == 't'){
	$meTeamLog["info"][] = "A tournament is currently in progress or will start soon. Meanwhile, the changing of teammembers will not be allowed";
}


logEcho($meTeamLog);
?>


<div class="container">
<div class="jumbotron" style="width:70%;margin-left:3%;">
	
	<form class="form-horizontal" action="meteam.php" method="POST" enctype="multipart/form-data">
	<div class="control-group">
	  
	
		
		<div class="page-header">
			<div class="row">
				<div class="col-lg-6" style="word-wrap: break-word;">
					<h1><?php echo $meTeamRes->teamname;?></h1>
				</div>
				<div class="col-lg-6">
					<div class="">
						<?php
						if($meTeamRes->avatarpic != ""){
						?>					                                                     
							<img src="uploads/avatars/<?php echo $meTeamRes->avatarpic;?>" alt="" style="width:100%;">	
						<?php
						}
						?>                                                     
						<br>
						<h3>Load Avatar</h3>
						<input class="btn btn-default" type="file"  name="fileUpload">
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
							<li><input class="form-control roasterInput" <?php if($resAdmin->tinprogress == 't'){echo "disabled";} ?> name="btag1" value="<?php echo $meTeamRes->btag1;?>"></li>
						</ul>
						
						<h1><p class="text-warning">Substitutes</p></h1>
						<ul class="breadcrumb" style="margin-bottom:1%; margin-left:5%;">
							<li><input class="form-control roasterInput" <?php if($resAdmin->tinprogress == 't'){echo "disabled";} ?> name="btag6" value="<?php echo $meTeamRes->btag6;?>"></li>
						</ul>
						<ul class="breadcrumb" style="margin-left:5%;">
							<li><input  class="form-control roasterInput" <?php if($resAdmin->tinprogress == 't'){echo "disabled";} ?> name="btag7" value="<?php echo $meTeamRes->btag7;?>"></li>
						</ul>
					</div>
				</div>	
			</div>
	
	    	<div class="col-lg-6">
	    		<h1><p class="text-warning">Core Member</p></h1>
				
				<div class="row">
					<div class="col-lg-10">
				
						<ul class="breadcrumb" style="margin-bottom:1%; margin-left:5%;">
							<li><input class="form-control roasterInput" <?php if($resAdmin->tinprogress == 't'){echo "disabled";} ?> name="btag2" value="<?php echo $meTeamRes->btag2;?>"></li>
						</ul>
						<ul class="breadcrumb" style="margin-bottom:1%; margin-left:5%;">
							<li><input class="form-control roasterInput" <?php if($resAdmin->tinprogress == 't'){echo "disabled";} ?> name="btag3" value="<?php echo $meTeamRes->btag3;?>"></li>
						</ul>
						<ul class="breadcrumb" style="margin-bottom:1%; margin-left:5%;">
							<li><input class="form-control roasterInput" <?php if($resAdmin->tinprogress == 't'){echo "disabled";} ?> name="btag4" value="<?php echo $meTeamRes->btag4;?>"></li>
						</ul>
						<ul class="breadcrumb" style="margin-left:5%;">
							<li><input class="form-control roasterInput" <?php if($resAdmin->tinprogress == 't'){echo "disabled";} ?> name="btag5"  value="<?php echo $meTeamRes->btag5;?>"></li>
						</ul>
					</div>
	 			</div>		
			</div>
			
	 	</div>
	 	
	 	<h2>About us</h2>
	
		<div class="panel-body" style="padding:3%;">							
			<div class="panel panel-primary">		
				<div class="panel-body">					
					<textarea class="form-control roasterInput" name="aboutUs" rows="10" id="textArea"><?php echo $meTeamRes->aboutus;?></textarea>										
				</div>
			</div>								
		</div>
	 	
	 	<br><br>	
	 	
	    <div class="row">
	    	<div class="col-lg-12"> 	
				<input type="submit" class="btn btn-success btn-lg btn-block" name="update" value="Update"/>		
			</div>
	    </div>

	
	</div>
	  
	</form>
	    
	<br><br>	
	
	
	
	<h2>Stats</h2>
	<h4><p class="text-primary">Yet to come</p></h4>
	<h2>Recent Activities</h2>
	<h4><p class="text-primary">Yet to come</p></h4>

</div>
</div>

<?php
include("inc/footer.php");
?> 
