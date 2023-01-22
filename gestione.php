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
    <title>Gestione degli studenti</title>
</head>

<body>

    <?php
    if (isset($_SESSION["msg"])) {
        echo $_SESSION["msg"];
        unset($_SESSION["msg"]);
    }

    $dati = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    ?>

    <div class="container text-center">
        <div class="row justify-content-around">

            <form class="col-6" action="" method="post">

                <div class="col-md-12 p-4">
                    <h3 class="text-uppercase">
                        <span class="blue">Gestione degli Studenti</span>
                    </h3> <br>
                </div>

                <?php
                $cerca_studenti = "";
                if (isset($dati['cerca_studenti'])) [
                    $cerca_studenti = $dati['cerca_studenti']
                ]
                ?>
                <div class="col-md-12">
                    <label class="form-label" for=""></label>
                    <input type="text" name="cerca_studenti" class="form-control" id="inputDado" value="<?php echo $cerca_studenti; ?>" placeholder="Cerca"><br>
                </div>

                <div class="col-md-12">
                    <input type="submit" name="cerca" value="Cerca" class="btn btn-primary" id="inputDado">
                </div>
                <br><br>
                <div class="col-md-14">
                    <a class="btn btn-link " href="registrazione.php">Registrazione </a>
                </div>
            </form>

        </div>
    </div><br>


    <?php

    if (!empty($dati['cerca'])) {
        $nome = "%" . $dati['cerca_studenti'] . "%";
        $query_studenti = "SELECT * FROM studenti WHERE nome LIKE :nome";
        $result_studenti = $conn->prepare($query_studenti);
        $result_studenti->bindParam(':nome', $nome, PDO::PARAM_STR);
        $result_studenti->execute();

        while ($row_studente = $result_studenti->fetch(PDO::FETCH_ASSOC)) {
            //var_dump($row_studente );
            extract($row_studente);

    ?>

            <table class="table table-info table-striped-columns ">
                <thead>
                    <tr>
                        <th scope="col">ID:</th>
                        <th scope="col">Nome:</th>
                        <th scope="col">Cognome:</th>
                        <th scope="col">E-mail:</th>
                        <th scope="col">Password:</th>
                        <th scope="col">ID corso:</th>
                        <th scope="col">Data registrazione:</th>
                        <th scope="col">Data modifica Registro: </th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td> <?php echo $id; ?></td>
                        <td> <?php echo  $nome; ?></td>
                        <td> <?php echo  $cognome; ?></td>
                        <td> <?php echo $email; ?></td>
                        <td> <?php echo  $password; ?></td>
                        <td> <?php echo  $id_corso; ?></td>
                        <td><?php echo  date('d/m/Y H:i', strtotime($created)); ?></td>
                        <td> <?php if (!empty($modified)) {
                                    echo (date('d/m/Y H:i', strtotime($modified)));
                                } ?></td>
                        <div class="col-md-8 ">
                            <?php echo "<a class='btn btn-outline-warning' target='_blank' href='modifica.php?studente_id=$id'>Modifica</a>" ?>
                            <?php echo   "<a class='btn btn-outline-danger' href='cancellare.php?studente_id=$id'>Cancellare</a>" ?>
                        </div>
                    </tr>
                </tbody>
            </table>

    <?php
        }
    } else {
        echo " <p class='alert alert-info d-flex align-items-center' role='alert' >Compila il campo di ricerca per elencare gli studenti </p> ";
    }
    ?>

</body>

</html>