<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()){
    Redirect::to('login.php');
}

$username = Input::get('user');
if (!$username) {
    $username = $user->data()->username;
}

$profileUser = new User($username);
if (!$profileUser->exists()) {
    Redirect::to('404.php');
}

$data = $profileUser->data();


$candidate = new Candidate();
$candidateData = $candidate->getWithDetailsByUserId($data->id);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Perfil de <?php echo escape($data->username); ?></title>
    <style>
/* ---------------------------------------------------------
   RESET & BASE
--------------------------------------------------------- */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: "Inter", Arial, sans-serif;
    background: #eef2f6;
    color: #333;
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

/* ---------------------------------------------------------
   CONTAINER DO PERFIL
--------------------------------------------------------- */
.profile-container {
    max-width: 950px;
    margin: 40px auto;
    background: white;
    padding: 40px 45px;
    border-radius: 14px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    animation: fadeIn .5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Título */
.profile-container h2 {
    text-align: center;
    color: #003f7f;
    font-size: 28px;
    margin-bottom: 30px;
    font-weight: 700;
}

/* ---------------------------------------------------------
   GRID DE INFORMAÇÕES
--------------------------------------------------------- */
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px 25px;
}

.info-item {
    background: #f4f8fc;
    padding: 14px 18px;
    border-radius: 8px;
    border-left: 4px solid #003f7f;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}

.info-item strong {
    display: block;
    font-size: 14px;
    color: #003f7f;
    margin-bottom: 3px;
}

.info-item span {
    font-size: 14px;
    color: #333;
}

/* ---------------------------------------------------------
   BOTÃO VOLTAR
--------------------------------------------------------- */
.back-link {
    display: inline-block;
    margin-top: 35px;
    padding: 12px 22px;
    background: #003f7f;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: 0.3s;
}

.back-link:hover {
    background: #002952;
}

/* ---------------------------------------------------------
   RESPONSIVIDADE
--------------------------------------------------------- */
@media (max-width: 780px) {
    .info-grid {
        grid-template-columns: 1fr;
    }

    .up-header {
        flex-direction: column;
        text-align: center;
        padding: 25px 20px;
    }

    .up-header img {
        height: 48px;
    }
}
    </style>
</head>

<body>

    <!-- HEADER FIXO -->
    <header class="up-header">
        <img src="public/images/logo.png" alt="UP Logo">
        <div class="title-block">
            <h1>Universidade Pedagógica de Moçambique</h1>
            <p>Portal do Candidato</p>
        </div>
    </header>

    <div class="profile-container">
        <h2>Perfil de <?php echo escape($data->username); ?></h2>

        <div class="info-grid">

            <div class="info-item">
                <strong>Nome completo</strong>
                <span><?php echo escape($candidateData->full_name ?? $data->name); ?></span>
            </div>

            <div class="info-item">
                <strong>Email</strong>
                <span><?php echo escape($candidateData->email ?? 'Não informado'); ?></span>
            </div>

            <div class="info-item">
                <strong>Telefone</strong>
                <span><?php echo escape($candidateData->phone_number ?? 'Não informado'); ?></span>
            </div>

            <div class="info-item">
                <strong>Data de nascimento</strong>
                <span><?php echo escape($candidateData->birthday ?? '—'); ?></span>
            </div>

            <div class="info-item">
                <strong>Documento de Identificação</strong>
                <span><?php echo escape($candidateData->id_number ?? '—'); ?></span>
            </div>

            <div class="info-item">
                <strong>NUIT</strong>
                <span><?php echo escape($candidateData->nuit_number ?? '—'); ?></span>
            </div>

            <div class="info-item">
                <strong>Província</strong>
                <span><?php echo escape($candidateData->province_name ?? '—'); ?></span>
            </div>

            <div class="info-item">
                <strong>Distrito</strong>
                <span><?php echo escape($candidateData->district_name ?? '—'); ?></span>
            </div>

            <div class="info-item">
                <strong>Escola de Proveniência</strong>
                <span><?php echo escape($candidateData->school_name ?? '—'); ?></span>
            </div>

            <div class="info-item">
                <strong>Curso Pretendido</strong>
                <span><?php echo escape($candidateData->course_name ?? '—'); ?></span>
            </div>

            <div class="info-item">
                <strong>Delegação</strong>
                <span><?php echo escape($candidateData->delegation_name ?? '—'); ?></span>
            </div>

            <div class="info-item">
                <strong>Regime</strong>
                <span><?php echo escape($candidateData->regime_name ?? '—'); ?></span>
            </div>

            <div class="info-item">
                <strong>Grupo</strong>
                <span><?php echo escape($candidateData->group_name ?? '—'); ?></span>
            </div>

            <div class="info-item">
                <strong>Possui certificado?</strong>
                <span><?php echo ($candidateData && $candidateData->has_certificate) ? 'Sim' : 'Não'; ?></span>
            </div>

        </div>

        <a href="index.php" class="back-link">← Voltar</a>
    </div>

</body>
</html>
