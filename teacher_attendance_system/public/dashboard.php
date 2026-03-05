<?php
require_once __DIR__ . '/../helpers.php';
$u = require_login();

$today = today_date();
$default_period = period_for_now();

$has_am = has_attendance($u['id'], $today, 'AM');
$has_pm = has_attendance($u['id'], $today, 'PM');

$recent = read_attendance_for_teacher($u['id'], 30);

include __DIR__ . '/partials/header.php';
?>
<div class="row g-4">
  <div class="col-lg-5">
    <div class="card p-4">
      <h4 class="mb-1">Record Attendance</h4>
      <div class="small-muted mb-3">Date: <strong><?= h($today) ?></strong></div>

      <div class="d-flex gap-2 mb-3 flex-wrap">
        <span class="badge text-bg-<?= $has_am ? 'success' : 'secondary' ?>">AM: <?= $has_am ? 'Recorded' : 'Not yet' ?></span>
        <span class="badge text-bg-<?= $has_pm ? 'success' : 'secondary' ?>">PM: <?= $has_pm ? 'Recorded' : 'Not yet' ?></span>
      </div>

      <form method="post" action="<?= h((BASE_URL ?: '') . '/public/record.php') ?>">
        <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">

        <div class="mb-3">
          <label class="form-label">Period</label>
          <select class="form-select" name="period" required>
            <option value="AM" <?= $default_period==='AM' ? 'selected' : '' ?>>AM</option>
            <option value="PM" <?= $default_period==='PM' ? 'selected' : '' ?>>PM</option>
          </select>
        </div>

        <button class="btn btn-primary w-100">Record Now</button>
      </form>

      <hr class="my-4">
      <div class="small-muted">
        Logged in as:<br>
        <strong><?= h($u['full_name']) ?></strong> (<?= h($u['id']) ?>)<br>
        Department: <?= h($u['department']) ?>
      </div>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="card p-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h4 class="mb-0">Your Recent Records</h4>
        <a class="btn btn-outline-secondary btn-sm" href="<?= h((BASE_URL ?: '') . '/public/export.php') ?>">Download My CSV</a>
      </div>
      <div class="small-muted mt-2 mb-3">Showing latest <?= count($recent) ?> records.</div>

      <div class="table-responsive">
        <table class="table table-sm align-middle">
          <thead>
            <tr>
              <th>Date</th>
              <th>Period</th>
              <th>Recorded At</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$recent): ?>
              <tr><td colspan="3" class="text-center small-muted py-4">No records yet.</td></tr>
            <?php else: ?>
              <?php foreach ($recent as $r): ?>
                <tr>
                  <td><?= h($r['date']) ?></td>
                  <td><span class="badge text-bg-light"><?= h($r['period']) ?></span></td>
                  <td><?= h($r['recorded_at']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <hr class="my-3">
      <div class="small-muted">
        Tip: You can record <strong>AM</strong> and <strong>PM</strong> once per day.
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
