<?php
require_once 'core/init.php';

$db = DB::getInstance();
$countries   = $db->getAll("country")->results();
$provinces   = $db->getAll("province")->results();
$districts   = $db->getAll("district")->results();
$schools     = $db->getAll("school")->results();
$regimes     = $db->getAll("regime")->results();
$groups      = $db->getAll("candidate_group")->results();
$delegations = $db->getAll("delegation")->results();
$courses     = $db->getAll("course")->results();

if (Input::exits() && Token::check(Input::get('token'))) {

    $validate = new Validate();
    $validation = $validate->check($_POST, [
        'username' => [
          'required' => true, 
          'min' => 2, 
          'max' => 20, 
          'unique' => 'users'
        ],
        'password' => [
          'required' => true, 
          'min' => 6
        ],
        'password_again' => [
          'required' => true, 
          'matches' => 'password'
        ],
        'name' => [
          'required' => true, 
          'min' => 2, 
          'max' => 50
        ],
        'email' => [
          'required' => true,
          'min' => 2, 
          'max' => 50
        ],
        'first_name' => [
          'required' => true,
          'min' => 2, 
          'max' => 50
        ],
        'last_name' => [
          'required' => true,
          'min' => 2, 
          'max' => 50
        ]
    ]);

    if ($validation->passed()) {
        $user = new User();
        

        $salt = Hash::salt(16);
        try {
            $user->create([
                'username' => Input::get('username'),
                'password' => Hash::make(Input::get('password'), $salt),
                'salt' => $salt,
                'name' => Input::get('name'),
                'joined' => date('Y-m-d H:i:s'),
                'group' => 1 
            ]);
            
            $user->find(Input::get('username'));

            $id = $user->data()->id;

            $candidate = new Candidate();
            $candidate->create( [
                'user_id' => $id,
                'first_name' => Input::get('first_name'),
                'last_name' => Input::get('last_name'),
                'full_name' => Input::get('full_name'),
                'birthday' => Input::get('birthday'),
                'id_number' => Input::get('id_number'),
                'nuit_number' => Input::get('nuit_number'),
                'email' => Input::get('email'),
                'phone_number' => Input::get('phone_number'),
                'id_country' => Input::get('id_country'),
                'id_province' => Input::get('id_province'),
                'id_district' => Input::get('id_district'),
                'id_school_provenience' => Input::get('id_school_provenience'),
                'id_regime' => Input::get('id_regime'),
                'id_group' => Input::get('id_group'),
                'id_delegation' => Input::get('id_delegation'),
                'id_course' => Input::get('id_course'),
                'has_certificate' => Input::get('has_certificate') ? 1 : 0
            ]);

            Session::flash('home', 'Candidato registado com sucesso! Já pode entrar.');
            Redirect::to('index.php');

        } catch (Exception $e) {
            die($e->getMessage());
        }

    } else {
        foreach ($validation->errors() as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Registo de Candidato</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f4f9;
        margin: 0;
        padding: 20px;
    }
    form {
        max-width: 800px;
        margin: 0 auto;
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        color: #333;
    }
    fieldset {
        border: 1px solid #ddd;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 5px;
    }
    legend {
        font-weight: bold;
        color: #555;
        padding: 0 10px;
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
    .field input, .field select {
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }
    .field input[type="checkbox"] {
        width: auto;
        margin-right: 5px;
    }
    input[type="submit"] {
        background: #4CAF50;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }
    input[type="submit"]:hover {
        background: #45a049;
    }
</style>
</head>
<body>

<form action="" method="POST">
  <h2>Registo de Candidato</h2>

  <fieldset>
    <legend>Conta de Usuário</legend>
    <div class="field">
      <label for="username">Usuário</label>
      <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off">
    </div>
    <div class="field">
      <label for="password">Senha</label>
      <input type="password" name="password" id="password">
    </div>
    <div class="field">
      <label for="password_again">Confirmar Senha</label>
      <input type="password" name="password_again" id="password_again">
    </div>
    <div class="field">
      <label for="name">Nome (para exibição)</label>
      <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>">
    </div>
  </fieldset>

  <fieldset>
    <legend>Informações Pessoais</legend>
    <div class="field">
      <label for="first_name">Nome</label>
      <input type="text" name="first_name" id="first_name" required>
    </div>
    <div class="field">
      <label for="last_name">Apelido</label>
      <input type="text" name="last_name" id="last_name" required>
    </div>
    <div class="field">
      <label for="full_name">Nome Completo</label>
      <input type="text" name="full_name" id="full_name" required>
    </div>
    <div class="field">
      <label for="birthday">Data de Nascimento</label>
      <input type="date" name="birthday" id="birthday" required>
    </div>
    <div class="field">
      <label for="id_number">Número do BI</label>
      <input type="text" name="id_number" id="id_number" required>
    </div>
    <div class="field">
      <label for="nuit_number">Número do NUIT</label>
      <input type="text" name="nuit_number" id="nuit_number">
    </div>
    <div class="field">
      <label for="email">E-mail</label>
      <input type="email" name="email" id="email" required>
    </div>
    <div class="field">
      <label for="phone_number">Telefone</label>
      <input type="text" name="phone_number" id="phone_number" required>
    </div>
  </fieldset>

  <fieldset>
    <legend>Localização e Curso</legend>
    <div class="field">
      <label for="id_country">País</label>
      <select name="id_country" id="id_country" required>
        <option value="">--Selecione--</option>
        <?php foreach ($countries as $c): ?>
          <option value="<?= $c->id ?>"><?= htmlspecialchars($c->designation) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label for="id_province">Província</label>
      <select name="id_province" id="id_province" required>
        <option value="">--Selecione--</option>
        <?php foreach ($provinces as $p): ?>
          <option value="<?= $p->id ?>"><?= htmlspecialchars($p->designation) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label for="id_district">Distrito</label>
      <select name="id_district" id="id_district" required>
        <option value="">--Selecione--</option>
        <?php foreach ($districts as $d): ?>
          <option value="<?= $d->id ?>"><?= htmlspecialchars($d->designation) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label for="id_school_provenience">Escola de Proveniência</label>
      <select name="id_school_provenience" id="id_school_provenience" required>
        <option value="">--Selecione--</option>
        <?php foreach ($schools as $s): ?>
          <option value="<?= $s->id ?>"><?= htmlspecialchars($s->designation) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label for="id_regime">Regime</label>
      <select name="id_regime" id="id_regime" required>
        <option value="">--Selecione--</option>
        <?php foreach ($regimes as $r): ?>
          <option value="<?= $r->id ?>"><?= htmlspecialchars($r->designation) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label for="id_group">Grupo</label>
      <select name="id_group" id="id_group" required>
        <option value="">--Selecione--</option>
        <?php foreach ($groups as $g): ?>
          <option value="<?= $g->id ?>"><?= htmlspecialchars($g->designation) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label for="id_delegation">Delegação</label>
      <select name="id_delegation" id="id_delegation" required>
        <option value="">--Selecione--</option>
        <?php foreach ($delegations as $del): ?>
          <option value="<?= $del->id ?>"><?= htmlspecialchars($del->designation) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label for="id_course">Curso Pretendido</label>
      <select name="id_course" id="id_course" required>
        <option value="">--Selecione--</option>
        <?php foreach ($courses as $c): ?>
          <option value="<?= $c->id ?>"><?= htmlspecialchars($c->designation) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label>
        <input type="checkbox" name="has_certificate" value="1">
        Possui Certificado de Ensino Médio
      </label>
    </div>
  </fieldset>

  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
  <input type="submit" value="Registar">
</form>

</body>
</html>
