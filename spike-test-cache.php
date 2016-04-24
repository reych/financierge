<?php
// Load the cache process
include("cache.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Financierge | Cache Test</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel = "stylesheet" type="text/css" href = "view/style.css">
  <link href='https://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="container-fluid">
	<h1>Spike Test Cache</h1>
	<hr>
		<div class = "login-outer">
			<div class = "login-module module">
                <p id='loginResult'></p>
				<form id = "userLoginForm">
				  <div class = "form-label">Username:</div>
				  <input id = "login-username" type="text" name="username" >
				  <br>
				  <div class = "form-label">Password:</div>
				  <input id = "login-password" type="password" name="password">
                  <br>
                  <br>
                  <button type="button" class="btn btn-primary" id = "login-submit" onclick="fakeLogin();">Fake Login</button>
				</form>
				<br>

			</div>
		</div>
</div>
</body>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script>
  	function fakeLogin() {
 		var element = document.getElementById("loginResult");
 		element.innerHTML = "This is a fake login";
  	}
  </script>
</html>

<?php
// Save the cache
include("cache_footer.php");
?>
