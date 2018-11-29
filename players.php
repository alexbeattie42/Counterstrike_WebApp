<!doctype html>



<html lang="en-US">
  <head>
    <title>Players</title>
   

<?php
require 'header.php';
include'menu.php';?>


      <section>

      
        <div class="container">
            <div class="row">
            <?php include './queries/users.php';?>
            
            </div>
        </div>

      </section>
    </div>
    <?php require 'footer.php';?>
    
  </body>
</html>
