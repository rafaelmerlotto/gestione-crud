<?php
session_start();
include_once "connessione.php";

$id = filter_input(INPUT_GET, "studente_id", FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    try {
        $query_studente = "DELETE FROM studenti WHERE id=:id LIMIT 1";
        $canc_studente = $conn->prepare($query_studente);
        $canc_studente->bindParam(':id', $id, PDO::PARAM_INT);
        if ($canc_studente->execute()) {
            $_SESSION["msg"] = "<p style='color:green;'> Studente cancellato con successo</p>";
            header('Location:gestione.php');
        } else {
            $_SESSION["msg"] = "<p style='color:red;'>Error: Studente non cancellato con sucessso</p>";
            header('Location:gestione.php');
        }
    } catch (PDOException $error) {
       $_SESSION["msg"] = "<p style='color:red;'>Erro: Studente non cancellato con sucessso</p>";
        //$_SESSION["msg"] = "<p style='color:red;'>Error: Studente non cancellato con sucessso ". $error->getMessage() ."</p>";
        header('Location:gestione.php');
    }
} else {
    $_SESSION["msg"] = "<p style='color:red;'>Error: Studente non trovato </p>";
    header('Location:gestione.php');
}


?>