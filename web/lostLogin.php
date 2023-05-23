<?php
session_start(); 
require_once("inc/dbcon.php" );
include("inc/header.php");
include("inc/captcha/simple-php-captcha.php");
$lostLoginFormLog = array();
$title="Retrieve Password";
$allpostvalues =false;




if(isset($_POST["lostEmail"]))
{
	if ($_POST["lostEmail"] == "")
	{
		$lostLoginFormLog["warning"][] = "Please enter email!";
	}
	else
	{
		$email = pg_escape_string($_POST["lostEmail"]);	
		$acc = getAccountByEmail($email);
		if($acc == null)
			$lostLoginFormLog["warning"][] = "Email does not exist!";
	}
		
	if($_POST["captcha"] == "")
	{
		$lostLoginFormLog["warning"][] = "Enter Captcha please!";
	}
	else 
	{
		if($_POST["captcha"] != $_SESSION['captcha']['code'])
			{
				$lostLoginFormLog["warning"][] = "Please enter correct Captcha!";
			}
	}	

	if(empty($lostLoginFormLog))
	{
        $letters = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
                    'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9','0');
        $code = '';
        for($i = 0; $i < 15; $i++)
            $code .= $letters[rand(0,(sizeof($letters))-1)];
        unset($letters);
		
		pg_query_params("UPDATE account SET pass = $1 WHERE id=$2",array(md5($code),$acc->id));

	    if (pg_last_error())
	        $lostLoginFormLog["warning"][] = pg_last_error();
	    else {	
	        $text =
            "<strong>Hello you, fighter of ".$acc->teamname." ,</strong><br /><br />
            Your have requested a new Password.<br />
            Here is it: ".$code."<br /><br />

            Greets,<br />
            Metactic";
		
		
			$sendgrid = new SendGrid('app36597875@heroku.com', 'tdhv2cwq2326');

			$message = new SendGrid\Email();
			$message->addTo($acc->email)->
            setFrom('Metactic')->
            setSubject('Password Reset')->
            setText('')->
            setHtml($text);
			$response = $sendgrid->send($message);
		

            unset($email);unset($_POST["email"]);
            unset($code);

            $lostLoginFormLog["success"][] = "New Password sent!";
		}
	
	}

}

include("inc/pageHeader.php");
logEcho($lostLoginFormLog);

$_SESSION['captcha'] = simple_php_captcha();
?>


<div class="container"><br>

<?php 

?>

<div class="row">
	<div class="col-lg-1"></div>
	<div class="col-lg-6">
	
			<form class="form-horizontal" action="lostLogin.php" method="POST">
			  
				<div class="form-group">
					<label for="inputEmail" class="col-lg-2 control-label">Email</label>
					<div class="col-lg-10">
						<input type="email" class=" form-control" name="lostEmail"<?php if(isset($_POST["lostEmail"]))echo ' value="'.$_POST["lostEmail"].'"'?>>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-lg-2 control-label">Captcha</label>
					<div class="col-lg-10">
						<div class="row">
							<div class="col-lg-6">
								<img src=<?php echo $_SESSION['captcha']['image_src']?>" alt="Captcha" />
							</div>
							<div class="col-lg-6">
								<input type="text" class="form-control" name="captcha">
							</div>
						</div>
					</div>
				</div>
			
			
				<div class="form-group">
					<div class="col-lg-10 col-lg-offset-2">
						<button type="submit" class="btn btn-success">Submit</button>
					</div>
				</div>
			</form>
	
	</div>
	<div class="col-lg-5"></div>
</div>




</div>
<?php
include("inc/footer.php");
?>

