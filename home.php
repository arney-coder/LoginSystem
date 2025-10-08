<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Universidade Pedagógica - Candidatura</title>
    <link rel="stylesheet" href="/login_system/public/css/style.css">
</head>
<body>
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
