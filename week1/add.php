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

	if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])){
		
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
			header('Location: add.php');
			return;	
		}
		$sql = 'insert into Profile(user_id, first_name, last_name, email, headline, summary) values (:user, :fn, :ln, :email, :head, :summary)';
		$stmt=$pdo->prepare($sql);
		$stmt->execute(array(
			':user'=>$_SESSION['user_id'],
			':fn'=>$fn,
			':ln'=>$ln,
			':email'=>$email,
			':head'=>$head,
			':summary'=>$summary
		));
		$_SESSION['success']="Profile added";
		header('Location: index.php');
		return ;


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
<?php
	echo '<h1>Adding Profile for '.$_SESSION['name'].'</h1>';

	if(isset($_SESSION['error'])){
		echo '<p style="color:red";>'.$_SESSION['error'].'</p>';
		unset($_SESSION['error']);
	}

?>
<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"/></p>
<p>Email:
<input type="text" name="email" size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"></textarea>
<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>
</body>
</html>
