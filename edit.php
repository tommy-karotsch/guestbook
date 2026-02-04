<?php

include 'config.php';

if(!isset($_SESSION['login'])){
    header('Location: connexion.php');
    exit();
}

if(!empty($_POST)){
    $new_login = ($_POST['new_login']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if(empty($new_login) || empty($current_password)){
        $message = 'Veuillez remplir tous les champs obligatoires.';
    }
    else{
        $sql ='SELECT * FROM user WHERE login = :login';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':login' => $_SESSION['login']]);
        $user = $stmt->fetch();

        if ($user && password_verify($current_password, $user['password'])){
            
            
            if(!empty($new_password) || !empty($confirm_password)){
                if($new_password !== $confirm_password){
                    $message = 'Les mots de passe ne correspondent pas.';
                } elseif(strlen($new_password) < 8 || !preg_match('/[0-9]/', $new_password) || !preg_match('/[A-Z]/', $new_password)){
                    $message = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.';
                } else {
                    // LOGIN et PASSWORD UPDATE
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $updateSql = 'UPDATE user SET login = :new_login, password = :new_password WHERE login = :current_login';
                    $updateStmt = $pdo->prepare($updateSql);

                    if($updateStmt->execute([':new_login' => $new_login, ':new_password' => $hashed_password, ':current_login' => $_SESSION['login']])){
                        $_SESSION['login'] = $new_login;
                        $message = 'Profil et mot de passe mis à jour avec succès.';
                        header('Location: profil.php');
                        exit();
                    } else{
                        $message = 'Erreur lors de la mise à jour du profil.';
                    }
                }
            } else {
                // LOGIN UPDATE  
                $updateSql = 'UPDATE user SET login = :new_login WHERE login = :current_login';
                $updateStmt = $pdo->prepare($updateSql);

                if($updateStmt->execute([':new_login' => $new_login, ':current_login' => $_SESSION['login']])){
                    $_SESSION['login'] = $new_login;
                    $message = 'Profil mis à jour avec succès.';
                    header('Location: profil.php');
                    exit();
                    
                } else{
                    $message = 'Erreur lors de la mise à jour du profil.';
                }
            }
        }
        else{
            $message = 'Mot de passe incorrect.';
        }
    } 
}

include 'includes/header.php';

?>
    <main>
        <h1>Modifier mon profil</h1>
        <div class="edit-container">
        <form method="post">
            <label for="new_login">Nouveau login:</label>
            <input type="text" name="new_login" id="new_login" required>
            
            <label for="current_password">Mot de passe actuel:</label>
            <input type="password" name="current_password" id="current_password" required>
            
            <label for="new_password">Nouveau mot de passe (optionnel):</label>
            <input type="password" name="new_password" id="new_password">
            
            <label for="confirm_password">Confirmer le nouveau mot de passe:</label>
            <input type="password" name="confirm_password" id="confirm_password">
            
            <?php if(isset($message)): ?>
                <p class="message-info"><?= $message ?></p>
            <?php endif; ?>

            <button type="submit">Mettre à jour</button>
        </form>
        </div>
    </main>