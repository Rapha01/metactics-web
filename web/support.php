<?php 
session_start();
require_once("inc/dbcon.php");
include("inc/upload.php");
include("inc/captcha/simple-php-captcha.php");
$loginRequired = true;
$supportFormLog = array();
include("inc/header.php");
$title="Support";


if(isset($_POST["subject"]))
{
	if($_POST["subject"] == "")
	{
		$supportFormLog["warning"][] = "Enter subject please!";
	} 

	if($_POST["message"] == "")
	{
		$supportFormLog["warning"][] = "Enter message please!";
	}
	
	if($_POST["captcha"] == "")
	{
		$supportFormLog["warning"][] = "Enter Captcha please!";
	}
	else 
	{
		if($_POST["captcha"] != $_SESSION['captcha']['code'])
			{
				$supportFormLog["warning"][] = "Please enter correct Captcha!";
			}
	}	
	

	$fileUploadName = "";
	if (isset($_FILES["fileUpload"]["name"]) && $_FILES["fileUpload"]["name"] != "") 
	{
		$uploadSuccess = supportUpload($_FILES["fileUpload"]);
		if($uploadSuccess != 1)
		{
			$supportFormLog["warning"][] = $uploadSuccess;
		}
		else
		{
			$fileUploadName = $_FILES["fileUpload"]["name"];
		}
	}
}

//If all ok, begin entry in DB
if(empty($supportFormLog["warning"]) && isset($_POST["subject"]))
{
		$subject = pg_escape_string($_POST["subject"]);
		$message = pg_escape_string($_POST["message"]);
		pg_query_params("INSERT INTO supporttickets (teamid, date, subject, message, response, resolved, filename) VALUES ($1,$2,$3,$4,$5,$6,$7)",
            array($_SESSION["sid"],$curDateTime,$subject,$message, 'Not answered yet, Sorry','f',$fileUploadName));
		$supportFormLog["success"][] = "Ticket sent. Thank you";
		unset($_POST["subject"]);
		unset($_POST["message"]);
}


if(isset($_POST["ticketId"]) && isset($_POST["editTicket"]))
{
	$ticketMessage = pg_escape_string($_POST["editTicket"]);
	$ticketId = pg_escape_string($_POST["ticketId"]);
    pg_query_params("UPDATE supporttickets SET message = $1 WHERE id = $2",array($ticketMessage,$ticketId));
}

$resS = pg_query_params("SELECT * FROM supporttickets WHERE teamid = $1",array($_SESSION["sid"]));


include("inc/pageHeader.php");
logEcho($supportFormLog);

$_SESSION['captcha'] = simple_php_captcha();
?>




<div class="container">
<h2>Write Support Ticket</h2>	
<br>
<div class="row" style="margin-left: 2%;">
	<div class="col-lg-5">
	
		<form class="form" action="support.php" method="post" enctype="multipart/form-data">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="form-group">
						<h3 class="panel-title"><input type="text" class="form-control" name="subject" placeholder="Subject" value="<?php if(isset($_POST["subject"])){echo $_POST["subject"];}?>"></h3>
					</div>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<textarea class="form-control" name ="message" rows="10" id="textArea" placeholder="Message"><?php if(isset($_POST["message"])){echo $_POST["message"];}?></textarea>
						<br>	
						<label class="control-label">Upload Replay</label>
						<input class="btn btn-default" type="file"  name="fileUpload">
						<br>
						<label class="control-label">Captcha</label>
						<div class="row">
							<div class="col-lg-1"></div>
							<div class="col-lg-6">
								<img src=<?php echo $_SESSION['captcha']['image_src']?>" alt="Captcha" />
							</div>
							<div class="col-lg-4">
								<input type="text" class="form-control" name="captcha">
							</div>
						</div>
				
					</div>
				</div>
			</div>
			
			
			<div class="form-group">
				<button type="submit" class="form-control btn btn-success">Send</button>
			</div>
		</form>
	
	</div>
	
	<div class="col-lg-6">
	</div>

</div>


</div>


<div class="container">
<h2>My Support Tickets</h2>
<br>
	
<?php 
$i=0;
while($rowS = pg_fetch_object($resS))
{
	$i++;			
	?>

	<div class="row">
		<div class="col-lg-1">
		</div>
		<div class="col-lg-10">
			
			<ul class="breadcrumb" id="supportLabel">
					<div class="row">
						<div class="col-lg-12">
							<div class="page-header" style="margin-left:2%;">
								<h3><?php echo $rowS->subject; ?></h3>
							</div>
						</div>
					</div>
	
					<div class="row" style="margin-left:2%;margin-bottom:1%;">
						<div class="col-lg-4">			
							<h3><p class="text-default"><?php echo $rowS->date; ?></p></h3>					
						</div>
						<div class="col-lg-4">					
							<h3><p class="text-default"><?php if($rowS->resolved == 't'){ echo '<span class="label label-success">Resolved</span>';}else{ echo '<span class="label label-warning">Unresolved</span>';} ?></p></h3>
						</div>
						<div class="col-lg-4">
							<a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $i;?>" class="btn btn-default btn-lg btn-block">Show Ticket</a>
						</div>
					</div>
			</ul>	
		</div>	
	  
		<div class="col-lg-1">
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-1">
		</div>
		<div class="col-lg-10">
			<div id="<?php echo $i; ?>" class="collapse" >
				<div class="panel-body" style="padding:3%;">
					<div class="row">
						
						<div class="col-lg-6">							
							<div class="panel panel-primary">
								<div class="panel-heading">
									<h3 class="panel-title">Your Message</h3>
								</div>
								<div class="panel-body">
									<form class="form" action="support.php" method="post">
										<textarea class="form-control"  name="editTicket" rows="18" id="textArea"><?php echo $rowS->message;?></textarea>										
										<input type="hidden" value="<?php echo $rowS->id;?>" name="ticketId"></input>
										<input type="submit" class="form-control btn btn-success" style="margin-top:1%;" value="Update"></input>
										<?php
										if($rowS->filename != "")
										{
										?>	
											<h3>Attachment</h3>
											<h4><p class="text-primary"><?php echo " ".$rowS->filename; ?></p></h4>
										<?php		
										}
										?>
									</form>
								</div>
							</div>						
						</div>
								
						<div class="col-lg-6">
							<div class="panel panel-info">
								<div class="panel-heading">
									<h3 class="panel-title">Our Response</h3>
								</div>
								<div class="panel-body">
									<p><?php echo nl2br($rowS->response);?></p>
								</div>
							</div>						
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-1">
		</div>
	</div>	
	
	
				
						
									
			
<?php	
}
?>

</div>




<?php
include("inc/footer.php");
?> 