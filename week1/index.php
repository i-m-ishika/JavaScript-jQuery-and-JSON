<?php
session_start();
require_once "pdo.php";
$stmt=$pdo->query("select profile_id, first_name, last_name, headline from Profile");
$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<title>67a00e4d Ishika Mitra's Resume Registry</title>
<?php require_once "bootstrap.php"?>
</head>
<body>
<div class="container">
<h1>Ishika Mitra's Resume Registry</h1>
<?php
	if(isset($_SESSION['success'])){
		echo '<p style="color:green";>'.$_SESSION['success'].'</p>';
		unset($_SESSION['success']);
	}
	if(isset($_SESSION['error'])){
		echo '<p style="color:red";>'.$_SESSION['error'].'</p>';
		unset($_SESSION['error']);
	}
	if(isset($_SESSION['name'])&&isset($_SESSION['user_id'])){
		echo '<p><a href="logout.php">Logout</a></p>';
	}
	else{
		echo '<p><a href="login.php">Please log in</a></p>';	
	}
	echo '<table border="1">';
	echo '<tr><th>Name</th><th>Headline</th>';
	if(isset($_SESSION['name'])&&isset($_SESSION['user_id'])){
		echo '<th>Action</th></tr>';	
	}
	
	foreach($rows as $row){
		echo '<tr><td>';
		echo '<a href="view.php?profile_id='.$row['profile_id'].'">';
		echo(htmlentities($row['first_name']).' '.htmlentities($row['last_name']));
		echo '</a>';
		echo '</td><td>';
		echo(htmlentities($row['headline']));
		echo '</td>';

		if(isset($_SESSION['name'])&&isset($_SESSION['user_id'])){
			echo '<td>';
			echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a>');
		    echo(' <a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
			echo '</td>';
		}
		echo '</tr>';
	}

	echo '</table>';
	if(isset($_SESSION['name'])&&isset($_SESSION['user_id'])){
		echo '<p><a href="add.php">Add New Entry</a></p>'; 
	}
		
?>

</table>
</div>
</body>
</html>

