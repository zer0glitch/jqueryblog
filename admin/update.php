<?php
 
  // Set default timezone
  date_default_timezone_set('UTC');
  error_log("------------------  START UPDATE -------------------");

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
                      where id = :id";

    error_log("Update String " . $update);
    
    $stmt = $file_db->prepare($update);

    // Bind parameters to statement variables
    $stmt->bindParam(':title', $_POST['title']);
    $stmt->bindParam(':message', nl2br($_POST['text'],true));
    $stmt->bindParam(':id', $_POST['edit_id']);

    error_log("title: " . $_POST['title']);
    error_log("text: " . $_POST['text']);
    error_log("id: " . $_POST['edit_id']);

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
  error_log("------------------  END  UPDATE -------------------");
?>

