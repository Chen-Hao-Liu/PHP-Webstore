<html lang="en">
<head>
  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    .navbar{
      margin-bottom: 0;
      border-radius: 0;
    }
   
    .row.content {height: 450px}
   
    .sidenav{
      padding-top: 20px;
      background-color: #f1f1f1;
      height: 100%;
    }

    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }

    table td{
      border: 0;
    }

    @media screen and (max-width: 767px) {
      .sidenav{
        height: auto;
        padding: 15px;
      }
      .row.content{height:auto;}
    }
  </style>
</head>

<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand">QuickBuy</a>
    </div>
   
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
	<li><a>
	   <form id="log" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
           <input type="hidden" id="login" name="login" value="1">
	   <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-log-in"></span> Login</button>
           </form>
        </a></li>
	<li><a>
	   <form id="sig" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
           <input type="hidden" id="sign" name="sign" value="1">
	   <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Sign Up</button> 
           </form>
	</a></li>
	<li><a>
	   <form id="nologin" method="post" action="noLogIn.php">
           <input type="hidden" id="nolog" name="nolog" value="1">
	   <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-chevron-right"></span> Continue Without Login</button> 
           </form>
        </a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid text-center">
  <div class="row content">
    <div class="col-sm-2 sidenav">
    </div>

    <div class="col-sm-8 text-left">

<?php
  require_once('connectvars.php');

  // TODO: Start the session
  session_start();  

  if(!isset($_POST['login'])){
      $_POST['login'] = 0;
  }

  if(!isset($_POST['lFlag'])){
      $_POST['lFlag'] = 0;
  }
  
  if($_POST['login'] == 1 || $_POST['lFlag'] == 1){ 
  echo '<h3>Login To Continue</h3>';
  // Clear the error message
  $error_msg = "";

  // TODO: If the user isn't logged in, try to log them in
  if (!isset($_POST['user_id'])) {
    if (isset($_POST['submit'])) {
      // Connect to the database
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

      // Grab the user-entered log-in data
      $user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
      $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));
       
      if (!empty($user_username) && !empty($user_password)) {
        // TODO: Look up the username and password in the database
	//$query = ;
	$query = "SELECT username, password, uid FROM users WHERE '$user_username'=username and '$user_password'=password";
        $data = mysqli_query($dbc, $query);

        // If The log-in is OK 
	if (mysqli_num_rows($data) == 1) {
          //Set no login condition to false
          $_SESSION['nolog'] = 0;	
          
          $row = mysqli_fetch_array($data);

          //TODO: so set the user ID and username session vars
	  $_SESSION['user_id'] = $row["uid"];
	  $_SESSION['username'] = $row["username"];
          //TODO: redirect to index.php 
          $home_url ='http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . '/index.php';
          header('Location: ' . $home_url);
        }
        else {
          // The username/password are incorrect so set an error message
          $error_msg = 'Sorry, you must enter a valid username and password to log in.';
        }
      }
      else {
        // The username/password weren't entered so set an error message
        $error_msg = 'Sorry, you must enter your username and password to log in.';
      }
    }
  }

  // If the session var is empty, show any error message and the log-in form; otherwise confirm the log-in
  if (empty($_SESSION['user_id'])) {
    echo '<p class="error">' . $error_msg . '</p>';
?>

  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
      <label for="username">Username:</label>
      <input type="text" name="username" value="<?php if (!empty($user_username)) echo $user_username; ?>" /><br />
      <label for="password">Password:</label>
      <input type="password" name="password" />
    </fieldset>
    <input type="hidden" id="lFlag" name="lFlag" value="1">
    <input type="submit" value="Log In" name="submit" />
    <!--<input type="submit" value="Continue Without Login" formaction="index.php">-->
  </form>
<?php
  }
  else {
    // Confirm the successful log-in
    echo('<p class="login">You are logged in as ' . $_SESSION['username'] . '.</p>');
  }
  //Both are used to keep track of log in page
  $_POST['lFlag'] = 0;
  $_POST['login'] = 0;
  }else{

    echo '<h3>Sign Up Below!</h3>';
    //Checks to see if user has entered data 	  
    if (isset($_POST['sign_up'])) {
      if($_POST['sign_up'] == 1){
          //Checks that all required fields are set and non-empty 
	  if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['fname']) && isset($_POST['lname'])){
              if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['fname']) && !empty($_POST['lname'])){
                  // Connect to the database
		  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

                  // Grab the user-entered log-in data
                  $user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
	          $user_password = mysqli_real_escape_string($dbc, trim($_POST['password'])); 
		  $user_fname = mysqli_real_escape_string($dbc, trim($_POST['fname']));
		  $user_lname = mysqli_real_escape_string($dbc, trim($_POST['lname']));
                  $user_insert = "";

		  //Middle initial is not required. If minit is set and not empty, insert accordingly
		  if(isset($_POST['minit'])){
		      if(!empty($_POST['minit'])){	  
			  $user_minit = mysqli_real_escape_string($dbc, trim($_POST['minit']));
		          $user_insert = "INSERT INTO users(fname, minit, lname, username, password) VALUES('" . $user_fname . "', '" . $user_minit . "', '" . $user_lname . "', '" . $user_username . "', '" . $user_password . "')";
		      }else{
		          //Insert without middle initial 
		          $user_insert = "INSERT INTO users(fname, lname, username, password) VALUES('" . $user_fname . "', '" . $user_lname . "', '" . $user_username . "', '" . $user_password . "')";
		      }
		  }else{
		      //Insert without middle initial 
		      $user_insert = "INSERT INTO users(fname, lname, username, password) VALUES('" . $user_fname . "', '" . $user_lname . "', '" . $user_username . "', '" . $user_password . "')";
		  }
                  
		  //Add credentials to database
		  $result = mysqli_query($dbc, $user_insert);
		  if($result){
		      echo '<p>New User Information Updated: Welcome!</p>'; 
		  }else{
		      echo "Error: " . $user_insert . " " . mysqli_error($dbc);
		  }
	      }else{
	          echo '<p>*Error: All fields are required except middle initial</p>';    
	      }    
	  }else{
	      echo '<p>*Error: All fields are required except middle initial</p>';
	  }

	  $_POST['sign_up'] = 0;
      }
    }
?>
  
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <table>
    <fieldset>
      <tr><td><label for="username">Username:</label></td>
      <td><input type="text" name="username" value="<?php if (!empty($user_username)) echo $user_username; ?>" /></td></tr>
      <tr><td><label for="password">Password:</label></td>
      <td><input type="password" name="password" /></td></tr>
      <tr><td><label for="fname">First Name:</label></td>
      <td><input type="text" name="fname" value="<?php if (!empty($user_fname)) echo $user_fname; ?>" /></td></tr>
      <tr><td><label for="minit">Middle Initial:</label></td>
      <td><input type="text" name="minit" value="<?php if (!empty($user_minit)) echo $user_minit; ?>" /></td></tr>
      <tr><td><label for="lname">Last Name:</label></td>
      <td><input type="text" name="lname" value="<?php if (!empty($user_lname)) echo $user_lname; ?>" /></td></tr>
    </fieldset>
    <input type="hidden" id="sign_up" name="sign_up" value="1">
    <tr><td><input type="submit" value="Sign Up" name="submit" /></td></tr>
    </table>
  </form>
<?php
  }
?>
  </div>
    <div class="col-sm-2 sidenav">
    </div>
</div>
</div>

<footer class="container-fluid text-center">
  <p>Please Log In Or Sign Up</p>
</footer>

</body>
</html>
