<?php
 
  // Set default timezone
  date_default_timezone_set('UTC');

  try {
    /**************************************
    * Create databases and                *
    * open connections                    *
    **************************************/
 
    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:microblog.sqlite3');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, 
                            PDO::ERRMODE_EXCEPTION);
 
 
    /**************************************
    * Create tables                       *
    **************************************/
 
    // Create table messages
    $file_db->exec("CREATE TABLE IF NOT EXISTS messages (
                    id time, 
                    title TEXT, 
                    message TEXT)");
 

    // Select all data from memory db messages table 
    $result = $file_db->query('SELECT * FROM messages order by id desc');


    if ($_GET['format'] == 'xml') {
      echo "<?xml version='1.0' encoding='UTF-8' standalone='no' ?>\n";
      echo "<feed>";
      foreach($result as $row) {
          echo "<entry>";
          echo "<id><![CDATA[" . $row['id'] . "]]></id>";
          echo "<title><![CDATA[" . $row['title'] . "]]></title>";
          echo "<text><![CDATA[" . $row['message'] . "]]></text>";
          echo "</entry>";
      }
      echo "</feed>";

    } else {
      $start = ($_GET['perPage'] * ($_GET['page'] - 1));

      $end = ($_GET['perPage'] * ($_GET['page']));

      $numRows = 0;
      $data = array();
      $count = 0;
      foreach($result as $row) {
            $rowData = (object)array(
                'id' => $row['id'],
                'title' => $row['title'],
                'text' => $row['message']
            );
          if (($count >= $start) and ($count < $end)) {
            $numRows = $numRows + 1;
            $data[] = $rowData;
          }
          $count = $count + 1;
      }

    
      $json = json_encode($data);
      echo "{";
      echo "\"records\": ";
      echo $json;
      echo ",";
      echo "\"queryRecordCount\": ";
      echo $count;
      echo ",";
      echo "\"totalRecordCount\":";
      echo  $count;
      echo "}";

    }

 
    /**************************************
    * Close db connections                *
    **************************************/
 
    // Close file db connection
    $file_db = null;
  } catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }


?>

