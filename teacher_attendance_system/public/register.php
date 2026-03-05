<?php
require_once __DIR__ . '/../helpers.php';
ensure_data_storage();
start_session();

if (current_user()) redirect('/public/dashboard.php');

$errors = [];
$values = ['full_name' => '', 'username' => '', 'department' => ALLOWED_DEPARTMENT];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $values['full_name'] = trim($_POST['full_name'] ?? '');
    $values['username'] = trim($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    $department = trim($_POST['department'] ?? '');

    $values['department'] = $department;

    if ($values['full_name'] === '') $errors[] = 'Full name is required.';
    if ($values['username'] === '' || !preg_match('/^[a-zA-Z0-9._-]{3,30}$/', $values['username'])) {
        $errors[] = 'Username must be 3–30 characters (letters, numbers, dot, underscore, dash).';
    }
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($department !== ALLOWED_DEPARTMENT) $errors[] = 'Registration is allowed for CS Department only.';

    if (!$errors) {
        if (find_user_by_username($values['username'])) {
            $errors[] = 'Username is already taken.';
        } else {
            $users = load_users();
            $id = 'T' . str_pad((string)(count($users) + 1), 5, '0', STR_PAD_LEFT);
            $users[] = [
                'id' => $id,
                'full_name' => $values['full_name'],
                'username' => $values['username'],
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'department' => $department,
                'is_admin' => false
            ];
            save_users($users);

            flash_set('success', 'Account created! Please login.');
            redirect('/public/login.php');
        }
    }
}

include __DIR__ . '/partials/header.php';
?>
<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card p-4">
      <h4 class="mb-1">Register (CS Department Only)</h4>
      <p class="small-muted mb-3">Only teachers from <strong><?= h(ALLOWED_DEPARTMENT) ?></strong> can register.</p>

      <?php if ($errors): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post">
        <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">

        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input class="form-control" name="full_name" value="<?= h($values['full_name']) ?>" placeholder="e.g., Juan Dela Cruz" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Username</label>
          <input class="form-control" name="username" value="<?= h($values['username']) ?>" placeholder="e.g., juan.dc" required>
          <div class="form-text">Allowed: letters, numbers, dot, underscore, dash.</div>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input class="form-control" type="password" name="password" placeholder="Min 6 characters" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Department</label>
          <select class="form-select" name="department" required>
            <option value="<?= h(ALLOWED_DEPARTMENT) ?>" selected><?= h(ALLOWED_DEPARTMENT) ?></option>
            <option value="Other">Other (not allowed)</option>
          </select>
        </div>

        <button class="btn btn-primary w-100">Create Account</button>
      </form>

      <div class="text-center mt-3 small-muted">
        Already have an account? <a href="<?= h((BASE_URL ?: '') . '/public/login.php') ?>">Login</a>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
