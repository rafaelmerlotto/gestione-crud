<?php

use PgSql\Lob;

//connessione con il database ultiliazando il PDO
$host = "localhost";
$user = "rafael";
$pass = "basiliko";
$dbname = "test_it";


try {
    $conn = new PDO("mysql:host=$host;dbname=" . $dbname, $user, $pass);
   // echo "<h1>connessione con il database eseguita con successo</h1>";
} catch (PDOException $error) {
    echo "Error: connessione con il database non eseguita con successo. " . $error->getMessage();
}



?>