<?php
require_once __DIR__ . '/../helpers.php';
$u = require_login();

// Simple admin: first user in users.json can be turned into admin manually (is_admin=true)
if (empty($u['is_admin'])) {
    http_response_code(403);
    echo "Forbidden: Admins only.";
    exit;
}

$rows = read_attendance_all(500);
include __DIR__ . '/partials/header.php';
?>
<div class="card p-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h4 class="mb-0">Admin: All Attendance</h4>
    <a class="btn btn-outline-secondary btn-sm" href="<?= h((BASE_URL ?: '') . '/public/dashboard.php') ?>">Back</a>
  </div>
  <div class="small-muted mt-2 mb-3">Latest <?= count($rows) ?> records.</div>

  <div class="table-responsive">
    <table class="table table-sm align-middle">
      <thead>
        <tr>
          <th>Date</th>
          <th>Period</th>
          <th>Teacher ID</th>
          <th>Full Name</th>
          <th>Department</th>
          <th>Recorded At</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= h($r['date']) ?></td>
            <td><span class="badge text-bg-light"><?= h($r['period']) ?></span></td>
            <td><?= h($r['teacher_id']) ?></td>
            <td><?= h($r['full_name']) ?></td>
            <td><?= h($r['department']) ?></td>
            <td><?= h($r['recorded_at']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?>
          <tr><td colspan="6" class="text-center small-muted py-4">No records yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="small-muted mt-3">
    To enable admin: open <code>/data/users.json</code> and set your user <code>"is_admin": true</code>.
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
