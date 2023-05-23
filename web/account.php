<?php 
session_start();
$loginRequired =true;
require_once("inc/dbcon.php");
include("inc/header.php");

$title = "Account Settings";
$accSettingsLog = array();

if (isset($_POST["oldpass"])) {
	
	$acc = getAccountById($_SESSION["sid"]);
	$oldpass = pg_escape_string($_POST["oldpass"]);
	$newpass = pg_escape_string($_POST["newpass"]);
	$newpasswh = pg_escape_string($_POST["newpasswh"]);

	if($acc->pass != md5($oldpass)) {
		$accSettingsLog["warning"][] = "You entered an incorrect old Password";
	}
		
	if($newpass != $newpasswh) {
		$accSettingsLog["warning"][] = "Your new password and it's repetition don't match";
	}
	
	if(empty($accSettingsLog["warning"])) {
		pg_query_params("UPDATE account SET pass = $1 WHERE id=$2",array(md5($newpass),$acc->id));
		$accSettingsLog["success"][] = "Password changed";
	}	
		

}

?>

<?php
include("inc/pageHeader.php");
logEcho($accSettingsLog);
?>
<div class="container">

<h2>Change Password</h2>
<br />
 <div class="row" style="margin-left:2%;">
        <div class="col-lg-6">
			
            <form class="form-horizontal" action="account.php" method="POST">

    			<div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Old Password</label>

                    <div class="col-lg-10">
                        <input type="password" class="form-control" name="oldpass">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Password</label>

                    <div class="col-lg-10">
                        <input type="password" class="form-control" name="newpass">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Password Verification</label>

                    <div class="col-lg-10">
                        <input type="password" class="form-control" name="newpasswh">
                    </div>
                </div>
               
                


                <div class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>

           

        </div>
        <div class="col-lg-6"></div>
    </div>
</div>
<?php
include("inc/footer.php");
?>