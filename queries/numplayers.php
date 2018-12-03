<?php 
require 'dbconn.php';
$sql_player_count = "SELECT Count(*) as total  FROM `Player` where Name IS NOT NULL";
$sql_match_count = "SELECT Count(*) as total FROM `Match`";
$player_count_result = $conn->query($sql_player_count)->fetch_assoc();
$match_count_result = $conn->query($sql_match_count)->fetch_assoc();

echo '  <div align="center">
<h1>Currently tracking <a href="./players.php">' . $player_count_result['total'].' players </a> across <a href="./matches.php">'. $match_count_result['total'].' matches</a>.</h1>
  </div>';
$conn->close();
?>