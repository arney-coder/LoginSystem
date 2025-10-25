<?php
require_once 'core/init.php';

// $user = new User();

// if(!$user->isLoggedIn()){
//   Redirect::to('login.php');
// }

// if($user->data()->group != 2){
//   Redirect::to('dashboard.php');
// }

// $username = $user->data()->username;
// if (!$username = Input::get('user')) {
//   Redirect::to('index.php');
// } else {
//   $user = new User(user: $username);

//   if (!$user->exists()) {
//     Redirect::to('404.php');
//   } else {
//     $data = $user->data();
//     $db = DB::getInstance();
//     $candidate = $db->get('candidates', ['user_id', '=', $data->id]);
//     $candidateData = $candidate->count() ? $candidate->first() : null;
//   }
// }

$user = new User();

if(!$user->isLoggedIn()){
  Redirect::to('login.php');
}

// Remove this redirect to dashboard
// if($user->data()->group != 2){
//   Redirect::to('dashboard.php');
// }

$username = Input::get('user');
if (!$username) {
  Redirect::to('index.php');
}

$profileUser = new User(user: $username);
if (!$profileUser->exists()) {
  Redirect::to('404.php');
}

$data = $profileUser->data();

?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Perfil de <?php echo escape($data->username); ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f9;
      margin: 0;
      padding: 0;
    }
    .profile-container {
      max-width: 700px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #333;
    }
    .info {
      margin-top: 20px;
    }
    .info p {
      font-size: 15px;
      padding: 6px 0;
      border-bottom: 1px solid #eee;
    }
    .info strong {
      color: #555;
    }
  </style>
</head>
<body>

  <div class="profile-container">
    <h2>Perfil de <?php echo escape($data->username); ?></h2>

    <div class="info">
      <p><strong>Nome completo:</strong> <?php echo escape($data->name); ?></p>
      <p><strong>Email:</strong> <?php echo escape($candidateData->email ?? 'Não informado'); ?></p>
      <p><strong>Telefone:</strong> <?php echo escape($candidateData->phone_number ?? 'Não informado'); ?></p>
      <p><strong>Data de nascimento:</strong> <?php echo escape($candidateData->birthday ?? '—'); ?></p>
      <p><strong>Documento de Identificação:</strong> <?php echo escape($candidateData->id_number ?? '—'); ?></p>
      <p><strong>NUIT:</strong> <?php echo escape($candidateData->nuit_number ?? '—'); ?></p>
      <p><strong>Província:</strong> <?php echo escape($candidateData->id_province ?? '—'); ?></p>
      <p><strong>Distrito:</strong> <?php echo escape($candidateData->id_district ?? '—'); ?></p>
      <p><strong>Escola de Proveniência:</strong> <?php echo escape($candidateData->id_school_provenience ?? '—'); ?></p>
      <p><strong>Curso Pretendido:</strong> <?php echo escape($candidateData->id_course ?? '—'); ?></p>
      <p><strong>Delegação:</strong> <?php echo escape($candidateData->id_delegation ?? '—'); ?></p>
      <p><strong>Regime:</strong> <?php echo escape($candidateData->id_regime ?? '—'); ?></p>
      <p><strong>Possui Certificado:</strong> <?php echo ($candidateData && $candidateData->has_certificate) ? 'Sim' : 'Não'; ?></p>
    </div>
  </div>

</body>
</html>
