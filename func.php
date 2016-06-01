<?php
function connect_db(){
	global $connection;
	$host="localhost";
	$user="test";
	$pass="t3st3r123";
	$db="test";
	$connection = mysqli_connect($host, $user, $pass, $db) or die("ei saa ühendust mootoriga- ".mysqli_error());
	mysqli_query($connection, "SET CHARACTER SET UTF8") or die("Ei saanud baasi utf-8-sse - ".mysqli_error($connection));
}

function kuva_tabel(){
	global $connection;
	$stmt = mysqli_query($connection, "SELECT (SELECT nimi FROM rain_linnad WHERE id = algkoht_id) AS algkoht, 
	( SELECT nimi FROM rain_linnad WHERE id = sihtkoht_id) AS sihtkoht, kuupaev_aeg, lennu_id FROM rain_lennud");
	include('view/pealeht.html');
}

function lisa_lend(){
	global $connection;
	if(!empty($_POST['variable']) && !empty($_SESSION['user'])) {		
		$sisestus = mysqli_real_escape_string ($connection, $_POST['variable']);
		$date = date_create()->format('Y-m-d H:i:s');
		$username = $_SESSION['user'];
		$query = "INSERT INTO rain_bron (lennu_id, username, kuupaev) VALUES ('$sisestus','$username','$date')";
		$stmt = mysqli_prepare($connection, $query);
		mysqli_stmt_bind_param($stmt, 'isi', $sisestus, $username, $date);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);	
	}
}	

function kuva_broneeringud() {
	global $connection;
	if(!empty($_SESSION['user'])) {		
	$username = $_SESSION['user'];
		$stmt = mysqli_query($connection, "SELECT rain_bron.username, rain_lennud.lennu_id, rain_linnad.nimi AS algkoht, rain_linnad_dupl.nimi AS sihtkoht, rain_lennud.kuupaev_aeg, rain_bron.kuupaev
		FROM ((rain_bron LEFT JOIN rain_lennud ON rain_bron.lennu_id = rain_lennud.lennu_id) 
		LEFT JOIN rain_linnad ON rain_lennud.algkoht_id = rain_linnad.id) 
		LEFT JOIN rain_linnad AS rain_linnad_dupl ON rain_lennud.sihtkoht_id = rain_linnad_dupl.id
		HAVING (((rain_bron.username)= '$username' ))");
		$row = mysqli_num_rows($stmt);
	include('view/broneeringud.html');		
	} else {header('Location: ?page=pealeht');}
}

function registreeri(){
  global $connection;
  $errors = array();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(!empty($_POST)){
			if(empty($_POST["eesnimi"])) {$errors[] = "Eesnimi on puudu, palun sisesta!";}
			if(empty($_POST["perenimi"])) {$errors[] = "Perekonnanimi on puudu, palun sisesta!";}
			if(empty($_POST["epost"])) {$errors[] = "E-post on puudu, palun sisesta!";}
			if(empty($_POST["username"])){$errors[] = "Kasutajanimi on puudu, palun sisesta!";}
			if(empty($_POST["parool"])){$errors[] = "Parool on puudu, palun sisesta!";}
			if(empty($_POST["parool2"])) {$errors[] = "Parool uuesti on puudu, palun sisesta!";}
			if($_POST["parool"] != $_POST["parool2"]) {$errors[]= "Paroolid ei klapi, palun sisesta uuesti!";}
			if (empty($errors)) {
				$eesnimi = mysqli_real_escape_string ($connection, $_POST["eesnimi"]);
				$perenimi = mysqli_real_escape_string ($connection, $_POST["perenimi"]);
				$epost = mysqli_real_escape_string ($connection, $_POST["epost"]);
				$username = mysqli_real_escape_string ($connection, $_POST["username"]);
				$parool = mysqli_real_escape_string ($connection, $_POST["parool"]);
				$query = "INSERT INTO rain_users (eesnimi, perenimi, epost, username, parool) VALUES ('$eesnimi','$perenimi','$epost','$username', SHA1('$parool'))";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, 'sssss', $eesnimi, $perenimi, $epost, $username, SHA1($parool));
				mysqli_stmt_execute($stmt);
				$kontroll = mysqli_insert_id($connection);
				if($kontroll){		
					header('Location: ?page=pealeht');
					exit;
				} else {
					header('Location: ?page=register');
					exit;
				}
				mysqli_stmt_close($stmt);
			}
		}		
	}
	include('view/register.html');	
}

function login(){
  global $connection;
  $errors = array();
      	if($_SERVER["REQUEST_METHOD"] == "POST"){
			if(!empty($_POST)){
				if(empty($_POST["username"])) {$errors[] = "Kasutajanimi on puudu, palun sisesta!";}
				if(empty($_POST["parool"])) {$errors[] = "Parool on puudu, palun sisesta!";}
				if (empty($errors)) {
					$username = mysqli_real_escape_string ($connection, $_POST["username"]);
					$parool = mysqli_real_escape_string ($connection, $_POST["parool"]);
					$query = "SELECT eesnimi, perenimi, username, parool FROM rain_users WHERE username='$username' AND parool = SHA1('$parool')";
					$result = mysqli_query($connection, $query);
					$row = mysqli_num_rows($result);
					while ($r = mysqli_fetch_array($result)) {
						$_SESSION['user'] = $r['username'];
						$_SESSION['eesnimi'] = $r['eesnimi'];
						$_SESSION['perenimi'] = $r['perenimi'];
					}
					if($row >=1){
					header('Location: ?page=pealeht');
					} else {
						$errors[] = "Kasutajanimi või parool on vale, palun sisesta uuesti!";
					}

				}				
			}			
		}
include('view/login.html');
}

function logout(){
	$_SESSION = array();
	session_destroy();
	header("Location: ?");
}
?>