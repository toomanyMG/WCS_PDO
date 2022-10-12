<?php
require_once '_connec.php';


$pdo = new \PDO(DSN, USER, PASS);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $friends = array_map('trim', $_POST);

    $errors = [];

    if (empty($friends['firstname'])) {
        $errors[] = 'Le prénom est obligatoire.';
    }

    $maxNameLength = 45;
    if (strlen($friends['firstname']) > $maxNameLength) {
        $errors[] = 'Le prénom doit faire maximum ' . $maxNameLength . ' caractères.';
    }

    if (empty($friends['lastname'])) {
        $errors[] = 'Le nom est obligatoire.';
    }

    if (strlen($friends['lastname']) > $maxNameLength) {
        $errors[] = 'Le nom doit faire maximum ' . $maxNameLength . ' caractères.';
    }

    if (empty($errors)) {
        header('Location:index.php');

        $query = 'INSERT INTO friends (firstname, lastname) VALUES (:firstname, :lastname)';

        $statement = $pdo->prepare($query);


        $statement->bindValue(':firstname', $friends['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $friends['lastname'], \PDO::PARAM_STR);

        $statement->execute();
    }
}


//RECUPERATION DANS LA BDD

$query = "SELECT * FROM friends";

$statement = $pdo->query($query);

$friendsArray = $statement->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>
</head>

<body>
    <h1>Friends</h1>

    <div>
        <?php foreach ($friendsArray as $friend) : ?>
            <div>
                <h2><?= $friend['firstname'] ?></h2>
                <h3><?= $friend['lastname'] ?></h3>
            </div>
        <?php endforeach; ?>
    </div>


    <form action="" method="post">


        <?php if (!empty($errors)) : ?>
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li> <?= $error; ?> </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <label for="firstname">Prénom</label>
        <input type="text" name="firstname" required <?= $friends['firstname'] ?? '' ?>>

        <label for="lastname">Nom</label>
        <input type="text" name="lastname" required <?= $friends['lastname'] ?? '' ?>>

        <button>Envoyer</button>



    </form>


</body>

</html>