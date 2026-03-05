<?php
require_once __DIR__ . '/../../helpers.php';
start_session();
$flash = flash_get();
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h(APP_NAME) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f7f7fb; }
    .card { border: 0; box-shadow: 0 6px 18px rgba(0,0,0,.06); border-radius: 14px; }
    .navbar { box-shadow: 0 4px 14px rgba(0,0,0,.04); }
    .btn { border-radius: 10px; }
    .form-control, .form-select { border-radius: 10px; }
    .badge { border-radius: 999px; }
    .small-muted { color: #6c757d; font-size: .92rem; }
    code { background: #f2f2f6; padding: 0.1rem 0.35rem; border-radius: 8px; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="<?= h((BASE_URL ?: '') . '/public/index.php') ?>"><?= h(APP_NAME) ?></a>
    <div class="ms-auto">
      <?php $u = current_user(); ?>
      <?php if ($u): ?>
        <span class="me-2 small-muted">Hi, <strong><?= h($u['full_name']) ?></strong></span>
        <a class="btn btn-outline-secondary btn-sm" href="<?= h((BASE_URL ?: '') . '/public/dashboard.php') ?>">Dashboard</a>
        <a class="btn btn-outline-danger btn-sm ms-2" href="<?= h((BASE_URL ?: '') . '/public/logout.php') ?>">Logout</a>
      <?php else: ?>
        <a class="btn btn-outline-primary btn-sm" href="<?= h((BASE_URL ?: '') . '/public/login.php') ?>">Login</a>
        <a class="btn btn-primary btn-sm ms-2" href="<?= h((BASE_URL ?: '') . '/public/register.php') ?>">Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<main class="container py-4">
  <?php if ($flash): ?>
    <div class="alert alert-<?= h($flash['type']) ?>"><?= h($flash['msg']) ?></div>
  <?php endif; ?>
