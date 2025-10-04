<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()){
  Redirect::to('index.php');
}

if(Input::exits()){
  if(Token::check(Input::get(''))){
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
      'password_current' => array(
        'required' => true,
        'min' => 6
      ),
      'password_new' => array(
        'required' =>true,
        'min' => 6
      ),
      'password_new_again' => array(
        'required' => true,
        'min' => 6,
        'matches' => 'password_new'
      )
    ));
    if($validation->passed()){
      if(Hash::make(Input::get('password_current'),$user->data()->salt) !== $user->data()->password){
        echo 'Senha actual errada';
      }else{
        $salt = Hash::salt(16);
        $user->update(array(
          'password' => Hash::make(Input::get('password_new'), $salt),
          'salt' => $salt
        ));
        Session::flash('home', 'a sua palavra passse foi alterada');
        Redirect::to('index.php');
      }
    }else{
      foreach ($validation->errors() as $error) {
        echo $error, '<br>';
      }
    }


  }

}

?>


<form action="" method="POST">
  
  <div class="field">
    <label for="password_current">Senha actual</label>
    <input type="password" name="password_current" id="password_current">
  </div>
  <div class="field">
    <label for="new_password">Insira a sua nova senha novamente</label>
    <input type="password" name="new_password" id="new_password">
  </div>
  <div class="field">
    <label for="new_password_again">Insira a sua senha novamente</label>
    <input type="password" name="new_password_again" id="new_password_again">
  </div>
  
  <input type="hidden" name="token" value="<?php echo Token::generate();?>">
  <input type="submit" value="Registar">

</form>