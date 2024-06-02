<?php
$mysqli = new mysqli("localhost", "user", "password", "citations_db");

if ($mysqli->connect_error) {
    die("Connexion échouée: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $siecle = $_POST['siecle'];
    $texte = $_POST['texte'];

    $auteur_result = $mysqli->query("SELECT id FROM auteurs WHERE nom='$nom' AND prenom='$prenom'");
    if ($auteur_result->num_rows > 0) {
        $auteur = $auteur_result->fetch_assoc();
        $auteur_id = $auteur['id'];
    } else {
        $mysqli->query("INSERT INTO auteurs (nom, prenom) VALUES ('$nom', '$prenom')");
        $auteur_id = $mysqli->insert_id;
    }

    $mysqli->query("INSERT INTO citations (texte, auteur_id, siecle) VALUES ('$texte', $auteur_id, $siecle)");
    $message = "Citation ajoutée avec succès.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter une citation</title>
</head>
<body>
    <header>
        <h1>Ajouter une nouvelle citation</h1>
    </header>
    
    <section>
        <form action="saisfecit.php" method="POST">
            <label>Nom de l'auteur:</label>
            <input type="text" name="nom" required><br>
            <label>Prénom de l'auteur:</label>
            <input type="text" name="prenom" required><br>
            <label>Siècle:</label>
            <select name="siecle" required>
                <?php foreach (range(16, 21) as $siecle): ?>
                    <option value="<?php echo $siecle; ?>"><?php echo $siecle; ?>e siècle</option>
                <?php endforeach; ?>
            </select><br>
            <label>Texte de la citation:</label>
            <textarea name="texte" required></textarea><br>
            <button type="submit">Ajouter</button>
            <button type="reset">Effacer</button>
        </form>
        <?php if (isset($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <a href="index.php">Retour à l'accueil</a>
    </section>
    
</body>
</html>

<?php
$mysqli->close();
?>
