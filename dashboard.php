<?php
require_once 'core/init.php';

$user = new User();
if(!$user->isLoggedIn()){
  Redirect::to('login.php');
}

if(!$user->hasPermission('admin')){
  // Redirect::to('profile.php');
  die('Acesso negado. Apenas administradores podem visualizar esta página.');
}

$db = DB::getInstance();

$users = $db->query("SELECT * FROM users")->results();
$candidates = $db->query("SELECT * FROM candidates")->results();

?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Painel Administrativo</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f6f8fa;
      margin: 0;
      padding: 0;
    }
    header {
      background: #004a99;
      color: white;
      text-align: center;
      padding: 20px;
    }
    header h1 { margin: 0; }
    main {
      padding: 30px;
      max-width: 1100px;
      margin: auto;
      background: white;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      color: #004a99;
      border-left: 5px solid #004a99;
      padding-left: 10px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 40px;
    }
    th, td {
      padding: 8px 12px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }
    th {
      background: #004a99;
      color: white;
    }
    tr:hover { background: #eef3ff; }
    footer {
      text-align: center;
      padding: 10px;
      color: #777;
    }
    .logout {
      background: #c82333;
      color: white;
      text-decoration: none;
      padding: 6px 12px;
      border-radius: 4px;
      font-size: 14px;
    }
  </style>
</head>
<body>

<header>
  <h1>Painel de Administração</h1>
  <p>Bem-vindo, <?= htmlspecialchars($user->data()->username); ?>!</p>
  <a href="logout.php" class="logout">Sair</a>
</header>

<main>
  <section>
    <h2>Usuários do Sistema</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Username</th>
          <th>Grupo</th>
          <th>Data de Criação</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($users as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u->id) ?></td>
          <td><?= htmlspecialchars($u->name) ?></td>
          <td><?= htmlspecialchars($u->username) ?></td>
          <td><?= htmlspecialchars($u->group) ?></td>
          <td><?= htmlspecialchars($u->joined) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </section>

  <section>
    <h2>Candidatos Registrados</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome Completo</th>
          <th>Email</th>
          <th>Telefone</th>
          <th>Província</th>
          <th>Curso</th>
          <th>Delegação</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($candidates as $c): ?>
        <tr>
          <td><?= htmlspecialchars($c->id) ?></td>
          <td><?= htmlspecialchars($c->full_name) ?></td>
          <td><?= htmlspecialchars($c->email) ?></td>
          <td><?= htmlspecialchars($c->phone_number) ?></td>
          <td><?= htmlspecialchars($c->id_province) ?></td>
          <td><?= htmlspecialchars($c->id_course) ?></td>
          <td><?= htmlspecialchars($c->id_delegation) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </section>
</main>

<footer>
  <p>&copy; <?= date('Y') ?> Universidade Pedagógica - Sistema de Candidaturas</p>
</footer>

</body>
</html>
