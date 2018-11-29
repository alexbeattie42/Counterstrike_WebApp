<?php 
require 'dbconn.php';
$sql_match = "SELECT Match_ID,MatchDate,Map FROM `Match` ORDER BY MatchDate DESC ";
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
        
        $sql_teams_in_match = "SELECT TeamA_ID,TeamB_ID FROM teams_in_match WHERE Match_ID = '$match_id' ";
        $teams_result = $conn->query($sql_teams_in_match);
        $num_rows_teams = $teams_result->num_rows;
            if ($num_rows_teams > 0) {
                $teams_row = $teams_result->fetch_assoc();
                $teamA_id = $teams_row["TeamA_ID"];
                $teamB_id = $teams_row["TeamB_ID"];

                $sql_teamA = "SELECT Team_ID, Name, RoundWins FROM team WHERE Team_ID = '$teamA_id' ";
                $teamA_result = $conn->query($sql_teamA);
                $teamA_row = $teamA_result->fetch_assoc();

                $sql_teamB = "SELECT Team_ID, Name, RoundWins FROM team WHERE Team_ID = '$teamB_id' ";
                $teamB_result = $conn->query($sql_teamB);
                $teamB_row = $teamB_result->fetch_assoc();

                $teamA_score = $teamA_row['RoundWins'];
                $teamB_score = $teamB_row['RoundWins'];

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
                echo '   <tr class="clickablerow" href="/matches/'.$match_id.'" id="matchrow">
                <td style="width: 10%;" class="infoFont">
                    
                  '.$match_date_formated.'
                </td>
                <td style="text-align: right;width: 25%">'.$teamA_row['Name'].'</td>
                <td style="text-align: center;width: 8%"><span class="'.$teamA_wlt.'">'.$teamA_score.'</span> - <span class="'.$teamB_wlt.'">'.$teamB_score.'</span></td>
                <td style="width: 25%">'.$teamB_row['Name'].'</td>
                <td style="width: 15%" class="infoFont">
                   '.$match_map.'
                    
                </td>
            </tr>';

            }

           

    }
    echo '</table>';
} else {
    echo "0 results";
}
$conn->close();
?>