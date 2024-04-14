<?php
require_once "../include/config_session.inc.php";
require_once '../include/dbconn.inc.php';
require_once 'index.php';

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header("location: ../index.php");
    exit();
}

$sql = "SELECT saksnummer, user_id, problem, 
        CASE 
            WHEN er_lost = 0 THEN 'Ikke løst' 
            WHEN er_lost = 1 THEN 'Under behandling' 
            WHEN er_lost = 2 THEN 'Løst' 
            ELSE 'Ukjent' 
        END AS status
        FROM saker";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>
    <h1>Admin Panel</h1>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Saksnummer</th>
                <th scope="col">Bruker-ID</th>
                <th scope="col">Problem</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row): ?>
            <tr>
                <td><a href="behandle_saker.php?saksnr=<?php echo $row['saksnummer']; ?>"><?php echo $row['saksnummer']; ?></a></td>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['problem']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>
