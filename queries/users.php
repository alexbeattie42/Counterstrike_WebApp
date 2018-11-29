<?php 
require 'dbconn.php';
$sql = "SELECT User_ID, Name FROM Player ORDER BY Name ASC ";
$result = $conn->query($sql);

$num_rows = $result->num_rows;
if ($num_rows > 0) {
    // output data of each row
    echo '<table id="playerList" class="highlight col s4 offset-s2 ot1">';
    $row_cnt = 0;
    while($row = $result->fetch_assoc()) {
        if($row_cnt == (floor($num_rows/2))){
            echo '</table>
        
            <table id="playerList2" class="highlight col s4 ot2">';
        }
        $row_cnt++;
        if(!empty($row["Name"])){
            echo '  <tr>
            <th style="text-align: center"><a href="./individualplayer.php?uid='. $row["User_ID"] . '">'. $row["Name"].'</a></th>
                 </tr>';
        }
    }
    echo '</table>';
} else {
    echo "0 results";
}
$conn->close();
?>