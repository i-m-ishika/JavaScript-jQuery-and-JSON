<?php
	session_start();
	require_once "pdo.php";
	if(isset($_POST['cancel'])){
		header('Location: index.php');
		return;		
	}
	if(isset($_POST['email'])&&isset($_POST['pass'])){
		$email=$_POST['email'];
		$pass=$_POST['pass'];
		$salt='XyZzy12*_';
		$check=hash('md5',$salt.$pass);
		$stmt=$pdo->prepare('select user_id, name from users where email = :email and password = :pass');
		$stmt->execute(array(':email'=>$email,':pass'=>$check));
		$row=$stmt->fetch(PDO::FETCH_ASSOC);

		if($row!==false){
			$_SESSION['name']=$row['name'];
			$_SESSION['user_id']=$row['user_id'];
			header('Location: index.php');
			return;
		}
		else{
			$_SESSION['error']="Incorrect password";
			header('Location: login.php');
			return;
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
<title>67a00e4d Ishika Mitra's Login Page</title>
<?php require_once "bootstrap.php"?>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
	if(isset($_SESSION['error'])){
		echo '<p style="color:red;">'.$_SESSION['error']."</p>\n";
		unset($_SESSION['error']);
	}
?>
<form method="POST" action="login.php">
	<label for="email">Email</label>
	<input type="text" name="email" id="email"><br/>
	<label for="pass">Password</label>
	<input type="password" name="pass" id="pass"><br/>
	<input type="submit" onclick="return doValidate();" value="Log In">
	<input type="submit" name="cancel" value="Cancel"> 
</form>
<p>
For a password hint, view source and find an account and password hint
in the HTML comments.
<!-- Hint: 
The account is umsi@umich.edu
php123. -->

</p>
<script>
	function doValidate(){
		console.log('Validating');
		try{
			addr = document.getElementById('email').value;
			pw = document.getElementById('pass').value;
			console.log("Validating addr="+addr+" pw="+pw);

			if(addr==null||addr==""||pw==null||pw==""){
				alert("Both fields must be filled out");
				return false;
			}
			if(addr.indexOf('@')==-1){
				alert("Invalid email address");
				return false;
			}
			return true;
		}
		catch(ex){
			return false;
		}
		return false;
	}
</script>

</body>

</html>

