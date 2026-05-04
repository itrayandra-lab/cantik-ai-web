<?php
$pageTitle = 'Pengaturan';
require_once __DIR__ . '/includes/header.php';
?>

<div style="max-width: 700px;">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">⚙️ Pengaturan Sistem</h3>
    </div>

    <div style="padding: 20px; text-align: center; color: var(--text-muted);">
      <div style="font-size: 48px; margin-bottom: 16px;">🚧</div>
      <h3 style="margin-bottom: 8px;">Halaman dalam pengembangan</h3>
      <p>Fitur pengaturan akan segera hadir.</p>
    </div>

    <div style="border-top: 1px solid var(--border); padding-top: 20px;">
      <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 12px;">Informasi Sistem</h4>
      <table style="width: 100%; font-size: 13px;">
        <tr>
          <td style="padding: 8px 0; color: var(--text-secondary);">PHP Version</td>
          <td style="padding: 8px 0; text-align: right;"><code style="background: var(--bg-card); padding: 2px 8px; border-radius: 4px;"><?= phpversion() ?></code></td>
        </tr>
        <tr>
          <td style="padding: 8px 0; color: var(--text-secondary);">Database</td>
          <td style="padding: 8px 0; text-align: right;"><code style="background: var(--bg-card); padding: 2px 8px; border-radius: 4px;"><?= DB_NAME ?></code></td>
        </tr>
        <tr>
          <td style="padding: 8px 0; color: var(--text-secondary);">Server</td>
          <td style="padding: 8px 0; text-align: right;"><code style="background: var(--bg-card); padding: 2px 8px; border-radius: 4px;"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></code></td>
        </tr>
        <tr>
          <td style="padding: 8px 0; color: var(--text-secondary);">Admin Panel Version</td>
          <td style="padding: 8px 0; text-align: right;"><code style="background: var(--bg-card); padding: 2px 8px; border-radius: 4px;">v1.0.0</code></td>
        </tr>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
