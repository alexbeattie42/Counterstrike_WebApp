<?php 
require 'dbconn.php';
$uid = $_GET['uid'];
$username = $_GET['userName'];
$sql = "UPDATE `Player` SET Name = '$username' WHERE User_ID = $uid ";
$result = $conn->query($sql);
if($result){
    echo 'User updated succesfully. Redirecting Soon';
}
else{
    echo 'User not updated. Redirecting Soon';
}
sleep(10);
echo "<script>window.location = '../individualplayer.php?uid=$uid'</script>";

?>