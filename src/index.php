<?php

try {

    $pdo = new PDO(
        "mysql:host=mysql;dbname=cours_docker;charset=utf8",
        "etudiant",
        "etudiant"
    );

    $sql = "SELECT * FROM utilisateurs";

    $stmt = $pdo->query($sql);

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    die("Erreur : " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Docker PHP MySQL</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="container">

        <h1>🚀 Docker fonctionne</h1>

        <h2>Liste des utilisateurs</h2>

        <ul>

            <?php foreach ($users as $user): ?>

                <li>
                    <?= htmlspecialchars($user['nom']) ?>
                </li>

            <?php endforeach; ?>

        </ul>

    </div>

</body>
</html>