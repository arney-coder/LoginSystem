<?php
require_once 'core/init.php';

$user = new User();


if(!$user->isLoggedIn()){
    Redirect::to('login.php');
}

if(!$user->hasPermission('admin')){
    Session::flash('dashboard', 'Acesso negado! Apenas administradores podem deletar candidatos.');
    Redirect::to('index.php');
}

// Verificar se ID foi passado
$candidateId = Input::get('id');
if(!$candidateId || !is_numeric($candidateId)) {
    Session::flash('dashboard', 'ID de candidato inválido.');
    Redirect::to('dashboard.php');
}

// Buscar dados do candidato para confirmação
$candidate = new Candidate();
$candidateData = $candidate->getWithDetails($candidateId);

if(!$candidateData) {
    Session::flash('dashboard', 'Candidato não encontrado.');
    Redirect::to('dashboard.php');
}

 
if(Input::exits()) {
    if(Token::check(Input::get('token'))) {
        
        $confirm = Input::get('confirm_delete');
        
        if($confirm === 'yes') {
            try {
            
                $candidate->delete($candidateId);
                
                Session::flash('dashboard', 'Candidato "' . $candidateData->full_name . '" excluído com sucesso!');
                Redirect::to('dashboard.php');
                
            } catch(Exception $e) {
                echo '<p style="color:red;">Erro ao excluir: ' . $e->getMessage() . '</p>';
            }
        } else {
     
            Session::flash('dashboard', 'Exclusão cancelada.');
            Redirect::to('dashboard.php');
        }
        
    } else {
        echo '<p style="color:red;">Token inválido!</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Excluir Candidato</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #d32f2f;
            margin-bottom: 20px;
        }
        .warning-box {
            background: #ffebee;
            border-left: 4px solid #d32f2f;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .warning-box strong {
            color: #d32f2f;
            font-size: 18px;
        }
        .candidate-info {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .candidate-info p {
            margin: 8px 0;
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }
        .candidate-info p:last-child {
            border-bottom: none;
        }
        .candidate-info strong {
            color: #555;
        }
        .buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-delete {
            background: #d32f2f;
            color: white;
        }
        .btn-delete:hover {
            background: #b71c1c;
        }
        .btn-cancel {
            background: #757575;
            color: white;
        }
        .btn-cancel:hover {
            background: #616161;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #666;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>⚠️ Confirmar Exclusão</h2>
        
        <div class="warning-box">
            <strong>ATENÇÃO!</strong>
            <p>Esta ação não pode ser desfeita. Todos os dados do candidato e do usuário serão permanentemente excluídos.</p>
        </div>
        
        <div class="candidate-info">
            <h3>Dados do Candidato:</h3>
            <p><strong>ID:</strong> <?php echo $candidateData->id; ?></p>
            <p><strong>Nome Completo:</strong> <?php echo escape($candidateData->full_name); ?></p>
            <p><strong>Username:</strong> <?php echo escape($candidateData->username); ?></p>
            <p><strong>Email:</strong> <?php echo escape($candidateData->email); ?></p>
            <p><strong>Telefone:</strong> <?php echo escape($candidateData->phone_number); ?></p>
            <p><strong>BI:</strong> <?php echo escape($candidateData->id_number); ?></p>
            <p><strong>Província:</strong> <?php echo escape($candidateData->province_name ?? '—'); ?></p>
            <p><strong>Curso:</strong> <?php echo escape($candidateData->course_name ?? '—'); ?></p>
        </div>
        
        <p style="text-align: center; font-weight: bold; color: #d32f2f;">
            Tem certeza que deseja excluir este candidato?
        </p>
        
        <form method="POST">
            <div class="buttons">
                <button type="submit" name="confirm_delete" value="yes" class="btn-delete">
                    🗑️ Sim, Excluir
                </button>
                <button type="submit" name="confirm_delete" value="no" class="btn-cancel">
                    ❌ Cancelar
                </button>
            </div>
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        </form>
        
        <a href="dashboard.php" class="back-link">← Voltar ao Dashboard</a>
    </div>
</body>
</html>