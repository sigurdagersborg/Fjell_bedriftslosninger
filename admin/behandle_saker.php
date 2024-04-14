<?php
require_once "../include/config_session.inc.php";
require_once '../include/dbconn.inc.php';
require_once 'index.php';

if (!isset($_SESSION['isAdmin'])) {
    header("location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $saksnr = $_POST['saksnr'];
    $melding = $_POST['melding'];
    $ny_status = $_POST['ny_status'];

    try {
        // SQL-spørring for å oppdatere statusen til saken
        $sql = "UPDATE saker SET er_lost=:ny_status WHERE saksnummer = :saksnr";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ny_status', $ny_status, PDO::PARAM_STR);
        $stmt->bindParam(':saksnr', $saksnr, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Feil under oppdatering av rad: " . $e->getMessage();
    }

    try {
        // SQL-spørring for å sette inn en ny melding
        $sql = "INSERT INTO messages (saksnummer, melding) VALUES (:saksnr, :melding)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':saksnr', $saksnr, PDO::PARAM_STR);
        $stmt->bindParam(':melding', $melding, PDO::PARAM_STR);
        $stmt->execute();
        echo "Ny melding ble lagt til.";
    } catch (PDOException $e) {
        echo "Feil under innsetting av ny melding: " . $e->getMessage();
    }
}




if (isset($_GET['saksnr']) && is_numeric($_GET['saksnr'])) {
    $saksnr = $_GET['saksnr'];
    $query = "SELECT * FROM messages WHERE saksnummer = :saksnr";
    $statement = $pdo->prepare($query);
    $statement->execute(array(':saksnr' => $saksnr));
    $meldinger = $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Behandle Sak</title>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .message-container {
            padding: 10px;
            margin-bottom: 10px;
            overflow: auto;
        }

        .message {
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 5px;
            width: 80%;
            float: left;
        }
    </style>
</head>
<body>
    <h1>Behandle Sak</h1>
    <h2>Saksnr: <?php echo isset($saksnr) ? $saksnr : ""; ?></h2>

    <div class="messages-container">
        <?php if (isset($meldinger) && !empty($meldinger)): ?>
            <?php foreach ($meldinger as $melding): ?>
                <div class="message-container">
                    <div class="message">
                        <p><?php echo $melding['melding']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Ingen meldinger å vise.</p>
        <?php endif; ?>
    </div>

    <h4>Send Melding eller Endre Status:</h4>
    <form method="POST" action="">
        <div class="form-group">
            <label for="melding">Melding:</label>
            <textarea class="form-control" id="melding" name="melding" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="ny_status">Ny Status:</label>
            <select class="form-control" id="ny_status" name="ny_status">
                <option value="0">Ikke løst</option>
                <option value="1">Under behandling</option>
                <option value="2">Løst</option>
            </select>
        </div>
        <input type="hidden" name="saksnr" value="<?php echo isset($saksnr) ? $saksnr : ""; ?>">
        <button type="submit" class="btn btn-primary">Send Melding eller Endre Status</button>
    </form>
</body>

</html>
