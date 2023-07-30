<?php
class dbObj
{
  var $servername = "localhost";
  var $username = "mohali728_codex";
  var $password = "<YOUR_PASSWORD>";
  var $dbname = "mohali728_codex";
  var $conn;

  function getConnstring()
  {
    $con = mysqli_connect($this->servername, $this->username, $this->password, $this->dbname) or die("Connection failed: " . mysqli_connect_error());
    /* check connection */
    if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
    } else {
      $this->conn = $con;
    }
    return $this->conn;
  }
}
?>