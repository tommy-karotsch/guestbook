<?php 

include 'config.php';

if(!isset($_SESSION['login'])){
    header('Location: connexion.php');
    exit();
}

$sql = 'SELECT message.message, message.date, user.login FROM message INNER JOIN user ON message.id_user =user.id ORDER BY message.date DESC';
$stmt = $pdo->query($sql);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include 'includes/header.php'; ?>


<main>
    <h1>Livre d'Or</h1>
        <?php foreach($messages as $msg): ?>
            <div class="box-guestbook">
            <?php
                $date_fr = date('d/m/Y', strtotime($msg['date']));
            ?>

    <div class="message-item">
        <p class="meta-info">
            Posté le <?php echo $date_fr; ?>
            par <strong><?php echo htmlspecialchars($msg['login']); ?></strong>
        </p>
        <p class="content">
            <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
        </p>
    </div>
</div>
<?php endforeach; ?>
</main>