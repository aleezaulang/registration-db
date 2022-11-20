<?php 

	include 'database.php';
	include 'phpmailer/src/PHPMailer.php';
	include 'phpmailer/src/SMTP.php';
	include 'phpmailer/src/Exception.php';
	$message = "";
	
	if (isset($_POST['submit']))
	{
		
		if ($stmt_select = $con->prepare('SELECT id FROM accounts WHERE username = ? OR email = ?'))
		{
			$username = mysqli_real_escape_string($con, $_POST['username']);
			$email = mysqli_real_escape_string($con, $_POST['email']);
			
			$stmt_select->bind_param('ss', $username, $email);
			$stmt_select->execute();
			$stmt_select->store_result();
			
			if ($stmt_select->num_rows > 0)
			{
				$message = "<div class='alert alert-danger'>Username or email is already existing.</div>";
			}
			else 
			{
				if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email, fname, lname, dateadded) VALUES (?, ?, ?, ?, ?, ?);'))
				{
					$username = mysqli_real_escape_string($con, $_POST['username']);
					$password = password_hash ($_POST['password'], PASSWORD_DEFAULT);
					$email = mysqli_real_escape_string($con, $_POST['email']);
					$fname = mysqli_real_escape_string($con, $_POST['fname']);
					$lname = mysqli_real_escape_string($con, $_POST['lname']);
					$dateadded = date('Y-m-d h: i :sa');
			
					$stmt->bind_param('ssssss', $username, $password, $email, $fname, $lname, $dateadded);
					$stmt->execute() or die ('Error' . mysqli_error($con));
					$subject = "Account Registered.";
					$receiver = $email;
					$post = "Hello " . $fname . " " . $lname . "!<br./>" .
					"You have successfully registered.";
					$mail = new PHPMailer(true);
					$mail->IsSMTP();
					$mail->SMTPAuth = true;
					$mail->SMTPSecure = "ssl";
					$mail->Host = "smpt.gmail.com";
					$mail->Port = "465"; 
					$mail->Username = "benilde.web.development@gmail.com";
					$mail->Password = "A!thisisalongpassword1234";
					$mail->AddAddress =($email, "User 1");
					$mail->SetFrom = ("benilde.web.development@gmail.com", "The Administrator");
					$mail->Subject = $subject;
					$mail->Body = $post;
					$mail->Send();
					
					
					
					//mail($receiver, $subject, $post);
					$message = "<div class='alert alert-success'> Account registered. </div>";
				}
				else
				{
					$message = "<div class='alert alert-danger'>" . mysqli_error($con) . "</div>";
				}
			}
		}
		
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Exercise #5: Registration</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="css/fontawesome.min.css" />
	</head>
	<body>
			<div class="container">
				<div class="row">
					<form class="col" action="index.php" method="POST">
					<?php echo $message; ?>
						<div class="form-group">	
							<label>Username</label>
							<input type="text" name="username" class="form-control" required />
						</div>
						<div class="form-group">	
							<label>Password</label>
							<input type="password" name="password" class="form-control" required />
						</div>
						<div class="form-group">	
							<label>Email Address</label>
							<input type="email" name="email" class="form-control" required />
						</div>
						<div class="form-group">	
							<label>First Name</label>
							<input type="text" name="fname" class="form-control" required />
						</div>
						<div class="form-group">	
							<label>Last Name</label>
							<input type="text" name="lname" class="form-control" required />
						</div>
						<div class="form-group">
							<button type="submit" name="submit" class="btn btn-success">
							Submit
							</button>
						</div>
					</form>
					<div class="col">
						<?php 
							
						
						?>
					</div>
				</div>
				
			</div>
	</body>
</html>