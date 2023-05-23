<?php
session_start();
         
if(isset($_COOKIE["cid"]) && isset($_COOKIE["cpass"]))
{
  setcookie('cid', '', time()-3600);				//Cookies werden gelöscht (Zeit auf negativ und Inhalt leer)
	setcookie('cpass', '', time()-3600);
}
  
session_destroy();
header("location:index.php");  
?>