<?php 
require 'dbconn.php';

$sql_match = "SELECT m.Match_ID,m.MatchDate,m.Map, 
ta.Name as ta_name, ta.RoundWins as ta_rw, tb.Name as tb_name, tb.RoundWins as tb_rw
 FROM `Match` as m
INNER JOIN teams_in_match as tm ON tm.Match_ID = m.Match_ID
INNER JOIN team as ta ON tm.TeamA_ID = ta.Team_ID
INNER JOIN team as tb on tm.TeamB_ID = tb.Team_ID
 ORDER BY m.MatchDate DESC";

$match_result = $conn->query($sql_match);

$num_rows = $match_result->num_rows;
if ($num_rows > 0) {
    // output data of each row
    echo ' <table class="col s12 bordered highlight grey lighten-3 black-text text-darken-2">';
    while($row = $match_result->fetch_assoc()) {
        $match_id = $row["Match_ID"];
        $match_date = new DateTime();
        $match_date->setTimestamp( $row["MatchDate"] );

        $match_date_formated = $match_date->format('Y-m-d H:i:s');
        $match_map = $row["Map"];

        $teamA_score = $row['ta_rw'];
        $teamB_score = $row['tb_rw'];

        $teamA_wlt;
        $teamB_wlt;
        if($teamA_score > $teamB_score){
            $teamA_wlt = "scoreWinner";
            $teamB_wlt = "scoreLoser";
        }
        else if ($teamA_score < $teamB_score){
            $teamB_wlt = "scoreWinner";
            $teamA_wlt = "scoreLoser";
        }
        else {
            $teamB_wlt = "scoreTie";
            $teamA_wlt = "scoreTie";
        }
        echo '   <tr  id="matchrow">
        <td style="width: 10%;" class="infoFont">
            
            '.$match_date_formated.'
        </td>
        <td style="text-align: right;width: 25%">'.$row['ta_name'].'</td>
        <td style="text-align: center;width: 8%"><span class="'.$teamA_wlt.'">'.$teamA_score.'</span> - <span class="'.$teamB_wlt.'">'.$teamB_score.'</span></td>
        <td style="width: 25%">'.$row['tb_name'].'</td>
        <td style="width: 15%" class="infoFont">
            '.$match_map.'
            
        </td>
    </tr>';

    }
    echo '</table>';
} else {
    echo "0 results";
}
$conn->close();
?>