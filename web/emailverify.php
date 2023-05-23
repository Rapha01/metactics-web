<?php 
session_start();
$title="Account activation";
// get code, user und datenbank verifyen und antwort entsprechend schreiben
$emailVerifylog = array();
include("inc/header.php");



if(isset($_GET["c"]))
{
	if(isset($_GET["id"])) {
		$id = pg_escape_string($_GET["id"]);
		$acc = getAccountById($id);
	}
	if(isset($_GET["email"])) {
		$email = pg_escape_string($_GET["email"]);
		$acc = getAccountByEmail($email);
	}
			
	$verifyCode = pg_escape_string($_GET["c"]);

	if($acc == null)
	    $emailVerifylog["warning"][] = "Unknown user.";
    else
	{
		$verifyId = $acc->id;
		if($acc->active == $verifyCode)
		{
			pg_query_params("UPDATE account SET active = '1' WHERE id = $1", array($verifyId));

            $emailVerifylog["success"][] = "Your account has been activated. You can log in now.";
		}
		else if($acc->active == "1")
            $emailVerifylog["warning"][] = "User already activated.";
        else
            $emailVerifylog["warning"][] = "Activation code is incorrect.";

	}
}


include("inc/pageHeader.php");
logEcho($emailVerifylog);

?>

<div class="container"><br>

    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-6">

          

            <form class="form-horizontal" action="emailverify.php" method="get">

              
                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Email</label>

                    <div class="col-lg-10">
                        <input type="text" class="form-control" name="email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Code</label>

                    <div class="col-lg-10">
                        <input type="text" class="form-control" name="c">
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
