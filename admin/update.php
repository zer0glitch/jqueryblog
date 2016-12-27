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
    $update = "update messages set title=:title,
                                   message=:message
                      where id = :id");
    
    $stmt = $file_db->prepare($insert);

    // Bind parameters to statement variables
    $stmt->bindParam(':title', $_POST['title']);
    $stmt->bindParam(':message', nl2br($_POST['text'],true));
    $stmt->bindParam(':id', $date);

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

