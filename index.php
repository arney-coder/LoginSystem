<?php 
require_once 'core/init.php';

if(Session::exists('home')){
  echo '<p>' . Session::flash('home') . '<p>';
}

$user = new User();
if($user->isLoggedIn()){
 ?>
 <p><Olá <a href="profile.php?user=<?php escape($user->data()->username);?>"><?php echo escape($user->data()->username);?> </a>!</p>
 <ul>
  <li><a href="logout.php">sair</a></li>
  <li><a href="update.php">actualizar</a></li>
  <li><a href="changepassword.php">trocar senha</a></li>
 </ul>
 
 <?php
  if($user->hasPermission('admin')){
    echo '<p>Você é administrador!</p>';
  }

}else{
  echo '<p><a href="login.php">entrar</a> ou <a href="register.php">registrar-se</a></p>';
}
// echo Session::get
// 07113243377