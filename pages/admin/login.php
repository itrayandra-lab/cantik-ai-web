<?php
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/helpers.php';

// Already logged in
if (isLoggedIn()) {
    redirect('/admin/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password wajib diisi.';
    } elseif (login($username, $password)) {
        redirect('/admin/index.php');
    } else {
        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Raymaizing Admin</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: 'Segoe UI', system-ui, sans-serif;
    background: #0f172a;
    color: #e2e8f0;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-image: radial-gradient(ellipse at 20% 50%, rgba(6,182,212,.08) 0%, transparent 60%),
                      radial-gradient(ellipse at 80% 20%, rgba(139,92,246,.08) 0%, transparent 60%);
  }
  .login-card {
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 20px;
    padding: 48px 40px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 25px 50px rgba(0,0,0,.5);
  }
  .logo { text-align: center; margin-bottom: 36px; }
  .logo-icon {
    width: 64px; height: 64px;
    background: linear-gradient(135deg, #06b6d4, #8b5cf6);
    border-radius: 16px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 28px; margin-bottom: 16px;
  }
  .logo h1 { font-size: 26px; font-weight: 700; background: linear-gradient(135deg, #06b6d4, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
  .logo p { color: #64748b; font-size: 14px; margin-top: 4px; }
  h2 { font-size: 20px; font-weight: 600; color: #f1f5f9; margin-bottom: 24px; text-align: center; }
  .form-group { margin-bottom: 18px; }
  label { display: block; font-size: 13px; font-weight: 500; color: #94a3b8; margin-bottom: 7px; }
  .input-wrap { position: relative; }
  .input-wrap span { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #475569; font-size: 16px; }
  input[type="text"], input[type="password"] {
    width: 100%; padding: 11px 14px 11px 42px;
    background: #0f172a; border: 1px solid #334155;
    border-radius: 10px; color: #f1f5f9; font-size: 14px;
    outline: none; transition: border-color .2s, box-shadow .2s;
  }
  input:focus { border-color: #06b6d4; box-shadow: 0 0 0 3px rgba(6,182,212,.15); }
  .btn-login {
    width: 100%; padding: 13px;
    background: linear-gradient(135deg, #06b6d4, #8b5cf6);
    border: none; border-radius: 10px;
    color: white; font-size: 15px; font-weight: 600;
    cursor: pointer; margin-top: 8px;
    transition: opacity .2s, transform .1s;
  }
  .btn-login:hover { opacity: .92; }
  .btn-login:active { transform: scale(.98); }
  .alert-error {
    background: #450a0a; border: 1px solid #ef4444;
    color: #fca5a5; padding: 11px 14px;
    border-radius: 8px; font-size: 13px; margin-bottom: 18px;
    display: flex; align-items: center; gap: 8px;
  }
  .footer-note { text-align: center; color: #475569; font-size: 12px; margin-top: 24px; }
</style>
</head>
<body>
<div class="login-card">
  <div class="logo">
    <div class="logo-icon">⚡</div>
    <h1>Raymaizing</h1>
    <p>Admin Panel</p>
  </div>

  <h2>Masuk ke Dashboard</h2>

  <?php if ($error): ?>
    <div class="alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" autocomplete="off">
    <div class="form-group">
      <label for="username">Username</label>
      <div class="input-wrap">
        <span>👤</span>
        <input type="text" id="username" name="username" placeholder="Masukkan username" required
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <div class="input-wrap">
        <span>🔒</span>
        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
      </div>
    </div>
    <button type="submit" class="btn-login">Masuk →</button>
  </form>

  <p class="footer-note">© <?= date('Y') ?> Raymaizing. All rights reserved.</p>
</div>
</body>
</html>
