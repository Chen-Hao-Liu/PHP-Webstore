<!DOCTYPE html>
<html>
<head>
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

  .row.content{height: 450px}

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

  @media screen and (max-width: 767px){
    .sidenav{
      height: auto;
      padding: 15px;
    }
    .row.content{height:auto;}
  }

  table {
    border-collapse: collapse;
    width: 100%;
  }

  th, td{
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }
 </style>
</head>

<body>
<nav class="navbar navbar-inverse">
  <div class = "container-fluid">
    <div class = "navbar-header">
      <a class="navbar-brand">QuickBuy</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
<?php
   // Generate the navigation menu
   $display = 0;
   if(!isset($_SESSION['nolog'])){
       $display = 1;
   }else{
       if($_SESSION['nolog'] == 0){
           $display = 1;
       }
   }

  if (isset($_SESSION['username']) || $display==0) {

    if($display == 1){	
        echo '<li><a href="index.php">Home</a></li>';
        echo '<li><a href="logout.php">Log Out (' . $_SESSION['username'] . ')</a></li>';
    }else{
        echo '<li><a href="noLogIn.php">Home</a></li>';
        echo '<li><a href="logout.php">Log In</a></li>';
    }
?>
  <li><a>Pick a category:</a></li>
   <li>
    <a>
    <form id="dropdown" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <select class="form-control" id="categories" name="categories" onchange="this.form.submit()">
    	<option value="general" <?php if(isset($_POST['categories'])){ if ($_POST['categories'] == 'general') echo 'selected="selected"';} ?>>General</option>
	<option value="clothing" <?php if(isset($_POST['categories'])){ if ($_POST['categories'] == 'clothing') echo 'selected="selected"';} ?>>Clothing</option>
	<option value="cosmetics" <?php if(isset($_POST['categories'])){ if ($_POST['categories'] == 'cosmetics') echo 'selected="selected"';} ?>>Cosmetics</option>
	<option value="pchardware" <?php if(isset($_POST['categories'])){ if ($_POST['categories'] == 'pchardware') echo 'selected="selected"';} ?>>PC Hardware</option>
	<option value="food" <?php if(isset($_POST['categories'])){ if ($_POST['categories'] == 'food') echo 'selected="selected"';} ?>>Food</option>
	<option value="furniture" <?php if(isset($_POST['categories'])){ if ($_POST['categories'] == 'furniture') echo 'selected="selected"';} ?>>Furniture</option>
	<option value="kitchen" <?php if(isset($_POST['categories'])){ if ($_POST['categories'] == 'kitchen') echo 'selected="selected"';} ?>>Kitchen Appliances</option>
	<option value="tools" <?php if(isset($_POST['categories'])){ if ($_POST['categories'] == 'tools') echo 'selected="selected"';} ?>>Tools</option> 
	<option value="home" <?php if(isset($_POST['categories'])){ if ($_POST['categories'] == 'home') echo 'selected="selected"';} ?>>Home</option>
	<option value="fashion" <?php if(isset($_POST['categories'])){ if ($_POST['categories'] == 'fashion') echo 'selected="selected"';} ?>>Fashion</option>
    </select>
    <input type="hidden" id="dFlag" name="dFlag" value="1">
    </form>
    </a>
   </li>
   
   <li> 
    <a>
      <form id="searchbar" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="text" class="form-control" placeholder="Search" name="search">
      <input type="hidden" id="sFlag" name="sFlag" value="1">
      </form>
    </a>
   </li>
  <li>
   <a>
     <button type="submit" class="btn btn-default" form="searchbar">Submit</button>
   </a>
  </li>

<?php
  }
  else {
    echo '<li><a href="login.php">Log In</a></li>';
  }
?>
      </ul>
<?php

  if($display == 1){
?>
<form id="history" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   <input type="hidden" id="hist" name="hist" value="1">
   <ul class="nav navbar-nav navbar-right">
      <li><a><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-th-list"></span> Order History</button></a></li>     
   </ul>
</form>

<form id="checkout" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   <input type="hidden" id="checked" name="checked" value="1">
   <ul class="nav navbar-nav navbar-right">
      <li><a><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-shopping-cart"></span> Checkout</button></a></li>     
   </ul>
</form>
<?php 
 }
?>
    </div>
  </div>
</nav>

</body>
</html>
