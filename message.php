<?php

include 'config.php';

if(!empty($_POST['message']) && isset($_SESSION['id'])){
$sql = 'INSERT INTO message (message, id_user, date) VALUES (:message, :id_user, NOW())';
$stmt = $pdo->prepare($sql);
$stmt->execute([':message'=>$_POST['message'],':id_user'=>$_SESSION['id']]);

    header('Location: guestbook.php');
    exit();
}
?>
<?php include 'includes/header.php'; ?>
    <main>
        <h1>Message</h1>
        <div class="box-message">
        <form method="POST">
        <textarea name="message" placeholder="Ecrivez votre message ici..." rows="5"></textarea>
        <input type="submit" value="Envoyer">
        </form>
        </div>
    </main>