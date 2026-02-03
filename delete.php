<?php

include 'config.php';

if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    
    $sql = 'DELETE FROM user WHERE login = :login';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':login' => $_SESSION['login'],
    ]);

    session_destroy();
    header('Location: index.php');
    exit();

} else {
    header('Location: profil.php');
    exit();
}