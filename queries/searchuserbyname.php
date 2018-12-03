<?php 
require 'dbconn.php';
$username = $_GET['userName'];
$sql = "SELECT User_ID FROM  `Player` Where Name LIKE '%$username%'";
$result = $conn->query($sql);
if($result){
    $row = $result->fetch_assoc();
    $uid = $row['User_ID'];
    if($uid){
        echo "<script>window.location = '../individualplayer.php?uid=$uid'</script>";
    }
    else{
        echo "User not found. Redirecting Soon";
        sleep(3);
        echo "<script>window.location = '../players.php'</script>";
    
    }
}


?>