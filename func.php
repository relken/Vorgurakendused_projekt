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
	$stmt = mysqli_query($connection, "SELECT (
	SELECT nimi
	FROM rain_linnad
	WHERE id = algkoht_id
	) AS algkoht, (

	SELECT nimi
	FROM rain_linnad
	WHERE id = sihtkoht_id
	) AS sihtkoht, kuupaev_aeg, lennu_id
	FROM rain_lennud");
	include('view/pealeht.html');
}

function lisa_lend(){
	if(isset($_POST['variable'])) {
		global $connection;
		$sisestus = mysqli_real_escape_string ($connection, $_POST['variable']);
		$date = date_create()->format('Y-m-d H:i:s');
		$query = "INSERT INTO rain_bron (lennu_id, user_id, kuupaev) VALUES ('$sisestus',1,'$date')";
		$stmt = mysqli_prepare($connection, $query);
		mysqli_stmt_bind_param($stmt, 'ii', $sisestus, $date);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		//$kontroll = mysqli_stmt_insert_id($stmt);
		//mysqli_stmt_close($stmt);
		//return $kontroll;
	}
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
?>