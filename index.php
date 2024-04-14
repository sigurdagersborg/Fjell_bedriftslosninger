<?php
require_once 'include/config_session.inc.php';
require_once 'include/signup/signup_view.inc.php';
require_once 'include/login/login_view.inc.php';
require_once "include/meny.inc.php";
require_once "include/dbconn.inc.php";

// Sjekk om brukeren er logget inn
if (!isset($_SESSION["user_id"])) {
    echo "Du er ikke logget inn!";
    exit;
}

// Håndter innsendt skjema
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $problem = $_POST["problem"];
    $user_id = $_SESSION["user_id"];

    // Sett inn data i databasen
    $sql = "INSERT INTO saker (user_id, problem, er_lost) VALUES (?, ?, 0)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $problem]);
}

// Hent brukerens saker fra databasen
$user_id = $_SESSION["user_id"];
$sql = "SELECT saksnummer, problem, 
        CASE 
            WHEN er_lost = 0 THEN 'Ikke løst' 
            WHEN er_lost = 1 THEN 'Under behandling' 
            WHEN er_lost = 2 THEN 'Løst' 
            ELSE 'Ukjent' 
        END AS status
        FROM saker WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dine Saker</title>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <h1>Dine saker:</h1>
    <ul>
    <?php foreach ($result as $row): ?>
    <li>Saksnummer: <?php echo $row['saksnummer']; ?> | Problem: <?php echo $row['problem']; ?> | Status: <?php echo $row['status']; ?>
        | <a href="sak_detaljer.php?saksnummer=<?php echo $row['saksnummer']; ?>">Se detaljer</a>
    </li>
<?php endforeach; ?>

    </ul>

    <form method="POST">
        <label for="problem">Hva er ditt problem?:</label><br>
        <input type="text" id="problem" name="problem" value="Bla bla bla"><br>  
        <input type="submit" value="Submit">
    </form>
</body>
</html>

<?php
$pdo = null;
?>
