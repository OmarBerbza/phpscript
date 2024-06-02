<?php
// Connexion à la base de données
$mysqli = new mysqli("localhost", "user", "password", "citations_db");

// Vérification de la connexion
if ($mysqli->connect_error) {
    die("Connexion échouée: " . $mysqli->connect_error);
}

// Tirage au sort d'une citation
$result = $mysqli->query("SELECT texte FROM citations ORDER BY RAND() LIMIT 1");
$citation_du_jour = $result->fetch_assoc();

// Récupération des auteurs
$auteurs = $mysqli->query("SELECT DISTINCT nom, prenom FROM auteurs");

// Récupération des siècles
$siecles = range(16, 21);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Accueil - Dictionnaire de Citations</title>
</head>
<body>
    <header>
        <h1>Bienvenue sur le dictionnaire de citations littéraires</h1>
    </header>
    
    <section>
        <h2>Citation du jour</h2>
        <p><?php echo $citation_du_jour['texte']; ?></p>
        
        <form action="affichecit.php" method="GET">
            <input type="text" name="keyword" placeholder="Recherche par mot-clé">
            <select name="auteur">
                <option value="">Tous les auteurs</option>
                <?php while($auteur = $auteurs->fetch_assoc()): ?>
                    <option value="<?php echo $auteur['nom'] . ' ' . $auteur['prenom']; ?>">
                        <?php echo $auteur['nom'] . ' ' . $auteur['prenom']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <select name="siecle">
                <option value="">Tous les siècles</option>
                <?php foreach ($siecles as $siecle): ?>
                    <option value="<?php echo $siecle; ?>">
                        <?php echo $siecle . 'e siècle'; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="radio" name="tri" value="auteur" checked> Par auteur
            <input type="radio" name="tri" value="siecle"> Par siècle
            <button type="submit">Rechercher</button>
        </form>
        
        <a href="saisfecit.php">Ajouter une nouvelle citation</a>
    </section>
</body>
</html>

<?php
$mysqli->close();
?>
