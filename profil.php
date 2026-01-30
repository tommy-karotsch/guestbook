<?php

include 'config.php';

if(!isset($_SESSION['login'])){
    header('Location: connexion.php');
    exit();
}
?>

<?php include 'includes/header.php'; ?>

<main>
    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['login']); ?> !</h1>
    
    <ul class="box-profil">
        <li><a href="edit.php">Modifier mon profil</a></li>
        <li><a href="guestbook.php">Voir le Livre d'Or</a></li>
        <li><a href="logout.php">Se déconnecter</a></li>
        <li><a href="delete.php">Supprimer mon compte</a></li>
    </ul>
</main>