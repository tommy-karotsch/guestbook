<?php

include 'config.php';

if(!empty($_POST)){
    $sql ="SELECT * FROM user WHERE login = :login";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':login' => $_POST['login']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])){
        $_SESSION['login'] = $user['login'];
        $_SESSION['id'] = $user['id'];
        header('Location: profil.php');
        exit();
    } else{
        $message_error = 'Identifiants incorrects.';
    }
}
?>
<?php include 'includes/header.php'; ?>
<body>
    <main>
        <h1>Connexion</h1>
        
        <?php if(isset($message_error)){ ?>
            <div style="color: red; background: #fdd; padding: 10px; border: 1px solid red; margin-bottom: 20px; text-align: center;">
                <?php echo $message_error; ?>
            </div>
        <?php } ?>

        <section>
            <div class="form-section-connexion">
            <form action="" method="post">
                <input type="text" name="login" placeholder="Login" value="<?php echo isset($_POST['login']) ? ($_POST['login']) : ''; ?>">
                <input type="password" name="password" placeholder="Mot de passe">
                <input type="submit" value="Se connecter">
            </form>
            </div>
        </section>
    </main>
</body>