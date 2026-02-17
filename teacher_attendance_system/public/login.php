<?php
require_once __DIR__ . '/../helpers.php';
ensure_data_storage();
start_session();

if (current_user()) redirect('/public/dashboard.php');

$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    $u = find_user_by_username($username);
    if (!$u || !password_verify($password, $u['password_hash'])) {
        $errors[] = 'Invalid username or password.';
    } else {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $u['id'];
        flash_set('success', 'Welcome back!');
        redirect('/public/dashboard.php');
    }
}

include __DIR__ . '/partials/header.php';
?>
<div class="row justify-content-center">
  <div class="col-lg-5">
    <div class="card p-4">
      <h4 class="mb-2">Login</h4>

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
          <label class="form-label">Username</label>
          <input class="form-control" name="username" value="<?= h($username) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input class="form-control" type="password" name="password" required>
        </div>

        <button class="btn btn-primary w-100">Login</button>
      </form>

      <div class="text-center mt-3 small-muted">
        No account yet? <a href="<?= h((BASE_URL ?: '') . '/public/register.php') ?>">Register</a>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
