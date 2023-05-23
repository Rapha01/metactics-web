<?php
session_start();
require_once("inc/dbcon.php");
include("inc/header.php");
require_once("inc/captcha/simple-php-captcha.php");
$registerFormLog = array();
$title = "Register";


if (isset($_POST["regteamname"])) {
    $allpostvalues = $_POST["regteamname"] != "" && $_POST["regemail"] != "" && $_POST["regpass"] != "" && $_POST["regpasswh"] != "" && $_POST["captcha"] != "";

    if (!$allpostvalues)
        $registerFormLog["warning"][] = "Please enter all necessary Information!";
    else {
        $regteamname = pg_escape_string($_POST["regteamname"]);
        $regemail = pg_escape_string($_POST["regemail"]);
        $regpass = pg_escape_string($_POST["regpass"]);
        $regpasswh = pg_escape_string($_POST["regpasswh"]);
        $captcha = pg_escape_string($_POST["captcha"]);

        if (strlen($_POST["regteamname"]) < 4 || strlen($_POST["regteamname"])>18)
            $registerFormLog["warning"][] = "Teamname has to contain at least 4 and at maximum 18 characters!";
        else {
            $res = pg_query_params("SELECT teamname FROM account WHERE teamname = $1", array($regteamname));
            if (pg_num_rows($res) == 1)
                $registerFormLog["warning"][] = "Teamname is already in use!";
        }

        if (strlen($regpass) < 7)
            $registerFormLog["warning"][] = "Password has to contain at least 7 characters!";
        else if ($regpass != $regpasswh)
            $registerFormLog["warning"][] = "Password and its repetition dont match!";

        if (strlen($regemail) < 5 || !ereg("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,4}$", $regemail))
            $registerFormLog["warning"][] = "Enter a correct mail please!";
        else {
            $res = pg_query_params("SELECT email FROM account WHERE email = $1", array($regemail));
            if (pg_num_rows($res) == 1) {
                $registerFormLog["warning"][] = "Email already in use!";
            }
        }

        if ($_POST["captcha"] != $_SESSION['captcha']['code'])
            $registerFormLog["warning"][] = "Please enter correct Captcha!";
    }


    if (empty($registerFormLog)) {
        $letters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');

        $code = '';
        for ($i = 0; $i < 15; $i++) {
            $code .= $letters[rand(0, (sizeof($letters)) - 1)];
        }
        unset($letters);

        pg_query_params("INSERT INTO account (email,teamname,pass,active) VALUES ($1,$2,$3,$4)",
            array($regemail, $regteamname, md5($regpass), $code));

        if (pg_last_error())
            $registerFormLog["warning"][] = pg_last_error();
        else {
            $acc = getAccountByEmail($regemail);

            $text =
                "<strong>Hello you, fighter of " . $regteamname . "</strong><br />
                Thanks for your registration.<br /><br/>
                
                Heres your activation Link:<br />
                https://metactics.herokuapp.com/emailverify.php?id=" .$acc->id."&c=".$code."<br /><br/>
              
                Greets,<br />
                Metactic<br /><br /><br />
                
                In case it doesn't work visit metactics.herokuapp.com/emailverify.php and enter your validation Code manually: ".$code.".
                ";
			
			
			$sendgrid = new SendGrid('app36597875@heroku.com', 'tdhv2cwq2326');

			$message = new SendGrid\Email();
			$message->addTo($regemail)->
            setFrom('Metactic')->
            setSubject('Registration')->
            setText('')->
            setHtml($text);
			$response = $sendgrid->send($message);
			

            unset($regpass);
            unset($_POST["regpass"]);
            unset($regpasswh);
            unset($_POST["regpasswh"]);
            unset($regemail);
            unset($_POST["regemail"]);
            unset($regteamname);
            unset($_POST["regteamname"]);
            unset($code);
            unset($id);

            $registerFormLog["success"][] = "Registration sent!";
        }
    }
}


include("inc/pageHeader.php");
logEcho($registerFormLog);


$_SESSION['captcha'] = simple_php_captcha();
?>


<div class="container"><br>

    <div class="row" style="margin-left:2%;">
        <div class="col-lg-6">

            <form class="form-horizontal" action="register.php" method="POST">

                <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Email</label>

                    <div class="col-lg-10">
                        <input type="email" class=" form-control"
                               name="regemail"<?php if (isset($_POST["regemail"])) echo ' value="' . $_POST["regemail"] . '"' ?>>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Password</label>

                    <div class="col-lg-10">
                        <input type="password" class="form-control"
                               name="regpass"<?php if (isset($_POST["regpass"]) && !isset($registerFormError["pass"])) echo 'value="' . $_POST["regpass"] . '"' ?>>
                    </div>
                </div>
              
                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Password Verification</label>

                    <div class="col-lg-10">
                        <input type="password" class="form-control"
                               name="regpasswh"<?php if (isset($_POST["regpasswh"]) && !isset($registerFormError["pass"])) echo 'value="' . $_POST["regpasswh"] . '"' ?>>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Teamname</label>

                    <div class="col-lg-10">
                        <input type="text" class="form-control"
                               name="regteamname"<?php if (isset($_POST["regteamname"])) echo 'value="' . $_POST["regteamname"] . '"'; ?>>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Captcha</label>

                    <div class="col-lg-10">
                        <div class="row">
                            <div class="col-lg-6">
                                <img src=<?php echo $_SESSION['captcha']['image_src'] ?>" alt=" Captcha" />
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


