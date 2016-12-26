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
 
    // Prepare INSERT statement to SQLite3 file db
    $insert = "INSERT INTO messages (title, message, id) 
                VALUES (:title, :message, :id)";
    $stmt = $file_db->prepare($insert);

    // Bind parameters to statement variables
    $stmt->bindParam(':title', $_POST['title']);
    $stmt->bindParam(':message', $_POST['text']);
    $stmt->bindParam(':id', date_timestamp_get($date));

    $stmt->execute();
 
    echo "Success\n";
 
 
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

