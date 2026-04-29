<?php
$pageTitle = 'Manajemen Pengguna';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

$pdo = getDB();

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    if ($delId === (int)$_SESSION['admin_user_id']) {
        setFlash('error', 'Tidak bisa menghapus akun sendiri.');
    } else {
        $pdo->prepare("DELETE FROM admin_users WHERE id = ?")->execute([$delId]);
        logActivity($_SESSION['admin_user_id'], 'delete_user', "Deleted user ID: $delId");
        setFlash('success', 'Pengguna berhasil dihapus.');
    }
    redirect('/admin/users/index.php');
}

// Handle toggle active
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $togId = (int)$_GET['toggle'];
    if ($togId === (int)$_SESSION['admin_user_id']) {
        setFlash('error', 'Tidak bisa menonaktifkan akun sendiri.');
    } else {
        $pdo->prepare("UPDATE admin_users SET is_active = NOT is_active WHERE id = ?")->execute([$togId]);
        setFlash('success', 'Status pengguna berhasil diubah.');
    }
    redirect('/admin/users/index.php');
}

// Handle create/update
$errors = [];
$editUser = null;

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editUser = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid       = (int)($_POST['id'] ?? 0);
    $username  = sanitize($_POST['username'] ?? '');
    $email     = sanitize($_POST['email'] ?? '');
    $full_name = sanitize($_POST['full_name'] ?? '');
    $role      = in_array($_POST['role'] ?? '', ['superadmin','admin','editor']) ? $_POST['role'] : 'editor';
    $password  = $_POST['password'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (empty($username))  $errors[] = 'Username wajib diisi.';
    if (empty($email))     $errors[] = 'Email wajib diisi.';
    if (empty($full_name)) $errors[] = 'Nama lengkap wajib diisi.';
    if ($uid === 0 && empty($password)) $errors[] = 'Password wajib diisi untuk pengguna baru.';

    // Check unique
    if (!empty($username)) {
        $check = $pdo->prepare("SELECT id FROM admin_users WHERE username = ? AND id != ?");
        $check->execute([$username, $uid]);
        if ($check->fetch()) $errors[] = 'Username sudah digunakan.';
    }
    if (!empty($email)) {
        $check = $pdo->prepare("SELECT id FROM admin_users WHERE email = ? AND id != ?");
        $check->execute([$email, $uid]);
        if ($check->fetch()) $errors[] = 'Email sudah digunakan.';
    }

    if (empty($errors)) {
        if ($uid > 0) {
            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $stmt = $pdo->prepare("UPDATE admin_users SET username=?, email=?, full_name=?, role=?, password=?, is_active=? WHERE id=?");
                $stmt->execute([$username, $email, $full_name, $role, $hash, $is_active, $uid]);
            } else {
                $stmt = $pdo->prepare("UPDATE admin_users SET username=?, email=?, full_name=?, role=?, is_active=? WHERE id=?");
                $stmt->execute([$username, $email, $full_name, $role, $is_active, $uid]);
            }
            logActivity($_SESSION['admin_user_id'], 'edit_user', "Updated user: $username");
            setFlash('success', "Pengguna \"$username\" berhasil diperbarui.");
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $stmt = $pdo->prepare("INSERT INTO admin_users (username, email, full_name, role, password, is_active) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$username, $email, $full_name, $role, $hash, $is_active]);
            logActivity($_SESSION['admin_user_id'], 'create_user', "Created user: $username");
            setFlash('success', "Pengguna \"$username\" berhasil dibuat.");
        }
        redirect('/admin/users/index.php');
    }
}

$users = $pdo->query("SELECT * FROM admin_users ORDER BY created_at DESC")->fetchAll();
?>

<div style="display: grid; grid-template-columns: 1fr 380px; gap: 20px; align-items: start;">

  <!-- Users Table -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">👥 Daftar Pengguna</h3>
      <span style="color: var(--text-muted); font-size: 13px;"><?= count($users) ?> pengguna</span>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Pengguna</th>
            <th>Role</th>
            <th>Status</th>
            <th>Login Terakhir</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <tr>
              <td>
                <div style="display: flex; align-items: center; gap: 10px;">
                  <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--accent-cyan),var(--accent-purple));display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;flex-shrink:0;">
                    <?= strtoupper(substr($u['full_name'], 0, 1)) ?>
                  </div>
                  <div>
                    <div style="font-weight: 600;"><?= htmlspecialchars($u['full_name']) ?></div>
                    <div style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($u['email']) ?></div>
                  </div>
                </div>
              </td>
              <td>
                <?php
                $roleColors = ['superadmin' => 'badge-purple', 'admin' => 'badge-info', 'editor' => 'badge-warning'];
                ?>
                <span class="badge <?= $roleColors[$u['role']] ?? 'badge-info' ?>"><?= ucfirst($u['role']) ?></span>
              </td>
              <td>
                <a href="?toggle=<?= $u['id'] ?>">
                  <?php if ($u['is_active']): ?>
                    <span class="badge badge-success">● Aktif</span>
                  <?php else: ?>
                    <span class="badge badge-danger">● Nonaktif</span>
                  <?php endif; ?>
                </a>
              </td>
              <td style="color: var(--text-muted); font-size: 12px;">
                <?= $u['last_login'] ? timeAgo($u['last_login']) : 'Belum pernah' ?>
              </td>
              <td>
                <div style="display: flex; gap: 6px;">
                  <a href="?edit=<?= $u['id'] ?>" class="btn btn-ghost btn-sm btn-icon" title="Edit">✏️</a>
                  <?php if ($u['id'] != $_SESSION['admin_user_id']): ?>
                    <a href="?delete=<?= $u['id'] ?>" class="btn btn-danger btn-sm btn-icon" title="Hapus"
                       data-confirm="Hapus pengguna <?= htmlspecialchars($u['username']) ?>?">🗑️</a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Add/Edit Form -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><?= $editUser ? '✏️ Edit Pengguna' : '➕ Tambah Pengguna' ?></h3>
      <?php if ($editUser): ?>
        <a href="/admin/users/index.php" class="btn btn-ghost btn-sm">✕ Batal</a>
      <?php endif; ?>
    </div>

    <?php if (!empty($errors)): ?>
      <div class="flash flash-error">❌ <div><?php foreach ($errors as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?></div></div>
    <?php endif; ?>

    <form method="POST">
      <input type="hidden" name="id" value="<?= $editUser['id'] ?? 0 ?>">

      <div class="form-group">
        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
        <input type="text" name="full_name" class="form-control" required
               value="<?= htmlspecialchars($_POST['full_name'] ?? $editUser['full_name'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Username <span class="required">*</span></label>
        <input type="text" name="username" class="form-control" required
               value="<?= htmlspecialchars($_POST['username'] ?? $editUser['username'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Email <span class="required">*</span></label>
        <input type="email" name="email" class="form-control" required
               value="<?= htmlspecialchars($_POST['email'] ?? $editUser['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Password <?= $editUser ? '<span style="color:var(--text-muted);font-weight:400;">(kosongkan jika tidak diubah)</span>' : '<span class="required">*</span>' ?></label>
        <input type="password" name="password" class="form-control" <?= !$editUser ? 'required' : '' ?> placeholder="<?= $editUser ? 'Kosongkan jika tidak diubah' : 'Masukkan password' ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Role</label>
        <select name="role" class="form-control">
          <?php foreach (['superadmin','admin','editor'] as $r): ?>
            <option value="<?= $r ?>" <?= ($editUser['role'] ?? 'editor') === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
          <input type="checkbox" name="is_active" value="1"
                 <?= ($editUser['is_active'] ?? 1) ? 'checked' : '' ?>
                 style="width: 16px; height: 16px; accent-color: var(--accent-cyan);">
          <span class="form-label" style="margin: 0;">Akun aktif</span>
        </label>
      </div>
      <button type="submit" class="btn btn-primary" style="width: 100%;">
        💾 <?= $editUser ? 'Simpan Perubahan' : 'Buat Pengguna' ?>
      </button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
