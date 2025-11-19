<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Universidade Pedagógica - Candidatura</title>
    <link rel="stylesheet" href="/login_system/public/css/style.css">
    <style>

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
    <header  class="banner">
        <h1>Bem-vindo à Universidade Pedagógica</h1>
        <p>Participe do processo de candidatura online e dê o próximo passo na sua carreira académica.</p>
        <div class="header-buttons">
            <a class="btn" href="register.php">Inscrever-se</a>
            <a class="btn btn-login" href="login.php">Entrar na Conta</a>
        </div>
    </header>

    <main>
        <section class="hero">
            <h2>Inscreva-se já!</h2>
            <p>Clique abaixo para iniciar a sua inscrição no processo selectivo.</p>
            <a class="btn" href="register.php">Inscrever-se</a>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> Universidade Pedagógica</p>
    </footer>
    <script>
      const anoActual = new Date().getFullYear();
      document.title = `Universidade Pedagógica - Candidatura ${anoActual}`;
    </script>
</body>
</html>
