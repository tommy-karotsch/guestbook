<?php

include 'config.php';
include 'includes/header.php';

$message = '';

if(!empty($_POST)){
    $login = $_POST['login'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if(!isset($login) || !isset($password) || !isset($confirm_password)){
        $message = 'Veuillez remplir tous les champs.';
    }
    elseif(strlen($password) < 8 || !preg_match('/[0-9]/', $password) || !preg_match('/[A-Z]/', $password)){
        $message = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.';
    }
    elseif($password !== $confirm_password){
        $message = 'Les mots de passe ne correspondent pas.';
    }
    else{
        $checkSql = "SELECT * FROM users WHERE login = :login";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([':login' => $login]);

        if($checkStmt->rowCount() > 0){
            echo 'Ce login est déjà pris.';
        }
        else{
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = 'INSERT INTO users (login, password) VALUES (:login, :password)';
            $stmt = $pdo->prepare($sql);

            if($stmt->execute([':login' => $login,':password' => $hash])){
                header('Location: connexion.php');
                exit();
            }
            else{
                $message = "Erreur lors de l'enregistrement en base de données.";
            }
        }
    }
}
?>
<body>
    <main>
        <h1>Inscription</h1>
        <section>
            <form action="" method="post">
                <input type="text" name="login" placeholder="Login" value="<?php echo isset($login) ? htmlspecialchars($login) : ''; ?>">
                <input type="password" name="password" placeholder="Mot de passe">
                <input type="password" name="confirm_password" placeholder="Confirmez le mot de passe">
                <input type="submit" value="S'inscrire">
            </form>
        </section>

        <?php if(!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?> </main>
</body>
</html>