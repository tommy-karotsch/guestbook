<?php

include 'config.php'; 

$message = '';

if(!empty($_POST)){
    $login = htmlspecialchars(trim($_POST['login'])); 
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if(empty($login) || empty($password) || empty($confirm_password)){
        $message = 'Veuillez remplir tous les champs.';
    }
    elseif(strlen($password) < 8 || !preg_match('/[0-9]/', $password) || !preg_match('/[A-Z]/', $password)){
        $message = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.';
    }
    elseif($password !== $confirm_password){
        $message = 'Les mots de passe ne correspondent pas.';
    }
    else{
        if(isset($pdo)) {
            try {
                $checkSql = "SELECT count(*) FROM user WHERE login = :login";
                $checkStmt = $pdo->prepare($checkSql);
                $checkStmt->execute([':login' => $login]);
                $userExists = $checkStmt->fetchColumn();

                if($userExists > 0){
                    $message = 'Ce login est déjà pris.';
                }
                else{
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO user (login, password) VALUES (:login, :password)";
                    $stmt = $pdo->prepare($sql);

                    if($stmt->execute([':login' => $login, ':password' => $hash])){
                        header('Location: connexion.php');
                        exit();
                    }
                    else{
                        $errorInfo = $stmt->errorInfo();
                        $message = "Erreur technique lors de l'inscription : " . $errorInfo[2];
                    }
                }
            } catch(PDOException $e) {
                $message = "Erreur SQL : " . $e->getMessage();
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<body>
    <main>
        <h1>Inscription</h1>
        
        <?php if(!empty($message)): ?>
            <div style="color: red; background: #fdd; padding: 10px; border: 1px solid red; margin-bottom: 20px; text-align: center;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <section>
            <form action="" method="post">
                <input type="text" name="login" placeholder="Login" value="<?php echo isset($login) ? $login : ''; ?>">
                <input type="password" name="password" placeholder="Mot de passe">
                <input type="password" name="confirm_password" placeholder="Confirmez le mot de passe">
                <input type="submit" value="S'inscrire">
            </form>
        </section>
    </main>
</body>
</html>