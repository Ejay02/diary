<?php
   session_start();
   $error = "";
   
   
   if (array_key_exists("logout", $_GET)) {
       session_unset();
       setcookie("id", "", time() - 60 * 60);
       $_COOKIE["id"] = "";
   } else if (array_key_exists("id", $_SESSION) OR array_key_exists("id", $_COOKIE)) {
       //go to the loggedinpage if you're still logged in
       header("Location: loggedInPage.php");
   }
   
   
   if (array_key_exists("submit", $_POST)) {
       
     include('connection.php');
       
       if (!$_POST['email']) {
           $error .= "Email address is required. <br>";
       }
       
       if (!$_POST['password']) {
           $error .= "Password is required. <br>";
       }
       
       if ($error != "") {
           $error = "<p>There were error(s) in your form!</p>" . $error;
       } else {
           $emailAddress = mysqli_real_escape_string($link, $_POST['email']);
           $password     = mysqli_real_escape_string($link, $_POST['password']);
           $password     = password_hash($password, PASSWORD_DEFAULT);
           
           if ($_POST['signUp'] == '1') {
               $query = "SELECT id FROM users WHERE email = '" . $emailAddress . "' LIMIT 1";
               
               $result = mysqli_query($link, $query);
               
               if (mysqli_num_rows($result) > 0) {
                   $error = "Email address already in use.";
               } else {
                   $query = "INSERT INTO users (email, password) VALUES ('" . $emailAddress . "', '" . $password . "')";
                   
                   if (!mysqli_query($link, $query)) {
                       $error .= "<p>Couldn't complete - Please try again later</p>";
                       $error .= "<p>" . mysqli_error($link) . "</p>";
                   } else {
                       // retrieve id from most recent query
                       $id = mysqli_insert_id($link);
                       
                       $_SESSION['id'] = $id;
                       
                       // cookies
                       if (isset($_POST['stayLoggedIn'])) {
                           setcookie("id", $id, time() + 60 * 60 * 24 * 365);
                       }
                       
                       header("Location: loggedInPage.php");
                       
                   }
               }
           } else {
               $query    = "SELECT * FROM users WHERE email = '" . $emailAddress . "'";
               $result   = mysqli_query($link, $query);
               $row      = mysqli_fetch_array($result);
               $password = mysqli_real_escape_string($link, $_POST['password']);
               
               if (isset($row) AND array_key_exists("password", $row)) {
                   $passwordMatches = password_verify($password, $row['password']);
                   
                   if ($passwordMatches) {
                       $_SESSION['id'] = $row['id'];
                       if (isset($_POST['stayLoggedIn'])) {
                           setcookie("id", $row['id'], time() + 60 * 60 * 24 * 365);
                       }
                       
                       header("Location: loggedInPage.php");
                   } else {
                       $error = "Please check email/password.";
                   }
               } else {
                   $error = "Please check email/password.";
               }
           }
       }
       
       
       
   }
   
   ?>
<?php include('header.php');?>
<div class="container" id="homePageContainer">
   <h1 id="font">Diary</h1>
   <!-- error -->
   <div id="error">
      <?php echo $error; 
         if($error != ""){
           echo '<div class="alert alert-danger" role="alert"> ' .
               $error . '</div>';
         }
         
         ?>
   </div>
   <!-- sign up  -->
   <form method="post" id="signup">
      <!-- <p>Sign up and start writing</p> -->
      <fieldset class="form-group">
         <input type="email" name="email" class="form-control" placeholder="Your email">
      </fieldset>
      <fieldset class="form-group">
         <input type="password" name="password"  class="form-control" placeholder="Password">
      </fieldset>
      <fieldset class="checkbox">
         <label>Stay logged in</label>
         <input type="checkbox" name="stayLoggedIn" value="1">
      </fieldset>
      <fieldset class="form-group">
         <input type="hidden" name="signUp" value="1">
         <input type="submit" name="submit" class="btn btn-success" value="Sign Up!">
      </fieldset>
      <p><a class="toggle">Log in</a></p>
   </form>
   <!-- log in -->
   <form method="post" id="login">
      <!-- <p>Log in using credentials</p> -->
      <fieldset class="form-group">
         <input type="email" name="email" class="form-control" placeholder="Your email">
      </fieldset>
      <fieldset class="form-group">
         <input type="password" name="password" class="form-control" placeholder="Password">
      </fieldset>
      <fieldset class="checkbox">
         <label>Stay logged in</label>
         <input type="checkbox" name="stayLoggedIn" value="1">
      </fieldset>
      <fieldset class="form-group">
         <input type="hidden" name="signUp" value="0">
         <input type="submit" name="submit" class="btn btn-success" value="Log In">
      </fieldset>
      <p><a class="toggle">Sign up</a></p>
   </form>
</div>
<?php include('footer.php');?>