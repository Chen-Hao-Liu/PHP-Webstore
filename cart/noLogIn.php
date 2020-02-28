<?php
    // Start the session
    session_start();

    // Insert the page header
    $page_title = 'Logged In!';
    
    // Acquire log in credentials
    require_once('appvars.php');
    require_once('connectvars.php');
    
    //Initialize no login indicator
    if(isset($_SESSION['nolog'])){	
        if(!isset($_POST['nolog'])){
            $_POST['nolog'] = 0;
	}
    }else{
        if(!isset($_POST['nolog'])){
            $_POST['nolog'] = 0;
            $_SESSION['nolog'] = 0;
        }
    }
    //Update session with corresponding post
    if($_POST['nolog'] == 1){
	$_POST['nolog'] = 0;    
	$_SESSION['nolog'] = 1;
    }
    
    //Require navigation menu
    require_once('navmenu.php');

    // Connect to the database 
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 
     
    //Default search query
    $query = "SELECT pid, pname, price, quantity, picture FROM product WHERE pname IS NOT NULL ORDER BY pid ASC";
 
    //Initialize search flag indicator to 0, but avoid changing $_SESSION variable if it is already set
    if(isset($_SESSION['sFlag'])){	
        if(!isset($_POST['sFlag'])){
            $_POST['sFlag'] = 0;
	}
    }else{
        if(!isset($_POST['sFlag'])){
            $_POST['sFlag'] = 0;
            $_SESSION['sFlag'] = 0;
        }
    }
    //Update session with corresponding post
    if($_POST['sFlag'] == 1){
	$_POST['sFlag'] = 0;    
	$_SESSION['sFlag'] = 1;
	$_SESSION['search'] = $_POST['search'];
    }

    //Initialize dropdown flag indicator to 0, but avoid changing $_SESSION variable if it is already set
    if(isset($_SESSION['dFlag'])){	
        if(!isset($_POST['dFlag'])){
            $_POST['dFlag'] = 0;
	}
    }else{
        if(!isset($_POST['dFlag'])){
            $_POST['dFlag'] = 0;
            $_SESSION['dFlag'] = 0;
        }
    }
    //Update session with corresponding post
    if($_POST['dFlag'] == 1){
	$_POST['dFlag'] = 0;    
	$_SESSION['dFlag'] = 1;
	$_SESSION['categories'] = $_POST['categories'];
    }

    //Test to see if search is set
    if($_SESSION['sFlag'] == 1){   
        //Retrieve product data based on manual search
	$query = "SELECT pid, pname, price, quantity, picture FROM product WHERE pname LIKE '%" . $_SESSION['search'] . "%'";
    }
    
    //Make sure a manual search is not in progress
    if($_SESSION['sFlag'] != 1){
	//Checks to see if dropdown search flag is high
	if($_SESSION['dFlag'] == 1){
	    //makes sure categories is not general
	    if($_SESSION['categories'] != "general"){
	        //Retrieve product data based on category
    		$query = "SELECT pid, pname, price, quantity, picture FROM product, category, belongs WHERE product_id=pid and ctg=cat_name and cat_name= \"" . $_SESSION['categories'] . "\" and pname IS NOT NULL ORDER BY pid ASC ";
	    }else{
		//Retrieve general data
		$query = "SELECT pid, pname, price, quantity, picture FROM product WHERE pname IS NOT NULL ORDER BY pid ASC";
	    }
            $_SESSION['dFlag'] = 0;
        }
    }else{
        $_SESSION['sFlag'] = 0;
    }

    $data = mysqli_query($dbc, $query);
    // Loop through the array of user data, formatting it as HTML
?>

<div class = "container-fluid text-center">
  <div class="row content">
    <div class="col-sm-2 sidenav">
    </div>
   <div class="col-sm-8 text-left">
<?php
    echo '<h3>Available Products</h3>';

    echo '<table>';
    while ($row = mysqli_fetch_array($data)) {
      //Uploading picture through html using filepath
      if (is_file(MM_UPLOADPATH . $row['picture']) && filesize(MM_UPLOADPATH . $row['picture']) > 0) {
	      echo '<tr><td><img src="' . MM_UPLOADPATH . $row['picture'] . '" alt="' . $row['pname'] . '" width="150" height="150"/></td>';
          if($row['quantity'] > 0){
	      echo '<td>Product: ' . $row['pname'] . '</td>';
	      echo '<td>Price: $' . $row['price'] . '</td>';
	      echo '<td>Quantity: ' . $row['quantity'] . '</td>';
          }else{
	      echo '<td><h3>OUT OF STOCK</h3></td></tr>';
	  }
      }
      else {
	      echo '<tr><td><img src="' . MM_UPLOADPATH . 'nopic.jpg' . '" alt="' . $row['pname'] . '" width="150" height="150"/></td>';
          if($row['quantity'] > 0){
	      echo '<td>Product: ' . $row['pname'] . '</td>';
	      echo '<td>Price: $' . $row['price'] . '</td>';
	      echo '<td>Quantity: ' . $row['quantity'] . '</td>';
          }else{
	      echo '<td><h3>OUT OF STOCK</h3></td></tr>';
	  } 
      }
    }
    echo '</table>';
    mysqli_close($dbc);
?>
  </div>
 
<div class="col-sm-2 sidenav">
</div>

</div>
</div>

<footer class="container-fluid text-center">
  <p>Shop With Us!</p>
</footer>

