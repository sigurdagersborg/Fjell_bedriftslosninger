<?php
require_once 'include/config_session.inc.php';
require_once 'include/signup/signup_view.inc.php';
require_once 'include/login/login_view.inc.php';
require_once "include/meny.inc.php";
require_once "include/dbconn.inc.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sak <?php echo $_GET['saksnummer']; ?> Detaljer</title>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<?php
if (!isset($_SESSION["user_id"])) {
    echo "Du er ikke logget inn!";
    exit;
}

if (!isset($_GET["saksnummer"]) || !is_numeric($_GET["saksnummer"])) {
    echo "Ugyldig saksnummer!";
    exit;
}

$saksnummer = $_GET["saksnummer"];
$sql = "SELECT saksnummer, problem, user_id,
        CASE 
            WHEN er_lost = 0 THEN 'Ikke løst' 
            WHEN er_lost = 1 THEN 'Under behandling' 
            WHEN er_lost = 2 THEN 'Løst' 
            ELSE 'Ukjent' 
        END AS status
        FROM saker WHERE saksnummer = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$saksnummer]);
$sak = $stmt->fetch(PDO::FETCH_ASSOC);

if ($sak["user_id"] !== $_SESSION["user_id"]) {
    echo "Du har ikke tilgang til denne saken!";
    exit;
}

$sql_meldinger = "SELECT melding FROM messages WHERE saksnummer = ?";
$stmt_meldinger = $pdo->prepare($sql_meldinger);
$stmt_meldinger->execute([$saksnummer]);
$meldinger = $stmt_meldinger->fetchAll(PDO::FETCH_COLUMN);
?>


<body>
    <h1>Sak <?php echo $sak['saksnummer']; ?> Detaljer</h1>
    <p><strong>Problem:</strong> <?php echo $sak['problem']; ?></p>
    <p><strong>Status:</strong> <?php echo $sak['status']; ?></p>

    <h2>Meldinger:</h2>
    <?php if (!empty($meldinger)): ?>
        <ul>
            <?php foreach ($meldinger as $melding): ?>
                <li><?php echo $melding; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Ingen meldinger å vise.</p>
    <?php endif; ?>
</body>
</html>
