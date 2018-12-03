<!doctype html>


 <?php

$uid = $_GET['uid'];
require './queries/dbconn.php';
$sql = "SELECT s.Title, s.Value, p.Name, p.Photo FROM  `Statistics` as s
INNER JOIN `Stat_Types` as st on st.T_ID = s.Type
INNER JOIN `Player` as p on s.User_ID = p.User_ID
 WHERE s.User_ID = '$uid' and st.Stat_Type = 'Player_Stat'
and s.Title != 'Weapon_Stat'";

$result = $conn->query($sql);
$num_rows = $result->num_rows;
if ($num_rows > 0) {
$first_row = $result->fetch_assoc();
 echo'
<html lang="en-US">
  <head>
    <title>'.$first_row["Name"].'</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">

    <!-- Begin Jekyll SEO tag v2.4.0 -->
<title>76561197961466249 | marxy.net</title>';

    require './header.php';
    require './menu.php';

echo '
      <section>
      <script src="./assets/js/Chart.bundle.js"></script>
<style>

#overview, #matches, #stats, #weapons, #clutches {
  display: none;
}

#playerName {
  margin-top: 5px;
}

.playerImage {
  border: 1px solid black;
  border-radius: 8px;
}

.otPad1 tr th, .otPad2 tr th {
  padding-left: 20px;
}

.otPad1 tr td, .otPad2 tr td {
  padding-right: 20px;
}

canvas {
  margin-bottom: 30px;
  padding-right: 10px;
}

.container {
  width: 100%;
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
</style>';

echo'
<div class="container">
<div class="row">
  <div class="center-align">
    <img src="'.$first_row["Photo"].'" class="playerImage hoverable" width="120" height="120">
  </div>
  <div class="col s12">  
    <h2 class="center-align" id="playerName">'.$first_row["Name"].'</h2>
  </div>
  <div class="col s12">
    <ul class="tabs">
      <li class="tab col s3"><a href="#overview">Statistics</a></li>
      <li class="tab col s2"><a href="#matches">Matches</a></li>
      <!--<li class="tab col s2"><a href="#stats">Statistics</a></li>-->
      <!--<li class="tab col s2"><a href="#weapons" onclick="drawCharts()">Weapon Charts</a></li>-->
      <!--<li class="tab col s2"><a href="#clutches" onclick="drawClutches()">Clutches</a></li>-->
      <li class="tab col s3"><a href="#settings">Settings</a></li>
    </ul>
  </div>
  <!-- Overview -->
  <div id="overview" class="col s12 tabContent">';
 // output data of each row
        echo '  <table class="col s6 highlight ot1 otPad1">';
        $row_cnt = 0;
        while($row = $result->fetch_assoc()) {
            if($row_cnt == (floor($num_rows/2))){
                echo '</table>
            
                <table class="col s6 bordered highlight grey lighten-3 black-text text-darken-2 ot2 otPad2">';
            }
            $row_cnt++;
                echo ' <tr>
                <th>'.$row["Title"].':</th>
                <td class="right-align">'.$row["Value"].'</td>
              </tr>';
        }
        echo '</table>';
    } else {
        echo "0 results";
    }


echo'
  </div>
  <!-- Matches -->
  <div id="matches" class="col s12 tabContent">
';    

$sql_match = "SELECT m.Match_ID,m.MatchDate,m.Map, 
ta.Name as ta_name, ta.RoundWins as ta_rw, tb.Name as tb_name, tb.RoundWins as tb_rw
 FROM `Match` as m
INNER JOIN teams_in_match as tm ON tm.Match_ID = m.Match_ID
INNER JOIN team as ta ON tm.TeamA_ID = ta.Team_ID
INNER JOIN team as tb on tm.TeamB_ID = tb.Team_ID
INNER JOIN belongs_to as b on tm.TeamA_ID = b.Team_ID or tm.TeamB_ID = b.Team_ID
WHERE b.User_ID = '$uid' ORDER BY m.MatchDate DESC";

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
        echo '   <tr id="matchrow">
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
  </div>
  <!-- Statistics
  <div id="stats" class="col s12 tabContent">
    <table class="col s6 bordered highlight grey lighten-3 black-text text-darken-2 ot1">
      <tr>
        <th>Kills:</th>
        <td class="right-align">69</td>
      </tr>
      <tr>
        <th>Assists:</th>
        <td class="right-align">8</td>
      </tr>
      <tr>
        <th>Deaths:</th>
        <td class="right-align">42</td>
      </tr>
      <tr>
        <th>Headshots:</th>
        <td class="right-align">23</td>
      </tr>
      <tr>
        <th>Team Kills:</th>
        <td class="right-align">1</td>
      </tr>
      <tr>
        <th>Trade Kills:</th>
        <td class="right-align">8</td>
      </tr>
      <tr>
        <th>Times Traded:</th>
        <td class="right-align">6</td>
      </tr>
      <tr>
        <th>First Kills:</th>
        <td class="right-align">12</td>
      </tr>
      <tr>
        <th>First Deaths:</th>
        <td class="right-align">7</td>
      </tr>
    </table>
    <table class="col s6 bordered highlight grey lighten-3 black-text text-darken-2 ot2">
      <tr>
        <th>Hltv Rating:</th>
        <td class="right-align">1.92</td>
      </tr>
      <tr>
        <th>Plants:</th>
        <td class="right-align">3</td>
      </tr>
      <tr>
        <th>Defuses:</th>
        <td class="right-align">0</td>
      </tr>
      <tr>
        <th>Health Damage:</th>
        <td class="right-align">7088</td>
      </tr>
      <tr>
        <th>Armor Damage:</th>
        <td class="right-align">808</td>
      </tr>
      <tr>
        <th>Rounds Played:</th>
        <td class="right-align">57</td>
      </tr>
      <tr>
        <th>Rounds Won:</th>
        <td class="right-align">31</td>
      </tr>
      <tr>
        <th>KAST Rounds:</th>
        <td class="right-align">41</td>
      </tr>
      <tr>
        <th>MVPs:</th>
        <td class="right-align">11</td>
      </tr>
    </table>
    <table class="col s6 bordered highlight grey lighten-3 black-text text-darken-2 ot1">
      <tr>
        <th>1Kill Rounds:</th>
        <td class="right-align">15</td>
      </tr>
      <tr>
        <th>2Kill Rounds:</th>
        <td class="right-align">9</td>
      </tr>
      <tr>
        <th>3Kill Rounds:</th>
        <td class="right-align">9</td>
      </tr>
      <tr>
        <th>4Kill Rounds:</th>
        <td class="right-align">0</td>
      </tr>
      <tr>
        <th>5Kill Rounds:</th>
        <td class="right-align">2</td>
      </tr>
      <tr>
        <th>Total Kill Rounds:</th>
        <td class="right-align">35</td>
      </tr>
    </table>
    <table class="col s6 bordered highlight grey lighten-3 black-text text-darken-2 ot2">
      <tr>
        <th>1v1 Clutches:</th>
        <td class="right-align">1</td>
      </tr>
      <tr>
        <th>1v2 Clutches:</th>
        <td class="right-align">0</td>
      </tr>
      <tr>
        <th>1v3 Clutches:</th>
        <td class="right-align">1</td>
      </tr>
      <tr>
        <th>1v4 Clutches:</th>
        <td class="right-align">0</td>
      </tr>
      <tr>
        <th>1v5 Clutches:</th>
        <td class="right-align">0</td>
      </tr>
      <tr>
        <th>Total Clutches:</th>
        <td class="right-align">2</td>
      </tr>
    </table>
    
    
    
    <table id="matrix" class="col s12 bordered highlight grey lighten-3 black-text text-darken-2">
      <thead>
        <tr class="header" id="ignoreglobal">
          <th style="text-align: center">Name</th>
          <th style="text-align: center">Killed:KilledBy</th>
        </tr>
      </thead>
      <tbody>
      
          <tr id="ignoreglobal">
            <td class="row" style="text-align: center"><a href="/players/76561198344040839">89iup34reawfdjcx</a></td>
              <td style="text-align: center">9 : 7</td>
          </tr>
   
          
          <tr id="ignoreglobal">
            <td class="row" style="text-align: center"><a href="/players/Ant-iwnl-">Ant-iwnl-</a></td>
              <td style="text-align: center">4 : 5</td>
          </tr>
     
          
          <tr id="ignoreglobal">
            <td class="row" style="text-align: center"><a href="/players/76561198256748178">Blue Cunts with Guns</a></td>
              <td style="text-align: center">9 : 5</td>
          </tr>
          
      
          <tr id="ignoreglobal">
            <td class="row" style="text-align: center"><a href="/players/Devyn">Devyn</a></td>
              <td style="text-align: center">6 : 4</td>
          </tr>
    
          
          <tr id="ignoreglobal">
            <td class="row" style="text-align: center"><a href="/players/76561198301074257">K o z y ツ</a></td>
              <td style="text-align: center">12 : 10</td>
          </tr>
          
          <tr id="ignoreglobal">
            <td class="row" style="text-align: center"><a href="/players/76561198025626073">Rainman</a></td>
              <td style="text-align: center">10 : 1</td>
          </tr>
      
          <tr id="ignoreglobal">
            <td class="row" style="text-align: center"><a href="/players/76561198117966816">rî§К</a></td>
              <td style="text-align: center">8 : 2</td>
          </tr>
          
          <tr id="ignoreglobal">
            <td class="row" style="text-align: center"><a href="/players/76561198137565812">✪ WAZZI</a></td>
              <td style="text-align: center">5 : 5</td>
          </tr>
     
      </tbody>
    </table>
  </div> -->
  <!-- Weapons -->
  <div id="weapons" class="col s12 tabContent">
    <div class="grey lighten-3 black-text text-darken-2">
      <br>
      <canvas id="rifleChart" class="hoverable" style="width: 90%;height: 350px;"></canvas>
      <canvas id="pistolChart" class="hoverable" style="width: 90%;height: 350px;"></canvas>
      <canvas id="smgChart" class="hoverable" style="width: 90%;height: 350px;"></canvas>
      <canvas id="heavyChart" class="hoverable" style="width: 90%;height: 350px;"></canvas>
      <canvas id="nadeChart" class="hoverable" style="width: 90%;height: 350px;"></canvas>
      <canvas id="miscChart" class="hoverable" style="width: 90%;height: 350px;"></canvas>
    </div>
  </div>
  <!-- Clutches -->
  <div id="clutches" class="col s12 tabContent">
    <div class="grey lighten-3 black-text text-darken-2">
      <div class="row">
      <div class="col s6">
      <canvas id="enteredChart" class="hoberable"></canvas>
      </div>
      <div class="col s6">
      <canvas id="wonChart" class="hoberable "></canvas>
      </div>
      </div>
      <table class="col s12 bordered highlight grey lighten-3 black-text text-darken-2 ot2">
        <thead>
          <tr>
            <th></th>
            <th class="center-align">Entered</th>
            <th class="center-align">Won</th>
            <th class="center-align">Percentage</th>
          </tr>
        </thead>
        <tbody>
          
            
            <tr>
              <th class="right-align">1v1s</th>
              <td class="center-align">1</td>
              <td class="center-align">1</td>
              
              
              <td class="center-align">100.0%</td>
            </tr>
          
            
            <tr>
              <th class="right-align">1v2s</th>
              <td class="center-align">3</td>
              <td class="center-align">0</td>
              
              
              <td class="center-align">0.0%</td>
            </tr>
          
            
            <tr>
              <th class="right-align">1v3s</th>
              <td class="center-align">1</td>
              <td class="center-align">1</td>
              
              
              <td class="center-align">100.0%</td>
            </tr>
          
            
            <tr>
              <th class="right-align">1v4s</th>
              <td class="center-align">1</td>
              <td class="center-align">0</td>
              
              
              <td class="center-align">0.0%</td>
            </tr>
          
            
            <tr>
              <th class="right-align">1v5s</th>
              <td class="center-align">0</td>
              <td class="center-align">0</td>
              
              
              <td class="center-align">0.0%</td>
            </tr>
          
          <tr>
            <th class="right-align">Total</th>
            <td class="center-align">6</th>
            <td class="center-align">2</th>
              
              
            <td class="center-align">33.33%</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <style>

  .button {
    background-color: #f44336;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin-top: 1em;
}
.button2 {background-color: #4CAF50; } /* Green */
  </style>
 
  <?php 
  $uid = $_GET['uid'];
  echo'
  <div id="settings" class="col s12 tabContent">
  <form action="./queries/updateusername.php">
  Current UserID:
  <input type="text" name="uid" readonly value="'.$uid.'">
  New Username: 
  <input type="text" name="userName" value="">
  <input class="button button2" type="submit" value="Submit">

  </form>
  <a id="delete" class="button " class="button" href="./queries/deleteuser.php?uid='.$uid.'">Delete User</a>

</div>';
?>
<script>
$(document).ready(function()
{
  var id = $('.tab > .active').attr('href');

  if (id)
  {
    $(id).show();

    if (id == "#weapons")
      drawCharts();
    else if (id == "#clutches")
      drawClutches();
  }
  else
    $('.tab:first').addClass('active');
});

var clutchesLoaded = false;

var chartColors = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(201, 203, 207)'
};

function drawClutches()
{
  if (clutchesLoaded)
    return;

  clutchesLoaded = true;

  var cStart = {"1":1,"2":3,"3":1,"4":1,"5":0};
  var cWon = {"1":1,"2":0,"3":1,"4":0,"5":0};
  var titles = [ "1v1", "1v2", "1v3", "1v4", "1v5" ];
  var startData = [];
  var wonData = [];

  for (i = 1; i <= 5; i++)
  {
    startData.push(cStart[i]);
    wonData.push(cWon[i]);
  }

  var datasets = [];

  datasets.push({
    label: "dataSet",
    backgroundColor: [
        window.chartColors.red,
        window.chartColors.orange,
        window.chartColors.yellow,
        window.chartColors.green,
        window.chartColors.blue,
    ],
    data: startData
  });
  
  datasets.push({
    label: "dataSet",
    backgroundColor: [
        window.chartColors.red,
        window.chartColors.orange,
        window.chartColors.yellow,
        window.chartColors.green,
        window.chartColors.blue,
    ],
    data: wonData
  });

  generatePieChart("enteredChart", "Clutches Entered", datasets[0], titles);
  generatePieChart("wonChart", "Clutches Won", datasets[1], titles);
}

function generatePieChart(canvasID, title, dataset, labels)
{
  var canvas = document.getElementById(canvasID);
  var ctx = canvas.getContext('2d');

  var myPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: labels,
      datasets: [dataset]
    },
    options: {
      title: {
        display: true,
        text: title,
        fontSize: 24
      },
      legend: {
        position: 'bottom'
      }
    }
  });
}

var names = {"Autos":"Autos","Other":"Other","Unknown":"Unknown","P2000":"P2000","Glock":"Glock-18","P250":"P250","Deagle":"Deagle","FiveSeven":"Five-seveN","DualBarettas":"Dualies","Tec9":"Tec-9","CZ":"CZ-75","USP":"USP-S","Revolver":"R8","MP7":"MP7","MP9":"MP9","Bizon":"PP-Bizon","Mac10":"Mac-10","UMP":"UMP-45","P90":"P90","SawedOff":"Sawed-Off","Nova":"Nova","Swag7":"Mag-7","XM1014":"XM1014","M249":"M249","Negev":"Negev","Gallil":"Galil","Famas":"Famas","AK47":"AK-47","M4A4":"M4A4","M4A1":"M4A1-S","Scout":"SSG 08","SG556":"SG-553","AUG":"AUG","AWP":"AWP","Scar20":"Scar-20","G3SG1":"G3SG1","Zeus":"Zeus","Kevlar":"Kevlar","Helmet":"Helmet","Bomb":"Bomb","Knife":"Knife","DefuseKit":"Defuse Kit","World":"World","Decoy":"Decoy","Molotov":"Molotov","Incendiary":"Incendiary","Flash":"Flash","Smoke":"Smoke","HE":"HE"};
var chartsLoaded = false;

function drawCharts()
{
  if (chartsLoaded)
    return;

  chartsLoaded = true;

  var wData = {"Glock":{"Kills":0,"Deaths":1,"Shots":18,"Hits":5,"Damage":54},"USP":{"Kills":3,"Deaths":2,"Shots":30,"Hits":6,"Damage":210},"Deagle":{"Kills":9,"Deaths":6,"Shots":42,"Hits":21,"Damage":1021},"UMP":{"Kills":0,"Deaths":3,"Shots":0,"Hits":0,"Damage":0},"AK47":{"Kills":12,"Deaths":7,"Shots":136,"Hits":41,"Damage":1316},"HE":{"Kills":1,"Deaths":0,"Shots":25,"Hits":15,"Damage":310},"Flash":{"Kills":0,"Deaths":0,"Shots":25,"Hits":0,"Damage":0},"M4A4":{"Kills":5,"Deaths":5,"Shots":79,"Hits":19,"Damage":521},"Knife":{"Kills":0,"Deaths":1,"Shots":11,"Hits":0,"Damage":0},"AWP":{"Kills":19,"Deaths":11,"Shots":40,"Hits":20,"Damage":1716},"Smoke":{"Kills":0,"Deaths":0,"Shots":32,"Hits":0,"Damage":0},"P250":{"Kills":4,"Deaths":1,"Shots":17,"Hits":5,"Damage":172},"Famas":{"Kills":2,"Deaths":1,"Shots":22,"Hits":7,"Damage":200},"Molotov":{"Kills":0,"Deaths":0,"Shots":5,"Hits":0,"Damage":0},"Incendiary":{"Kills":0,"Deaths":0,"Shots":13,"Hits":5,"Damage":14},"MP7":{"Kills":2,"Deaths":1,"Shots":76,"Hits":11,"Damage":205},"Decoy":{"Kills":0,"Deaths":0,"Shots":1,"Hits":0,"Damage":0},"Scar20":{"Kills":8,"Deaths":0,"Shots":71,"Hits":13,"Damage":720},"XM1014":{"Kills":0,"Deaths":1,"Shots":0,"Hits":0,"Damage":0},"CZ":{"Kills":1,"Deaths":1,"Shots":13,"Hits":5,"Damage":100},"Gallil":{"Kills":0,"Deaths":1,"Shots":0,"Hits":0,"Damage":0},"FiveSeven":{"Kills":2,"Deaths":0,"Shots":41,"Hits":8,"Damage":215},"MP9":{"Kills":1,"Deaths":0,"Shots":122,"Hits":12,"Damage":239},"Zeus":{"Kills":0,"Deaths":0,"Shots":1,"Hits":0,"Damage":0},"M249":{"Kills":1,"Deaths":0,"Shots":20,"Hits":5,"Damage":124}};
  var oKills = 0;
  var oDeaths = 0;
  var cutoff = 0.02;
  var weaponLineTitles = [ "Kills", "Deaths", "Accuracy" ];
  var rifles = [ "AK47", "M4A4", "M4A1", "AWP", "Scout", "Gallil", "Famas", "SG556", "AUG" ];
  var pistols = [ "Glock", "USP", "P2000", "Deagle", "P250", "CZ", "FiveSeven", "Tec9", "DualBarettas", "Revolver" ];
  var smgs = [ "Mac10", "UMP", "MP9", "MP7", "P90", "Bizon" ];
  var heavys = [ "Swag7", "SawedOff", "Nova", "XM1014", "Negev", "M249" ];
  var nades = [ "Flash", "Smoke", "Decoy", "HE", "Molotov", "Incendiary" ];
  var misc = [ "Zeus", "Knife", "World" ];
  var rifleData = [ ["NaN"], ["NaN"], ["NaN"] ];
  var pistolData = [ ["NaN"], ["NaN"], ["NaN"] ];
  var smgData = [ ["NaN"], ["NaN"], ["NaN"] ];
  var heavyData = [ ["NaN"], ["NaN"], ["NaN"] ];
  var nadeData = [ ["NaN"], ["NaN"], ["NaN"] ];
  var miscData = [ ["NaN"], ["NaN"], ["NaN"] ];

  for (i = 0; i < rifles.length; i++)
  {
    if (wData.hasOwnProperty(rifles[i]))
      addWeaponDataPoint(rifleData, wData[rifles[i]], rifles[i]);
    else
      addWeaponDataPoint(rifleData, null, rifles[i]);
  }

  var autoHits = 0, autoShots = 0, autoKills = 0, autoDeaths = 0;

  if (wData.hasOwnProperty("Scar20"))
  {
    autoKills += wData["Scar20"].Kills;
    autoDeaths += wData["Scar20"].Deaths;
    autoShots += wData["Scar20"].Shots;
    autoHits += wData["Scar20"].Hits;
  }

  if (wData.hasOwnProperty("G3SG1"))
  {
    autoKills += wData["G3SG1"].Kills;
    autoDeaths += wData["G3SG1"].Deaths;
    autoShots += wData["G3SG1"].Shots;
    autoHits += wData["G3SG1"].Hits;
  }

  if (autoShots > 0)
    addWeaponDataPointEx(rifleData, autoKills, autoDeaths, Number(((autoHits / autoShots) * 100).toFixed(2)), "Autos");
  else
    addWeaponDataPointEx(rifleData, autoKills, autoDeaths, 0, "Autos");

  rifles.push("Autos");

  for (i = 0; i < pistols.length; i++)
  {
    if (wData.hasOwnProperty(pistols[i]))
      addWeaponDataPoint(pistolData, wData[pistols[i]], pistols[i]);
    else
      addWeaponDataPoint(pistolData, null, pistols[i]);
  }

  for (i = 0; i < smgs.length; i++)
  {
    if (wData.hasOwnProperty(smgs[i]))
      addWeaponDataPoint(smgData, wData[smgs[i]], smgs[i]);
    else
      addWeaponDataPoint(smgData, null, smgs[i]);
  }

  for (i = 0; i < heavys.length; i++)
  {
    if (wData.hasOwnProperty(heavys[i]))
      addWeaponDataPoint(heavyData, wData[heavys[i]], heavys[i]);
    else
      addWeaponDataPoint(heavyData, null, heavys[i]);
  }

  for (i = 0; i < misc.length; i++)
  {
    if (misc[i] == "World")
      addWeaponDataPointEx(miscData, "NaN", 0, "NaN", names[misc[i]]);
    else if (wData.hasOwnProperty(misc[i]))
      addWeaponDataPointEx(miscData, wData[misc[i]].Kills, wData[misc[i]].Deaths, "NaN", names[misc[i]]);
    else
      addWeaponDataPointEx(miscData, 0, 0, "NaN", names[misc[i]]);
  }

  for (i = 0; i < nades.length; i++)
  {
    if (wData.hasOwnProperty(nades[i]))
      addNadeDataPoint(nadeData, wData[nades[i]], nades[i]);
    else
      addNadeDataPoint(nadeData, null, nades[i]);
  }

  generateChart("rifleChart", rifleData, "Rifles", weaponLineTitles, rifles);   
  generateChart("pistolChart", pistolData, "Pistols", weaponLineTitles, pistols);   
  generateChart("smgChart", smgData, "SMGs", weaponLineTitles, smgs);   
  generateChart("heavyChart", heavyData, "Heavys", weaponLineTitles, heavys);   
  generateChart("nadeChart", nadeData, "Nades", [ "Throws", "Hits or Impacts", "Damage / Throw" ], nades);   
  generateChart("miscChart", miscData, "Misc", weaponLineTitles, misc);   
}
function addWeaponDataPointEx(data, num1, num2, num3, lblText)
{
  if (num1 != -1)
    data[0].push(num1);
  if (num2 != -1)
    data[1].push(num2);
  if (num3 != -1)
    data[2].push(num3);
}
function addWeaponDataPoint(data, weapon, key)
{
  var lblText = names[key];
  if (weapon == null)
    addWeaponDataPointEx(data, 0, 0, 0, lblText);
  else
  {
    if (weapon.Shots > 0)
       addWeaponDataPointEx(data, weapon.Kills, weapon.Deaths, Number(((weapon.Hits / weapon.Shots) * 100).toFixed(2)), lblText);
    else
      addWeaponDataPointEx(data, weapon.Kills, weapon.Deaths, 0, lblText);
  }
}
function addNadeDataPoint(data, nade, key)
{
  var lblText = names[key];
  if (nade == null)
    addWeaponDataPointEx(data, 0, 0, 0, lblText);
  else
  {
    if (nade.Shots > 0)
       addWeaponDataPointEx(data, nade.Shots, nade.Hits, Number((nade.Damage /nade.Shots).toFixed(2)), lblText);
    else
      addWeaponDataPointEx(data, nade.Shots, nade.Hits, "NaN", lblText);
  }
}

var lineColors = [ '#08a500', '#e83c3c', '#3ca4e8' ];

Array.prototype.extend = function (other_array, other) {
    /* you should include a test to check whether other_array really is an array */
    if (other)
      other_array.forEach(function(v) {this.push(names[v])}, this);    
    else
      other_array.forEach(function(v) {this.push(v)}, this);    
}

Chart.Tooltip.positioners.cursor = function(chartElements, coordinates) {
  return coordinates;
};

function generateChart(canvasID, data, title, lineLabels, xLabels)
{
  var labels = [];
  var datasets = [];

  labels.push("");
  labels.extend(xLabels, true);
  labels.push("");

  for (i = 0; i < lineLabels.length; i++)
  {
    var tData = [];

    tData.extend(data[i]);
    tData.push("NaN");

    datasets.push({
      label: lineLabels[i],
      borderColor: lineColors[i],
      fill: false,
      data: tData,
      lineTension: 0
    });
  }

  var canvas = document.getElementById(canvasID);
  var ctx = canvas.getContext('2d');

  var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: datasets
    },
    options: {
      title: {
        display: true,
        text: title,
        fontSize: 24
      },
      legend: {
        position: 'bottom'
      },
      tooltips: {
        intersect: false,
        mode: 'index',
        position: 'average'
      },
      scales: {
        yAxes: [{
          display: true,
          ticks: {
            sugestedMin: 0,
            maxTicksLimit: 10
          }
        }]
      }
    }
  });
}
</script>

      </section>
    </div>
    <?php 
    require 'footer.php';
    ?>
    
  </body>
</html>
