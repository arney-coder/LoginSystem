<?php
require_once 'core/init.php';

$user = new User();


if(!$user->isLoggedIn()){
    Redirect::to('login.php');
}

if(!$user->hasPermission('admin')){
    echo '<p style="color:red;">Acesso negado! Apenas administradores.</p>';
    echo '<a href="profile.php?user=' . $user->data()->username . '">Voltar</a>';
    exit();
}
 
$candidate = new Candidate();
$candidatesData = $candidate->getAllWithDetails();


$db = DB::getInstance();
$totalUsers = $db->getAll('users')->count();
$totalCandidates = count($candidatesData);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            margin: 0;
            /* padding: 20px; */
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .admin-info {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 13px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px 8px;
            text-align: left;
        }
        table th {
            background-color: #4CAF50;
            color: white;
            position: sticky;
            top: 0;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .actions a {
            margin-right: 8px;
            color: #4CAF50;
            text-decoration: none;
            font-size: 12px;
        }
        .actions a:hover {
            text-decoration: underline;
        }
        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-box {
            flex: 1;
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-box h3 {
            margin: 0;
            font-size: 32px;
            color: #1976d2;
        }
        .stat-box p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .menu {
            margin-bottom: 20px;
        }
        .menu a {
            margin-right: 15px;
            padding: 8px 15px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .menu a:hover {
            background: #45a049;
        }
        .table-container {
            overflow-x: auto;
        }
        .badge-yes {
            background: #4CAF50;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
        }
        .badge-no {
            background: #f44336;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
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
    <div class="container">
        <h1>Dashboard do Administrador</h1>
        <?php if(Session::exists('dashboard')): ?>
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #28a745;">
            <?php echo Session::flash('dashboard'); ?>
        </div>
        <?php endif; ?>
        <div class="admin-info">
            <strong>Logado como:</strong> <?php echo escape($user->data()->name); ?> 
            (<?php echo escape($user->data()->username); ?>)
        </div>
        
        <div class="menu">
            <a href="index.php">← Início</a>
            <a href="profile.php?user=<?php echo $user->data()->username; ?>">Meu Perfil</a>
            <a href="logout.php">Sair</a>
        </div>
        
        <div class="stats">
            <div class="stat-box">
                <h3><?php echo $totalCandidates; ?></h3>
                <p>Total de Candidatos</p>
            </div>
            <div class="stat-box">
                <h3><?php echo $totalUsers; ?></h3>
                <p>Total de Usuários</p>
            </div>
        </div>
        
        <h2>Lista de Candidatos Registrados</h2>
        
        <?php if(count($candidatesData) > 0): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Nome Completo</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Província</th>
                        <th>Curso</th>
                        <th>Delegação</th>
                        <th>BI</th>
                        <th>Certificado</th>
                        <th>Registrado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($candidatesData as $c): ?>
                    <tr>
                        <td><?php echo $c->id; ?></td>
                        <td><?php echo escape($c->username); ?></td>
                        <td><?php echo escape($c->full_name); ?></td>
                        <td><?php echo escape($c->email); ?></td>
                        <td><?php echo escape($c->phone_number); ?></td>
                        <td><?php echo escape($c->province_name ?? '—'); ?></td>
                        <td><?php echo escape($c->course_name ?? '—'); ?></td>
                        <td><?php echo escape($c->delegation_name ?? '—'); ?></td>
                        <td><?php echo escape($c->id_number); ?></td>
                        <td>
                            <?php if($c->has_certificate): ?>
                                <span class="badge-yes">Sim</span>
                            <?php else: ?>
                                <span class="badge-no">Não</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($c->joined)); ?></td>
                        <td class="actions">
                            <a href="profile.php?user=<?php echo $c->username; ?>">Ver</a>
                            <a href="edit_candidate.php?id=<?php echo $c->id; ?>">Editar</a>
                            <a href="delete_candidate.php?id=<?php echo $c->id; ?>" 
                               onclick="return confirm('Tem certeza que deseja excluir este candidato?')">Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p style="text-align:center; color:#999;">Nenhum candidato registrado ainda.</p>
        <?php endif; ?>
    </div>
</body>
</html>