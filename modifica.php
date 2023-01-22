<?php
session_start();
include_once "connessione.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@1,900&display=swap" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <title>Modificare dati studente</title>
</head>

<body>

    <?php
    if (isset($_SESSION["msg"])) {
        echo $_SESSION["msg"];
        unset($_SESSION["msg"]);
    }

    $dati = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if (!empty($dati['submit'])) {
        try {
            $query_edit_studente = "UPDATE studenti SET nome=:nome, cognome=:cognome, email=:email, password=:password, id_corso=:id_corso, modified = NOW() WHERE id=:id";
            $edit_studente = $conn->prepare($query_edit_studente);
            $edit_studente->bindParam(':id', $dati['id'], PDO::PARAM_INT);
            $edit_studente->bindParam(':nome', $dati['nome'], PDO::PARAM_STR);
            $edit_studente->bindParam(':cognome', $dati['cognome'], PDO::PARAM_STR);
            $edit_studente->bindParam(':email', $dati['email'], PDO::PARAM_STR);
            $password_cript = password_hash($dati['password'], PASSWORD_DEFAULT);
            $edit_studente->bindParam(':password', $password_cript, PDO::PARAM_STR);
            $edit_studente->bindParam(':id_corso', $dati['id_corso'], PDO::PARAM_INT);

            if ($edit_studente->execute()) {
                $_SESSION["msg"] = "<p style='color:green;'> Studente modificato con successo</p>";
                header('Location:modifica.php');
            } else {
                echo "Error: Studente non modificato con successo!";
            }
        } catch (PDOException $error) {
            echo "Error: Studente non modificato con successo!";
            // echo "Error: Studente non modificato con successo ". $error->getMessage();
        }
    }

    //ricevere L'ID tramite URL ultilizando il metodo GET
    $id = filter_input(INPUT_GET, "studente_id", FILTER_SANITIZE_NUMBER_INT);

    //cercare le informazioni dello studente nel database
    try {
        $query_studente = "SELECT id, nome, cognome, email, password, id_corso FROM studenti  WHERE id=:id LIMIT 1";
        $result_studente = $conn->prepare($query_studente);
        $result_studente->bindParam(':id', $id, PDO::PARAM_INT);

        $result_studente->execute();
        $row_studente = $result_studente->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $error) {
        $_SESSION["msg"] = "<p style='color:red;'>Error: Studente non modificato con successo</p>";
        header('Location:gestione.php');
        //echo "Erro: Studente non modificato con successo". $error->getMessage();
    }
    ?>

    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-6">
                <form class="row g-3 " method="post" action="">

                    <div class="col-md-12 p-4">
                        <h3 class="text-uppercase text-center">
                            <span class="blue">Modifica dati</span>
                        </h3><br>
                    </div>

                    <!-- ID -->
                    <?php
                    $id = "";
                    if (isset($row_studente['id'])) {
                        $id = $row_studente['id'];
                    }
                    ?>
                    <input type="hidden" name="id" class="form-control" id="inputDado" value="<?php echo $id; ?>" required>

                    <!-- NOME -->
                    <?php
                    $nome = "";
                    if (isset($row_studente['nome'])) {
                        $nome = $row_studente['nome'];
                    }
                    ?>
                    <div class="col-md-12">
                        <label class="form-label">Nome </label>
                        <input type="text" name="nome" placeholder="Nome" class="form-control" id="inputDado" value="<?php echo $nome; ?>" required><br>
                    </div>

                    <!-- cognome -->
                    <?php
                    $cognome = "";
                    if (isset($row_studente['cognome'])) {
                        $cognome = $row_studente['cognome'];
                    }
                    ?>
                    <div class="col-md-12">
                        <label class="form-label">Cognome </label>
                        <input type="text" name="cognome" placeholder="Cognome" class="form-control" id="inputDado" value="<?php echo $cognome; ?>" required><br>
                    </div>

                    <!-- EMAIL -->
                    <?php
                    $email = "";
                    if (isset($row_studente['email'])) {
                        $email = $row_studente['email'];
                    }
                    ?>
                    <div class="col-md-12">
                        <label class="form-label">E-mail </label>
                        <input type="email" name="email" placeholder="E-mail" class="form-control" id="inputDado" value="<?php echo $email; ?>" required><br>
                    </div>

                    <!-- password -->
                    <div class="col-md-12">
                        <label class="form-label">Password </label>
                        <input type="password" name="password" class="form-control" id="inputDado" placeholder="Nuova password " required><br>
                    </div>

                    <!-- CURSO -->
                    <?php
                    $query_corso = "SELECT id, nome, insegnante FROM corsi ";
                    $result_corso = $conn->prepare($query_corso);
                    $result_corso->execute();
                    ?>
                    <div class="col-md-12">
                        <label class="form-label">Corso </label>
                        <select class="form-control" id="inputDado" name="id_corso" required>
                            <option class="form-control" value="">Selezionare</option>
                            <?php
                            while ($row_corso = $result_corso->fetch(PDO::FETCH_ASSOC)) {
                                extract($row_corso);
                                $select_corso = "";
                                if (isset($dati['id_corso']) and ($dati['id_corso'] == $id)) {
                                    $select_corso = "selected";
                                } elseif (((!isset($dati['id_corso'])) and (isset($row_studente['id_corso']))) and ($row_studente['id_corso'] == $id)) {
                                    $select_corso = "selected";
                                }
                                echo "<option value='$id' $select_corso >$nome</option>";
                            }
                            ?>
                        </select> <br><br>
                    </div>
                    <div class="col-md-12">
                        <input type="submit" value="Modifica" name="submit" class="btn btn-primary text-center" id="inputDado"><br><br>
                    </div>

                </form>
                <div class="col-md-12">
                    <a class="btn btn-link" href="gestione.php">← Gestione degli studenti</a>
                </div>
            </div>
        </div>
    </div>














</body>

</html>