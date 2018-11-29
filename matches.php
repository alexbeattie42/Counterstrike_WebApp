<!doctype html>



<html lang="en-US">
  <head>
    <title>Matches</title>

<?php
require 'header.php';
include'menu.php';?>

      <section>
      <style>
.container {
  width: 100%;
}

table.highlight>tbody>tr:hover {
  background-color: #8ee0ff73;
}

.tabs .tab a:hover, .tabs .tab a.active {
  color: rgba(81, 150, 255, 0.7);
}

.tabs .tab a {
  color: rgba(81, 150, 255, 1);
}

.tabs .indicator {
  background-color: rgb(81, 150, 255);
}

.infoFont {
  font-size: 12px;
  font-weight: bold;
}
</style>

<div align="center" class="matchresult">

    <table class="col s12 bordered highlight grey lighten-3 black-text text-darken-2">
            
            <?php include './queries/matchlist.php';?>
        
 
    </table>
</div>

      </section>
    </div>
    <?php require 'footer.php';?>
    
  </body>
</html>
