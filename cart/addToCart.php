<?php
  session_start();
  //Check if post is set
  if(!isset($_POST['chng'])){
      $_POST['chng'] = 0;
  }
  
  //Check if change value flag is indicated
  if($_POST['chng'] == 1){
      $skip = 1;
      $i = 0;
      //Loop through until pid matches
      foreach($_SESSION['cart'] as &$var){
	  //Skip over dummy set
	  if($skip == 0){
              //Index for removal
              $i++;
              //Change pid amount to new amount
	      if($var['pid'] == $_POST['pid']){
		  //If the value has been changed to zero, remove item from cart    
		  if($_POST['num'] == 0){
                      //Remove row
	              unset($_SESSION['cart'][$i]);
                      //Normalize indexes
                      $_SESSION['cart'] = array_values($_SESSION['cart']);
                      break;
		  }else{
		      $var['amount'] = $_POST['num'];
	          }
	      }
	  }else{
	      $skip = 0;
	  }    
      }
      //Reset change flag to 0
      $_POST['chng'] = 0;    
      //Set Session checked to 1 to return to same page
      $_SESSION['checked'] = 1;
  }else{

  $newEntry = array("pid"=>$_POST['pid'],"amount"=>$_POST['quantity'],"pname"=>$_POST['pname'],"max"=>$_POST['max'],"flag"=>1,"pic"=>$_POST['pic'],"price"=>$_POST['price']);
  $add = 1;
  $skip = 1;
  foreach($_SESSION['cart'] as &$var){
      if($skip == 0){
          //Check to see if product already exists in cart
	  if($var['pid'] == $_POST['pid']){
              //Check to see if product is still within stock quantity
	      if(($var['amount'] + $_POST['quantity']) <= $var['max']){
                  //Increment amount
	          $var['amount'] += $_POST['quantity'];
	      }else{
		  //Indicate quantity overflow error
	          $var['flag'] = 0;
	      }
	      $add=0;
	      break;
	  }
      }else{
          $skip = 0;
      }
  }
  //If product was already in cart, skip over new entry step
  if($add == 1){
      array_push($_SESSION['cart'], $newEntry);
  }

  //Adjust return page based on search type flag
  if(isset($_SESSION['searchType'])){
      if($_SESSION['searchType'] == 1){
          $_SESSION['sFlag'] = 1;
      }else{
          $_SESSION['sFlag'] = 0; 	  
          $_SESSION['dFlag'] = 1;
      }
  }

  }
  header('location:index.php');
?>
