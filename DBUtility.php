<?php require_once 'Connection.php'; ?>

<?php

class DBUtility {

  private $db = null;
  private $cmd = null;
  private $conn = false;

  // These are for $_FILES
  private $name;
  private $tmp_name;
  private $size;
  private $type;

  public function __construct() {

    // Connect to database
    try {

      $this->db = new PDO('mysql:host='.HOST.';dbname='.DBNAME, USER, PASS);
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->conn = true;

    } catch (PDOException $e) {

      $this->conn = false;
      echo "Connection failed. <hr />".$e->getMessage();
    }
  }

  // Checks connection
  public function checkConn(){

    if($this->conn) {

      echo "<br /><strong>Connection:</strong> true<hr />";

    }

    // We don't need to return false if not connected to the database
    // since we have a TRY CATCH
    return true;
  }

  // Fetch method for Foreach Loop
  public function forFetchAll() {
    return $this->cmd->fetchAll();
  }

  // Fetch method for While Loop
  public function whileFetch() {
    return $this->cmd->fetch(PDO::FETCH_ASSOC);
  }

  private function checkSQL($sql){

    if (!isset($sql)){

      echo 'PHPSimplify - You did not pass a query.';

      return false;

    }

    return true;
  }

  private function checkData($data) {

    if(!is_null($data) || !empty($data) || !isset($data)) {

      return true;

    } else {

      echo "PHPSimplify - Data doesn't have a value.";
      return false;

    }

  }

  // Binds data
  private function bindDataStruct($data, $keyData) {

    // For Loop when there's 2 or more data in a dictionary
    for($i = 0; $i < count($keyData); $i++) {

      // Way of getting data from a dictionary
      // ex. $data['username']
      // ex. $keyData[0] = Mikaela231
      if(is_string($data[$keyData[$i]])) {

        $this->cmd->bindValue(':'.$keyData[$i], $data[$keyData[$i]], PDO::PARAM_STR);

      } else if (is_int($data[$keyData[$i]])) {

        $this->cmd->bindValue(':'.$keyData[$i], $data[$keyData[$i]], PDO::PARAM_INT);

      } else if ($data[$keyData[$i]]) {

        $this->cmd->bindValue(':'.$keyData[$i], $data[$keyData[$i]], PDO::PARAM_BOOL);

      } else if (is_null($data[$keyData[$i]])) {

        $this->cmd->bindValue(':'.$keyData[$i], $data[$keyData[$i]], PDO::PARAM_NULL);
      }
    }
  }

  // Executes the query
  public function executeQuery($sql, $data) {

    // Check query
    if ($this->checkSQL($sql)) {

      $this->cmd = $this->db->prepare($sql);

      // Check data
      if($this->checkData($data)) {

        // Turn array keys into a value
        $keyData = array_keys($data);

        // Bind Data
        $this->bindDataStruct($data, $keyData);

        // Execute
        $this->cmd->execute();

        return true;
      }

    }

    return false;
  }

  // Read data from database
  public function readData($sql) {

    if ($this->checkSQL($sql)) {

      $this->cmd = $this->db->prepare($sql);

      // Execute
      $this->cmd->execute();

      return true;

    }

    return false;

  }

  // Close connection
  public function closeConn(){
    $this->db = null;

    return true;
  }

  // Upload a file
  public function uploadFile($sql, $data, $inputName, $maxSize, $accept, $bool, $fileLoc) {

    if($data['name'] === 'default'){

      if(isset($_FILES[$inputName]['name'])){

        $data['name'] = $_FILES[$inputName]['name'];

        // Get all properties of the file
        // name, tmp_name, size, type
        $this->name = $_FILES[$inputName]['name'];
        $this->tmp_name = $_FILES[$inputName]["tmp_name"];
        $this->size = $_FILES[$inputName]["size"];
        $this->type = mime_content_type($this->tmp_name);

        // echo $this->name.'<br />';
        // echo $this->tmp_name.'<br />';
        // echo $this->size.' bytes <br />';
        // echo $this->type.'<br />';

        // Split type and turn it into an array
        $this->type = explode("/", $this->type);

        if($this->checkSizeAndFormat($maxSize, $accept)) {

          // Creates a unique name when $bool is set to true
          if($bool) {

            $this->name = uniqid(str_shuffle($this->name), true).'.'.$this->type[1];
            $data['name'] = $this->name;

          // Returns false when boolean is undefined
          } else if (!isset($bool)) {

            echo 'PHPSimplify - Boolean must be true or false when uploading a file!';

            return false;

          }

          // Check if path exist
          if(!is_dir($fileLoc)){

            echo 'PHPSimplify - Path location for the file not found.';

            return false;

          } else if (substr($fileLoc, -1) !== '/') {

            echo 'PHPSimplify - Path name must have a slash. Example: "Folder/"';

            return false;

          } else {

            // Move it to another directory
            move_uploaded_file($this->tmp_name, $fileLoc.$this->name);
          }

          if ($this->checkSQL($sql)) {

            // Upload it to the database
            // Query statement
            $this->cmd = $this->db->prepare($sql);

            // Turn array keys into a value
            $keyData = array_keys($data);

            // Bind Data
            $this->bindDataStruct($data, $keyData);

            // Execute
            $this->cmd->execute();

            return true;

          }

        }

      }  else if(!$_FILES[$inputName]['name']) {

        echo "PHPSimplify - Input file name is incorrect or doesn't have a value";

        return false;

      }

    } else if(!isset($data['name'])){

      echo 'PHPSimplify - "name" => "default" must be set in the dictionary!';

      return false;

    } else {

      echo 'PHPSimplify - File name must be "default"';

      return false;
    }

  }

  private function checkSizeAndFormat($maxSize, $accept) {

    if ($this->size <= $maxSize) {

      if(in_array($this->type[1], $accept)){

        return true;

      } else {

        echo 'PHPSimplify - Acceptable file format: ';

        for($i = 0; $i < count($accept); $i++) {
          echo $accept[$i].' ';
        }

        return false;
      }

    } else {

      echo 'PHPSimplify - Max size: '.$maxSize.' bytes. <br />PHPSimplify - File size should be lower than max size.';

      return false;
    }
  }

}

?>
