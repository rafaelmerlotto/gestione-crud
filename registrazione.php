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
    <link rel="stylesheet" href="style/style_bootstrap.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@1,900&display=swap" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <title>Registazione studenti</title>
</head>

<body>

    <?php

    if (isset($_SESSION["msg"])) {
        echo $_SESSION["msg"];
        unset($_SESSION["msg"]);
    }

    try {

        $dati = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($dati['submit'])) {

            $query_studente =  "INSERT INTO studenti (nome, cognome, email, password ,id_corso, created) VALUES(:nome, :cognome, :email, :password,  :id_corso,  NOW())";

            $regis_studente = $conn->prepare($query_studente);
            $regis_studente->bindParam(':nome', $dati['nome'], PDO::PARAM_STR);
            $regis_studente->bindParam(':cognome', $dati['cognome'], PDO::PARAM_STR);
            $regis_studente->bindParam(':email', $dati['email'], PDO::PARAM_STR);
            $password_cript = password_hash($dati['password'], PASSWORD_DEFAULT);
            $regis_studente->bindParam(':password', $password_cript);
            $regis_studente->bindParam(':id_corso', $dati['id_corso'], PDO::PARAM_INT);
            $regis_studente->execute();

            if ($regis_studente->rowCount()) {

                $_SESSION['msg'] = "<p style='color:green;'> Studente registato con successo</p>";
                header("Location:registrazione.php");
                unset($dati);
            } else {
                echo "Studente non registato con successo";
            }
        }
    } catch (PDOException $error) {
        //echo " Studente non registato con successo<br>";
        echo  " Studente non registato con successo " . $error->getMessage() . "<br>";
    }
    ?>

    <div class="container text-center">
        <div class="row justify-content-center ">
            <div class="col-6">
                <form class="row g-3 " action="" method="post">

                    <div class="col-md-12 p-4">
                        <h3 class="text-uppercase text-center">
                            <span class="blue">Registrazione</span>
                        </h3><br>
                    </div>

                    <div class="col-md-12 ">
                        <label class="form-label">Nome</label>
                        <input type="text" name="nome" class="form-control" id="inputDado" placeholder="Nome" value="<?php if (isset($dati['nome'])) {
                                                                                                                            echo $dati['nome'];
                                                                                                                        } ?>" required> <br>
                    </div>

                    <div class="col-md-12">
                        <label for="" class="form-label">Cognome</label>
                        <input type="text" name="cognome" class="form-control" id="inputDado" placeholder="Cognome" value="<?php if (isset($dati['cognome'])) {
                                                                                                                                    echo $dati['cognome'];
                                                                                                                                } ?>" required> <br>
                    </div>

                    <div class="col-md-12">
                        <label for="" class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-control" id="inputDado" placeholder="E-mail" value="<?php if (isset($dati['email'])) {
                                                                                                                                echo $dati['email'];
                                                                                                                            } ?>" required> <br>
                    </div>

                    <div class="col-md-12">

                        <label for="" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="inputDado" placeholder="Password" value="<?php if (isset($dati['password'])) {
                                                                                                                                echo $dati['password'];
                                                                                                                            } ?>" required><br>

                        <?php
                        $query_corso =  "SELECT id, nome FROM corsi ORDER BY nome ASC";
                        $result_corso = $conn->prepare($query_corso);
                        $result_corso->execute();
                        ?>
                        <div class="col-md-14">
                            <label class="form-label">Corso</label>
                            <select class="form-control" id="inputDado" name="id_corso" required>
                                <option class="form-control" value="">Selezionare</option>
                                <?php
                                while ($row_corso = $result_corso->fetch(PDO::FETCH_ASSOC)) {
                                    $select_corso = "";
                                    if (isset($dati['id_corso']) and ($dati['id_corso'] == $row_corso['id'])) {
                                        $select_corso = "selected";
                                    }

                                    echo " <option value='" . $row_corso['id'] . "'$select_corso>" . $row_corso['nome'] . "</option>";
                                }
                                ?>
                            </select> <br><br>
                        </div>

                    </div>

                    <div class="col-md-12">
                        <input type="submit" value="Registrare" name="submit" class="btn btn-primary text-center" id="inputDado"><br><br>
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