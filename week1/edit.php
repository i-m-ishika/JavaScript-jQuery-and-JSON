<?php
	session_start();
	require_once "pdo.php";
	if(!(isset($_SESSION['name'])&&isset($_SESSION['user_id']))){
		die('ACCESS DENIED');		
	}	
	if(isset($_POST['cancel'])){
		header('Location: index.php');
		return;
	}

	if( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])){
		$fail=false;

		$fn = $_POST['first_name'];
		$ln = $_POST['last_name'];
		$email = $_POST['email'];
		$head = $_POST['headline'];
		$summary = $_POST['summary'];

		if(strlen($fn)<1 || strlen($ln)<1 || strlen($email)<1 || strlen($head)<1 || strlen($summary)<1){
			$fail = "All fields are required";
		}
		else if(strpos($email,'@')===false){
			$fail="Email address must contain @";
		}
		if($fail!==false){
			$_SESSION['error']=$fail;
			header('Location: edit.php?profile_id='.$_POST['profile_id']);
			return;
		}

		$sql="update Profile set first_name=:fn, last_name=:ln, email=:email, headline=:head, summary=:summary where profile_id=:p";
		$stmt=$pdo->prepare($sql);
		$stmt->execute(array(
			':fn'=>$fn,
			':ln'=>$ln,
			':email'=>$email,
			':head'=>$head,
			':summary'=>$summary,
			':p'=>$_POST['profile_id']
		));
		$_SESSION['success']="Profile updated";
		header('Location:index.php');
		return;
		
	}

	if(!isset($_GET['profile_id'])){
		$_SESSION['error'] = "Could not load profile";
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
<title>67a00e4d Ishika Mitra's Profile Edit</title>
<?php require_once "bootstrap.php"?>
</head>
<body>
<div class="container">
<h1>Editing Profile for <?=$_SESSION['name']?></h1>
<?php
	if(isset($_SESSION['error'])){
		echo '<p style="color:red;">'.$_SESSION['error'].'</p>';
		unset($_SESSION['error']);
	}
?>

<form method="post" action="edit.php">
<p>First Name:
<input type="text" name="first_name" size="60" value="<?=htmlentities($row['first_name'])?>"/> </p>
<p>Last Name:
<input type="text" name="last_name" size="60"
value="<?=htmlentities($row['last_name'])?>"/></p>
<p>Email:
<input type="text" name="email" size="30"
value="<?=htmlentities($row['email'])?>"
/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"
value="<?=htmlentities($row['headline'])?>"
/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80" >
<?=htmlentities($row['summary'])?>
</textarea>
<p>
<input type="hidden" name="profile_id" value="<?=htmlentities($row['profile_id'])?>"/>
<input type="submit" value="Save">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>
</body>
</html>
