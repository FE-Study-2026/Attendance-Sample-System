<?php
require_once __DIR__ . '/../helpers.php';
ensure_data_storage();
$u = current_user();
if ($u) redirect('/public/dashboard.php');
include __DIR__ . '/partials/header.php';
?>
<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card p-4">
      <h3 class="mb-2">Teacher Attendance System</h3>
      <p class="small-muted mb-3">
        Simple <strong>PHP</strong> attendance system for <strong>Computer Science</strong> teachers.
        Supports <strong>AM/PM</strong> attendance recording with file-based storage.
      </p>

      <div class="d-flex gap-2 flex-wrap">
        <a class="btn btn-primary" href="<?= h((BASE_URL ?: '') . '/public/register.php') ?>">Create Account</a>
        <a class="btn btn-outline-primary" href="<?= h((BASE_URL ?: '') . '/public/login.php') ?>">Login</a>
      </div>

      <hr class="my-4">
      <div class="row g-3">
        <div class="col-md-6">
          <div class="p-3 bg-light rounded-3">
            <div class="fw-semibold mb-1">Features</div>
            <ul class="mb-0 small-muted">
              <li>Username + password login</li>
              <li>Registration (CS Department only)</li>
              <li>AM/PM attendance recording</li>
              <li>File-based storage (JSON + CSV)</li>
            </ul>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-3 bg-light rounded-3">
            <div class="fw-semibold mb-1">Storage</div>
            <div class="small-muted">
              Users: <code>/data/users.json</code><br>
              Attendance: <code>/data/attendance.csv</code>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
