<?php
require_once 'core/init.php';
// var_dump(Token::check(Input::get('token')));

if(Input::exits()){
  if(Token::check(Input::get('token'))){
    echo 'Fui executado';
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
      'username' => array(
        'required' => true,
        'min' => 2,
        'max' => 20,
        'unique' => 'users'
      ),
      'password' => array(
        'required' => true,
        'min' => 6
      ),
      'password_again' => array(
        'required' => true,
        'matches' => 'password'
      ),
      'name' => array(
        'required' => true,
        'min' => 2,
        'max' => 50
      )
    ));
    if ($validation->passed()) {
      $user = new User();
      $salt = Hash::salt(16);
      try{
        $user->create(array(
          'username' => Input::get('username'), 
          'password' => Hash::make(Input::get('password'), $salt),
          'salt' => $salt,
          'name' => Input::get('name'),
          'joined' => date('Y-m-d H:i:s'),
          'group' => 1
        ));

        Session::flash('home', 'Registado, já pode entrar');
        Redirect::to('index.php');
      }catch(Exception $e){
        die($e->getMessage());
      }
    } else {
      foreach($validation->errors() as $error){
        echo $error, '<br>';
      }
    }
  }
}
?>

<form action="" method="POST">
  <div class="field">
    <label for="username">Usuário</label>
    <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username'));?>" autocomplete="off">
  </div>
  <div class="field">
    <label for="password">Escolha uma senha</label>
    <input type="password" name="password" id="password">
  </div>
  <div class="field">
    <label for="password_again">Insira a sua senha novamente</label>
    <input type="password" name="password_again" id="password_again">
  </div>
  <div class="field">
    <label for="name">Nome</label>
    <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name'));?>">
  </div>
  <input type="hidden" name="token" value="<?php echo Token::generate();?>">
  <input type="submit" value="Registar">

</form>