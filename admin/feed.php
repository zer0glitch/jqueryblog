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

    $len = 0;

    $query_string = "";
    if (isset($_GET['id'])) { 
      $query_string = "SELECT * FROM messages where id = '" . $_GET['id'] . "'";
      $len = 1;
    } else {
      $query_string = "SELECT * FROM messages order by id desc";
      $countResult = $file_db->query('SELECT count(*) as counted FROM messages');
      foreach($countResult as $crow) {
        $len = $crow['counted']; 
      }
    }

    error_log(" query_string: " . $query_string);
    $result = $file_db->query($query_string);

    if ($_GET['format'] == 'xml') {
      echo "<?xml version='1.0' encoding='UTF-8' standalone='no' ?>\n";
      echo "<feed>";
      foreach($result as $row) {
        echo "<entry>";
        echo "<id><![CDATA[" . $row['id'] . "]]></id>";
        echo "<title><![CDATA[" . $row['title'] . "]]></title>";
        echo "<text><![CDATA[" . nl2br($row['message']) . "]]></text>";
        echo "</entry>";
      }
      echo "</feed>";

    } else {
      $numRows = 0;
      $data = array();
      $count = 1;
      echo "{";
      echo "\"data\": ";
      echo "[";


      foreach($result as $row) {
        $rowData = (object)array(
            'id' => $row['id'],
            'title' => $row['title'],
            'text' => $row['message'],
            'links' => "<a href=edit.php?id=" . $row['id'] . ">edit</a>&nbsp;" .
                       "<a href=delete.php?id=" . $row['id'] . ">delete</a>" 
        );
        $numRows = $numRows + 1;
        $data[] = $rowData;
        echo "[";
        echo "\"" . $row['id'] . "\",";
        echo "\"" . $row['title'] . "\",";
        echo "" . json_encode($row['message']) . ",";
        echo "\"" . "" . "\"";
        echo "]";

        // Test for last
        error_log("count " . $count . " == " . $len);
         if ($count != $len) {
            echo ",";
         }
         $count = $count + 1;
      }
      echo "]";
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

