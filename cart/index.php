<?php
    // Start the session
    session_start();

    // Insert the page header
    $page_title = 'Logged In!';

    //Acquire connection credentials
    require_once('appvars.php');
    require_once('connectvars.php');
    require_once('navmenu.php');

    //only if the user has logged in 
    if (isset($_SESSION['user_id'])) {

    // Connect to the database 
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 
    
    //Declare array for our cart and initialize with dummy entry
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = array(array("pid"=>0,"amount"=>0,"pname"=>"X","max"=>0,"flag"=>0,"pic"=>"X","price"=>1.5));
    }
    
    //Initialize order history indicator to 0, but avoid changing $_SESSION variable if it is already set
    if(isset($_SESSION['hist'])){	
        if(!isset($_POST['hist'])){
            $_POST['hist'] = 0;
	}
    }else{
        if(!isset($_POST['hist'])){
            $_POST['hist'] = 0;
            $_SESSION['hist'] = 0;
        }
    }
    //Update session with corresponding post
    if($_POST['hist'] == 1){
	$_POST['hist'] = 0;    
        $_SESSION['hist'] = 1;
    }

    //Initialize to checkout indicator to 0, but avoid changing $_SESSION variable if it is already set
    if(isset($_SESSION['checked'])){	
        if(!isset($_POST['checked'])){
            $_POST['checked'] = 0;
	}
    }else{
        if(!isset($_POST['checked'])){
            $_POST['checked'] = 0;
            $_SESSION['checked'] = 0;
        }
    }
    //Update session with corresponding post
    if($_POST['checked'] == 1){
	$_POST['checked'] = 0;    
        $_SESSION['checked'] = 1;
    }

    //If checkout is confirmed
    if(isset($_POST['cFlag'])){
	if($_POST['cFlag'] == 1){
            //Insert cart
	    $insertCart="INSERT INTO cart (user_id, date_time) VALUES(" . $_SESSION['user_id'] . ", now())";
	    mysqli_query($dbc, $insertCart);
	    //Acquire last auto generated cart id
	    $last_id = mysqli_insert_id($dbc);
            //Loop through cart and update table
	    $skip = 1;  
	    foreach($_SESSION['cart'] as $a){
		if($skip == 0){
                    //Insert product relation into contains table
	            $contains="INSERT INTO contains (cart_id, product_id, amount) VALUES(" . $last_id . ", " . $a['pid'] . ", " . $a['amount'] . ")";
		    mysqli_query($dbc, $contains);
		    //Update product inventory quantity
		    $update="UPDATE product SET quantity=" . ($a['max']-$a['amount']) . " WHERE pid=" . $a['pid'];
		    mysqli_query($dbc, $update);
		}else{
		    $skip = 0;
		}
	    }
	    //Order Recorded, Reset cart 
            $_SESSION['cart'] = array(array("pid"=>0,"amount"=>0,"pname"=>"X","max"=>0,"flag"=>0,"pic"=>"X","price"=>1.5));
	    //$_POST['cFlag'] = 0;
	    $_SESSION['checked'] = 1;
	}
    }
    
    //Default search query
    $query = "SELECT pid, pname, price, quantity, picture FROM product WHERE pname IS NOT NULL ORDER BY pid ASC";

    //This $_SESSION['searchType'] flag variable is used to keep track of whether the page should return to
    //Category search state or manual search state when the form is submitted so that
    //the previous page is maintained without always returning to the default general state
    // 0 indicates Category/Dropdown search
    // 1 indicates manual search entry
 
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
	//Change search type flag accordingly
	$_SESSION['searchType'] = 1;   
        //Retrieve product data based on manual search
	$query = "SELECT pid, pname, price, quantity, picture FROM product WHERE pname LIKE '%" . $_SESSION['search'] . "%'";
    }
    
    //Make sure a manual search is not in progress
    if($_SESSION['sFlag'] != 1){
	//Checks to see if dropdown search flag is high
	if($_SESSION['dFlag'] == 1){
            //Change search type flag accordingly
            $_SESSION['searchType'] = 0;
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
  if($_SESSION['checked'] == 1){
    echo '<h3>My Cart</h3>';
    $skipA = 1;
    $total = 0;
    echo '<table>';
    echo '<tr>';
    echo '<th>Image</th>';
    echo '<th>Item</th>';
    echo '<th>Quantity</th>';
    echo '<th>Price</th>';
    echo '<th>Total</th>';
    echo '</tr>';
    $countA = 0;
    foreach($_SESSION['cart'] as $b){
	if($skipA == 0){
	    if(is_file(MM_UPLOADPATH . $b['pic']) && filesize(MM_UPLOADPATH . $b['pic']) > 0){
                echo '<tr><td><img src="' . MM_UPLOADPATH . $b['pic'] . '" alt="' . $b['pname'] . '" width="150" height="150"/></td>';
	    }else{  
	        echo '<tr><td><img src="' . MM_UPLOADPATH . 'nopic.jpg' . '" alt="' . $b['pname'] . '" width="150" height="150"/></td>';
	    }
	    echo '<td>' . $b['pname'] .'</td>';	
	    echo '<td>';
	    echo '<form id="tweak' . $countA . '" method="post" action="addToCart.php">';
	    echo '<input type="number" id="num" name="num" min="0" max="' . $b['max'] . '" value="' . $b['amount'] . '">';
	    echo '<input type="hidden" id="pid" name="pid" value="' . $b['pid']  .'">';
	    echo '<input type="hidden" id="chng" name="chng" value="1">';
	    echo '<br />';
	    echo '<button type="submit" class="btn btn-default">Change</button>';
	    echo '</form>';
	    echo '</td>';
	    echo '<td>$' . $b['price'] . '</td>';
	    echo '<td>$' . $b['price'] * $b['amount'] . '</td></tr>';
	    
	    //Increment total and count index
	    $total += $b['price'] * $b['amount'];  
	    $countA++;
	}else{
	    $skipA = 0;
	}	
    }
    //Only display confirm button if there are items in the cart (Skip the first dummy set)
    if(sizeof($_SESSION['cart']) > 1){
    	echo '<tr>';
    	echo '<td> </td>';
    	echo '<td> </td>';
   	echo '<td> </td>';
    	echo '<td> </td>';
    	echo '<form id="confirm" method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    	echo '<input type="hidden" id="cFlag" name="cFlag" value="1">';
    	echo '<td>Total: $' . $total . ' <button type="submit" class="btn btn-default">Confirm</button></td>';
    	echo '</form>';
    	echo '</tr>';
    } 
    //Print order confirmation message
    if(isset($_POST['cFlag'])){
	if($_POST['cFlag'] == 1){
            //Reset checkout flag indicator
            $_POST['cFlag'] = 0;
	    echo '<tr>';
  	    echo '<h4 align="center">Thank you for your purchase!</h4>';
	    echo '</tr>';
        }
    }
    
    echo '</table>';
    $_SESSION['checked'] = 0;

  }elseif($_SESSION['hist'] == 1){
    echo '<h3>Order History</h3>';
    $queryA = "SELECT cid, date_time FROM cart WHERE user_id=" . $_SESSION['user_id'] . " ORDER BY date_time DESC";
    $dataA = mysqli_query($dbc, $queryA);

    //Loop through past orders by user
    while ($rowA = mysqli_fetch_array($dataA)){
      $queryB = "SELECT p.pid, p.pname, p.price, c.amount FROM product p, contains c WHERE c.cart_id=" . $rowA['cid'] . " and c.product_id=p.pid";
      $dataB = mysqli_query($dbc, $queryB);
      echo '<table>';
      echo '<tr>';
      echo '<th>Order #: ' . $rowA['cid'] . '</th>';
      echo '<th>DateTime: ' . $rowA['date_time'] . '</th>';
      echo '</tr>';

      //Loop through each product in specified cart
      while ($rowB = mysqli_fetch_array($dataB)){
	  echo '<tr><td>(' . $rowB['pid'] . ') ' . $rowB['pname'] . '</td>';
          echo '<td>Price: $' . $rowB['price'] . '</td>';
	  echo '<td>Quantity: ' . $rowB['amount'] . '</td></tr>';
      }
      echo '</table>';
      echo '<br />';  
    }

    //Close dbc and reset session to 0
    mysqli_close($dbc);
    $_SESSION['hist'] = 0;
  }else{
    echo '<h3>Available Products</h3>';
    $count = 0;

    echo '<table>';
    while ($row = mysqli_fetch_array($data)) {
      //Uploading picture through html using filepath
      if (is_file(MM_UPLOADPATH . $row['picture']) && filesize(MM_UPLOADPATH . $row['picture']) > 0) {
	      echo '<tr><td><img src="' . MM_UPLOADPATH . $row['picture'] . '" alt="' . $row['pname'] . '" width="150" height="150"/></td>';
          if($row['quantity'] > 0){
	      echo '<td>Product: ' . $row['pname'] . '</td>';
	      echo '<td>Price: $' . $row['price'] . '</td>';
	      echo '<td>Quantity: ' . $row['quantity'] . '</td>';
?>
	      <td>
<?php
	      echo '<form id="addToCart' . $count . '" method="post" action="addToCart.php">';
?>
                  <label for="quantity">Quantity: </label>
		  <input type="number" id="quantity" name="quantity" min="0" max="<?php echo $row['quantity']; ?>">
		  <input type="hidden" id="pid" name="pid" value="<?php echo $row['pid']; ?>">
		  <input type="hidden" id="pname" name="pname" value="<?php echo $row['pname']; ?>">
		  <input type="hidden" id="max" name="max" value="<?php echo $row['quantity']; ?>">
		  <input type="hidden" id="pic" name="pic" value="<?php echo $row['picture']; ?>">
                  <input type="hidden" id="price" name="price" value="<?php echo $row['price']; ?>">
                </form>
	      </td>
<?php
	      echo '<td><button type="submit" class="btn btn-default" form="addToCart' . $count . '">Add To Cart</button></td></tr>';
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
?> 
	      <td>
<?php
	      echo '<form id="addToCart' . $count . '" method="post" action="addToCart.php">';
?>
		  <label for="quantity">Quantity: </label>
		  <input type="number" id="quantity" name="quantity" min="0" max="<?php echo $row['quantity']; ?>">
		  <input type="hidden" id="pid" name="pid" value="<?php echo $row['pid']; ?>">
		  <input type="hidden" id="pname" name="pname" value="<?php echo $row['pname']; ?>">
		  <input type="hidden" id="max" name="max" value="<?php echo $row['quantity']; ?>">
		  <input type="hidden" id="pic" name="pic" value="<?php echo $row['picture']; ?>">
                  <input type="hidden" id="price" name="price" value="<?php echo $row['price']; ?>">
                </form>
	      </td>
<?php
	      echo '<td><button type="submit" class="btn btn-default" form="addToCart' . $count . '">Add To Cart</button></td></tr>'; 
          }else{
	      echo '<td><h3>OUT OF STOCK</h3></td></tr>';
	  } 
      }
      $count++;
    }
    echo '</table>';

    mysqli_close($dbc);
  }
}
?>
  </div>
 
<div class="col-sm-2 sidenav">
  <div class="well">
    <p>Items in Cart</p>
<?php
    $skip=1;
    echo '<table>';
    echo '<tr>';
    echo '<th>Product</th>';
    echo '<th>Quantity</th>';
    echo '</tr>'; 
    //Go through current cart and display items 
    foreach($_SESSION['cart'] as &$var){
	//Skip the first dummy set
        if($skip == 0){
	    echo '<tr><td>(' . $var['pid'] . ') ' . $var['pname'] . '</td>';
	    echo '<td>' . $var['amount'] . '</td>';

	    //Check to see if a quantity overflow had occurred
	    if($var['flag'] == 0){
		echo '<td>Error: Not enough in stock</td></tr>';
                $var['flag'] = 1;
	    }else{
	        echo '</tr>';
	    }
	}else{
	    $skip = 0;
	}
    }
    echo '</table>';
?>
  </div>
</div>

</div>
</div>

<footer class="container-fluid text-center">
  <p>Shop With Us!</p>
</footer>

