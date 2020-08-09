<?php
	session_start();
	require_once  "pdo.php";
	if(!(isset($_SESSION['name'])&&isset($_SESSION['user_id']))){
		die('Not logged in');
	}
	if(isset($_POST['cancel'])){
		header('Location: index.php');
		return;
	}

	if(isset($_POST['delete']) && isset($_POST['profile_id'])){

		$sql='delete from Profile where profile_id='.$_POST['profile_id'];
		$stmt=$pdo->prepare($sql);
		$stmt->execute();
		$_SESSION['success']="Profile deleted";
		header('Location: index.php');
		return;
	}


	if(!isset($_GET['profile_id'])||strlen($_GET['profile_id'])<1){
		$_SESSION['error']="Could not load profile";
		header('Location: index.php');
		return;
	}

	$stmt = $pdo->prepare("select profile_id, first_name, last_name from Profile where user_id =:uid and profile_id= :pid");
	$stmt->execute(array(
		':uid'=>$_SESSION['user_id'], 
		':pid'=>$_GET['profile_id'])
	);
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
<title>67a00e4d Ishika Mitra's Profile Add</title>
<?php require_once "bootstrap.php"?>
</head>
<body>
<div class="container">
<h1>Deleting Profile</h1>
<form method="post" action="delete.php">
<p>First Name: <?=htmlentities($row['first_name'])?></p>
<p>Last Name: <?=htmlentities($row['last_name'])?></p>
<input type="hidden" name="profile_id" value="<?=$row['profile_id']?>"/>
<input type="submit" name="delete" value="Delete">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

</div>
</body>
</html>