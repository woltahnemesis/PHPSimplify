<?php require_once 'DBUtility.php'; ?>

<?php

// Instantiation of DBUtility
$db = new DBUtility();

// CHECK CONNETION
if($db->checkConn()) {
  echo 'Connected!<br />';
}


// INSERT - Short Method
// $sql = 'INSERT INTO users (username, password) VALUES(:username, :password)';
//
// // Data to bind
// $data = [
//   "username" => "HallelujahGord",
//   "password" => "WashingMachineDishes712"
// ];
//
// // Insert Data
// $db->executeQuery($sql, $data);
// $db->closeConn();

//READ - Short Method
// $sql = 'SELECT * FROM users';

// // reads the data
// if($db->readData($sql)){
//   echo '<br />Data has been read<br />';
// }
//
// // // Foreach loop method
// // foreach($db->forFetchAll() as $user) {
// //
// //   $id = $user['id'];
// //   $username = $user['username'];
// //   $password = $user['password'];
// //
// //   echo 'Id: '.$id.' Username: '.$username.' Password: '.$password.'<br />';
// // }
//
// //While loop method
// while($row = $db->whileFetch()) {
//     $id = $row['id'];
//     $username = $row['username'];
//     $password = $row['password'];
//
//     echo 'Id: '.$id.' Username: '.$username.' Password: '.$password.'<br />';
// }
// $db->closeConn();

// UPDATE - Short Method
// $sql = 'UPDATE users SET username = :username, password = :password WHERE id = :id';
//
// // Data to bind
// $data = [
//   "username" => "TheWickedasdfasd",
//   "password" => "TheOld1",
//   "id" => 15
// ];
//
// // Upate Data
// if($db->executeQuery($sql, $data)) {
//   echo '<br />Data has been updated';
// }
//
// if($db->closeConn()) {
//   echo '<br />Connection has been closed';
// }

// DELETE - Short Method
// $sql = 'DELETE FROM users WHERE id = :id';
//
// // Data to bind
// $data = [
//   "id" => 4
// ];
//
// // Delete Data
// $db->executeQuery($sql, $data);
// $db->closeConn();

?>
