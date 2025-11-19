<?php
require_once 'core/init.php';

if(Input::exits()){
    if(Token::check(Input::get('token'))){
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'username' => ['required' => true],
            'password' => ['required' => true],
        ]);

        if ($validation->passed()) {
            $user = new User();
            $remember = (Input::get('remember') === 'on') ? true : false;
            $login = $user->login(Input::get('username'), Input::get('password'), $remember);

            if($login){
                $group = $user->data()->group;
                if ($group == 1) {
                    Redirect::to('profile.php');
                } elseif ($group == 2) {
                    Redirect::to('dashboard.php');
                }
            } else {
                echo '<p style="color:red;">Erro ao acessar a conta</p>';
            }
        } else {
            foreach($validation->errors() as $error){
                echo "<p style='color:red;'>$error</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Login</title>
<style>
    
    body {
        font-family: Arial, sans-serif;
        background: #f4f4f9;
        margin: 0;
        /* padding: 20px; */
    }
    form {
        max-width: 400px;
        margin: 50px auto;
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }
    .field {
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
    }
    .field label {
        margin-bottom: 5px;
        font-weight: bold;
    }
    .field input[type="text"],
    .field input[type="password"] {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }
    .field input[type="checkbox"] {
        width: auto;
        margin-right: 5px;
    }
    input[type="submit"] {
        width: 100%;
        background: #4CAF50;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
    }
    input[type="submit"]:hover {
        background: #45a049;
    }
    p {
        text-align: center;
        margin-top: 10px;
    }

/* ---------------------------------------------------------
   HEADER INSTITUCIONAL
--------------------------------------------------------- */
.up-header {
    position: sticky;
    top: 0;
    width: 100%;
    background: #003f7f;
    padding: 18px 40px;
    display: flex;
    align-items: center;
    gap: 20px;
    color: white;
    border-bottom: 4px solid #002952;
    z-index: 10;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.up-header img {
    height: 55px;
}

.up-header .title-block h1 {
    font-size: 20px;
    font-weight: 700;
    letter-spacing: .5px;
}

.up-header .title-block p {
    font-size: 13px;
    opacity: 0.85;
    margin-top: 2px;
}

</style>
</head>
<body>
<header class="up-header">
        <img src="public/images/logo.png" alt="UP Logo">
        <div class="title-block">
            <h1>Universidade Pedagógica de Moçambique</h1>
            <p>Portal do Candidato</p>
        </div>
    </header>

<form action="" method="post">
  <h2>Login</h2>

  <div class="field">
    <label for="username">Usuário</label>
    <input type="text" name="username" id="username" autocomplete="off" value="<?php echo escape(Input::get('username')); ?>">
  </div>

  <div class="field">
    <label for="password">Senha</label>
    <input type="password" name="password" id="password">
  </div>

  <div class="field">
    <label>
      <input type="checkbox" name="remember" id="remember">
      Lembrar-me
    </label>
  </div>

  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
  <input type="submit" value="Entrar">
</form>

</body>
</html>
