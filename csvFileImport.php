<?php
  class CSV extends mysqli{

    public $columnName;
    public function __construct(){
      parent::__construct("localhost","root","","csv");
      if($this->connect_error){
        echo "unable to connect to database ". $this->connect_error;
      }
    }

    public function createTable($table, $file){

      // Checking for existing table
      if ($result = $this->query("SHOW TABLES LIKE '".$table."'")) {
        if($result->num_rows == 1) {
            // echo "Table exists. Skipping Creating table. Moving on to insertion";
            return true;
        }
      }

      $file = fopen($file,'r');
      $column = fgetcsv($file);
      for($i=0;$i<sizeof($column);$i++){
        $this->columnName = implode(" varchar(20),",$column);
      }
      $createTable = "CREATE TABLE ".$table."(id INT(11) NOT NULL AUTO_INCREMENT ,".$this->columnName." varchar(20), PRIMARY KEY (`id`))";
      echo $createTable;
      if($this->query($createTable)){
        echo "Created ";
        return true;
      } else {
        echo "Not Created".$this->error;
        return false;
      }
    }

    public function import($file,$table){
      $count = 0;
      $file = fopen($file,'r');
      while($row = fgetcsv($file)){
          $value = "'".implode("','",$row)."'";
          if($count!=0){
            $q="INSERT INTO ".$table." VALUES('".$count."',".$value.")";
            echo $q;
            if($this->query($q)){
              // echo "done";
            } else {
              echo $this->error;
            }
          }
          $count++;
      }
    }

    public function fetchTableName(){


      $sql = "SHOW TABLES FROM csv";
      $result = $this->query($sql);
      // var_dump($result);
      return $result;
    }

    public function fetchData($table){
      $result = array();
      $result1 = $this->query("SHOW COLUMNS FROM ".$table);
      if (!$result1) {
          echo 'Could not run query: ' . mysqli_error();
          exit;
      }


      $q = "SELECT * FROM ".$table;
      $result2 = $this->query($q);

      array_push($result,$result1,$result2);
      return $result;
    }


  }
?>
