<?php
$dbconn = pg_connect("dbname=XXX host=XXX.compute-1.amazonaws.com port=XXX user=XXX password=XXX sslmode=require")
    or die('Verbindungsaufbau fehlgeschlagen: ' . pg_last_error());

function db_fetch_object($query) {
    if (pg_num_rows($query) == 0)
        return null;
    else
        return pg_fetch_object($query);
}

function getTeamNameById($teamId){
    $res = db_fetch_object(pg_query_params("SELECT teamname FROM account WHERE id = $1",array($teamId)));
    if($res == null)
		if($teamId == -1)
			return "No Team";
		else
        	return "";
    else
        return $res->teamname;
}

function getAccountById($id) {
    return db_fetch_object(pg_query_params("SELECT * FROM account WHERE id = $1",array($id)));
}

function getAccountByEmail($email) {
    return db_fetch_object(pg_query_params("SELECT * FROM account WHERE LOWER(email) = LOWER($1)",array($email)));
}


function getAdmin() {
	return pg_fetch_object(pg_query("SELECT * FROM administration Where id=1"));
}

function getActiveTournament() {
    return db_fetch_object(pg_query("SELECT * FROM tournaments t natural join administration a WHERE a.id = 1"));
}

function getTournamentById($id) {
    return db_fetch_object(pg_query_params("SELECT * FROM tournaments WHERE id = $1",array($id)));
}

function getRegistration($teamId, $tournamentId) {
    return db_fetch_object(pg_query_params("SELECT * FROM tournamentregistrations WHERE tournamentid = $1 AND teamid = $2", array($tournamentId,$teamId)));
}

function addRegistration($teamId,$tournamentId) {
    pg_query_params("INSERT INTO tournamentregistrations (teamid, tournamentid) VALUES ($1, $2)", array($teamId,$tournamentId));
}

function removeRegistration($teamId,$tournamentId) {
    pg_query_params("DELETE FROM tournamentregistrations WHERE tournamentid = $1 AND teamid = $2", array($tournamentId, $teamId));
}

?>