<?php
/**
 * Bazar Mix da Jô — Login administrativo
 *
 * Autenticação por email + senha com password_verify().
 * Sessão PHP para proteger acesso ao painel.
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';

// Se já logado, redireciona para o dashboard
if (is_logged_in()) {
    redirect('/admin/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Informe email e senha.';
    } else {
        $stmt = db()->prepare('SELECT * FROM admins WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            redirect('/admin/dashboard.php');
        }

        $error = 'Email ou senha inválidos.';
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Login - <?= e(STORE_NAME) ?></title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body class="login-page">
    <main class="login-card">
        <h1>🛍️ <?= e(STORE_NAME) ?></h1>
        <p>Acesso administrativo</p>

        <?php if ($error): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <label>Email
                <input type="email" name="email" required autocomplete="email" placeholder="admin@bazarmixjo.com">
            </label>
            <label>Senha
                <input type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            </label>
            <button type="submit">Entrar</button>
        </form>
        <a href="/">← Voltar para a loja</a>
    </main>
</body>
</html>
