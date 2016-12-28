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
 
 
    /**************************************
    * Play with databases and tables      *
    **************************************/

    $date = time();
 
    // Prepare INSERT statement to SQLite3 file db
    $delete = "delete from messages where id = :id";

    error_log("delete statement " . $delete);
    
    $stmt = $file_db->prepare($delete);

    // Bind parameters to statement variables
    $stmt->bindParam(':id', $_GET['id']);

    error_log('id ' . $_GET['id']);

    $stmt->execute();

    error_log("time statemp " . $date);
 
    echo "Success ";
 
 
    /**************************************
    * Close db connections                *
    **************************************/
 
    // Close file db connection
    $file_db = null;
  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }
?>

