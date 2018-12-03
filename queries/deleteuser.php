<?php 
require 'dbconn.php';
$uid = $_GET['uid'];
$sql = " DELETE FROM `Player` WHERE User_ID = $uid; ";
$result = $conn->query($sql);
if($result){
    echo 'Deleted user succesfully. Redirecting Soon';
}
else{
    echo 'User not deleted. Redirecting Soon';
}
sleep(10);
echo "<script>window.location = '../'</script>";

?>