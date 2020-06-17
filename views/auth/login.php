<?php

use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\Manager\Exception\NotFoundException;
use App\Manager\UserManager;
use App\Model\User;

$title = "Login";

$user = new User();
$errors = [];

if (!empty($_POST)) {
    $user->setUsername($_POST['username']);
    $errors['password'] = "Identifiant ou mot de passe incorrect";    
    
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $userManager = new UserManager(Connection::getPdo());
        try {
            $find = $userManager->findByUserName($_POST['username']);
            if (password_verify($_POST['password'], $find->getPassword())===true){
                session_start();
                $_SESSION['auth'] = $find->getId();
                header('Location:'.$router->url('admin_post'));
            }
        } catch (NotFoundException $e) {
        }
    }
}
$form = new Form($user, $errors);

?>


<h1>Se connecter</h1>
<?= isset($_GET['error'])? '<div class="alert alert-danger mt-5">Vous n\'êtes pas identifié</div>' : "" ?>
<?= isset($_GET['logout'])? '<div class="alert alert-info mt-5">Vous êtes déconnecté</div>' : "" ?>
<form action="<?= $router->url('login') ?>" method="POST">
    <?= $form->input('username', 'Nom d\'utilisateur') ?>
    <?= $form->input('password', 'Mot de passe') ?>
    <button type="submit" class="btn btn-primary">Se connecter</button>
</form>