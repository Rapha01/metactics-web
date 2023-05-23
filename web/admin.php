<?php
session_start();
require_once("inc/dbcon.php");
include("inc/printbracket.php");
include("inc/showParticipants.php");
$adminRequired = true;
include("inc/header.php");


$adminLog = array();
$title = "Admin Space";






// Set or unset selected Tournament active
if (isset($_POST["editActualT"])) {
    pg_query_params("UPDATE administration SET actualtid = $1 WHERE id =1", array($_POST["editActualT"]));
    $resAdmin = getAdmin();
}


//Rewrite Tournament brackets
if (isset($_POST["editMatches"])) {
    foreach ($_POST as $name => $value) {
        $matchId = filter_var($name, FILTER_SANITIZE_NUMBER_INT);
        //echo $matchId;

        //TeamIds
        if (strpos($name, 'editMatchTeamTop') === 0) {

            //echo "match ID: " . $matchId . "  insert top: " . $value . "<br>";
            pg_query_params("UPDATE matches SET teamId1 = $1 WHERE id = $2", array($value, $matchId));
        }

        if (strpos($name, 'editMatchTeamBot') === 0) {
            //echo "match ID: " . $matchId . "  insert bot: " . $value . "<br>";
            pg_query_params("UPDATE matches SET teamId2 = $1 WHERE id = $2", array($value, $matchId));
        }

        //Scores

        if (strpos($name, 'editMatchScoreTop') === 0) {

            //echo "match ID: " . $matchId . "  insert top: " . $value . "<br>";
            pg_query_params("UPDATE matches SET score1 = $1 WHERE id = $2", array($value, $matchId));
        }

        if (strpos($name, 'editMatchScoreBot') === 0) {
            //echo "match ID: " . $matchId . "  insert bot: " . $value . "<br>";
            pg_query_params("UPDATE matches SET score2 = $1 WHERE id = $2", array($value, $matchId));
        }
    }
}

if (isset($_POST["ticketId"]) && (isset($_POST["editTicket"]) || isset($_POST["resolved"]))) {
    pg_query_params("UPDATE supporttickets SET response = $1, resolved = $2 WHERE id = $3",
        array($_POST["editTicket"], $_POST["resolved"], $_POST["ticketId"]));
}


if (isset($_POST["showResolvedTickets"])) {
    pg_query_params("UPDATE administration SET showresolvedsupporttickets = $1 WHERE id = 1", array($_POST["showResolvedTickets"]));
    $showResolvedSupportTickets = $_POST["showResolvedTickets"];
}


//Add Tournament Slots
if (isset($_POST["addMatchesToTournament"])) {
    //mysql_query("UPDATE administration SET actualTID = '".$_POST["editActualT"]."' WHERE id ='1'");
    //$actualTId = $_POST["editActualT"];

    $row = pg_fetch_row(pg_query("SELECT MAX(id) FROM matches"));
    $highestMatchId = $row[0];

    pg_query_params("DELETE FROM matches WHERE tournamentId = $1", array($resAdmin->actualtid));

    $matchCount = $_POST["addMatchesToTournament"] / 2;
    $i = 1;
    $j = 0;

    while ($matchCount != 1) {
        for ($j = 1; $j <= $matchCount; $j++) {

            $matchId = $highestMatchId + $j;
            $parentId = $highestMatchId + $matchCount + ceil($j / 2);

            /*echo $highestMatchId.":".$_POST["addMatchesToTournament"]."   ";
            echo "bId: ".$matchId." parentId: ".$parentId." j: ".$j."  ";
            echo $matchId-$highestMatchId.":".$_POST["addMatchesToTournament"]."  <br> ";*/

            pg_query_params("INSERT INTO matches (id,tournamentId, parentId) VALUES ($1,$2,$3)",
                array($matchId, $resAdmin->actualtid, $parentId));


        }

        $highestMatchId = $highestMatchId + $j - 1;
        $matchCount = $matchCount / 2;

        //winner match with parent null
        if ($matchCount == 1) {
            //letzte zeile $matchId+1,

            /*echo "bId: ".($matchId +1)." parentId: null"." j: ".$j."  ";
            echo $matchId-$highestMatchId.":".$_POST["addMatchesToTournament"]."  <br> ";*/
            pg_query_params("INSERT INTO matches (id,tournamentId, parentId) VALUES ($1,$2,$3)",
                array($matchId + 1, $resAdmin->actualtid, null));
        }

    }
}


//echo $_POST["activeTtimerDate"];
//echo $_POST["activeTstate"];
//echo $_POST["activeTshout"];
//echo $_POST["activeTshoutColor"];
//echo $_POST["activeTshowBracket"];

//active Tournament Settings
if (isset($_POST["activeTtimerDate"])) {
    if ($_POST["activeTtimerDate"] == "" || $_POST["activeTtimerDate"] == "0") {
        pg_query("UPDATE administration SET activettimerDate = '0000-00-00 00:00:00' WHERE id = 1");
    }

    if (is_numeric(strtotime($_POST["activeTtimerDate"]))) {
        pg_query_params("UPDATE administration SET activettimerdate = $1 WHERE id = 1", array($_POST["activeTtimerDate"]));
    }

    if (isset($_POST["activeTstate"]) && isset($_POST["activeTshout"]) && isset($_POST["activeTshoutColor"])) {
        pg_query_params("UPDATE administration SET activetstate = $1, activetstateInfo = $2, activetshout = $3, activetshoutcolor = $4 WHERE id = 1",
            array($_POST["activeTstate"], $_POST["activeTstateInfo"], $_POST["activeTshout"], $_POST["activeTshoutColor"]));
    }

    if (isset($_POST["tSetActive"])) 
        pg_query("UPDATE administration SET tinprogress = true WHERE id = 1");
    else
        pg_query("UPDATE administration SET tinprogress = false WHERE id = 1");

    if (isset($_POST["activeTshowBracket"]))
        pg_query("UPDATE administration SET activetshowbracket = true WHERE id = 1");
    else
        pg_query("UPDATE administration SET activetshowbracket = false WHERE id = 1");

}


//Edit Participants
if (isset($_POST["editParticipantsSubmit"])) {
    if ($_POST["addParticipant"] != "") {
        $addParticipant = pg_escape_string($_POST["addParticipant"]);


        if (!is_numeric($addParticipant)) {
            $res = pg_query_params("SELECT id FROM account WHERE LOWER(teamname) = LOWER($1)", array($addParticipant));
            if (pg_num_rows($res) == 1)
                $addParticipant = pg_fetch_object($res)->id;
        }

        if (is_numeric($addParticipant) && getTeamNameById($addParticipant) != "") {
            pg_query_params("INSERT INTO tournamentregistrations (teamid, tournamentid) VALUES ($1,$2)",
                array($addParticipant, $resAdmin->actualtid));
        } else {
            $adminLog["warning"][] = "Cant add Participant: No such Name/id found.";
        }

    }


    foreach ($_POST as $name => $value) {
        if (strpos($name, 'delParticipant') === 0) {
            pg_query_params("DELETE FROM tournamentregistrations WHERE tournamentid=$1 AND teamid=$2", array($resAdmin->actualtid, $value));
        }
    }
}


//update admin object
$resAdmin = getAdmin();
?>







<head>
    <style>
    </style>
</head>

<body>
<?php
include("inc/pageHeader.php");
logEcho($adminLog);
?>
<div class="container">
    <div class="jumbotron">

        <form class="form-horizontal" action="admin.php" method="post">
            <fieldset>

                <legend></legend>
                <div class="form-group">
                    <label for="select" class="col-lg-2 control-label">Select Tournament</label>

                    <div class="col-lg-10">
                        <select name="editActualT" class="form-control" id="select">
                            <?php
                            $query = pg_query("SELECT * FROM tournaments");
                            while ($rowT = pg_fetch_object($query)) {
                                ?>
                                <option <?php if ($resAdmin->actualtid == $rowT->id) echo('selected="selected"') ?>
                                    value=<?php echo $rowT->id; ?>><?php echo($rowT->id . ": " . $rowT->name . ", " . $rowT->date);?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <br/><br/>

                <div class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </div>

            </fieldset>
        </form>

    </div>
</div>


<div class="container">
    <div class="jumbotron">
        <form class="form-horizontal" action="admin.php" method="POST">
            <legend>Active Tournament Settings</legend>
            <div class="form-group">
                <label for="select" class="col-lg-2 control-label">Countdown to DateTime</label>

                <div class="col-lg-10">
                    <input type="text" class="form-control" name="activeTtimerDate"
                           value="<?php echo $resAdmin->activettimerdate; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="select" class="col-lg-2 control-label">State</label>

                <div class="col-lg-10">
                    <input type="text" class="form-control" name="activeTstate"
                           value="<?php echo $resAdmin->activetstate; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="select" class="col-lg-2 control-label">StateInfo</label>

                <div class="col-lg-10">
                    <input type="text" class="form-control" name="activeTstateInfo"
                           value="<?php echo $resAdmin->activetstateinfo; ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="select" class="col-lg-2 control-label">Shout</label>

                <div class="col-lg-6">
                    <input type="text" class="form-control" name="activeTshout"
                           value="<?php echo $resAdmin->activetshout; ?>">
                </div>
                <label for="select" class="col-lg-2 control-label">Color</label>

                <div class="col-lg-2">
                    <select name="activeTshoutColor" class="form-control" id="select">
                        <option value="warning" <?php if ($resAdmin->activetshoutcolor == "warning") {
                            echo "selected";
                        } ?>>warning
                        </option>
                        <option value="info" <?php if ($resAdmin->activetshoutcolor == "info") {
                            echo "selected";
                        } ?>>info
                        </option>
                        <option value="danger" <?php if ($resAdmin->activetshoutcolor == "danger") {
                            echo "selected";
                        } ?>>danger
                        </option>
                        <option value="success" <?php if ($resAdmin->activetshoutcolor == "success") {
                            echo "selected";
                        } ?>>success
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="select" class="col-lg-2 control-label">ShowBracket</label>

                <div class="col-lg-10">
                    <section title=".squaredOne">
                        <div class="squaredOne">
                            <input type="checkbox" id="squaredOne"
                                   name="activeTshowBracket" <?php if ($resAdmin->activetshowbracket == "t") echo "checked"; ?> />
                            <label for="squaredOne"></label>
                        </div>
                    </section>
                </div>
            </div>
            <div class="form-group">
                <label for="select" class="col-lg-2 control-label">Set Tournament Active</label>

                <div class="col-lg-10">
                    <section title=".squaredTwo">
                        <div class="squaredOne">
                            <input type="checkbox" id="squaredTwo"
                                   name="tSetActive" <?php if ($resAdmin->tinprogress == "t") echo "checked"; ?> />
                            <label for="squaredTwo"></label>
                        </div>
                    </section>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-10 col-lg-offset-2">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>
        </form>

    </div>
</div>


<div class="container">
    <div class="jumbotron">

        <div class="row">
            <div class="col-lg-12">

                <tbody>
                <legend>Edit Participants</legend>
                <button type="button" class="btn btn-success" data-toggle="collapse" data-target="#editParticipants">
                    Show Participants
                </button>
                </td>
                <div id="editParticipants" class="collapse">
                    <?php editParticipants($resAdmin->actualtid); ?>
                </div>
                </tbody>
                </p>
            </div>
        </div>

    </div>
</div>


<div class="container">
    <div class="jumbotron">

        <div class="row">
            <div class="col-lg-12">

                <tbody>
                <legend>Edit Tournament</legend>
                <button type="button" class="btn btn-success" data-toggle="collapse" data-target="#editBracket">Show
                    Bracket
                </button>
                </td>
                <div id="editBracket" class="collapse">
                    <?php editBracket($resAdmin->actualtid); ?>
                </div>
                </tbody>
                </p>
            </div>
        </div>

    </div>
</div>


<div class="container">
    <div class="jumbotron">

        <form class="form-horizontal" action="admin.php" method="post">

            <legend></legend>
            <div class="form-group">
                <label for="select" class="col-lg-2 control-label">Reset matches and create new matches in Playersize
                    of</label>

                <div class="col-lg-10">
                    <select name="addMatchesToTournament" class="form-control" id="select">
                        <option value="8">8</option>
                        <option value="16">16</option>
                        <option value="32">32</option>
                        <option value="64">64</option>
                    </select>
                </div>
            </div>

            <br/>

            <div class="form-group">
                <div class="col-lg-10 col-lg-offset-2">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>

        </form>

    </div>
</div>




<?php

$resS = pg_query("SELECT * FROM supporttickets");
?>

<div class="container">
    <div class="jumbotron">


        <legend>Support Tickets</legend>

        <form class="form-horizontal" action="admin.php" method="post" name="showResolvedTicketsForm">


            <div class="form-group">
                <div class="col-lg-10">
                    <div class="radio">
                        <label>
                            <input type="radio" name="showResolvedTickets" id="optionsRadios1" value="t"
                                   onchange="showResolvedTicketsForm.submit();" <?php if ($resAdmin->showresolvedsupporttickets == 't') {
                                echo "checked";
                            } ?>>
                            Show resolved Tickets too
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="showResolvedTickets" id="optionsRadios2" value="f"
                                   onchange="showResolvedTicketsForm.submit();" <?php if ($resAdmin->showresolvedsupporttickets == 'f') {
                                echo "checked";
                            } ?>>
                            Show unresolved only
                        </label>
                    </div>
                </div>
            </div>

            <input type="submit" style="display: none;" class="btn btn-success" value=""></input>

        </form>


        <div class="row">
            <div class="col-lg-1">
            </div>
            <div class="col-lg-10">

                <div class="row">
                    <div class="col-lg-3">
                        <h3>Subject</h3>
                    </div>
                    <div class="col-lg-3">
                        <h3>Team</h3>
                    </div>
                    <div class="col-lg-3">
                        <h3>Date</h3>
                    </div>
                    <div class="col-lg-3">
                        <h3>Resolved</h3>
                    </div>

                </div>



                <?php
                $i = 0;
                while ($rowS = pg_fetch_object($resS)) {
                    if ($resAdmin->showresolvedsupporttickets == 't' || $rowS->resolved == 'f') {
                        $i++;
                        ?>


                        <a data-toggle="collapse" data-target="#messageEmbed<?php echo $i; ?>">
                            <ul class="breadcrumb" id="tournamentLabel">

                                <div class="row">
                                    <div class="col-lg-3">
                                        <?php echo $rowS->subject; ?>
                                    </div>
                                    <div class="col-lg-3">
                                        <?php echo getTeamNameById($rowS->teamid); ?>
                                        <!--button type="button" class="btn btn-info" data-toggle="collapse" data-target="#messageEmbed<?php echo $i; ?>">Show Ticket</button-->
                                    </div>
                                    <div class="col-lg-3">
                                        <?php echo $rowS->date; ?>
                                    </div>
                                    <div class="col-lg-3">
                                        <?php if ($rowS->resolved == 't') {
                                            echo '<span class="label label-success">Yes</span>';
                                        } else {
                                            echo '<span class="label label-warning">No</span>';
                                        } ?>
                                    </div>

                                </div>
                            </ul>
                        </a>


                        <div id="messageEmbed<?php echo $i; ?>" class="collapse">


                            <div class="row">
                                <div class="col-lg-6">

                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Your Message</h3>
                                        </div>
                                        <div class="panel-body">
                                            <p><?php echo nl2br($rowS->message);?></p>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-6">

                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Our Response</h3>
                                        </div>
                                        <div class="panel-body">

                                            <form class="form" action="admin.php" method="post">
                                                <textarea class="form-control" name="editTicket" rows="10"
                                                          id="textArea"><?php echo $rowS->response;?></textarea>
                                                <input type="hidden" value="<?php echo $rowS->id;?>"
                                                       name="ticketId"></input>

                                                <div class="form-group">
                                                    <label class="col-lg-2 control-label">Radios</label>

                                                    <div class="col-lg-10">
                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" name="resolved" id="optionsRadios1"
                                                                       value="f" <?php if ($rowS->resolved == 0) {
                                                                    echo "checked";
                                                                }?>>
                                                                Unresolved
                                                            </label>
                                                        </div>
                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" name="resolved" id="optionsRadios2"
                                                                       value="t" <?php if ($rowS->resolved == 1) {
                                                                    echo "checked";
                                                                }?>>
                                                                Resolved
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php
                                                if ($rowS->filename != "") {
                                                    ?>
                                                    <p>Attachment: <a
                                                            href="uploads/support/<?php echo $rowS->filename; ?>"
                                                            class="btn btn-primary btn-sm"><?php echo " " . $rowS->filename; ?></a>
                                                    </p>
                                                <?php
                                                }
                                                ?>

                                                <input type="submit" class="btn btn-success" value="Update"></input>

                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>

                    <?php
                    }
                }
                ?>
            </div>

            <div class="col-lg-1">
            </div>
        </div>


    </div>
</div>





<?php
include("inc/footer.php");
?> 
