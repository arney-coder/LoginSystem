<?php 
require_once 'core/init.php';

if(Session::exists('home')){
    echo '<p>' . Session::flash('home') . '</p>';
}

$user = new User();

if($user->isLoggedIn()){
?>
    <p>Olá <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>!</p>
    <ul>
        <li><a href="logout.php">Sair</a></li>
        <li><a href="update.php">Atualizar</a></li>
        <li><a href="changepassword.php">Trocar senha</a></li>
    </ul>
    <?php
    if($user->hasPermission('admin')){
        echo '<p>Você é administrador!</p>';
    }
} else {
    echo '<p><a href="login.php">Entrar</a> ou <a href="register.php">Registrar-se</a></p>';
}
?>