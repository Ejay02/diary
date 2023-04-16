<?php
   session_start();
   
   $diaryContent = "";
   
   
   if(array_key_exists('id', $_COOKIE)){
    $_SESSION['id'] = $_COOKIE['id'];
   }
   
   if(array_key_exists("id", $_SESSION)){
   //  echo "<p>Welcome back! <a href='index.php?logout=1'>Log out </a></p>";
   
    include('connection.php');
   
    $query = "SELECT diary FROM users WHERE id = " . 
         mysqli_real_escape_string($link, $_SESSION['id']) . " LIMIT 1";
   
         $result = mysqli_query($link, $query);
         $row = mysqli_fetch_array($result);
   
         $diaryContent = $row['diary'];
   }
   else {
    header("Location: index.php");
   }
   
   include('header.php');
   ?>
<nav class="navbar navbar-light bg-faded navbar-fixed-top">
   <a class="navbar-brand" href="#" >Diary</a>
   <div class="pull-sx-right">
      <a href="index.php?logout=1">
      <button class="btn btn-success-outline">Logout</button>
      </a>
   </div>
</nav>
<div class="container-fluid">
   <textarea id="diary" class="form-control"  style="resize:none" placeholder="Write your thoughts here..." >
   <?php echo $diaryContent;?>
   </textarea>
</div>
<?php
   include('footer.php');
   ?>