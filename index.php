<?php
session_start();
require_once('func.php');
connect_db();

 $page="";
if (isset($_GET['page']) && $_GET['page']!=""){
	$page=htmlspecialchars($_GET['page']);
}

require_once('view/head.html');

switch($page){
	case "login":
		login();
	break;
	case "register":
		registreeri();
	break;
	case "logout":
		logout();
	break;
	case "bron":
		kuva_broneeringud();
	break;
		case "lisalend":
		lisa_lend();
	break;
	default:
		kuva_tabel();
	break;
} 

require_once('view/foot.html');
?>