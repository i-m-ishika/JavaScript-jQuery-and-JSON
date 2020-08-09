<?php
	session_start();
	require_once "pdo.php";
	if(!isset($_GET['profile_id']) || strlen($_GET['profile_id'])<1){
		$_SESSION['error']="Could not load profile";
		header('Location: index.php');
		return;
	}
	$sql="select * from Profile where profile_id=:pid";
	$stmt=$pdo->prepare($sql);
	$stmt->execute(array(':pid'=>$_GET['profile_id']));
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	if($row===false){
		$_SESSION['error']="Could not load profile";
		header('Location: index.php');
		return;	
	}
?>
<!DOCTYPE html>
<html>
<head>
<title>67a00e4d Ishika Mitra's Profile View</title>
<?php require_once "bootstrap.php"?>
</head>
<body>
<div class="container">
<h1>Profile information</h1>
<p>First Name: <?=htmlentities($row['first_name'])?> </p>
<p>Last Name: <?=htmlentities($row['last_name'])?></p>
<p>Email: <?=htmlentities($row['email'])?></p>
<p>Headline:<br/>
<?=htmlentities($row['headline'])?></p>
<p>Summary:<br/>
<?=htmlentities($row['summary'])?><p>
</p>
<a href="index.php">Done</a>
</div>
</body>
</html>

