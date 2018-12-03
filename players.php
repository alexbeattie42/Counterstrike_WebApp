<!doctype html>



<html lang="en-US">
  <head>
    <title>Players</title>
   

<?php
require 'header.php';
include'menu.php';?>


      <section>
      <style>

.button {
  background-color: #4CAF50;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin-top: 1em;
}

</style>

      
        <div class="container">
            <div class="row">
            <?php 
            require './queries/dbconn.php';
            $sql = "SELECT avg(Rounds) as avg FROM test.`match` ";
            $result = $conn->query($sql)->fetch_assoc();
            echo'
            <div id="search" class="col s12 ">
            <form action="./queries/searchuserbyname.php">
            Search By Username: 
            <input type="text" name="userName" value="">
            <input class="button" type="submit" value="Search">
          
            </form>
          

          </div>';
            
            
            
            
            
            
            include './queries/users.php';?>
            
            </div>
        </div>

      </section>
    </div>
    <?php require 'footer.php';?>
    
  </body>
</html>
