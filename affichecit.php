<?php
$mysqli = new mysqli("localhost", "user", "password", "citations_db");

if ($mysqli->connect_error) {
    die("Connexion échouée: " . $mysqli->connect_error);
}

$keyword = $_GET['keyword'] ?? '';
$auteur = $_GET['auteur'] ?? '';
$siecle = $_GET['siecle'] ?? '';
$tri = $_GET['tri'] ?? 'auteur';

$query = "SELECT c.texte, a.nom, a.prenom, c.siecle
          FROM citations c
          JOIN auteurs a ON c.auteur_id = a.id
          WHERE (c.texte LIKE '%$keyword%' OR '$keyword' = '')
          AND (CONCAT(a.nom, ' ', a.prenom) = '$auteur' OR '$auteur' = '')
          AND (c.siecle = $siecle OR '$siecle' = '')
          ORDER BY " . ($tri == 'auteur' ? 'a.nom, a.prenom' : 'c.siecle');

$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Résultats de la recherche</title>
</head>
<body>
    <header>
        <h1>Résultats de la recherche</h1>
    </header>
    
    <section>
        <table>
            <tr>
                <th>Texte</th>
                <th>Auteur</th>
                <th>Siècle</th>
            </tr>
            <?php while($citation = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $citation['texte']; ?></td>
                    <td><?php echo $citation['nom'] . ' ' . $citation['prenom']; ?></td>
                    <td><?php echo $citation['siecle']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <a href="index.php">Retour à l'accueil</a>
        <a href="saisfecit.php">Ajouter une nouvelle citation</a>
    </section>

</body>
</html>

<?php
$mysqli->close();
?>
