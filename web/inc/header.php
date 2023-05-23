<?php
require_once("inc/dbcon.php");
require_once("inc/logEcho.php");
require_once("../vendor/autoload.php");

$loggedIn = false;
$mee =  basename($_SERVER["PHP_SELF"]);
$loginFormLog = array();

$resAdmin = getAdmin();

$curDateTime = date('Y-m-d H:i:s');

//checks for logged in user and sets var
if(isset($_SESSION["sid"]) && isset($_SESSION["semail"]))
{
	$acc = getAccountById($_SESSION["sid"]);
	if ($acc->email == $_SESSION["semail"])
	{
		$loggedIn = true;
	}
}

//Links to index.php if not loggedIn and user tries to use a loggenIn-only space
if(isset($loginRequired) && $loginRequired == true && $loggedIn == false)
{
	header("location:index.php");
}

if(isset($adminRequired) && $adminRequired == true && $_SESSION["sid"] != '0')
{
	header("location:index.php");
}
                                                   
//If there are Cookies, the Login (Session) will be performed with the Information given
if(isset($_COOKIE["cid"]) && isset($_COOKIE["cpass"])&& !isset($_SESSION["sid"]))		
{
	//for defect/suspicious Cookies, reset of Cookies
	function wrong()									
	{         
		setcookie('cid', '', time()-3600, '/');		
		setcookie('cpass', '', time()-3600, '/');
    	header("location:index.php");
		exit();
	}
  
	//Check for validity of values, load the user information from the db
	if(!is_numeric($_COOKIE["cid"]))
	{
		wrong();
		exit();
	}				
	
	$acc = getAccountById($_COOKIE["cid"]);		//Passwort das zu der ID passt wird aus Datenbank geholt.
	
	if($acc == null)
	{
		wrong();
		exit();
	}
	
  //Creation of the Session, if Password matches
	if($acc->pass == $_COOKIE["cpass"])							//Wenn Passwort passt. (ist in Cookie und Datenbank als md5-Hash gespeichert)
	{
    	session_start();
		$_SESSION["sid"] = $_COOKIE["cid"];						//ID wird in Session gespeichert (--> eingeloggt)
		$_SESSION["semail"] = $acc->email;
		$loggedIn = true;
	}
	else
	{
		wrong();
		exit();                                                                                                                                            
	}
}
//If the loginform was sent, the login check and session creation starts 
else if(isset($_POST["email"]))                                                // wenn kein cooky da ist dann neue id in session schreiben:
{
	if($_POST["email"] == "")
	{
		$loginFormLog["warning"][] = "Please enter a Username";
	}
	
	if($_POST["pass"] == "")                   // wenn es pw gibt aber keinen namen
	{
		$loginFormLog["warning"][] = "Please enter a Password";
	}
	
	                                                           								     // wenn post name und pass da sind und passen dann in session schreiben
	if(empty($loginFormLog))
	{
		$email = pg_escape_string($_POST["email"]);
		$pass = pg_escape_string($_POST["pass" ]);

        $acc = getAccountByEmail($email);
		#$sql = "SELECT id, email, pass, active FROM account WHERE email='".."' LIMIT 1";

		if($acc == null)
		{
			$loginFormLog["warning"][] = "Username doesn&#39t exist!";
		}
		else
		{
			//passwordcheck
			if(md5($pass) == $acc->pass)                                // starte PW abfrage
			{
				if ($acc->active)
				{
					$_SESSION["sid"] = $acc->id;
					$_SESSION["semail"] = $acc->email;
					$loggedIn = true;
					
					//If checkbox was selected, a Cookie is created
					if(isset($_POST["stay"]) && $_POST["stay"] == 'on')
					{
						setcookie('cid',$acc->id,time()+2592000);					//2592000 sec are 30 days
						setcookie('cpass',md5($pass), time()+2592000);
					}
				}
				else
				{
					$loginFormLog["warning"][] = "Please activate your Account!";
					//+ direkt oder button f�r activation link email senden
				}
			}
			else
			{
				$loginFormLog["warning"][] = "Password and Username don&#39t match!";
			}
		}
	}
}

?>



<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Metactic</title>
		<meta charset="utf-8">

		<link rel="stylesheet" href="css/jquery.datatables.css"></style>
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/boboStyles.css">
		<link rel="stylesheet" href="css/checkbox.css">
		<script language="JavaScript" src="javascript.js"></script>
		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/jquery.dataTables.js"></script>
	</head>
	
	<style>
		html,body{
		    overflow-x: hidden; 
		}
	</style>
	
	<script>
		$(document).ready(function(){
		    $('#myTable').dataTable();
		});
	</script>

	<body>
		<nav class="navbar navbar-default" style="margin-bottom:0px;">
			<div class="container-fluid">

				<div class="navbar-header">
					<a class="navbar-brand" href="index.php">Metactic</a>
				</div>

				<div>
					<ul class="nav navbar-nav">
						<li class="<?php if ($mee == "index.php") echo "active"; ?>">
							<a href="index.php">Home</a>
						</li>
						<li class="dropdown <?php if ($mee == "tournamentsactive.php" || $mee == "tournamentspast.php" || $mee == "tournamentsfuture.php") echo "active"; ?>">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Tournaments<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li>
									<a href="tournamentsactive.php">Active</a>
								</li>
								<li>
									<a href="tournamentspast.php">Past</a>
								</li>
								<li>
									<a href="tournamentsfuture.php">Future</a>
								</li>
							</ul>
						</li>
						<li class="<?php if ($mee == "info.php") echo "active"; ?>">
							<a href="info.php">Info</a>
						</li>
						<li class="<?php if ($mee == "teamprofiles.php") echo "active"; ?>">
							<a href="teamprofiles.php">Teams</a>
						</li>
						<li class="<?php if ($mee == "gameGuide.php") echo "active"; ?>">
							<a href="gameGuide.php">Guides</a>
						</li>
						<li class="<?php if ($mee == "halloffame.php") echo "active"; ?>">
							<a href="hallofheroes.php">Hall of Heroes</a>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">

						<?php 
						if (isset($_SESSION["sid"]))
						{
						?>
							<li class="<?php if ($mee == "account.php") echo "active"; ?>">
								<a href="account.php"><span class="glyphicon glyphicon-user"></span>Account</a>
							</li>
							<li class="<?php if ($mee == "meteam.php") echo "active"; ?>">
								<a href="meteam.php"><span class="glyphicon glyphicon-log-in"></span>meTeam</a>
							</li>
							<li class="<?php if ($mee == "support.php") echo "active"; ?>">
								<a href="support.php"><span class="glyphicon glyphicon-log-in"></span> Support</a>
							</li>
							<li class="<?php if ($mee == "logout.php") echo "active"; ?>">
								<a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a>
							</li>


						<?php 
						} 
						else 
						{
						?>
						<li class="dropdown" id="menuLogin">
							<a class="dropdown-toggle" href="#" data-toggle="dropdown" id="navLogin"><span class="glyphicon glyphicon-log-in"></span> Login</a>
							<div class="dropdown-menu" style="padding:17px;">
								<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">

									<fieldset>
										<div id="legend">
											<legend class="">
												Login
											</legend>
										</div>
	
										
		
      
						      <div class="control-group">
						      	<label class="control-label" for="email">Email</label>
						        <input type="text" id="email" name="email" placeholder="" class="input-xlarge">
						      </div>
						      <br>
						  
						 <div class="control-group">
						      <label class="control-label" for="password">Password</label> 
						        <input type="password"  name="pass" placeholder="" class="input-xlarge">
						    </div>   
						    <br>
						        
						      <div class="control-group">										
								<p class="help-block">
									Want to stay logged in?
								</p>								
								<input type="checkbox" name="stay" value="on" class="input-xlarge">								
							</div>
																
														
															
						
							<br>
							<div class="control-group">
						      	<input type="submit" class="btn btn-success"   value="Login">
							</div>
							
							<p class="help-block">
								<a href="lostLogin.php"><span class="text-primary">Lost Password?</span> </a>
							</p>	


  									</fieldset>
  
								</form>
							</div>
						</li>

						<!-- Register aufklapp form mit formziel register.php  -->

						<li>
							<a href="register.php"><span class="glyphicon glyphicon-log-in"></span> Register</a>
						</li>
						<?php 
						}
						?>
					</ul>
				</div>

			</div>
		</nav>


<?php
if($mee=="index.php")
{
	?>
	<div class="">                                                      
		<img src="img/tyrael3big4.png" alt="" style="width:100%; margin-bottom:-20%;">
	</div>
	<?php
}
else
{
	?>
	<div class="">                                                      
		<img src="img/tyrael3small4.png" alt="" style="width:100%; margin-bottom:-25%;">
	</div>
	<?php
}

